<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Modules\Report\Exports\GeneralItemExport;
use Illuminate\Http\Request;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Document;
use App\Models\Tenant\PurchaseItem;
use App\Models\Tenant\DocumentItem;
use App\Models\Tenant\SaleNoteItem;
use App\Models\Tenant\Company;
use Carbon\Carbon;
use Modules\Report\Http\Resources\GeneralItemCollection;
use Modules\Report\Traits\ReportTrait;


class ReportGeneralItemController extends Controller
{
    use ReportTrait;

    public function __construct()
    {
    }

    public function filter() {

        $document_types = DocumentType::whereIn('id', ['01', '03', '80'])->get();

        return compact('document_types');
    }


    public function index() {

        return view('report::general_items.index');
    }


    public function records(Request $request)
    {

        $records = $this->getRecordsItems($request->all());


        return new GeneralItemCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function getRecordsItems($request){

        $data_of_period = $this->getDataOfPeriod($request);
        $data_type = $this->getDataType($request);

        $document_type_id = $request['document_type_id'];
        $d_start = $data_of_period['d_start'];
        $d_end = $data_of_period['d_end'];

        $user = $request['user'];

        $records = $this->dataItems($d_start, $d_end, $document_type_id, $data_type,$user);

        return $records;

    }


    private function dataItems($date_start, $date_end, $document_type_id, $data_type, $user)
    {

        if( $document_type_id && $document_type_id == '80' )
        {
            $data = SaleNoteItem::whereHas('sale_note', function($query) use($date_start, $date_end){
                $query
                ->whereBetween('date_of_issue', [$date_start, $date_end])
                ->latest()
                ->whereTypeUser();
            });
        }
        else{

            $model = $data_type['model'];
            $relation = $data_type['relation'];

            $document_types = $document_type_id ? [$document_type_id] : ['01','03'];

            $data = $model::whereHas($relation, function($query) use($date_start, $date_end, $document_types){
                            $query
                            ->whereBetween('date_of_issue', [$date_start, $date_end])
                            ->whereIn('document_type_id', $document_types)
                            ->latest()
                            ->whereTypeUser();
                        })
                        ->whereHas('document.user', function($query) use($user){
                            $query->where('name', 'like', "%{$user}%");
                        });

        }

        return $data;

    }


    private function getDataType($request){

        if($request['type'] == 'sale'){

            $data['model'] = DocumentItem::class;
            $data['relation'] = 'document';

        }else{

            $data['model'] = PurchaseItem::class;
            $data['relation'] = 'purchase';

        }

        return $data;
    }

    public function excel(Request $request) {

        $records = $this->getRecordsItems($request->all())->get();
        $type = ($request->type == 'sale') ? 'Ventas_':'Compras_';
        $document_type_id = $request['document_type_id'];

        return (new GeneralItemExport)
                ->records($records)
                ->type($request->type)
                ->document_type_id($document_type_id)
                ->download('Reporte_General_Productos_'.$type.Carbon::now().'.xlsx');

    }
}
