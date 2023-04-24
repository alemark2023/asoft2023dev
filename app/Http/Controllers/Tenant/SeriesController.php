<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\SeriesRequest;
use App\Http\Resources\Tenant\SeriesCollection;
use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Series;

class SeriesController extends Controller
{
    public function create()
    {
        return view('tenant.series.form');
    }

    public function records($establishmentId, $document_type = null)
    {
        $records = Series::FilterEstablishment($establishmentId);
        if(!empty($document_type)){
            $records->FilterDocumentType($document_type);
        }
        $records = $records->get();

        return new SeriesCollection($records);
    }

    public function tables()
    {
        $document_types = DocumentType::OnlyAvaibleDocuments()->get();

        return compact('document_types');
    }

    public function store(SeriesRequest $request)
    {

        $validate_series = $this->validateSeries($request);
        if(!$validate_series['success']) return $validate_series;

        $id = $request->input('id');
        $series = Series::firstOrNew(['id' => $id]);
        $series->fill($request->all());
        $series->save();

        return [
            'success' => true,
            'message' => ($id)?'Serie editada con éxito':'Serie registrada con éxito'
        ];
    }

    
    /**
     * 
     * Validar datos
     *
     * @param  SeriesRequest $request
     * @return array
     */
    public function validateSeries(SeriesRequest $request)
    {

        $record = Series::where([['document_type_id',$request->document_type_id],['number', $request->number]])->first();

        if($record)
        {
            return [
                'success' => false,
                'message' => 'La serie ya ha sido registrada'
            ];
        }

        
        return [
            'success' => true,
            'message' => null
        ];
    }


    public function destroy($id)
    {
        $item = Series::findOrFail($id);
        $item->delete();

        return [
            'success' => true,
            'message' => 'Serie eliminada con éxito'
        ];
    }
}
