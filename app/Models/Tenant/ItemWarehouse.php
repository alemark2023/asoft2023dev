<?php

namespace App\Models\Tenant;


use Illuminate\Support\Facades\Config;

class ItemWarehouse extends ModelTenant
{
    protected $table = 'item_warehouse';

    protected $fillable = [
        'item_id',
        'warehouse_id',
        'stock',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    /**
     * Evalua la configuracion para mostrar solo los items asociados al almacen del usuario show_all_items_at_invoice
     * Devuelve verdadero si esta desactivada la configuracion o, si esta activado, evalua que el almacen sea el mismo del usuario.
     *
     * @param int $warehouse_id
     * @param int $user_warehouse_id
     *
     * @return bool
     */
    public function showAllItemsAtInvoice($warehouse_id = 0, $user_warehouse_id = 0)
    {
        if (!Config::get('configuration.show_all_items_at_invoice')) {
            if ($warehouse_id == $user_warehouse_id) {
                return true;
            }
            return false;
        }

        return true;
    }
}
