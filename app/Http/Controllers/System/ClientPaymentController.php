<?php
namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\System\ClientPaymentRequest;
use App\Http\Requests\System\DocumentRequest;
use App\Http\Resources\System\ClientPaymentCollection;
use App\Models\System\Client;
use App\Models\System\ClientPayment;
use App\Models\System\PaymentMethodType;
use App\Models\System\CardBrand;

class ClientPaymentController extends Controller
{
    public function records($client_id)
    {
        $records = ClientPayment::where('client_id', $client_id)->get();

        return new ClientPaymentCollection($records);
    }

    public function tables()
    {
        return [
            'payment_method_types' => PaymentMethodType::all(),
            'card_brands' => CardBrand::all()
        ];
    }

    public function client($client_id)
    {
        $client = Client::find($client_id);

        $total_paid = collect($client->payments)->where('state',true)->sum('payment');
        $total = collect($client->payments)->sum('payment');
        $total_difference = round($total - $total_paid, 2);

        return [
            'name' => $client->name,
            'total_paid' => $total_paid,
            'total' => $total,
            'total_difference' => $total_difference
        ];

    }

    public function store(ClientPaymentRequest $request)
    {
        $id = $request->input('id');
        $record = ClientPayment::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();

        return [
            'success' => true,
            'message' => ($id)?'Pago editado con éxito':'Pago registrado con éxito'
        ];
    }

    public function destroy($id)
    {
        $item = ClientPayment::findOrFail($id);
        $item->delete();

        return [
            'success' => true,
            'message' => 'Pago eliminado con éxito'
        ];
    }

    
    public function cancel_payment($client_payment_id)
    {
        $client = ClientPayment::find($client_payment_id);
        $client->state = true;
        $client->save();

        return [
            'success' => true,
            'message' => 'Monto cancelado', 
        ];

    } 
}
