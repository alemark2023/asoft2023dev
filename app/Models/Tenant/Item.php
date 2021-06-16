<?php

namespace App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\Catalogs\UnitType;
use Illuminate\Support\Facades\Config;
use Modules\Account\Models\Account;
use Modules\Inventory\Models\Warehouse;
use Modules\Item\Models\Category;
use Modules\Item\Models\Brand;
use Modules\Item\Models\ItemLot;
use Modules\Item\Models\ItemLotsGroup;
use Modules\Item\Models\WebPlatform;


/**
 * Class Item
 *
 * @package App\Models\Tenant
 * @mixin  ModelTenant
 */
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
        'sanitary',
        'cod_digemid',
        // 'warehouse_id'
    ];

    protected $casts = [
        'date_of_due' => 'date'
    ];

    /**
     * @return mixed
     */
    public function getSanitary() {
        return $this->sanitary;
    }

    /**
     * @param mixed $sanitary
     *
     * @return Item
     */
    public function setSanitary($sanitary) {
        $this->sanitary = $sanitary;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodDigemid() {
        return $this->cod_digemid;
    }

    /**
     * @param mixed $cod_digemid
     *
     * @return Item
     */
    public function setCodDigemid($cod_digemid) {
        $this->cod_digemid = $cod_digemid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param mixed $description
     *
     * @return Item
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

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
     * Devuelve la esuctura de item para los select correspondientes.
     *
     * @param \App\Models\Tenant\Warehouse|\Modules\Inventory\Models\Warehouse $warehouse
     * @param false $extended
     *
     * @return array
     */
    public function getFullDescription($warehouse, $extended = false) {

        $desc = ($this->internal_id) ? $this->internal_id.' - '.$this->description : $this->description;
        $category = ($this->category) ? "{$this->category->name}" : '';
        $brand = ($this->brand) ? "{$this->brand->name}" : '';
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
        if($extended == false) {
            $desc = "{$desc} - {$brand}";
        }else {
            $desc = "{$desc} {$category} {$brand}";
        }
        return [
            'full_description'      => $desc,
            'brand'                 => $brand,
            'category'              => $category,
            'stock'                 => $stock,
            'warehouse_description' => $warehouse->description,
        ];
    }

    /**
     * Devuelve un estandar de estructura para items.
     *
     * Es utilizado en :
     * app/Http/Controllers/Tenant/DocumentController.php
     * modules/Order/Http/Controllers/OrderNoteController.php
     *
     * @param \App\Models\Tenant\Warehouse|\Modules\Inventory\Models\Warehouse|null $warehouse
     * @param false                                    $with_lots_has_sale
     * @param false                                    $extended_description
     *
     * @return array
     */
    public function getDataToItemModal($warehouse = null, $with_lots_has_sale = false, $extended_description = false) {

        if ($warehouse == null) {
            $establishment_id = auth()->user()->establishment_id;
            $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();
        }
        $detail = $this->getFullDescription($warehouse,$extended_description);
        $realtion_item_unit_types = $this->item_unit_types;
        $lots_grp = $this->lots_group;
        $lots = [];
        if ($with_lots_has_sale == true) {
            $lots = $this->item_lots->where('has_sale', false)->transform(function ($row) {
                return [
                    'id'           => $row->id,
                    'series'       => $row->series,
                    'date'         => $row->date,
                    'item_id'      => $row->item_id,
                    'warehouse_id' => $row->warehouse_id,
                    'has_sale'     => (bool)$row->has_sale,
                    'lot_code'     => ($row->item_loteable_type)
                        ?
                        (isset($row->item_loteable->lot_code)
                            ?
                            $row->item_loteable->lot_code
                            :
                            null)
                        :
                        null,
                ];
            })->values();
        }


        $data = [
            'id'                               => $this->id,
            'full_description'                 => $detail['full_description'],
            'model'                            => $this->model,
            'brand'                            => $detail['brand'],
            'warehouse_description'            => $detail['warehouse_description'],
            'category'                         => $detail['category'],
            'stock'                            => $detail['stock'],
            'internal_id'                      => $this->internal_id,
            'description'                      => $this->description,
            'currency_type_id'                 => $this->currency_type_id,
            'currency_type_symbol'             => $this->currency_type->symbol,
            'sale_unit_price'                  => self::getSaleUnitPriceByWarehouse($this, $warehouse->id),
            'purchase_unit_price'              => $this->purchase_unit_price,
            'unit_type_id'                     => $this->unit_type_id,
            'sale_affectation_igv_type_id'     => $this->sale_affectation_igv_type_id,
            'purchase_affectation_igv_type_id' => $this->purchase_affectation_igv_type_id,
            'calculate_quantity'               => (bool)$this->calculate_quantity,
            'has_igv'                          => (bool)$this->has_igv,
            'has_plastic_bag_taxes'            => (bool)$this->has_plastic_bag_taxes,
            'amount_plastic_bag_taxes'         => $this->amount_plastic_bag_taxes,
            'item_unit_types'                  => collect($realtion_item_unit_types)->transform(function ($item_unit_types) {
                return [
                    'id'            => $item_unit_types->id,
                    'description'   => "{$this->description}",
                    'item_id'       => $item_unit_types->item_id,
                    'unit_type_id'  => $item_unit_types->unit_type_id,
                    'quantity_unit' => $item_unit_types->quantity_unit,
                    'price1'        => $item_unit_types->price1,
                    'price2'        => $item_unit_types->price2,
                    'price3'        => $item_unit_types->price3,
                    'price_default' => $item_unit_types->price_default,
                ];
            }),
            'warehouses'                       => collect($this->warehouses)->transform(function ($warehouses) use ($warehouse) {
                return [
                    'warehouse_description' => $warehouses->warehouse->description,
                    'stock'                 => $warehouses->stock,
                    'warehouse_id'          => $warehouses->warehouse_id,
                    'checked'               => ($warehouses->warehouse_id == $warehouse->id) ? true : false,
                ];
            }),
            'attributes'                       => $this->attributes ? $this->attributes : [],
            'lots_group'                       => collect($lots_grp)->transform(function ($lots_group) {
                return [
                    'id'          => $lots_group->id,
                    'code'        => $lots_group->code,
                    'quantity'    => $lots_group->quantity,
                    'date_of_due' => $lots_group->date_of_due,
                    'checked'     => false,
                ];
            }),
            'lots'                             => $lots,
            'lots_enabled'                     => (bool)$this->lots_enabled,
            'series_enabled'                   => (bool)$this->series_enabled,
            'is_set'                           => (bool)$this->is_set,


        ];

        return $data;
    }

    /**
     * Retorna un standar de nomenclatura para el modelo
     *
     * @param \App\Models\Tenant\Configuration|null $configuration
     *
     * @return array
     */
    public function getCollectionData(Configuration $configuration = null){
        if(empty($configuration)){
            $configuration =  Configuration::first();
        }
        $brand = null;
        if (!empty($this->brand_id)) {
            $brand = $this->brand()->first()->name;
        }
        $has_igv_description = null;
        $purchase_has_igv_description = null;

        $affectation_igv_types_exonerated_unaffected = ['20', '21', '30', '31', '32', '33', '34', '35', '36', '37'];

        if (in_array($this->sale_affectation_igv_type_id, $affectation_igv_types_exonerated_unaffected)) {
            $has_igv_description = 'No';
        } else {
            $has_igv_description = ((bool)$this->has_igv) ? 'Si' : 'No';
        }

        if (in_array($this->purchase_affectation_igv_type_id, $affectation_igv_types_exonerated_unaffected)) {
            $purchase_has_igv_description = 'No';
        } else {
            $purchase_has_igv_description = ((bool)$this->purchase_has_igv) ? 'Si' : 'No';
        }

        return [
            'id'                           => $this->id,
            'unit_type_id'                 => $this->unit_type_id,
            'description'                  => $this->description,
            'name'                         => $this->name,
            'second_name'                  => $this->second_name,
            'model'                        => $this->model,
            'barcode'                      => $this->barcode,
            'brand'                        => $brand,
            'warehouse_id'                 => $this->warehouse_id,
            'internal_id'                  => $this->internal_id,
            'item_code'                    => $this->item_code,
            'item_code_gs1'                => $this->item_code_gs1,
            'stock'                        => $this->getStockByWarehouse(),
            'stock_min'                    => $this->stock_min,
            'currency_type_id'             => $this->currency_type_id,
            'currency_type_symbol'         => $this->currency_type->symbol,
            'sale_affectation_igv_type_id' => $this->sale_affectation_igv_type_id,
            'amount_sale_unit_price'       => $this->sale_unit_price,
            'calculate_quantity'           => (bool)$this->calculate_quantity,
            'has_igv'                      => (bool)$this->has_igv,
            'active'                       => (bool)$this->active,
            'has_igv_description'          => $has_igv_description,
            'purchase_has_igv_description' => $purchase_has_igv_description,
            'sale_unit_price'              => "{$this->currency_type->symbol} {$this->sale_unit_price}",
            'purchase_unit_price'          => "{$this->currency_type->symbol} {$this->purchase_unit_price}",
            'created_at'                   => ($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : '',
            'updated_at'                   => ($this->created_at) ? $this->updated_at->format('Y-m-d H:i:s') : '',
            'warehouses'                   => collect($this->warehouses)->transform(function ($row) {
                return [
                    'warehouse_description' => $row->warehouse->description,
                    'stock'                 => $row->stock,
                ];
            }),
            'apply_store'                  => (bool)$this->apply_store,
            'image_url'                    => ($this->image !== 'imagen-no-disponible.jpg')
                ? asset('storage'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'items'.DIRECTORY_SEPARATOR.$this->image)
                : asset("/logo/{$this->image}"),
            'image_url_medium'             => ($this->image_medium !== 'imagen-no-disponible.jpg')
                ? asset('storage'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'items'.DIRECTORY_SEPARATOR.$this->image_medium)
                : asset("/logo/{$this->image_medium}"),
            'image_url_small'              => ($this->image_small !== 'imagen-no-disponible.jpg')
                ? asset('storage'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'items'.DIRECTORY_SEPARATOR.$this->image_small)
                : asset("/logo/{$this->image_small}"),
            'tags'                         => $this->tags,
            'tags_id'                      => $this->tags->pluck('tag_id'),
            'item_unit_types'              => collect($this->item_unit_types)->transform(function ($row) use ($configuration) {
                return [
                    'id'            => $row->id,
                    'description'   => "{$row->description}",
                    'item_id'       => $row->item_id,
                    'unit_type_id'  => $row->unit_type_id,
                    'quantity_unit' => number_format($this->quantity_unit, $configuration->decimal_quantity, '.', ''),
                    'price1'        => number_format($this->price1, $configuration->decimal_quantity, '.', ''),
                    'price2'        => number_format($this->price2, $configuration->decimal_quantity, '.', ''),
                    'price3'        => number_format($this->price3, $configuration->decimal_quantity, '.', ''),
                    'price_default' => $this->price_default,
                ];
            }),


        ];
    }
}
