<?php
namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Catalogs\Country;
use App\Models\Tenant\Catalogs\Department;
use App\Models\Tenant\Catalogs\District;
use App\Models\Tenant\Catalogs\Province;
use App\Models\Tenant\Establishment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\EstablishmentRequest;
use App\Http\Resources\Tenant\EstablishmentResource;
use App\Http\Resources\Tenant\EstablishmentCollection;
use App\Models\Tenant\Warehouse;
use App\Models\Tenant\Person;
use Modules\Finance\Helpers\UploadFileHelper;
use Exception;


class EstablishmentController extends Controller
{
    public function index()
    {
        return view('tenant.establishments.index');
    }

    public function create()
    {
        return view('tenant.establishments.form');
    }

    public function tables()
    {
        $countries = Country::whereActive()->orderByDescription()->get();
        $departments = Department::whereActive()->orderByDescription()->get();
        $provinces = Province::whereActive()->orderByDescription()->get();
        $districts = District::whereActive()->orderByDescription()->get();

        $customers = Person::whereType('customers')->orderBy('name')->take(1)->get()->transform(function($row) {
            return [
                'id' => $row->id,
                'description' => $row->number.' - '.$row->name,
                'name' => $row->name,
                'number' => $row->number,
                'identity_document_type_id' => $row->identity_document_type_id,
            ];
        });

        return compact('countries', 'departments', 'provinces', 'districts', 'customers');
    }

    public function record($id)
    {
        $record = new EstablishmentResource(Establishment::findOrFail($id));

        return $record;
    }
    
    
    /**
     *
     * @param  EstablishmentRequest $request
     * @return array
     */
    public function store(EstablishmentRequest $request)
    {
        try 
        {
            $id = $request->input('id');
            $has_igv_31556 = ($request->input('has_igv_31556') === 'true');
            $establishment = Establishment::firstOrNew(['id' => $id]);
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $request->validate(['file' => 'mimes:jpeg,png,jpg|max:1024']);
                $file = $request->file('file');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;

                UploadFileHelper::checkIfValidFile($filename, $file->getPathName(), true);

                $file->storeAs('public/uploads/logos', $filename);
                $path = 'storage/uploads/logos/' . $filename;
                $request->merge(['logo' => $path]);
            }
            $establishment->fill($request->all());
            $establishment->has_igv_31556 = $has_igv_31556;
            $establishment->save();

            if(!$id) {
                $warehouse = new Warehouse();
                $warehouse->establishment_id = $establishment->id;
                $warehouse->description = 'Almacén - '.$establishment->description;
                $warehouse->save();
            }

            return [
                'success' => true,
                'message' => ($id)?'Establecimiento actualizado':'Establecimiento registrado'
            ];
        } 
        catch(Exception $e)
        {
            $this->generalWriteErrorLog($e);

            return $this->generalResponse(false, 'Error desconocido: '.$e->getMessage());
        }
    }


    public function records()
    {
        $records = Establishment::all();

        return new EstablishmentCollection($records);
    }

    public function destroy($id)
    {
        $establishment = Establishment::findOrFail($id);
        $establishment->delete();

        return [
            'success' => true,
            'message' => 'Establecimiento eliminado con éxito'
        ];
    }
}
