<?php
namespace Modules\Payment\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\DocumentPaymentRequest;
use App\Http\Resources\Tenant\DocumentPaymentCollection;
use App\Models\Tenant\{
    DocumentPayment,
    Company
};
use Exception, Illuminate\Support\Facades\DB;
use Modules\Payment\Http\Requests\PaymentLinkRequest;
use Carbon\Carbon;
use Modules\Payment\Models\PaymentLink;
use Illuminate\Support\Str;


class PaymentLinkController extends Controller
{


    public function record($document_payment_id, $instance_type, $payment_link_type_id)
    {

        $payment_link = PaymentLink::where('payment_link_type_id', $payment_link_type_id)
                                        ->where('payment_id', $document_payment_id)
                                        ->where('payment_type', PaymentLink::getModelByType($instance_type))
                                        ->first();


        if(is_null($payment_link))
        {
            return [
                'has_payment_link' => false,
                'data' => [],
            ];
        }

        return [
            'has_payment_link' => true,
            'data' => $payment_link->getRowResource(),
        ];

    }



    public function store(PaymentLinkRequest $request)
    {

        $record = PaymentLink::create([
            'user_id' => auth()->id(),
            'uuid' => Str::uuid()->toString(),
            'soap_type_id' => Company::select('soap_type_id')->firstOrFail()->soap_type_id,
            'payment_link_type_id' => $request->payment_link_type_id,
            'payment_id' => $request->payment_id,
            'payment_type' => $this->getModelByType($request->origin_type),
            'total' => $request->total,
        ]);

        return [
            'success' => true,
            'message' => 'Link generado con éxito'
        ];

    }

    public function index() {
        return view('payment::generate.index');
    }

}
