<?php

    namespace Modules\Finance\Http\Controllers;

    use App\Models\Tenant\BankAccount;
    use App\Models\Tenant\Cash;
    use App\Models\Tenant\Company;
    use App\Models\Tenant\Configuration;
    use App\Models\Tenant\Establishment;
    use Barryvdh\DomPDF\Facade as PDF;
    use Carbon\Carbon;
    use Illuminate\Contracts\View\Factory;
    use Illuminate\Foundation\Application;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Illuminate\Routing\Controller;
    use Illuminate\Support\Collection;
    use Illuminate\View\View;
    use Modules\Finance\Exports\BalanceExport;
    use Modules\Finance\Models\GlobalPayment;
    use Modules\Finance\Traits\FinanceTrait;
    use Symfony\Component\HttpFoundation\BinaryFileResponse;

    class BalanceController extends Controller
    {

        use FinanceTrait;

        /**
         * @return Factory|Application|View
         */
        public function index()
        {
            $configuration = Configuration::first();
            return view('finance::balance.index', compact('configuration'));
        }


        /**
         * @return array
         */
        public function filter()
        {

            $payment_types = [];
            $destination_types = [];

            return compact('payment_types', 'destination_types');
        }

        /**
         * @param Request $request
         *
         * @return array
         */
        public function records(Request $request)
        {
            return $this->setTotals([], $this->getRecords($request->all()));
        }

        /**
         * @param array                                               $data
         * @param \Illuminate\Database\Eloquent\Collection|Collection $record
         */
        public function setTotals($data = [], $record)
        {
            $data['records'] = $record;
            $data['totals'] = [];

            $data['totals']['t_initial_balance'] = $this::FormatNumber($record->sum('initial_balance'));
            $data['totals']['t_documents'] = $this::FormatNumber($record->sum('document_payment'));
            $data['totals']['t_sale_notes'] = $this::FormatNumber($record->sum('sale_note_payment'));
            $data['totals']['t_quotations'] = $this::FormatNumber($record->sum('quotation_payment'));
            $data['totals']['t_contracts'] = $this::FormatNumber($record->sum('contract_payment'));
            $data['totals']['t_technical_services'] = $this::FormatNumber($record->sum('technical_service_payment'));
            $data['totals']['t_income'] = $this::FormatNumber($record->sum('income_payment'));
            $data['totals']['t_expenses'] = $this::FormatNumber($record->sum('expense_payment'));
            $data['totals']['t_balance'] = $this::FormatNumber($record->sum('balance'));
            $data['totals']['t_purchases'] = $this::FormatNumber($record->sum('purchase_payment'));
            return $data;
        }

        /**
         * @param array $request
         *
         * @return \Illuminate\Database\Eloquent\Collection|Collection
         */
        public function getRecords($request = [])
        {
            set_time_limit(3900);

            $data_of_period = $this->getDatesOfPeriod($request);

            $params = (object)[
                'date_start' => $data_of_period['d_start'],
                'date_end' => $data_of_period['d_end'],
            ];

            $bank_accounts = BankAccount::
            with(['global_destination' => function ($query) use ($params) {
                $query->whereFilterPaymentType($params);
            }])
                ->get();

            $all_cash = GlobalPayment::whereFilterPaymentType($params)
                ->with(['payment'])
                ->whereDestinationType(Cash::class)
                ->get();
            $balance_by_bank_acounts = $this->getBalanceByBankAcounts($bank_accounts);
            $balance_by_cash = $this->getBalanceByCash($all_cash);

            return $balance_by_bank_acounts->push($balance_by_cash);

        }


        /**
         * @param Request $request
         *
         * @return Response|BinaryFileResponse
         */
        public function pdf(Request $request)
        {

            $company = Company::first();
            $establishment = ($request->establishment_id)
                ? Establishment::findOrFail($request->establishment_id)
                : auth()->user()->establishment;
            $records = $this->getRecords($request->all());
            $data = $this->setTotals([], $records);
            $pdf = PDF::loadView('finance::balance.report_pdf', compact('records', 'company', 'establishment', 'data'));
            $filename = 'Balance_' . date('YmdHis');

            return $pdf->download($filename . '.pdf');
        }


        /**
         * @param Request $request
         *
         * @return Response|BinaryFileResponse
         */
        public function excel(Request $request)
        {

            $company = Company::first();
            $establishment = ($request->establishment_id)
                ? Establishment::findOrFail($request->establishment_id)
                : auth()->user()->establishment;
            $records = $this->getRecords($request->all());
            $data = $this->setTotals([], $records);
            $balance = new BalanceExport();
            $balance
                ->records($records)
                ->setData($data)
                ->company($company)
                ->establishment($establishment);

            // return $balance->View();
            return $balance->download('Balance_' . Carbon::now() . '.xlsx');

        }

        public function getBankAcounts()
        {
            $banks = BankAccount::where('status', 1)->get()->transform(function ($bank) {
                $data = [
                    'id' => "B:" . $bank->id,
                    'bank_id' => $bank->bank_id,
                    'description' => $bank->description,
                    'number' => $bank->number,
                    'currency_type_id' => $bank->currency_type_id,
                    'cci' => $bank->cci,
                    // 'initial_balance'=> $bank-> initial_balance,
                ];

                $data['description'] = $data['description'] . " - " . $data['currency_type_id'] . " - " . $data['cci'];
                return $data;

            });
            return compact('banks');
        }

        public function getCashAcounts()
        {
            $cash = Cash::where('state', 1)->get()->transform(function ($cash) {
                $data = [
                    'id' => "C:" . $cash->id,

                    'user_id' => $cash->user_id,
                    'date_opening' => $cash->date_opening,
                    'time_opening' => $cash->time_opening,
                    'date_closed' => $cash->date_closed,
                    'time_closed' => $cash->time_closed,
                    'beginning_balance' => $cash->beginning_balance,
                    'final_balance' => $cash->final_balance,
                    'income' => $cash->income,
                    'state' => $cash->state,
                    'reference_number' => $cash->reference_number,

                ];

                $data['description'] = $data['reference_number'];
                return $data;

            });
            return compact('cash');
        }

        public function makeTransfer(Request $request)
        {

            $return = $request->toArray();
            if ($request->has('data')) {
                $data = $request->data;
                $from = $data['from'];
                $to = $data['to'];
                $amount_transform = $data['amount_transform'];
                $origin = null;
                $destiny = null;
                self::setAcountToTransfer($origin, $from);
                self::setAcountToTransfer($destiny, $to);
                $return['modelo']['origin']= $origin;
                $return['modelo']['destiny']= $destiny;

            }
            return $return;
        }


        public static function setAcountToTransfer(&$model, $id)
        {
            $split = explode(':', $id);

            if ($split[0] == 'C') {
                $model = Cash::find($split[1]);
            } elseif ($split[0] == 'B') {
                $model = BankAccount::find($split[1]);
            }
        }
    }
