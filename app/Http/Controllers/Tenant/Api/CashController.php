<?php
namespace App\Http\Controllers\Tenant\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Cash;
use App\Models\Tenant\Document;


class CashController extends Controller
{
   
    public function storeRestaurant(Request $request) {

        $cash = new Cash();
        $cash->user_id = auth()->user()->id;
        $cash->date_opening = $request->dateOpening;
        $cash->time_opening = $request->timeOpening;
        $cash->date_closed = date('Y-m-d');
        $cash->time_closed = date('H:i:s');
        $cash->beginning_balance = (float)$request->beginningBalance;
        $cash->final_balance = 0;
        $cash->income = 0;
        $cash->state = 0;
        $cash->save();

        $total_documents = 0;
        foreach ($request->internalsId as $external_id) {

            $document = Document::where('external_id', $external_id)->first();
            $total_documents += (float)$document->total;
        }

        $cash->income = $total_documents;
        $cash->final_balance = $cash->beginning_balance + $cash->income;
        $cash->save();

        return [
            'success' => true,
            'message' => 'Caja creada con éxito'
        ];
    }   
    
}
