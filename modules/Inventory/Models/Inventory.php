<?php

namespace Modules\Inventory\Models;

use App\Models\Tenant\Item;
use App\Models\Tenant\ModelTenant;
use Modules\Item\Models\ItemLot;

class Inventory extends ModelTenant
{
    protected $with = ['transaction', 'warehouse', 'warehouse_destination', 'item'];

    protected $fillable = [
        'type',
        'description',
        'item_id',
        'warehouse_id',
        'warehouse_destination_id',
        'quantity',
        'inventory_transaction_id',
        'lot_code',
        'detail',
        'inventories_transfer_id'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function warehouse_destination()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_destination_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    /**
     * Se usa en la relacion con el inventario kardex en modules/Inventory/Traits/InventoryTrait.php.
     * Tambien se debe tener en cuenta modules/Inventory/Providers/InventoryKardexServiceProvider.php y
     * app/Providers/KardexServiceProvider.php para la correcta gestion de kardex
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function inventory_kardex()
    {
        return $this->morphMany(InventoryKardex::class, 'inventory_kardexable');
    }

    public function transaction()
    {
        return $this->belongsTo(InventoryTransaction::class, 'inventory_transaction_id');
    }

    public function lots()
    {
        return $this->morphMany(ItemLot::class, 'item_loteable');
    }

    public function getRowResourceReport()
    {

        $input = '-';
        $output = '-';

        if($this->transaction->type === 'input'){
            $input = $this->quantity;
        }else{
            $output = -$this->quantity;
        }

        return [
            'description' => $this->description,
            'item_id' => $this->item_id,
            'item_description' => $this->item->getInternalIdDescription(),
            'inventory_transaction_id' => $this->inventory_transaction_id,
            'quantity' => $this->quantity,
            'input' => $input,
            'output' => $output,
            'date_time' => $this->created_at->format('Y-m-d H:i:s'),
        ];

    }
    
    /**
     * Filtros para reporte movimientos
     * Usado en: 
     * ReportMovementController
     *
     * @param  $query
     * @param  $warehouse_id
     * @param  $inventory_transaction_id
     * @param  $date_start
     * @param  $date_end
     */
    public function scopeWhereFilterReportMovement($query, $warehouse_id, $inventory_transaction_id, $date_start, $date_end)
    {
        return $query->with(['inventory_kardex'])
                    ->where('warehouse_id', $warehouse_id)
                    ->where('inventory_transaction_id', $inventory_transaction_id)
                    ->whereHas('inventory_kardex', function($query) use($date_start, $date_end){
                        
                        if ($date_start) $query->where('date_of_issue', '>=', $date_start);
                        if ($date_end) $query->where('date_of_issue', '<=', $date_end);

                    });
    }

}
