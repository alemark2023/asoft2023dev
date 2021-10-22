<?php

namespace Modules\Suscription\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class SuscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('suscription::index');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function clients_index()
    {
        return view('suscription::clients.index');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function services_index()
    {
        return view('suscription::services.index');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function products_index()
    {
        return view('suscription::products.index');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function plans_index()
    {
        return view('suscription::plans.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('suscription::create');
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
        return view('suscription::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('suscription::edit');
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
}
