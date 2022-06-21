<?php

namespace Modules\MobileApp\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Tenant\Document;
use App\Models\Tenant\Series;
use App\Models\Tenant\StateType;
use Modules\Document\Http\Requests\ValidateDocumentsRequest;
use App\Models\Tenant\Company;
use Illuminate\Support\Facades\DB;
use App\CoreFacturalo\Services\IntegratedQuery\{
    AuthApi,
    ValidateCpe,
};

class ValidateDocumentController extends Controller
{

    protected $access_token;



    public function records(ValidateDocumentsRequest $request)
    {


        $auth_api = (new AuthApi())->getToken();
        if(!$auth_api['success']) return $auth_api;
        $this->access_token = $auth_api['data']['access_token'];

        $records = $this->getRecords($request);
        $validate_documents = $this->validateDocuments($records);

        return new ValidateDocumentsCollection($validate_documents);

    }


    public function validateDocuments(ValidateDocumentsRequest $request)
    {


        // dd($this->access_token, $records_paginate->getCollection());


            $validate_cpe = new ValidateCpe(
                                $this->access_token,
                                $document->company->number,
                                $document->document_type_id,
                                $document->series,
                                $document->number,
                                $document->date_of_issue,
                                $document->total
                            );

            $response = $validate_cpe->search();

            // dd($response);

            if ($response['success']) {

                $document->message = $response['message'];
                $document->sunat_state_type_id = $response['data']['state_type_id'];
                $document->code = $response['data']['estadoCp'];
                $document->response = $response;

            } else{

                $document->message = $response['message'];
                $document->sunat_state_type_id = null;
                $document->code = '-2';  //custom code
                $document->response = $response;

            }


    }



}
