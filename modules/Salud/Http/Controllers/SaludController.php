<?php

namespace Modules\Salud\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Salud\Models\Especialidad;

use function GuzzleHttp\Promise\all;

class SaludController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        // echo "FUCK YOU";
        return view('salud::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('salud::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            $id = $request->id;
            $salud = Especialidad::firstOrNew(['id' => $id]);
            
            $salud->name = $request->name;
            $salud->description = $request->description;
            $salud->user_created = Auth::user()->id;
            ($id) ? $salud->user_updated = Auth::user()->id : null;
            $salud->created_at = Carbon::now();
            $salud->created_at = Carbon::now();
            $salud->save();
            
            $msg = ($id) ? 'Especialidad editado con éxito' : 'Especialidad registrado con éxito';
            return [
                'success' => true,
                'message' => $msg,
                'id' => $salud->id
            ];
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('salud::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('salud::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function patient($type)
    {
        $api_service_token = \App\Models\Tenant\Configuration::getApiServiceToken();

        return view('tenant.persons.index', compact('type', 'api_service_token'));
    }

    public function specialty()
    {
        return view('salud::specialty');
    }

    public function records(Request $request)
    {
        $records = Especialidad::where('name', 'like', "%{$request->value}%")->orderBy('name');

        return new SaludCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function record($id)
    {
        $record = new SaludResource(Especialidad::findOrFail($id));
        // $record = Especialidad::findOrFail($id);
        // dd($record);
        return $record;
    }

    public function enabled($type, $id)
    {

        $person = Especialidad::findOrFail($id);
        $person->enabled = $type;
        $person->save();

        $type_message = ($type) ? 'habilitado' : 'inhabilitado';

        return [
            'success' => true,
            'message' => "Cliente {$type_message} con éxito"
        ];

    }
}
