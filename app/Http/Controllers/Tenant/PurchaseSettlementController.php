<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Resources\Tenant\PurchaseSettlementCollection;
use App\Models\Tenant\PurchaseSettlement;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PurchaseSettlementController extends Controller
{

    public function index()
    {
        return view('tenant.purchase-settlements.index');
    }

    public function create($order_id = null)
        {
            $type = 'settlements';
            return view('tenant.purchases.form', compact('order_id','type'));
        }

    public function columns()
    {
        return [
            'number' => 'Número',
            'date_of_issue' => 'Fecha de emisión'
        ];
    }

    public function records(Request $request)
    {
        $records = PurchaseSettlement::where($request->column, 'like', "%{$request->value}%")
                            ->where('user_id', auth()->id())
                            ->latest();

        return new PurchaseSettlementCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function store(Request $request)
    {

        // dd($request->all());
        try {
        $fact = DB::connection('tenant')->transaction(function () use($request) {

            $facturalo = new Facturalo();
            $facturalo->save($request->all());
            $facturalo->createXmlUnsigned();
            $facturalo->signXmlUnsigned();
            $facturalo->updateHash();
            // $facturalo->updateQr();
            $facturalo->createPdf();
            $facturalo->sendEmail();
            $facturalo->senderXmlSignedBill();

            return $facturalo;
        });

        $document = $fact->getDocument();
        $response = $fact->getResponse();

        return [
            'success' => true,
            'data' => [
                'id' => $purchase->id,
                'number_full' => "{$purchase->series}-{$purchase->number}",
            ],
            /* 'success' => true,
            'data' => [
                'number' => $document->number_full,
                'filename' => $document->filename,
                'external_id' => $document->external_id,
                'number_to_letter' => $document->number_to_letter,
                'hash' => $document->hash,
            ],
            'links' => [
                'xml' => $document->download_external_xml,
                'pdf' => $document->download_external_pdf,
                'cdr' => ($response['sent'])?$document->download_external_cdr:'',
            ],
            'response' => ($response['sent'])?array_except($response, 'sent'):[] */
        ];
        } catch (Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}