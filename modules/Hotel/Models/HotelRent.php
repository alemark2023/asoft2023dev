<?php

namespace Modules\Hotel\Models;

use App\Models\Tenant\ModelTenant;

/**
 * \Modules\Hotel\Models\HotelRent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|HotelRent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HotelRent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HotelRent query()
 * @mixin ModelTenant
 * @mixin \Eloquent
 */
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
		'output_date',
		'output_time',
		'input_date',
		'input_time',
	];

	public function getCustomerAttribute($value)
	{
		return (is_null($value)) ? null : (object) json_decode($value);
	}

	public function setCustomerAttribute($value)
	{
		$this->attributes['customer'] = (is_null($value)) ? null : json_encode($value);
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room()
	{
		return $this->belongsTo(HotelRoom::class, 'hotel_room_id');
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
	{
		return $this->hasMany(HotelRentItem::class, 'hotel_rent_id');
	}
}
