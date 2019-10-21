<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\System\Client;
use App\Models\System\Plan;
use App\Models\Tenant\Company;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\AccountPayment;
use App\Models\System\ClientPayment;



use App\Http\Resources\Tenant\AccountPaymentCollection;
use Culqi\Culqi;
use Culqi\CulqiException;
use Illuminate\Support\Facades\Mail;
use App\Mail\Tenant\CulqiEmail;
use stdClass;



use Exception;


class AccountController extends Controller
{
    public function index()
    {
        return view('tenant.account.configuration');
    }

    public function tables()
    {
        $plans = Plan::all();
        $configuration = Configuration::first();


        return compact('plans', 'configuration');
    }

    public function paymentIndex()
    {
        return view('tenant.account.payment_index');
    }

    public function paymentRecords()
    {
        $records = AccountPayment::all();
        return new AccountPaymentCollection($records);
        
    }

    public function updatePlan(Request $request)
    {
        try{

            $company = Company::active();
            $client = Client::where('number', $company->number)->first();
            $configuration = Configuration::first();

            $configuration->plan = Plan::find($request->plan_id);
            $configuration->save();

            $client->plan_id = $request->plan_id;
            $client->save();

            return [
                'success' => true,
                'message' => 'Cliente Actualizado satisfactoriamente'
            ];

        }catch(Exception $e)
        {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

    }

    public function paymentCulqui(Request $request)
    {
        try{

           
            $user = auth()->user();
    
            $SECRET_API_KEY = "sk_test_gZ9jAaILIsIweKfm";
            $culqi = new Culqi(array('api_key' => $SECRET_API_KEY));
    
            $charge = $culqi->Charges->create(
                array(
                    "amount" => $request->precio,
                    "currency_code" => "PEN",
                    "email" => $request->email,
                    "description" =>  $request->producto, 
                    "source_id" => $request->token,
                    "installments" => $request->installments
                  )
            );

            $account_payment = AccountPayment::find($request->id_payment_account);
            $account_payment->state = 1;
            $account_payment->date_of_payment_real = date('Y-m-d'); 
            $account_payment->save();


            $system_client_payment =  ClientPayment::find($account_payment->reference_id);
            $system_client_payment->state = 1;
            $system_client_payment->save();

    
            $customer_email = $request->email;
            $document = new stdClass;
            $document->client = $user->name;
            $document->product = $request->producto;
            $document->total = $request->precio_culqi;
            Mail::to($customer_email)->send(new CulqiEmail($document));
    
            return [
                'success' => true,
                'culqui' => $charge,
                'message' => 'Pago efectuado correctamente'
            ];
          }
          catch(Exception $e)
          {
            return [
                'success' => false,
                'message' =>  $e->getMessage()
            ];
          }
    }

   

    

    
}