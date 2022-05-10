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
use Modules\Payment\Models\{
    PaymentLink,
    PaymentLinkType,
    PaymentConfiguration,
};
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Modules\Payment\Mail\PaymentLinkEmail;
use Modules\Finance\Helpers\UploadFileHelper;


class PaymentLinkController extends Controller
{

    
    /**
     * Buscar link de pago
     *
     * @param  int $document_payment_id
     * @param  string $instance_type
     * @param  int $payment_link_type_id
     * @return array
     */
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


    
    /**
     * Registrar link de pago
     *
     * @param  PaymentLinkRequest $request
     * @return array
     */
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

     
    /**
     * Mostrar formulario público del link de pago
     *
     * @param  string $uuid
     * @param  string $payment_link_type_id
     * @param  float $total
     */
    public function publicPaymentLink($uuid, $payment_link_type_id, $total)
    {

        $this->validatePublicParams($payment_link_type_id, $total);
        $payment_link = PaymentLink::select('payment_link_type_id')->where('uuid', $uuid)->firstOrFail();
        $company = $this->getPublicDataCompany();
        $payment_configuration = PaymentConfiguration::getPublicRowResource();

        return view('payment::payment_links.public.index', compact('payment_link', 'company', 'payment_configuration', 'total'));

    }
    

    /**
     *
     * @return Company
     */
    public function getPublicDataCompany()
    {
        return Company::select('name', 'number')->first();
    }

    
    /**
     * 
     * Validar datos
     *
     * @param  string $payment_link_type_id
     * @param  float $total
     * @return void
     */
    public function validatePublicParams($payment_link_type_id, $total)
    {

        $validate = [
            'success' => true,
            'message' => null,
        ];

        if(!is_numeric($total))
        {
            $validate = [
                'success' => false,
                'message' => 'El total debe ser númerico',
            ];
        }
        else
        {
            if($total <= 0)
            {
                $validate = [
                    'success' => false,
                    'message' => 'El total debe ser mayor a 0',
                ];
            }
        }

        if(!PaymentLinkType::find($payment_link_type_id))
        {
            $validate = [
                'success' => false,
                'message' => 'Tipo de link no permitido',
            ]; 
        }
        
        if(!$validate['success']) throw new Exception($validate['message']);

    }

    
    /**
     * Enviar correo
     *
     * @param  Request $request
     * @return array
     */
    public function email(Request $request)
    {

        $company = $this->getPublicDataCompany();

        Mail::to($request->customer_email)->send(new PaymentLinkEmail($company, $request->user_payment_link));
        
        return [
            'success' => true,
            'message' => 'El correo fue enviado satisfactoriamente'
        ];

    }

    
    
    /**
     * Cargar voucher
     *
     * @param  Request $request
     * @return array
     */
    public function uploadedFile(Request $request)
    {

        $validate_upload = UploadFileHelper::validateUploadFile($request, 'file', 'jpg,jpeg,png,svg');
        if(!$validate_upload['success']) return $validate_upload;

        if ($request->hasFile('file')) 
        {

            $payment_link = PaymentLink::findOrFail($request->id);

            $new_request = [
                'file' => $request->file('file'),
                'type' => $request->input('type'),
            ];

            $temp_file = UploadFileHelper::getTempFile($new_request);

            if($temp_file['success'])
            {
                $filename = UploadFileHelper::uploadFileFromTempFile('payment_links', $temp_file['data']['filename'], $temp_file['data']['temp_path'], $payment_link->id);
                $payment_link->uploaded_filename = $filename;
                $payment_link->save();
                
                return [
                    'success' => true,
                    'message' => 'Archivo cargado correctamente',
                ];

            }


        }

        return [
            'success' => false,
            'message' =>  __('app.actions.upload.error'),
        ];
    }

}
