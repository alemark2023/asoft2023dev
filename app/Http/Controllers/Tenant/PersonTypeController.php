<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Requests\Tenant\PersonTypeRequest;
use App\Http\Resources\Tenant\PersonTypeCollection;
use App\Http\Resources\Tenant\PersonTypeResource;
use App\Imports\PersonsImport;
use App\Models\Tenant\Catalogs\Country;
use App\Models\Tenant\Catalogs\Department;
use App\Models\Tenant\Catalogs\District;
use App\Models\Tenant\Catalogs\IdentityDocumentType;
use App\Models\Tenant\Catalogs\Province;
use App\Http\Controllers\Controller;
use App\Models\Tenant\PersonType;
use Modules\Item\Models\ItemPriceType;
use Modules\Item\Models\NamePriceType;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class PersonTypeController extends Controller
{
    public function index()
    {
        $item_price_types = NamePriceType::with('item_price_type')->get();
        return view('tenant.person_types.index', compact('item_price_types'));
    }

    public function columns()
    {
        return [
            'description' => 'Descripción',
        ];
    }

    public function records(Request $request)
    {

        $records = PersonType::where($request->column, 'like', "%{$request->value}%")
                            ->latest();
        /* dd($records); */
        return new PersonTypeCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function create()
    {
        return view('tenant.customers.form');
    }
 

    public function record($id)
    {
        $record = PersonType::findOrFail($id);

        return $record;
    }

    public function store(PersonTypeRequest $request)
    {
        $id = $request->input('id');
        $name=$request->input('price_id');
        /* dd($name); */
        
        if($name){
            $list_price = ItemPriceType::where('name_price_id', $name)->get();
            foreach ($list_price as $value) {
                $value->type_customer_id = $id;
                $value->save();
            }
        }
        $person_type = PersonType::firstOrNew(['id' => $id]);
        $person_type->fill($request->all());
        $person_type->save();
  

        return [
            'success' => true,
            'message' => ($id)?'Tipo de cliente editado con éxito':'Tipo de cliente registrado con éxito',
        ];
    }

    public function destroy($id)
    {
        try {            
            
            $person_type = PersonType::findOrFail($id);
            $person_type_type = 'Tipo de cliente';
            $person_type->delete();

            return [
                'success' => true,
                'message' => $person_type_type.' eliminado con éxito'
            ];

        } catch (Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => "El {$person_type_type} esta siendo usado por otros registros, no puede eliminar"] : ['success' => false,'message' => "Error inesperado, no se pudo eliminar el {$person_type_type}"];

        }
        
    }
  
}