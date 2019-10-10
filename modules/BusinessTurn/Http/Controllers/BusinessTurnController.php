<?php

namespace Modules\BusinessTurn\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Tenant\Catalogs\IdentityDocumentType;
use Modules\BusinessTurn\Http\Requests\DocumentHotelRequest;
use Modules\BusinessTurn\Models\BusinessTurn;

class BusinessTurnController extends Controller
{
    
    public function index()
    {
        return view('businessturn::configurations.index');
    }

    public function records()
    {
        return BusinessTurn::get()->transform(function($row, $key) {
            return [
                'id' => $row->id,
                'active' => (bool)$row->active,
                'name' => $row->name, 
            ];
        });
    }

    public function store(Request $request)
    { 

        $record = BusinessTurn::findOrFail($request->id);
        $record->active = ($record->active) ? false:true;
        $record->save();

        return [
            'success' => true, 
            'message' => $record->active ? 'Giro de negocio activado' : 'Giro de negocio desactivado', 
        ];
    }

    public function validate_hotel(DocumentHotelRequest $request)
    { 
        return [
            'success' => true, 
        ];
    }

    public function tables()
    { 
        $identity_document_types = IdentityDocumentType::whereIn('id',['1','4','7'])->get();

        $sexs = [
            ['id'=>'M','description'=>'Masculino'],
            ['id'=>'F','description'=>'Femenino'],
        ];

        $civil_status = [
            ['id'=>'S','description'=>'Soltero/a'],
            ['id'=>'C','description'=>'Casado/a'],
            ['id'=>'V','description'=>'Viudo/a'],
            ['id'=>'D','description'=>'Divorciado/a'],
        ];

        return compact('identity_document_types','civil_status','sexs');
    }
}
