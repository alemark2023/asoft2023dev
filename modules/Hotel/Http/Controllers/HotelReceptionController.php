<?php

namespace Modules\Hotel\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Hotel\Models\HotelRoom;
use Modules\Hotel\Models\HotelFloor;

class HotelReceptionController extends Controller
{
	public function index()
	{
		$rooms = HotelRoom::with('category', 'floor');

		if (request()->ajax()) {
			if (request('hotel_floor_id')) {
				$rooms = $rooms->where('hotel_floor_id', request('hotel_floor_id'));
			}
			if (request('status')) {
				$rooms = $rooms->where('status', request('status'));
			}

			return response()->json([
				'success' => true,
				'rooms'   => $rooms->get(),
			], 200);
		}

		$rooms = $rooms->get();

		$floors = HotelFloor::where('active', true)
				->orderBy('description')
				->get();

		$roomStatus = HotelRoom::$status;

		return view('hotel::rooms.reception', compact('rooms', 'floors', 'roomStatus'));
	}
}
