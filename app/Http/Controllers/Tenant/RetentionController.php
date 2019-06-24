<?php
namespace App\Http\Controllers\Tenant;

use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\RetentionRequest;
use App\Http\Resources\Tenant\RetentionCollection;
use App\Http\Resources\Tenant\RetentionResource;
use App\Models\Tenant\Catalogs\Code;
use App\Models\Tenant\Catalogs\RetentionType;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Series;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Retention;
use App\Models\Tenant\Supplier;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RetentionController extends Controller
{
    use StorageDocument;

    public function index()
    {
        return view('tenant.retentions.index');
    }

    public function columns()
    {
        return [
            'number' => 'Número'
        ];
    }

    public function records(Request $request)
    {
        $records = Retention::where($request->column, 'like', "%{$request->value}%")
                            ->orderBy('series')
                            ->orderBy('number', 'desc');

        return new RetentionCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function create()
    {
        return view('tenant.retentions.form');
    }

    public function tables()
    {
        $user_id = Auth::id();
        $establishments = Establishment::all();
        $retention_types = Code::whereCatalog('23')->whereActive()->orderByDescription()->get();
        $suppliers = $this->table('suppliers');
        $series = Series::all();

        return compact('user_id', 'establishments', 'retention_types', 'suppliers', 'series');
    }

    public function document_tables()
    {
        $document_types = Code::whereCatalog('01')->whereCodes(['01', '03'])->get();
        $currency_types = Code::whereCatalog('02')->whereActive()->get();
        $retention_types = Code::whereCatalog('23')->whereActive()->orderByDescription()->get();

        return compact('document_types', 'currency_types', 'retention_types');
    }

    public function table($table)
    {
        if ($table === 'suppliers') {
            $suppliers = Supplier::orderBy('name')->get()->transform(function($row) {
                return [
                    'id' => $row->id,
                    'description' => $row->number.' - '.$row->name,
                    'name' => $row->name,
                    'number' => $row->number,
                    'identity_document_type_id' => $row->identity_document_type_id,
                    'identity_document_type_code' => $row->identity_document_type->code
                ];
            });
            return $suppliers;
        }

        return [];
    }

    public function record($id)
    {
        $record = new RetentionResource(Retention::findOrFail($id));

        return $record;
    }

//    public function setNumber($data)
//    {
//        $number = $data['number'];
//        $series_id = $data['series_id'];
//        $document_type_id = $data['document_type_id'];
//        $soap_type_id = $data['soap_type_id'];
//        if ($data['number'] === '#') {
//            $document = Retention::select('number')
//                                    ->where('series_id', $series_id)
//                                    ->where('document_type_id', $document_type_id)
//                                    ->where('soap_type_id', $soap_type_id)
//                                    ->orderBy('number', 'desc')
//                                    ->first();
//             $number = ($document)?(int)$document->number+1:1;
//        }
//        return $number;
//    }

    public function store(RetentionRequest $request)
    {
        $id = $request->input('id');
        $record = Retention::firstOrNew(['id' => $id]);
        $attributes = $request->all();
        $attributes['number'] = $this->setNumber($attributes);
        $record->fill($attributes);
        $record->save();
        foreach ($attributes['items'] as $detail) {
            $record->details()->create($detail);
        }
        return [
            'success' => true,
            'message' => ($id)?'Retención editada con éxito':'Retención registrada con éxito'
        ];
    }

    public function downloadExternal($type, $external_id)
    {
        $retention = Retention::where('external_id', $external_id)->first();
        if(!$retention) {
            throw new Exception("El código {$external_id} es inválido, no se encontro documento relacionado");
        }

        switch ($type) {
            case 'pdf':
                $folder = 'pdf';
                break;
            case 'xml':
                $folder = 'signed';
                break;
            case 'cdr':
                $folder = 'cdr';
                break;
            default:
                throw new Exception('Tipo de archivo a descargar es inválido');
        }

        return $this->downloadStorage($retention->filename, $folder);
    }
}