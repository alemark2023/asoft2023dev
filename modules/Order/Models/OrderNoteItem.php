<?php

    namespace Modules\Order\Models;

    use App\Models\Tenant\Catalogs\AffectationIgvType;
    use App\Models\Tenant\Catalogs\PriceType;
    use App\Models\Tenant\Catalogs\SystemIscType;
    use App\Models\Tenant\Item;
    use App\Models\Tenant\ModelTenant;
    use App\Traits\AttributePerItems;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Modules\Inventory\Models\Warehouse;

    /**
     * Class OrderNoteItem
     *
     * @package Modules\Order\Models
     * @mixin ModelTenant
     * @property AffectationIgvType $affectation_igv_type
     * @property mixed              $attributes
     * @property mixed              $charges
     * @property mixed              $discounts
     * @property mixed              $item
     * @property OrderNote          $order_note
     * @property PriceType          $price_type
     * @property Item               $relation_item
     * @property SystemIscType      $system_isc_type
     * @property Warehouse          $warehouse
     * @method static Builder|OrderNoteItem newModelQuery()
     * @method static Builder|OrderNoteItem newQuery()
     * @method static Builder|OrderNoteItem query()
     * @method static Builder|OrderNoteItem whereDefaultState($params)
     * @method static Builder|OrderNoteItem wherePendingState($params)
     * @method static Builder|OrderNoteItem whereProcessedState($params)
     */
    class OrderNoteItem extends ModelTenant
    {
        use AttributePerItems;

        public $timestamps = false;
        protected $with = [
            'affectation_igv_type',
            'system_isc_type',
            'price_type'
        ];
        protected $fillable = [
            'order_note_id',
            'item_id',
            'item',
            'quantity',
            'unit_value',

            'affectation_igv_type_id',
            'total_base_igv',
            'percentage_igv',
            'total_igv',

            'system_isc_type_id',
            'total_base_isc',
            'percentage_isc',
            'total_isc',

            'total_base_other_taxes',
            'percentage_other_taxes',
            'total_other_taxes',
            'total_taxes',

            'price_type_id',
            'unit_price',

            'total_value',
            'total_charge',
            'total_discount',
            'total',

            'attributes',
            'charges',
            'discounts',
            'warehouse_id',
            'total_plastic_bag_taxes',
            'additional_information',
            'name_product_pdf'
        ];

        public function getItemAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setItemAttribute($value)
        {
            $this->attributes['item'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getAttributesAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setAttributesAttribute($value)
        {
            $this->attributes['attributes'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getChargesAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setChargesAttribute($value)
        {
            $this->attributes['charges'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getDiscountsAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setDiscountsAttribute($value)
        {
            $this->attributes['discounts'] = (is_null($value)) ? null : json_encode($value);
        }

        /**
         * @return BelongsTo
         */
        public function affectation_igv_type()
        {
            return $this->belongsTo(AffectationIgvType::class, 'affectation_igv_type_id');
        }

        /**
         * @return BelongsTo
         */
        public function system_isc_type()
        {
            return $this->belongsTo(SystemIscType::class, 'system_isc_type_id');
        }

        /**
         * @return BelongsTo
         */
        public function price_type()
        {
            return $this->belongsTo(PriceType::class, 'price_type_id');
        }


        /**
         * @return BelongsTo
         */
        public function order_note()
        {
            return $this->belongsTo(OrderNote::class, 'order_note_id');
        }


        /**
         * @param Builder $query
         * @param array   $params
         *
         * @return Builder
         */
        public function scopeWherePendingState(Builder $query, $params = [])
        {
            $query->whereHas('order_note', function ($q) use ($params) {
                if ($params['person_id']) {
                    $q->where('customer_id', $params['person_id']);
                } else {
                    $q->where('user_id', $params['seller_id']);
                }
                $q->whereTypeUser();
            });

            return $query;
        }


        /**
         * @param Builder $query
         * @param array   $params
         *
         * @return Builder
         */
        public function scopeWhereProcessedState(Builder $query, $params = [])
        {
            $query->whereHas('order_note', function ($q) use ($params) {
                $q->whereHas('documents');
                $q->whereBetween($params['date_range_type_id'], [$params['date_start'], $params['date_end']]);
                if ($params['person_id']) {
                    $q->where('customer_id', $params['person_id']);
                } else {
                    $q->where('user_id', $params['seller_id']);

                }
                $q->whereTypeUser();
            });
            return $query;

        }

        /**
         * @param Builder $query
         * @param array   $params
         *
         * @return Builder
         */
        public function scopeWhereDefaultState(Builder $query, $params = [])
        {
            $query->whereHas('order_note', function ($q) use ($params) {
                $q->whereBetween($params['date_range_type_id'], [$params['date_start'], $params['date_end']]);
                if ($params['person_id']) {
                    $q->where('customer_id', $params['person_id']);
                } else {
                    $q->where('user_id', $params['seller_id']);
                }
                $q->whereTypeUser();
            });

            return $query;


        }

        /**
         * @return BelongsTo
         */
        public function warehouse()
        {
            return $this->belongsTo(Warehouse::class);
        }

        /**
         * @return BelongsTo
         */
        public function relation_item()
        {
            return $this->belongsTo(Item::class, 'item_id');
        }

        /**
         * @return float
         */
        public function getTotalPlasticBagTaxes(): float
        {
            return $this->total_plastic_bag_taxes;
        }

        /**
         * @param float $total_plastic_bag_taxes
         *
         * @return OrderNoteItem
         */
        public function setTotalPlasticBagTaxes(float $total_plastic_bag_taxes): OrderNoteItem
        {
            $this->total_plastic_bag_taxes = $total_plastic_bag_taxes;
            return $this;
        }

        /**
         * @return string
         */
        public function getAdditionalInformation(): string
        {
            return $this->additional_information;
        }

        /**
         * @param string $additional_information
         *
         * @return OrderNoteItem
         */
        public function setAdditionalInformation(string $additional_information): OrderNoteItem
        {
            $this->additional_information = $additional_information;
            return $this;
        }

        /**
         * @param string $name_product_pdf
         *
         * @return OrderNoteItem
         */
        public function setNameProductPdf(string $name_product_pdf): OrderNoteItem
        {
            $this->name_product_pdf = $name_product_pdf;
            return $this;
        }

        /**
         * Devuelve el numero de la cantidad, si es int, devuelve el numero con 0 decimales
         *
         * @return mixed|string
         */
        public function getStringQty()
        {
            $int_qty = (int)$this->quantity;
            $qty = $this->quantity;
            if (is_int($qty)) {
                $qty = number_format($qty, 0);
            }
            return $qty;
        }

        /**
         * Devuelve unit_price formateado a string con N decimales
         *
         * @param int $decimal
         *
         * @return string
         */
        public function getStringUnitPrice($decimal = 2)
        {
            return number_format($this->unit_price, $decimal);
        }

        /**
         *  Devuelve total formateado a string con N decimales
         *
         * @param int $decimal
         *
         * @return string
         */
        public function getStringTotal($decimal = 2)
        {
            return number_format($this->total, $decimal);
        }

        /**
         *  Devuelve la descripcion del producto
         *
         * @return string
         */
        public function getTemplateDescription()
        {
            if (empty($this->name_product_pdf)) {
                return $this->item->description;
            }
            return $this->getNameProductPdf();

        }

        /**
         * @return string
         */
        public function getNameProductPdf(): string
        {
            return $this->name_product_pdf;
        }
    }
