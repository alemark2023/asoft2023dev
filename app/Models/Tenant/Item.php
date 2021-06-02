<?php

namespace App\Models\Tenant;
use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\Catalogs\UnitType;
use Modules\Account\Models\Account;
use Modules\Inventory\Models\Warehouse as ModuleWarehouse;
use Modules\Item\Models\Brand;
use Modules\Item\Models\Category;
use Modules\Item\Models\ItemLot;
use Modules\Item\Models\ItemLotsGroup;
use Modules\Item\Models\WebPlatform;


class Item extends ModelTenant
{
    protected $with = ['item_type', 'unit_type', 'currency_type', 'warehouses','item_unit_types', 'tags'];
    protected $fillable = [
        'warehouse_id',
        'name',
        'second_name',
        'description',
        'model',
        'technical_specifications',
        'item_type_id',
        'internal_id',
        'item_code',
        'item_code_gs1',
        'unit_type_id',
        'currency_type_id',
        'sale_unit_price',
        'purchase_unit_price',
        'has_isc',
        'system_isc_type_id',
        'percentage_isc',
        'suggested_price',

        'sale_affectation_igv_type_id',
        'purchase_affectation_igv_type_id',
        'calculate_quantity',
        'has_igv',

        'stock',
        'stock_min',
        'percentage_of_profit',

        'attributes',
        'has_perception',
        'percentage_perception',
        'image',
        'image_medium',
        'image_small',

        'account_id',
        'amount_plastic_bag_taxes',
        'date_of_due',
        'is_set',
        'sale_unit_price_set',
        'apply_store',
        'brand_id',
        'category_id',
        'lot_code',
        'lots_enabled',
        'active',
        'line',
        'series_enabled',
        'purchase_has_igv',
        'web_platform_id',
        'has_plastic_bag_taxes',
        'barcode',
        // 'warehouse_id'
    ];

    protected $casts = [
        'date_of_due' => 'date'
    ];
    /*protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('active', 1);
        });
    }*/

    public function getAttributesAttribute($value)
    {
        return (is_null($value))?null:json_decode($value);
    }

