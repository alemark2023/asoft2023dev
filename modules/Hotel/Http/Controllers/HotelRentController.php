<?php

namespace Modules\Hotel\Http\Controllers;

use App\Models\Tenant\Person;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Hotel\Http\Requests\HotelRentRequest;
use Modules\Hotel\Models\HotelRent;
use Modules\Hotel\Models\HotelRoom;

class HotelRentController extends Controller
{
	public function rent($roomId)
	{
		$room = HotelRoom::with('category', 'rates.rate')
			->findOrFail($roomId);

		return view('hotel::rooms.rent', compact('room'));
	}

	public function store(HotelRentRequest $request, $roomId)
	{
		DB::connection('tenant')->beginTransaction();
		try {
			$room = HotelRoom::findOrFail($roomId);
			if ($room->status !== 'DISPONIBLE') {
				return response()->json([
					'success' => true,
					'message' => 'La habitación seleccionada no esta disponible',
				], 500);
			}

			$request->merge(['hotel_room_id' => $roomId]);
			HotelRent::create($request->only('customer_id', 'customer', 'notes', 'towels', 'hotel_room_id', 'duration', 'quantity_persons', 'payment_status', 'payment_type', 'payment_number_operation', 'output_date', 'output_time'));

			$room->status = 'OCUPADO';
			$room->save();

			DB::connection('tenant')->commit();

			return response()->json([
				'success' => true,
				'message' => 'Habitación rentada de forma correcta.',
			], 200);
		} catch (\Throwable $th) {
			DB::connection('tenant')->rollBack();

			return response()->json([
				'success' => true,
				'message' => 'No se puede procesar su transacción. Detalles: ' . $th->getMessage(),
			], 500);
		}
	}

	public function searchCustomers()
	{
		$customers = $this->customers();

		return response()->json([
			'customers' => $customers,
		], 200);
	}

	private function customers()
	{
		$customers = Person::with('addresses')
			->whereType('customers')
			->whereIsEnabled()
			->whereIn('identity_document_type_id', [1, 6])
			->orderBy('name');

		$query = request('input');
		if ($query) {
			if (is_numeric($query)) {
				$customers = $customers->where('number', 'like', "%{$query}%");
			} else {
				$customers = $customers->where('name', 'like', "%{$query}%");
			}
		}

		$customers = $customers->take(20)
			->get()
			->transform(function ($row) {
				return [
					'id'                          => $row->id,
					'description'                 => $row->number . ' - ' . $row->name,
					'name'                        => $row->name,
					'number'                      => $row->number,
					'identity_document_type_id'   => $row->identity_document_type_id,
					'identity_document_type_code' => $row->identity_document_type->code,
					'addresses'                   => $row->addresses,
					'address'                     => $row->address,
					'internal_code'               => $row->internal_code
				];
			});

		return $customers;
	}

	public function tables()
	{
		$customers = $this->customers();

		return response()->json([
			'customers'            => $customers,
		], 200);
	}
}
