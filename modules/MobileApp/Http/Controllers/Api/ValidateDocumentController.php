<?php

namespace Modules\MobileApp\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Tenant\Document;
use App\Models\Tenant\StateType;
use Modules\MobileApp\Http\Requests\Api\ValidateDocumentRequest;
use App\Models\Tenant\Company;
use Illuminate\Support\Facades\DB;
use App\CoreFacturalo\Services\IntegratedQuery\{
    AuthApi,
    ValidateCpe,
};

class ValidateDocumentController extends Controller
{

    protected $access_token;

    public function setToken()
    {
        $auth_api = (new AuthApi())->getToken();
        if(!$auth_api['success']) return $auth_api;
        $this->access_token = $auth_api['data']['access_token'] ?? null;

        if(!$this->access_token) throw new Exception('Error al obtener token de autenticación SUNAT');
    }


    public function validateDocument(ValidateDocumentRequest $request)
    {

        $this->setToken();
        $company_number = Company::getRecordIndividualColumn('number');
        $validate_cpe = new ValidateCpe($this->access_token,$company_number, $request->document_type_id, $request->series, $request->number, $request->date_of_issue, $request->total);
        $response = $validate_cpe->search();

        dd($response);

        $data_response = [
            'message' => $response['message'],
            'sunat_state_type_id' => null,
            'code' => '-2',
            'response' => $response,
        ];

        if ($response['success']) 
        {
            $data_response['sunat_state_type_id'] = $response['data']['state_type_id'],
            $data_response['code'] = $response['data']['estadoCp']
        }
        
        return $this->processResponse($data_response);

    }

    public function processResponse($data_response)
    {

    }



}
