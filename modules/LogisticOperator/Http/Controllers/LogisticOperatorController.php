<?php

namespace Modules\LogisticOperator\Http\Controllers;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Series;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class LogisticOperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function yobel_index()
    {
        return view('logisticoperator::yobel.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('logisticoperator::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('logisticoperator::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('logisticoperator::edit');
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

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


    public function columns()
    {
        return [
            'id' => 'Codigo de Pedido',
            'number_document' => 'Comprobante Electronico',
        ];
    }


    public function tables()
    {
        $establishments = Establishment::where('id', auth()->user()->establishment_id)->get();
        $series = collect(Series::all())->transform(function($row) {
            return [
                'id' => $row->id,
                'contingency' => (bool) $row->contingency,
                'document_type_id' => $row->document_type_id,
                'establishment_id' => $row->establishment_id,
                'number' => $row->number
            ];
        });

        $document_types = DocumentType::all();

        return compact('series', 'establishments', 'document_types');

    }
}