    public function setAttributesAttribute($value)
    {
        $this->attributes['attributes'] = (is_null($value))?null:json_encode($value);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function item_type()
    {
        return $this->belongsTo(ItemType::class);
    }

    public function unit_type()
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }

    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }

    public function system_isc_type()
    {
        return $this->belongsTo(SystemIscType::class, 'system_isc_type_id');
    }

    public function kardex()
    {
        return $this->hasMany(Kardex::class);
    }

    public function inventory_kardex()
    {
        return $this->hasMany(InventoryKardex::class);
    }

    public function purchase_item()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function sale_affectation_igv_type()
    {
        return $this->belongsTo(AffectationIgvType::class, 'sale_affectation_igv_type_id');
    }

    public function purchase_affectation_igv_type()
    {
        return $this->belongsTo(AffectationIgvType::class, 'purchase_affectation_igv_type_id');
    }

     public function scopeWhereWarehouse($query)
     {
        $establishment_id = auth()->user()->establishment_id;
        $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();
        if ($warehouse) {
            return $query->whereHas('warehouses', function($query) use($warehouse) {
                            $query->where('warehouse_id', $warehouse->id);
                        })->orWhere('unit_type_id', 'ZZ');
        }
        return $query;
     }

    public function scopeWhereTypeUser($query)
    {
        $user = auth()->user();
        return ($user->type == 'seller') ? $this->scopeWhereWarehouse($query) : null;
    }

    public function scopeWhereNotIsSet($query)
    {
        return $query->where('is_set', false);
    }

    public function scopeWhereIsActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeWhereIsSet($query)
    {
        return $query->where('is_set', true);
    }

    public function getStockByWarehouse()
    {
        if(auth()->user())
        {
            $establishment_id = auth()->user()->establishment_id;
            $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();
            if ($warehouse) {
                $item_warehouse = $this->warehouses->where('warehouse_id',$warehouse->id)->first();
                return ($item_warehouse) ? $item_warehouse->stock : 0;
            }
        }

        return 0;
    }

    public function warehouses()
    {
        return $this->hasMany(ItemWarehouse::class)->with('warehouse');
    }


    public function item_unit_types()
    {
        return $this->hasMany(ItemUnitType::class);
    }

    public function tags()
    {
        return $this->hasMany(ItemTag::class);
    }

    public function sets()
    {
    return $this->hasMany(ItemSet::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class)->withDefault([
            'id' => '',
            'name' => ''
        ]);
    }

    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault([
            'id' => '',
            'name' => ''
        ]);
    }

    public function item_lots()
    {
        return $this->hasMany(ItemLot::class, 'item_id');
    }

    public function lots()
    {
        return $this->morphMany(ItemLot::class, 'item_loteable');
    }

    public  function images()
    {
        return $this->hasMany(ItemImage::class, 'item_id');
    }

    public function lots_group()
    {
        return $this->hasMany(ItemLotsGroup::class, 'item_id');
    }

    public function scopeWhereNotService($query)
    {
        return $query->where('unit_type_id','!=', 'ZZ');
    }

    public function scopeWhereService($query)
    {
        return $query->where('unit_type_id', 'ZZ');
    }

    public  function document_items()
    {
        return $this->hasMany(DocumentItem::class, 'item_id');
    }

    public  function sale_note_items()
    {
        return $this->hasMany(SaleNoteItem::class, 'item_id');
    }

    public function scopeWhereFilterValuedKardex($query, $params)
    {

        if($params->establishment_id){

            return $query->with(['document_items'=> function($q) use($params){
                        $q->whereHas('document', function($q) use($params){
                            $q->whereStateTypeAccepted()
                                ->whereTypeUser()
                                ->whereBetween('date_of_issue', [$params->date_start, $params->date_end])
                                ->where('establishment_id', $params->establishment_id);
                        });
                    },
                    'sale_note_items' => function($q) use($params){
                        $q->whereHas('sale_note', function($q) use($params){
                            $q->whereStateTypeAccepted()
                                ->whereNotChanged()
                                ->whereTypeUser()
                                ->whereBetween('date_of_issue', [$params->date_start, $params->date_end])
                                ->where('establishment_id', $params->establishment_id);
                        });
                    }]);

        }

        return $query->with(['document_items'=> function($q) use($params){
                    $q->whereHas('document', function($q) use($params){
                        $q->whereStateTypeAccepted()
                            ->whereTypeUser()
                            ->whereBetween('date_of_issue', [$params->date_start, $params->date_end]);
                    });
                },
                'sale_note_items' => function($q) use($params){
                    $q->whereHas('sale_note', function($q) use($params){
                        $q->whereStateTypeAccepted()
                            ->whereNotChanged()
                            ->whereTypeUser()
                            ->whereBetween('date_of_issue', [$params->date_start, $params->date_end]);
                    });
                }]);
    }

    public function scopeWhereIsNotActive($query)
    {
        return $query->where('active', false);
    }

    public function scopeWhereHasInternalId($query)
    {
        return $query->where('internal_id','!=', null);
    }

    public function web_platform()
    {
        return $this->belongsTo(WebPlatform::class);
    }

    public function warehousePrices()
    {
        return $this->hasMany(ItemWarehousePrice::class, 'item_id')->select('item_id', 'price', 'warehouse_id');
    }

    public static function getSaleUnitPriceByWarehouse(Item $item, int $warehouseId): string
    {
        $warehousePrice = $item->warehousePrices->where('item_id', $item->id)
            ->where('warehouse_id', $warehouseId)
            ->first();

        $price = $warehousePrice ? $warehousePrice->price : $item->sale_unit_price;
        return number_format($price, 4, ".", "");
    }

    /**
     * @return array
     */
    public function getItemDataToQuotation() {
        return $this->getDataItem('Quotation');
    }

    protected function getDataItem($origin ='') {
        $full_description = $this->getFullDescription($origin);
        $warehouse_id = $full_description['warehouse_id'];
        return [
            'id' => $this->id,
            'full_description' => $full_description['full_description'],
            'model' => $this->model,
            'brand' => $full_description['brand'],
            'warehouse_description' => $full_description['warehouse_description'],
            'category' => $full_description['category'],
            'stock' => $full_description['stock'],
            'internal_id' => $this->internal_id,
            'description' => $this->description,
            'currency_type_id' => $this->currency_type_id,
            'currency_type_symbol' => $this->currency_type->symbol,
            'sale_unit_price' => Item::getSaleUnitPriceByWarehouse($this,$warehouse_id),
            'purchase_unit_price' => $this->purchase_unit_price,
            'unit_type_id' => $this->unit_type_id,
            'sale_affectation_igv_type_id' => $this->sale_affectation_igv_type_id,
            'purchase_affectation_igv_type_id' => $this->purchase_affectation_igv_type_id,
            'calculate_quantity' => (bool) $this->calculate_quantity,
            'has_igv' => (bool) $this->has_igv,
            'has_plastic_bag_taxes' => (bool) $this->has_plastic_bag_taxes,
            'amount_plastic_bag_taxes' => $this->amount_plastic_bag_taxes,
            'is_set'                           => (bool)$this->is_set,
            'item_unit_types' => collect($this->item_unit_types)->transform(function($row) {
                return [
                    'id' => $row->id,
                    'description' => "{$row->description}",
                    'item_id' => $row->item_id,
                    'unit_type_id' => $row->unit_type_id,
                    'quantity_unit' => $row->quantity_unit,
                    'price1' => $row->price1,
                    'price2' => $row->price2,
                    'price3' => $row->price3,
                    'price_default' => $row->price_default,
                ];
            }),
            'warehouses' => collect($this->warehouses)->transform(function($row) use($warehouse_id){
                return [
                    'warehouse_description' => $row->warehouse->description,
                    'stock' => $row->stock,
                    'warehouse_id' => $row->warehouse_id,
                    'checked' => ($row->warehouse_id == $warehouse_id) ? true : false,
                ];
            }),
            'attributes' => $this->attributes ? $this->attributes : [],
            'lots_group' => collect($this->lots_group)->transform(function($row){
                return [
                    'id'  => $row->id,
                    'code' => $row->code,
                    'quantity' => $row->quantity,
                    'date_of_due' => $row->date_of_due,
                    'checked'  => false
                ];
            }),
            'lots' => [],
            'lots_enabled' => (bool) $this->lots_enabled,
            'series_enabled' => (bool) $this->series_enabled,

        ];
    }

    /**
     * Devuelve el standar de estrucutra para documentos, es migrado de app/Http/Controllers/Tenant/DocumentController.php
     * @return array
     * @example
     * <?php
     *         $item->getItemDataToDocument();
     * ?>
     */
    public function getItemDataToDocument(){
        return $this->getDataItem();
    }

    public function getFullDescription($full_description = '') {

        $desc = ($this->internal_id) ? $this->internal_id.' - '.$this->description : $this->description;
        $category = ($this->category) ? " - {$this->category->name}" : '';
        $brand = ($this->brand) ? " - {$this->brand->name}" : '';
        $desc = "{$desc} - {$brand}";
        if ($full_description === 'Quotation') {
            $desc = "{$desc} {$category} {$brand}";
        }

        $establishment_id = auth()->user()->establishment_id;
        $warehouse = ModuleWarehouse::where('establishment_id', $establishment_id)->first();

        if ($this->unit_type_id != 'ZZ') {
            if (isset($this['stock'])) {
                $warehouse_stock = number_format($this['stock'], 2);
            } else {
                $warehouse_stock = ($this->warehouses && $warehouse)
                    ?
                    number_format($this->warehouses->where('warehouse_id', $warehouse->id)->first()->stock, 2)
                    :
                    0;
            }

            $stock = ($this->warehouses && $warehouse) ? "{$warehouse_stock}" : '';
        } else {
            $stock = '';
        }


        return [
            'full_description'      => $desc,
            'brand'                 => $brand,
            'category'              => $category,
            'stock'                 => $stock,
            'warehouse_description' => $warehouse->description,
            'warehouse_id' => $warehouse->id,

        ];
    }
}
