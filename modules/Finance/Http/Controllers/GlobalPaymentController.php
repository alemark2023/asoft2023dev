<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\GlobalPayment;
use App\Models\Tenant\Cash;
use App\Models\Tenant\BankAccount;
use Modules\Finance\Traits\FinanceTrait; 
use Modules\Finance\Http\Resources\GlobalPaymentCollection;

class GlobalPaymentController extends Controller
{ 

    use FinanceTrait;

    public function index() {

        return view('finance::global_payments.index');
    }

    public function records(Request $request)
    {

        // $records = GlobalPayment::whereDestinationType(Cash::class)->first();

        $records = GlobalPayment::latest();

        
        return new GlobalPaymentCollection($records->paginate(config('tenant.items_per_page')));

    }

}
