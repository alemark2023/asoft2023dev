<?php

namespace App\Http\Controllers\Tenant\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
            return [
                'id' => $row->id,
                'full_description' => $full_description,
                'internal_id' => $row->internal_id,
                'description' => $row->description,
                'currency_type_id' => $row->currency_type_id,
                'currency_type_symbol' => $row->currency_type->symbol,
                'sale_unit_price' => $row->sale_unit_price,
                'purchase_unit_price' => $row->purchase_unit_price,
                'unit_type_id' => $row->unit_type_id,
                'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                'calculate_quantity' => (bool) $row->calculate_quantity,
                'has_igv' => (bool) $row->has_igv,
                'amount_plastic_bag_taxes' => $row->amount_plastic_bag_taxes,
                
            ];
        });

        return [
            'success' => true,
            'data' => array('customers' => $customers, 'items' => $items)
        ];
       
    }
 
}
