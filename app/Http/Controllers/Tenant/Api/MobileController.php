<?php

namespace App\Http\Controllers\Tenant\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant\Person;
use App\Models\Tenant\Item;
use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use App\Mail\Tenant\DocumentEmail;
use Illuminate\Support\Facades\Mail;





class MobileController extends Controller
{

    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return [
                'success' => false,
                'message' => 'No Autorizado'
            ];
        }

        $user = $request->user();
        return [
            'success' => true,
            'name' => $user->name,
            'email' => $user->email,
            'token' => $user->api_token,
        ];

    }

    public function tables()
    {
        $affectation_igv_types = AffectationIgvType::whereActive()->get();
        $customers = Person::whereType('customers')->orderBy('name')->take(20)->get()->transform(function($row) {
            return [
                'id' => $row->id,
                'description' => $row->number.' - '.$row->name,
                'name' => $row->name,
                'number' => $row->number,
                'identity_document_type_id' => $row->identity_document_type_id,
                'identity_document_type_code' => $row->identity_document_type->code
            ];
        });

        $items = Item::whereWarehouse()->whereNotIsSet()->orderBy('description')->get()->transform(function($row){
            $full_description = ($row->internal_id)?$row->internal_id.' - '.$row->description:$row->description;

            return [
                'id' => $row->id,
                'item_id' => $row->id,
                'name' => $row->name,
                'full_description' => $full_description,
                'description' => $row->description,
                'currency_type_id' => $row->currency_type_id,
                'internal_id' => $row->internal_id,
                'item_code' => $row->item_code,
                'currency_type_symbol' => $row->currency_type->symbol,
                'sale_unit_price' => number_format( $row->sale_unit_price, 2),
                'purchase_unit_price' => $row->purchase_unit_price,
                'unit_type_id' => $row->unit_type_id,
                'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                'calculate_quantity' => (bool) $row->calculate_quantity,
                'has_igv' => (bool) $row->has_igv,
                'is_set' => (bool) $row->is_set,
                'aux_quantity' => 1,
            ];
        });

        return [
            'success' => true,
            'data' => array('customers' => $customers, 'items' => $items, 'affectation_types' => $affectation_igv_types)
        ];

    }

    public function document_email(Request $request)
    {
        $company = Company::active();
        $document = Document::find($request->id);
        $customer_email = $request->email;

        Mail::to($customer_email)->send(new DocumentEmail($company, $document));

        return [
            'success' => true,
            'message'=> 'Email enviado correctamente.'
        ];
    }

}

