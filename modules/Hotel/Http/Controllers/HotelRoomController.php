<?php

namespace Modules\Hotel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Hotel\Models\HotelRoom;
use Modules\Hotel\Models\HotelFloor;
use Modules\Hotel\Models\HotelCategory;
use Modules\Hotel\Http\Requests\HotelRoomRequest;
use Modules\Hotel\Http\Requests\HotelFloorRequest;

class HotelRoomController extends Controller
{
	/**
	 * Display a listing of the resource.
	 * @return Response
	 */
	public function index()
	{
		$rooms = HotelRoom::with('category', 'floor')
			->orderBy('id', 'DESC')
			->get();

		$categories = HotelCategory::where('active', true)
			->orderBy('description')
			->get();

		$floors = HotelFloor::where('active', true)
			->orderBy('description')
			->get();

		return view('hotel::rooms.index', compact('rooms', 'floors', 'categories'));
	}

	/**
	 * Store a newly created resource in storage.
	 * @param Request $request
	 * @return Response
	 */
	public function store(HotelRoomRequest $request)
	{
		$room = HotelRoom::create($request->only('description', 'active', 'name', 'hotel_category_id', 'hotel_floor_id'));
		$room->status = 'DISPONIBLE';
		$room->load('category', 'floor');

		return response()->json([
			'success' => true,
			'data'    => $room
		], 200);
	}

	/**
	 * Update the specified resource in storage.
	 * @param Request $request
	 * @param int $id
	 * @return Response
	 */
	public function update(HotelFloorRequest $request, $id)
	{
		$room = HotelRoom::findOrFail($id);
		$room = $room->fill($request->only('description', 'active', 'name', 'hotel_category_id', 'hotel_floor_id'));
		$room->save();

		$room->load('category', 'floor');

		return response()->json([
			'success' => true,
			'data'    => $room
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
			HotelRoom::where('id', $id)
				->delete();

			return response()->json([
				'success' => true,
				'message' => 'Información actualizada'
			], 200);
		} catch (\Throwable $th) {
			return response()->json([
				'success' => false,
				'data'    => 'Ocurrió un error al procesar su petición. Detalles: ' . $th->getMessage()
			], 500);
		}
	}
}
