<?php

namespace Modules\DocumentaryProcedure\Http\Controllers;

use App\Models\Tenant\User;
use Illuminate\Routing\Controller;
use Modules\DocumentaryProcedure\Http\Requests\OfficeRequest;
use Modules\DocumentaryProcedure\Models\DocumentaryOffice;
use Modules\DocumentaryProcedure\Models\RelUserToDocumentaryOffices;

class DocumentaryOfficeController extends Controller
{
	public function index()
	{
		$offices = DocumentaryOffice::orderBy('id', 'DESC');
        $parents = $offices;
        if (request()->ajax()) {
			$filter = request('name');
			if ($filter) {
				$offices->where('name', 'like', "%$filter%");
			}
            $offices = $offices->get()->transform(function($row){
                /** @var DocumentaryOffice $row */
                return $row->getCollectionData();
            });;
			return response()->json(['data' => $offices], 200);
		}





		$offices = $offices->get()->transform(function($row){
		    /** @var DocumentaryOffice $row */
            return $row->getCollectionData();
        });

        $parents = $parents->where('parent_id',0)->get()->transform(function($row){
            /** @var DocumentaryOffice $row */
            return $row->getCollectionData();
        });
		$users = User::GetWorkers()->get()->transform(function($row){
		  return $row->getCollectionData();
        });

		return view('documentaryprocedure::offices', compact('offices','users','parents'));
	}

	/**
	 * Store a newly created resource in storage.
	 * @param Request $request
	 * @return Response
	 */
	public function store(OfficeRequest $request)
	{
		$office = new DocumentaryOffice();

        $office
            ->setDescription($request->description)
            ->setName($request->name)
            ->setParentId($request->parent_id)
            ->setOrder($request->order)
        ;
        $office->fill($request->only( 'active'));
        $office->push();
                foreach($request->users as $user){
                    $e =  RelUserToDocumentaryOffices::firstOrCreate([
                                                                         'documentary_office_id'=>$office->id,
                                                                         'user_id'=>$user
                                                                     ]);
                    $e->setActive(true)->push();
                }
        return response()->json([
            'data' => $office,
            'message' => 'Oficina guardada de forma correcta.',
            'succes' => true,
        ], 200);
	}

	/**
	 * Update the specified resource in storage.
	 * @param Request $request
	 * @param int $id
	 * @return Response
	 */
	public function update(OfficeRequest $request, $id)
	{

		$office = DocumentaryOffice::findOrFail($id);

		$office
            ->setDescription($request->description)
            ->setName($request->name)
            ->setParentId($request->parent_id)
            ->setOrder($request->order)
            ;
		$office->fill($request->only( 'active'));
		$office->save();

		$delete =  RelUserToDocumentaryOffices::where('documentary_office_id',$office->id)
                                              ->where('user_id','!=',$request->users)->delete();



        foreach($request->users as $user){
		    $e =  RelUserToDocumentaryOffices::firstOrCreate([
                                                                 'documentary_office_id'=>$office->id,
                                                                 'user_id'=>$user
                                                             ]);
		    $e->setActive(true)->push();
        }
		return response()->json([
            'data' => $office,
            'message' => 'Oficina actualizada de forma correcta.',
            'succes' => true,
        ], 200);
	}

	/**
	 * Remove the specified resource from storage.
	 * @param int $id
	 * @return Response
	 */
	public function destroy($id)
	{
		try {
			$office = DocumentaryOffice::findOrFail($id);
			$relations = RelUserToDocumentaryOffices::where('documentary_office_id',$office->id)->get();
			$office->delete();
			foreach($relations as $rel){
			    $rel->delete();
            }

			return response()->json([
                'data' => null,
                'message' => 'Oficina eliminada de forma correcta.',
                'succes' => true,
            ], 200);
		} catch (\Throwable $th) {
			return response()->json([
				'success' => false,
				'data'    => 'Ocurrió un error al procesar su petición. Detalles: ' . $th->getMessage()
			], 500);
		}
	}
}
