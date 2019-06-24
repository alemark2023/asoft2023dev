<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\DocumentPaymentRequest;
use App\Http\Requests\Tenant\DocumentRequest;
use App\Http\Resources\Tenant\DocumentPaymentCollection;
use App\Models\Tenant\Document;
use App\Models\Tenant\DocumentPayment;
use App\Models\Tenant\PaymentMethodType;

class DocumentPaymentController extends Controller
{
    public function records($document_id)
    {
        $records = DocumentPayment::where('document_id', $document_id)->get();

        return new DocumentPaymentCollection($records);
    }

    public function tables()
    {
        return [
            'payment_method_types' => PaymentMethodType::all()
        ];
    }

    public function document($document_id)
    {
        $document = Document::find($document_id);

        $total_paid = collect($document->payments)->sum('payment');
        $total = $document->total;
        $total_difference = round($total - $total_paid, 2);

        return [
            'number_full' => $document->number_full,
            'total_paid' => $total_paid,
            'total' => $total,
            'total_difference' => $total_difference
        ];

    }

    public function store(DocumentPaymentRequest $request)
    {
        $id = $request->input('id');
        $record = DocumentPayment::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();

        return [
            'success' => true,
            'message' => ($id)?'Pago editado con éxito':'Pago registrado con éxito'
        ];
    }

    public function destroy($id)
    {
        $item = DocumentPayment::findOrFail($id);
        $item->delete();

        return [
            'success' => true,
            'message' => 'Pago eliminado con éxito'
        ];
    }
}
