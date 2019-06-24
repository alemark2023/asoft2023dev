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

        return compact('countries', 'departments', 'provinces', 'districts');
    }

    public function record($id)
    {
        $record = new EstablishmentResource(Establishment::findOrFail($id));

        return $record;
    }

    public function store(EstablishmentRequest $request)
    {
        $id = $request->input('id');
        $establishment = Establishment::firstOrNew(['id' => $id]);
        $establishment->fill($request->all());
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