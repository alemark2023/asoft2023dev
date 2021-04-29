<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Abstracts\TenantModel;

class ItemWarehousePrice extends TenantModel
{
    protected $table = 'item_warehouse_prices';
    protected $fillable = [
        'item_id',
        'warehouse_id',
        'price',
    ];
    public $timestamps = false;

    /**
     * @return int
     */
    public function getItemId()
    {
        return $this->item_id;
    }

    /**
     * @param int $item_id
     *
     * @return ItemWarehousePrice
     */
    public function setItemId($item_id)
    {
        $this->item_id = $item_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getWarehouseId()
    {
        return $this->warehouse_id;
    }

    /**
     * @param int $warehouse_id
     *
     * @return ItemWarehousePrice
     */
    public function setWarehouseId($warehouse_id)
    {
        $this->warehouse_id = $warehouse_id;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     *
     * @return ItemWarehousePrice
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Se obtiene el item basado en el almacen y el id del producto.
     *
     * @param int $warehouse
     * @param int $item_id
     *
     * @return ItemWarehousePrice|null
     */
    public static function GetItemByWarehouseAndId($warehouse = 0, $item_id = 0)
    {
        $where = [
            'warehouse_id' => $warehouse,
            'item_id' => $item_id
        ];
        return self::where($where)->first();
    }
}
