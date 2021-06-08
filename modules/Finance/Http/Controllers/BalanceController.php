<?php

    namespace Modules\Finance\Http\Controllers;

    use App\Models\Tenant\BankAccount;
    use App\Models\Tenant\Cash;
    use App\Models\Tenant\Company;
    use App\Models\Tenant\Configuration;
    use App\Models\Tenant\Establishment;
    use App\Models\Tenant\GlobalPaymentsRelations;
    use Barryvdh\DomPDF\Facade as PDF;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Routing\Controller;
    use Illuminate\Support\Facades\DB;
    use Modules\Finance\Exports\BalanceExport;
    use Modules\Finance\Models\GlobalPayment;
    use Modules\Finance\Traits\FinanceTrait;

    class BalanceController extends Controller {

        use FinanceTrait;

        /**
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
         */
        public function index() {
            $configuration = Configuration::first();
            return view('finance::balance.index', compact('configuration'));
        }


        /**
         * @return array
         */
        public function filter() {

            $payment_types = [];
            $destination_types = [];

            return compact('payment_types', 'destination_types');
        }

        /**
         * @param array $data
         * @param \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection $record
         */
        public  function setTotals($data = [], $record){
            $data['records'] =   $record;
            $data['totals'] = [];

            $data['totals']['t_initial_balance'] = $this::FormatNumber($record->sum('initial_balance'));
            $data['totals']['t_documents'] = $this::FormatNumber($record->sum('document_payment'));
            $data['totals']['t_sale_notes'] =$this::FormatNumber($record->sum('sale_note_payment'));
            $data['totals']['t_quotations'] =$this::FormatNumber($record->sum('quotation_payment'));
            $data['totals']['t_contracts'] =$this::FormatNumber($record->sum('contract_payment'));
            $data['totals']['t_technical_services'] =$this::FormatNumber($record->sum('technical_service_payment'));
            $data['totals']['t_income'] =$this::FormatNumber($record->sum('income_payment'));
            $data['totals']['t_expenses'] =$this::FormatNumber($record->sum('expense_payment'));
            $data['totals']['t_balance'] =$this::FormatNumber($record->sum('balance'));
            $data['totals']['t_purchases'] =$this::FormatNumber($record->sum('purchase_payment'));
            return $data;
        }

        /**
         * @param \Illuminate\Http\Request $request
         *
         * @return array
         */
        public function records(Request $request) {
            return $this->setTotals([], $this->getRecords($request->all()));
        }


        /**
         * @param array $request
         *
         * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
         */
        public function getRecords($request = [],   &$times = []) {
            set_time_limit (3900);

            $old = true;

            if(isset($_GET['nuevo'])&&$_GET['nuevo']== false){
                $old = false;
            }
            if(!isset($times['base'])){$times['base'] = microtime(true);}


            $data_of_period = $this->getDatesOfPeriod($request);

            $params = (object)[
                'date_start' => $data_of_period['d_start'],
                'date_end'   => $data_of_period['d_end'],
            ];

            $times[__FILE__.'::'.__LINE__] = microtime(true) - $times['base'];

            $return = null;
            if($old == true) {
                $bank_accounts = BankAccount:: with(['global_destination' => function ($query) use ($params,&$a) {
                    $query->whereFilterPaymentType($params);
                }])->get();
                $times[__FILE__.'::'.__LINE__] = microtime(true) - $times['base'];
                $all_cash = GlobalPayment::whereFilterPaymentType($params)
                                         ->with(['payment'])
                                         ->whereDestinationType(Cash::class)
                                         ->get();
                $times[__FILE__.'::'.__LINE__] = microtime(true) - $times['base'];
                $balance_by_bank_acounts = $this->getBalanceByBankAcounts($bank_accounts);
                $times[__FILE__.'::'.__LINE__] = microtime(true) - $times['base'];
                $balance_by_cash = $this->getBalanceByCash($all_cash);
                $times[__FILE__.'::'.__LINE__] = microtime(true) - $times['base'];
            }else{
                $bank_accounts = BankAccount::
                with(['global_destination_relations' => function ($query) use ($params) {
                    $query->whereFilterPaymentType($params);
                }])->get();
                $times[__FILE__.'::'.__LINE__] = microtime(true) - $times['base'];
                $all_cash = GlobalPaymentsRelations::whereFilterPaymentType($params)->wherenotnull('cash_id')->WhereNoNotes();
                $all_cash = $all_cash->get();
                $times[__FILE__.'::'.__LINE__] = microtime(true) - $times['base'];
                $balance_by_bank_acounts = $this->getBalanceByBankAcounts2($bank_accounts);
                $times[__FILE__.'::'.__LINE__] = microtime(true) - $times['base'];
                $balance_by_cash = $this->getBalanceByCashAcounts($all_cash);
                $times[__FILE__.'::'.__LINE__] = microtime(true) - $times['base'];

            }

            return $balance_by_bank_acounts->push($balance_by_cash);

        }


        /**
         * @param \Illuminate\Http\Request $request
         *
         * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
         */
        public function pdf(Request $request) {


            $times = [];
            $times['base'] = microtime(true);

            $company = Company::first();
            $establishment = ($request->establishment_id)
                ? Establishment::findOrFail($request->establishment_id)
                : auth()->user()->establishment;
            $records = $this->getRecords($request->all(), $times);
            $data = $this->setTotals([], $records);
            $data['times'] = $times;
            return view('finance::balance.report_pdf',compact('records', 'company', 'establishment', 'data'));
            $pdf = PDF::loadView('finance::balance.report_pdf',compact('records', 'company', 'establishment', 'data'));
            $filename = 'Balance_'.date('YmdHis');

            return $pdf->download($filename.'.pdf');
        }


        /**
         * @param \Illuminate\Http\Request $request
         *
         * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
         */
        public function excel(Request $request) {

            $company = Company::first();
            $establishment = ($request->establishment_id)
                ? Establishment::findOrFail($request->establishment_id)
                : auth()->user()->establishment;
            $records = $this->getRecords($request->all());
            $data = $this->setTotals([], $records);
            $balance =new BalanceExport();
            $balance
                ->records($records)
                ->setData($data)
                ->company($company)
                ->establishment($establishment);

            // return $balance->View();
            return $balance->download('Balance_'.Carbon::now().'.xlsx');

        }

    }
