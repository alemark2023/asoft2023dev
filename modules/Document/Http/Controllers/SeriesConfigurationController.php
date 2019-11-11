<?php

namespace Modules\Document\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Tenant\Document;
use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Series;
use App\Models\Tenant\StateType;
use Modules\Document\Models\SeriesConfiguration;
use Modules\Document\Http\Requests\SeriesConfigurationsRequest;


class SeriesConfigurationController extends Controller
{
    
    public function index()
    {
        return view('document::series_configurations.index');
    }

    public function records()
    {
        $records = $this->getRecords();
        return $records;
    }

    public function getRecords(){
        
        $records = SeriesConfiguration::get()->transform(function($row, $key) {
            return [
                'id' => $row->id,
                'series_id' => $row->series_id,
                'series' => $row->series,
                'number' => $row->number, 
                'initialized_description' => ($row->relationSeries->documents->count() > 0) ? 'SI':'NO',
                'btn_delete' => ($row->relationSeries->documents->count() > 0) ? false:true 
            ];
        });
 
        return $records;

    }

    public function tables()
    {
        
        $establishmentId = auth()->user()->establishment_id;

        $series = Series::whereIn('document_type_id', ['01', '03','07', '08'])
                        ->where('establishment_id', $establishmentId)
                        ->doesntHave('series_configurations')
                        ->doesntHave('documents')
                        ->get();
                       
        return compact('series');

    }

    
    public function store(SeriesConfigurationsRequest $request)
    {
        $id = $request->input('id');
        $record = SeriesConfiguration::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();

        return [
            'success' => true,
            'message' => ($id)?'Configuración editada con éxito':'Configuración registrada con éxito'
        ];
    }

    public function destroy($id)
    {
        try {
            
            $record = SeriesConfiguration::findOrFail($id);
            $record->delete();

            return [
                'success' => true,
                'message' => 'Configuración de serie eliminada con éxito'
            ];

        } catch (Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => 'La Configuración de serie esta siendo usada por otros registros, no puede eliminar'] : ['success' => false,'message' => 'Error inesperado, no se pudo eliminar la Configuración de serie'];

        }

        
    }
    
}
