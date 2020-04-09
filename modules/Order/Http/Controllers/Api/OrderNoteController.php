<?php

namespace Modules\Order\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Order\Http\Requests\OrderNoteRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Order\Models\OrderNote;
use Modules\Order\Http\Resources\OrderNoteCollection;
use Modules\Order\Http\Resources\OrderNoteResource;

class OrderNoteController extends Controller
{
    // public function store(OrderNoteRequest $request)
    // {
    //     DB::connection('tenant')->transaction(function () use ($request) {
    //         $data = $this->mergeData($request);

    //         $this->order_note =  OrderNote::create($data);

    //         foreach ($data['items'] as $row) {
    //             $this->order_note->items()->create($row);
    //         }

    //         $this->setFilename();
    //         $this->createPdf($this->order_note, "a4", $this->order_note->filename);

    //     });

    //     return [
    //         'success' => true,
    //         'data' => [
    //             'id' => $this->order_note->id,
    //         ],
    //     ];
    // }

    public function lists()
    {
        $records = OrderNote::orderBy('id', 'desc')->get();

        return new OrderNoteCollection($records);
    }

    // private function setFilename(){

    //     $name = [$this->order_note->prefix,$this->order_note->id,date('Ymd')];
    //     $this->order_note->filename = join('-', $name);
    //     $this->order_note->save();

    // }
}
