<?php

namespace Modules\Hotel\Models;

use App\Models\Tenant\ModelTenant;

class HotelRent extends ModelTenant
{
	protected $table = 'hotel_rents';

	protected $fillable = [
		'customer_id',
		'customer',
		'notes',
		'towels',
		'hotel_room_id',
		'duration',
		'quantity_persons',
		'payment_status',
		'payment_type',
		'payment_number_operation',
		'output_date',
		'output_time',
	];

	public function getCustomerAttribute($value)
	{
		return (is_null($value)) ? null : (object) json_decode($value);
	}

	public function setCustomerAttribute($value)
	{
		$this->attributes['customer'] = (is_null($value)) ? null : json_encode($value);
	}
}
