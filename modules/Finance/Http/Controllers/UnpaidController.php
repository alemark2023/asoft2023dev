<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\GlobalPayment;
use App\Models\Tenant\Cash;
use App\Models\Tenant\BankAccount;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Tenant\Company;
use Modules\Finance\Traits\FinanceTrait;
use Modules\Finance\Http\Resources\GlobalPaymentCollection;
use Modules\Finance\Exports\BalanceExport;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Tenant\Establishment;
use Carbon\Carbon;
use App\Models\Tenant\Person;
use Modules\Dashboard\Helpers\DashboardView;
use App\Exports\AccountsReceivable;
use Modules\Finance\Exports\UnpaidPaymentMethodDayExport;
use App\Models\Tenant\User;
use App\Models\Tenant\PaymentMethodType;

class UnpaidController extends Controller
{

    use FinanceTrait;

    public function index()
    {
        return view('finance::unpaid.index');
    }

    public function filter()
    {
        $customers = Person::whereType('customers')->orderBy('name')->get()->transform(function($row) {
            return [
                'id' => $row->id,
                'description' => $row->number.' - '.$row->name,
                'name' => $row->name,
                'number' => $row->number,
                'identity_document_type_id' => $row->identity_document_type_id,
            ];
        });

        $establishments = DashboardView::getEstablishments();

        $users = [];

        if(auth()->user()->type == 'admin')
        {
            $users = User::where('id', '!=', auth()->user()->id)->whereIn('type', ['admin', 'seller'])->get();
        }

        $payment_method_types = PaymentMethodType::whereIn('id', ['05', '08', '09'])->get();

        return compact('customers', 'establishments', 'users', 'payment_method_types');
    }

    public function records(Request $request)
    {
        return [
            'records' => (new DashboardView())->getUnpaidFilterUser($request->all())
       ];
    }

    public function unpaidall()
    {
        return Excel::download(new AccountsReceivable, 'Allclients.xlsx');
    }


    public function reportPaymentMethodDays(Request $request)
    {

        $all_records = (new DashboardView())->getUnpaid($request->all());

        $records = collect($all_records)->where('total_to_pay', '>', 0)->where('type', 'document')->map(function($row){
            $row['difference_days'] = Carbon::parse($row['date_of_issue'])->diffInDays($row['date_of_due']);
            return $row;
        });

        $company = Company::first();

        return (new UnpaidPaymentMethodDayExport)
                ->company($company)
                ->records($records)
                ->download('Reporte_C_Cobrar_F_Pago'.Carbon::now().'.xlsx');

    }

}
