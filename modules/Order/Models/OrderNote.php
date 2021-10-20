<?php

    namespace Modules\Order\Models;

    use App\Models\Tenant\Catalogs\CurrencyType;
    use App\Models\Tenant\Dispatch;
    use App\Models\Tenant\Document;
    use App\Models\Tenant\GuideFile;
    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\PaymentMethodType;
    use App\Models\Tenant\Person;
    use App\Models\Tenant\Quotation;
    use App\Models\Tenant\SaleNote;
    use App\Models\Tenant\SoapType;
    use App\Models\Tenant\StateType;
    use App\Models\Tenant\User;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Support\Collection;
    use Modules\Inventory\Models\InventoryKardex;
    use Modules\Item\Models\ItemLot;

    /**
     * Class OrderNote
     *
     * @property                                                                                $quotation_id
     * @package Modules\Order\Models
     * @mixin ModelTenant
     * @property CurrencyType                                                                   $currency_type
     * @property \Illuminate\Database\Eloquent\Collection|Document[]                            $documents
     * @property int|null                                                                       $documents_count
     * @property mixed                                                                          $charges
     * @property mixed                                                                          $customer
     * @property mixed                                                                          $detraction
     * @property mixed                                                                          $discounts
     * @property mixed                                                                          $establishment
     * @property mixed                                                                          $guides
     * @property mixed                                                                          $identifier
     * @property mixed                                                                          $legends
     * @property mixed                                                                          $number_full
     * @property mixed                                                                          $number_to_letter
     * @property mixed                                                                          $perception
     * @property mixed                                                                          $prepayments
     * @property mixed                                                                          $related
     * @property \Illuminate\Database\Eloquent\Collection|GuideFile[]                           $guide_files
     * @property int|null                                                                       $guide_files_count
     * @property \Illuminate\Database\Eloquent\Collection|InventoryKardex[]                     $inventory_kardex
     * @property int|null                                                                       $inventory_kardex_count
     * @property \Illuminate\Database\Eloquent\Collection|OrderNoteItem[]                       $items
     * @property int|null                                                                       $items_count
     * @property PaymentMethodType                                                              $payment_method_type
     * @property Person                                                                         $person
     * @property \Illuminate\Database\Eloquent\Collection|SaleNote[]                            $sale_notes
     * @property int|null                                                                       $sale_notes_count
     * @property SoapType                                                                       $soap_type
     * @property StateType                                                                      $state_type
     * @property User                                                                           $user
     * @method static Builder|OrderNote newModelQuery()
     * @method static Builder|OrderNote newQuery()
     * @method static Builder|OrderNote query()
     * @method static Builder|OrderNote whereDefaultState($params)
     * @method static Builder|OrderNote wherePendingState($params)
     * @method static Builder|OrderNote whereProcessedState($params)
     * @method static Builder|OrderNote whereTypeUser()
     */
    class OrderNote extends ModelTenant
    {
        protected $with = [
            'user',
            'soap_type',
            'state_type',
            'currency_type',
            'items'
        ];

        protected $fillable = [
            'id',
            'user_id',
            'external_id',
            'establishment_id',
            'establishment',
            'soap_type_id',
            'state_type_id',
            'payment_method_type_id',
            'prefix',
            'date_of_issue',
            'time_of_issue',
            'date_of_due',
            'delivery_date',
            'customer_id',
            'customer',
            'currency_type_id',
            'exchange_rate_sale',
            'total_prepayment',
            'total_discount',
            'total_charge',
            'total_exportation',
            'total_free',
            'total_taxed',
            'total_unaffected',
            'total_exonerated',
            'total_igv',
            'total_base_isc',
            'total_isc',
            'total_base_other_taxes',
            'total_other_taxes',
            'total_taxes',
            'total_value',
            'total',
            'charges',
            'discounts',
            'prepayments',
            'guides',
            'related',
            'perception',
            'detraction',
            'legends',
            'filename',
            'shipping_address',
            'quotation_id',
            'observation',
            'total_igv_free',

        ];

        protected $casts = [
            'date_of_issue' => 'date',
            'date_of_due' => 'date',
            'delivery_date' => 'date',
            'quotation_id' => 'int',
        ];

        public function getEstablishmentAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setEstablishmentAttribute($value)
        {
            $this->attributes['establishment'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getCustomerAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setCustomerAttribute($value)
        {
            $this->attributes['customer'] = (is_null($value)) ? null : json_encode($value);
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

        public function getPrepaymentsAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setPrepaymentsAttribute($value)
        {
            $this->attributes['prepayments'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getGuidesAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setGuidesAttribute($value)
        {
            $this->attributes['guides'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getRelatedAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setRelatedAttribute($value)
        {
            $this->attributes['related'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getPerceptionAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setPerceptionAttribute($value)
        {
            $this->attributes['perception'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getDetractionAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setDetractionAttribute($value)
        {
            $this->attributes['detraction'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getLegendsAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setLegendsAttribute($value)
        {
            $this->attributes['legends'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getIdentifierAttribute()
        {
            return $this->prefix . '-' . $this->id;
        }

        public function getNumberFullAttribute()
        {
            return $this->prefix . '-' . $this->id;
        }

        public function user()
        {
            return $this->belongsTo(User::class);
        }

        public function soap_type()
        {
            return $this->belongsTo(SoapType::class);
        }

        public function state_type()
        {
            return $this->belongsTo(StateType::class);
        }

        public function person()
        {
            return $this->belongsTo(Person::class, 'customer_id');
        }


        public function currency_type()
        {
            return $this->belongsTo(CurrencyType::class, 'currency_type_id');
        }

        public function items()
        {
            return $this->hasMany(OrderNoteItem::class);
        }


        public function documents()
        {
            return $this->hasMany(Document::class);
        }

        public function sale_notes()
        {
            return $this->hasMany(SaleNote::class);
        }

        public function payment_method_type()
        {
            return $this->belongsTo(PaymentMethodType::class);
        }

        public function getNumberToLetterAttribute()
        {
            $legends = $this->legends;
            $legend = collect($legends)->where('code', '1000')->first();
            return $legend->value;
        }

        public function scopeWhereTypeUser(Builder $query)
        {
            $user = auth()->user();
            return ($user->type == 'seller') ? $query->where('user_id', $user->id) : null;
        }


        public function inventory_kardex()
        {
            return $this->morphMany(InventoryKardex::class, 'inventory_kardexable');
        }


        public function scopeWherePendingState(Builder $query, $params)
        {

            $query
                ->doesntHave('documents')
                ->whereBetween($params['date_range_type_id'], [$params['date_start'], $params['date_end']]);
            if ($params['person_id']) {
                $query->where('customer_id', $params['person_id']);
            } else {
                $query->where('user_id', $params['seller_id']);
            }
            return $query;

        }


        public function scopeWhereProcessedState(Builder $query, $params)
        {

            $query
                ->whereHas('documents')
                ->whereBetween($params['date_range_type_id'], [$params['date_start'], $params['date_end']]);
            if ($params['person_id']) {
                $query->where('customer_id', $params['person_id']);
            } else {
                $query->where('user_id', $params['seller_id']);
            }
            return $query;

        }


        public function scopeWhereDefaultState(Builder $query, $params)
        {

            $query->whereBetween($params['date_range_type_id'], [$params['date_start'], $params['date_end']]);
            if ($params['person_id']) {
                $query->where('customer_id', $params['person_id']);

            } else {
                $query->where('user_id', $params['seller_id']);
            }

            return $query;

        }

        /**
         * Establece el status anulado (11) para el pedido
         * Recorre los items, si estos tienen lotes serán habilitados nuevamente
         *
         * @return $this
         */
        public function VoidOrderNote(): OrderNote
        {
            $order_items = $this->items;
            /** @var OrderNoteItem $item */
            foreach ($order_items as $items) {
                $item = $items->item;
                if (property_exists($item, 'lots')) {
                    $lots = $item->lots;
                    $total_lot = count($lots);
                    for ($i = 0; $i < $total_lot; $i++) {
                        $lot = $lots[$i];
                        if (property_exists($lot, 'has_sale') && $lot->has_sale == true) {
                            $item_lot = ItemLot::find($lot->id);
                            if (!empty($item_lot) && $item_lot->has_sale == true) {
                                $item_lot->setHasSale(false)->push();
                            }
                        }
                    }
                }
            }
            $this->state_type_id = '11';
            return $this;
        }

        /**
         * @return array
         */
        public function getCollectionData()
        {
            $btn_generate = (count($this->documents) > 0 || count($this->sale_notes) > 0) ? false : true;
            $quotation = Quotation::find($this->quotation_id);
            if ($quotation !== null) {
                $quotation = [
                    'id' => $quotation->id,
                    'full_number' => $quotation->getNumberFullAttribute(),
                ];
            } else {
                $quotation = [];
            }
            $dispatches = $this->getDispatches()->transform(function ($row) {
                return $row->getCollectionData();
            });
            $state_type_description = $this->state_type->description;
            if (!empty($dispatches) && count($dispatches) != 0) {
                $state_type_description = 'Despachado';
                // #596
            }

            return [
                'id' => $this->id,
                'quotation' => (object)$quotation,
                'soap_type_id' => $this->soap_type_id,
                'external_id' => $this->external_id,
                'date_of_issue' => $this->date_of_issue->format('Y-m-d'),
                'date_of_due' => ($this->date_of_due) ? $this->date_of_due->format('Y-m-d') : null,
                'delivery_date' => ($this->delivery_date) ? $this->delivery_date->format('Y-m-d') : null,
                'identifier' => $this->identifier,
                'user_name' => $this->user->name,
                'customer_name' => $this->customer->name,
                'customer_number' => $this->customer->number,
                'currency_type_id' => $this->currency_type_id,
                'total_exportation' => number_format($this->total_exportation, 2),
                // 'total_free' => number_format($this->total_free,2),
                'total_unaffected' => number_format($this->total_unaffected, 2),
                'total_exonerated' => number_format($this->total_exonerated, 2),
                'total_taxed' => number_format($this->total_taxed, 2),
                'total_igv' => number_format($this->total_igv, 2),
                'total' => number_format($this->total, 2),
                'state_type_id' => $this->state_type_id,
                'state_type_description' => $state_type_description,
                'documents' => $this->documents->transform(function ($row) {
                    /** @var Document $row */
                    return [
                        'number_full' => $row->number_full,
                        'state_type_id' => $row->state_type_id,
                    ];
                }),
                'sale_notes' => $this->sale_notes->transform(function ($row) {
                    /** @var SaleNote $row */
                    return [
                        'identifier' => $row->identifier,
                        'state_type_id' => $row->state_type_id,
                    ];
                }),
                'btn_generate' => $btn_generate,
                'dispatches' => $dispatches,
                'created_at' => $this->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
                'print_a4' => url('') . "/order-notes/print/{$this->external_id}/a4",
            ];
        }

        /**
         * @return Dispatch[]|Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|Collection|mixed
         */
        public function getDispatches()
        {
            return Dispatch::where('reference_order_note_id', $this->id)->get();
        }

        /**
         * @return int|null
         */
        public function getQuotationId(): ?int
        {
            return $this->quotation_id;
        }

        /**
         * @param int|null $quotation_id
         *
         * @return OrderNote
         */
        public function setQuotationId(?int $quotation_id): OrderNote
        {
            $this->quotation_id = $quotation_id;
            return $this;
        }

        /**
         * @return HasMany
         */
        public function guide_files()
        {
            return $this->hasMany(GuideFile::class);
        }
    }
