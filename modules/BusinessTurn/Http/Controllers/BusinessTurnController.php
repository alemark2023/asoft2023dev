<?php

namespace Modules\BusinessTurn\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Tenant\Catalogs\IdentityDocumentType;
use Modules\BusinessTurn\Http\Requests\DocumentHotelRequest;
use Modules\BusinessTurn\Http\Requests\DocumentTransportRequest;
use Modules\BusinessTurn\Models\BusinessTurn;
use App\Models\Tenant\Catalogs\{
    Department,
    Province,
    District
};
use Modules\BusinessTurn\Models\DocumentTransport;

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

    
    public function validate_transports(DocumentTransportRequest $request)
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

    public function tablesTransports()
    { 
        
        $identity_document_types = IdentityDocumentType::whereIn('id',['1','4','7'])->get();

        $locations = [];
        $departments = Department::whereActive()->get();
        foreach ($departments as $department)
        {
            $children_provinces = [];
            foreach ($department->provinces as $province)
            {
                $children_districts = [];
                foreach ($province->districts as $district)
                {
                    $children_districts[] = [
                        'value' => $district->id,
                        'label' => $district->description
                    ];
                }
                $children_provinces[] = [
                    'value' => $province->id,
                    'label' => $province->description,
                    'children' => $children_districts
                ];
            }
            $locations[] = [
                'value' => $department->id,
                'label' => $department->description,
                'children' => $children_provinces
            ];
        }

        return compact('identity_document_types','locations');
    }
}
