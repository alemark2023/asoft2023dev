<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\Tenant\TransportRequest;
use App\Http\Resources\Tenant\TransportCollection;
use App\Http\Resources\Tenant\TransportResource;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Transport;
use Modules\Order\Http\Requests\DriverRequest;
use Modules\Order\Http\Resources\DriverCollection;
use Modules\Order\Http\Resources\DriverResource;
use App\Models\Tenant\Catalogs\IdentityDocumentType;
use App\Http\Controllers\Controller;
use Modules\Order\Models\Driver;
use Illuminate\Http\Request;

class TransportController extends Controller
{
    public function index()
    {
        return view('tenant.dispatches.transports.index');
    }

    public function columns()
    {
        return [
            'plate_number' => 'Nro. de Placa',
            'model' => 'Modelo',
            'brand' => 'Marca',
        ];
    }

    public function records(Request $request)
    {
        $records = Transport::where($request->column, 'like', "%{$request->value}%")
                            ->orderBy('plate_number');

        return new TransportCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = new TransportResource(Transport::findOrFail($id));

        return $record;
    }

    public function store(TransportRequest $request)
    {
        $id = $request->input('id');
        $record = Transport::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();

        return [
            'success' => true,
            'message' => ($id)?'Vehículo editado con éxito':'Vehículo registrado con éxito',
            'id' => $record->id
        ];
    }

    public function destroy($id)
    {
        $record = Transport::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Vehículo eliminado con éxito'
        ];

    }

}
