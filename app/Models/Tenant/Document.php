<?php

    namespace App\Models\Tenant;

    use App\Http\Controllers\Tenant\DownloadController;
    use App\Models\Tenant\GuideFile;
    use App\Models\Tenant\Catalogs\CurrencyType;
    use App\Models\Tenant\Catalogs\DocumentType;
    use App\Traits\SellerIdTrait;
    use Eloquent;
    use ErrorException;
    use Exception;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\HasOne;
    use Illuminate\Database\Eloquent\Relations\MorphMany;
    use Illuminate\Database\Query\Builder;
    use Illuminate\Support\Collection;
    use Modules\BusinessTurn\Models\DocumentHotel;
    use Modules\BusinessTurn\Models\DocumentTransport;
    use Modules\Item\Models\WebPlatform;
    use Modules\Order\Models\OrderNote;
    use Modules\Sale\Models\TechnicalService;


    /**
 * App\Models\Tenant\Document
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Note[]            $affected_documents
 * @property-read int|null
 *                $affected_documents_count
 * @property-read CurrencyType                                               $currency_type
 * @property-read DocumentType                                               $document_type
 * @property-read \Illuminate\Database\Eloquent\Collection|DocumentFee[]     $fee
 * @property-read int|null                                                   $fee_count
 * @property-read mixed
 *                $additional_information
 * @property mixed                                                           $charges
 * @property-read mixed                                                      $company
 * @property mixed                                                           $customer
 * @property mixed                                                           $data_json
 * @property mixed                                                           $detraction
 * @property mixed                                                           $discounts
 * @property-read mixed
 *                $download_external_cdr
 * @property-read mixed
 *                $download_external_pdf
 * @property-read mixed
 *                $download_external_xml
 * @property mixed                                                           $establishment
 * @property mixed                                                           $guides
 * @property-read mixed                                                      $is_editable
 * @property mixed                                                           $legends
 * @property-read mixed                                                      $number_full
 * @property-read mixed                                                      $number_to_letter
 * @property mixed                                                           $perception
 * @property mixed                                                           $prepayments
 * @property mixed                                                           $related
 * @property mixed
 *           $response_regularize_shipping
 * @property mixed
 *           $soap_shipping_response
 * @property-read Group                                                      $group
 * @property-read DocumentHotel|null                                         $hotel
 * @property-read \Illuminate\Database\Eloquent\Collection|InventoryKardex[] $inventory_kardex
 * @property-read int|null
 *                $inventory_kardex_count
 * @property-read Invoice|null                                               $invoice
 * @property-read \Illuminate\Database\Eloquent\Collection|DocumentItem[]    $items
 * @property-read int|null                                                   $items_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Kardex[]          $kardex
 * @property-read int|null                                                   $kardex_count
 * @property-read Note|null                                                  $note
 * @property-read OrderNote                                                  $order_note
 * @property-read PaymentMethodType
 *                $payment_method_type
 * @property-read \Illuminate\Database\Eloquent\Collection|DocumentPayment[] $payments
 * @property-read int|null                                                   $payments_count
 * @property-read Person                                                     $person
 * @property-read Quotation                                                  $quotation
 * @property-read \Illuminate\Database\Eloquent\Collection|Dispatch[]        $reference_guides
 * @property-read int|null
 *                $reference_guides_count
 * @property-read SaleNote                                                   $sale_note
 * @property-read User                                                       $seller
 * @property-read SoapType                                                   $soap_type
 * @property-read StateType                                                  $state_type
 * @property-read SummaryDocument|null                                       $summary_document
 * @property-read DocumentTransport|null                                     $transport
 * @property-read User                                                       $user
 * @method static \Illuminate\Database\Eloquent\Builder|Document newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Document newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Document query()
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereAffectationTypePrepayment($type)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereHasPrepayment()
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereNotSent()
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereRegularizeShipping()
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereStateTypeAccepted()
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereTypeUser()
 * @method static \Illuminate\Database\Eloquent\Builder|Document WhereEstablishmentId()
 * @mixin Eloquent
 * @property \App\Models\Tenant\Dispatch $dispatch
 * @property \Illuminate\Database\Eloquent\Collection|GuideFile[] $guide_files
 * @property int|null $guide_files_count
 * @property \App\Models\Tenant\PaymentCondition $payment_condition
 * @property TechnicalService $technical_service
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereEstablishmentId($establishment_id = 0)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereValuedKardexFormatSunat($params)
 */
    class Document extends ModelTenant
    {
        use SellerIdTrait;

        protected $with = [
            'user',
            'soap_type',
            'state_type',
            'document_type',
            'currency_type',
            'group',
            'items',
            'invoice',
            'note',
            'payments',
            'fee'
        ];
        public static function boot()
        {
            parent::boot();
            static::creating(function (self $model) {
                self::adjustSellerIdField($model);
            });

        }
        protected $fillable = [
            'user_id',
            'external_id',
            'establishment_id',
            'establishment',
            'soap_type_id',
            'state_type_id',
            'ubl_version',
            'group_id',
            'document_type_id',
            'series',
            'number',
            'date_of_issue',
            'time_of_issue',
            'customer_id',
            'customer',
            'currency_type_id',
            'purchase_order',
            'quotation_id',
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
            'additional_information',
            'filename',
            'hash',
            'qr',
            'has_xml',
            'has_pdf',
            'has_cdr',
            'has_prepayment',
            'affectation_type_prepayment',
            'data_json',
            'send_server',
            'shipping_status',
            'sunat_shipping_status',
            'query_status',
            'total_plastic_bag_taxes',
            'sale_note_id',
            'success_shipping_status',
            'success_sunat_shipping_status',
            'success_query_status',
            'plate_number',
            'total_canceled',
            'order_note_id',
            'soap_shipping_response',
            'pending_amount_prepayment',
            'payment_method_type_id',
            'regularize_shipping',
            'response_regularize_shipping',
            'seller_id',
            'reference_data',
            'terms_condition',
            'payment_condition_id',
            'is_editable',
            'dispatch_id',
            'subtotal',
            'total_igv_free',
            'technical_service_id',
            // 'pending_amount_detraction',
            'total_pending_payment', //usado para detracciones - retenciones
            'retention',
        ];

        protected $casts = [
            'date_of_issue' => 'date',
        ];

        /**
         * Devuelve el ultimo numero por serie, si no existe devielve 0
         *
         * @param string $serie
         *
         * @return int
         */
        public static function getLastNumberBySerie($serie)
        {
            $t = Document::where('series', $serie)->select('number')->orderby('number', 'DESC')->first();
            if (!empty($t)) {
                return $t->number;
            }
            return 0;
        }

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

        public function getDataJsonAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setDataJsonAttribute($value)
        {
            $this->attributes['data_json'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getSoapShippingResponseAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setSoapShippingResponseAttribute($value)
        {
            $this->attributes['soap_shipping_response'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getResponseRegularizeShippingAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setResponseRegularizeShippingAttribute($value)
        {
            $this->attributes['response_regularize_shipping'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getRetentionAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setRetentionAttribute($value)
        {
            $this->attributes['retention'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getAdditionalInformationAttribute($value)
        {
            $arr = explode('|', $value);
            return $arr;
        }

        /**
         * @return BelongsTo
         */
        public function user()
        {
            return $this->belongsTo(User::class);
        }

        /**
         * @return BelongsTo
         */
        public function seller()
        {
            return $this->belongsTo(User::class);
        }

        /**
         * @return BelongsTo
         */
        public function soap_type()
        {
            return $this->belongsTo(SoapType::class);
        }

        /**
         * @return BelongsTo
         */
        public function state_type()
        {
            return $this->belongsTo(StateType::class);
        }

        /**
         * @return BelongsTo
         */
        public function person()
        {
            return $this->belongsTo(Person::class, 'customer_id');
        }

        /**
         * @return BelongsTo
         */
        public function group()
        {
            return $this->belongsTo(Group::class);
        }

        /**
         * @return BelongsTo
         */
        public function document_type()
        {
            return $this->belongsTo(DocumentType::class, 'document_type_id');
        }

        /**
         * @return BelongsTo
         */
        public function currency_type()
        {
            return $this->belongsTo(CurrencyType::class, 'currency_type_id');
        }

        /**
         * @return Company|Model|mixed|object|null
         */
        public function getCompanyAttribute()
        {
            return Company::first();
        }

        /**
         * @return HasOne
         */
        public function invoice()
        {
            return $this->hasOne(Invoice::class);
        }

        /**
         * @return HasOne
         */
        public function note()
        {
            return $this->hasOne(Note::class);
        }

        /**
         * @return HasMany
         */
        public function items()
        {
            return $this->hasMany(DocumentItem::class);
        }

        /**
         * @return HasMany
         */
        public function kardex()
        {
            return $this->hasMany(Kardex::class);
        }

        /**
         * @return HasMany
         */
        public function payments()
        {
            return $this->hasMany(DocumentPayment::class);
        }

        /**
         * @return HasMany
         */
        public function fee()
        {
            return $this->hasMany(DocumentFee::class);
        }

        /**
         * Se usa en la relacion con el inventario kardex en modules/Inventory/Traits/InventoryTrait.php.
         * Tambien se debe tener en cuenta modules/Inventory/Providers/InventoryKardexServiceProvider.php y
         * app/Providers/KardexServiceProvider.php para la correcta gestion de kardex
         *
         * @return MorphMany
         */
        public function inventory_kardex()
        {
            return $this->morphMany(InventoryKardex::class, 'inventory_kardexable');
        }

        /**
         * @return BelongsTo
         */
        public function quotation()
        {
            return $this->belongsTo(Quotation::class);
        }

        /**
         * @return BelongsTo
         */
        public function sale_note()
        {
            return $this->belongsTo(SaleNote::class, 'sale_note_id');
        }

        /**
         * @return BelongsTo
         */
        public function dispatch()
        {
            return $this->belongsTo(Dispatch::class, 'dispatch_id');
        }

        /**
         * @return HasOne
         */
        public function hotel()
        {
            return $this->hasOne(DocumentHotel::class);
        }

        /**
         * @return HasOne
         */
        public function transport()
        {
            return $this->hasOne(DocumentTransport::class);
        }

        /**
         * @return string
         */
        public function getNumberFullAttribute()
        {
            return $this->series . '-' . $this->number;
        }

        public function getNumberToLetterAttribute()
        {
            $legends = $this->legends;
            $legend = collect($legends)->where('code', '1000')->first();
            return $legend->value;
        }

        /**
         * @return string
         */
        public function getDownloadExternalXmlAttribute()
        {
            return route('tenant.download.external_id', ['model' => 'document', 'type' => 'xml', 'external_id' => $this->external_id]);
        }

        /**
         * @return string
         */
        public function getDownloadExternalPdfAttribute()
        {
            return route('tenant.download.external_id', ['model' => 'document', 'type' => 'pdf', 'external_id' => $this->external_id]);
        }

        /**
         * @return string
         */
        public function getDownloadExternalCdrAttribute()
        {
            return route('tenant.download.external_id', ['model' => 'document', 'type' => 'cdr', 'external_id' => $this->external_id]);
        }

        /**
         * @param $query
         *
         * @return null
         */
        public function scopeWhereTypeUser($query)
        {
            /** @var User $user */
            $user = auth()->user();
            return ($user->type === 'admin') ? null : $query->where('user_id', $user->id)->orWhere('seller_id', $user->id)->latest();
            // return ($user->type == 'seller') ? $query->where('user_id', $user->id) : null;
        }

        /**
         * @param $query
         *
         * @return mixed
         */
        public function scopeWhereNotSent($query)
        {
            return $query->whereIn('state_type_id', ['01', '03'])->where('date_of_issue', '<=', date('Y-m-d'));
        }

        /**
         * @return HasMany
         */
        public function affected_documents()
        {
            return $this->hasMany(Note::class, 'affected_document_id');
        }

        /**
         * @param $query
         *
         * @return mixed
         */
        public function scopeWhereHasPrepayment($query)
        {
            return $query->where([['has_prepayment', true], ['was_deducted_prepayment', false], ['state_type_id', '05']]);
        }

        /**
         * @return HasMany
         */
        public function reference_guides()
        {
            return $this->hasMany(Dispatch::class, 'reference_document_id', 'id');
        }

        /**
         * @return HasOne
         */
        public function summary_document()
        {
            return $this->hasOne(SummaryDocument::class);
        }

        /**
         * @param $query
         * @param $type
         *
         * @return mixed
         */
        public function scopeWhereAffectationTypePrepayment($query, $type)
        {
            return $query->where('affectation_type_prepayment', $type);
        }

        /**
         * @param $query
         *
         * @return mixed
         */
        public function scopeWhereStateTypeAccepted($query)
        {
            return $query->whereIn('state_type_id', ['01', '03', '05', '07', '13']);
        }

        /**
         * @return BelongsTo
         */
        public function payment_method_type()
        {
            return $this->belongsTo(PaymentMethodType::class);
        }

        /**
         * @param $query
         *
         * @return mixed
         */
        public function scopeWhereRegularizeShipping($query)
        {
            return $query->where('state_type_id', '01')->where('regularize_shipping', true);
        }

        /**
         * @return BelongsTo
         */
        public function order_note()
        {
            return $this->belongsTo(OrderNote::class);
        }

        /**
         * @return BelongsTo
         */
        public function technical_service()
        {
            return $this->belongsTo(TechnicalService::class);
        }

        /**
         * @return BelongsTo
         */
        public function payment_condition()
        {
            return $this->belongsTo(PaymentCondition::class);
        }

        /**
         * @param $value
         *
         * @return bool
         */
        public function getIsEditableAttribute($value)
        {
            return $value ? true : false;
        }

        /**
         * Evalua si es posible borrarlo basado en las condiciones:
         * regularize_shipping y response_regularize_shipping no este vacio
         * El documento este replicado  en series y numero
         *
         * @return bool
         */
        public function canDelete()
        {
            if (!empty($this->regularize_shipping) &&
                !empty($this->response_regularize_shipping)) {
                $duplicated = self::where([
                    'series' => $this->series,
                    'number' => $this->number,
                ])->where('id', '!=', $this->id)->first();
                if (!empty($duplicated)) {
                    return true;
                }
            }
            return false;
        }

        /**
         * Actualiza los pdf que se encuentran en las carpetas
         *
         * @return $this
         * @throws Exception
         */
        public function updatePdfs()
        {
            // se genera un solo formato para ser guardado en storage, en este caso esta quedando el ultimo formato, en una actualizacion se podria deifinir por configuracion cual formato es el que esta asignado por defecto
            $formats = [
                'ticket',
                'a5',
                'a4',
            ];
            $DownloadController = new DownloadController();
            foreach ($formats as $format) {
                try {
                    $DownloadController
                        ->toPrint('Document', $this->external_id, $format);
                } catch (ErrorException $e) {
                    // do nothing
                }
            }
            return $this;

        }

        /**
         * Devuelve notas de credito o debito que afectan al documento
         *
         * @return Note[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Builder[]|Collection|mixed
         */
        public function getNotes()
        {
            return Note::where('affected_document_id', $this->id)->get();
        }

        /**
         * @return float
         */
        public function getTotal(): float
        {
            return $this->total;
        }

        /**
         * @param float $total
         *
         * @return Document
         */
        public function setTotal(float $total): Document
        {
            $this->total = $total;
            return $this;
        }

        /**
         * Retorna una coleccion de nota de ventas con el formato especificado
         *
         * @return SaleNote[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Builder[]|Collection|mixed
         */
        public function getNvCollection()
        {

            return SaleNote::where('document_id', $this->id)
                ->orWhere('id', $this->sale_note_id)
                ->get()
                ->transform(function ($sale_note) {
                    /** @var SaleNote $sale_note */
                    return $sale_note->getCollectionData();
                });
        }

        /**
         * @return array
         */
        public function getOrderNoteCollection()
        {
            $orderNote = OrderNote::find($this->order_note_id);
            if ($orderNote === null) return [];
            return $orderNote->getCollectionData();
        }

        /**
         * Devuelve una coleccion de plataformas web basado en los items.
         *
         * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Builder[]|Collection|mixed|WebPlatform|WebPlatform[]
         */
        public function getPlatformThroughItems()
        {
            /**
             * @var \Illuminate\Database\Eloquent\Collection $items
             * @var WebPlatform                              $web_platforms
             */
            $items = $this->items->pluck('item_id');
            $web_platform_table_name = (new WebPlatform())->getTable();
            $item_table_name = (new Item())->getTable();
            return WebPlatform::leftJoin('items', "$web_platform_table_name.id", '=', "$item_table_name.web_platform_id")
                ->select("$web_platform_table_name.id", "$web_platform_table_name.name")
                ->wherein("$item_table_name.id", $items)
                ->get();
        }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeWhereValuedKardexFormatSunat($query, $params)
    {
        return $query->whereStateTypeAccepted()
                    ->whereTypeUser()
                    ->whereBetween('date_of_issue', [$params->date_start, $params->date_end]);
    }


        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function guide_files()
        {
            return $this->hasMany(GuideFile::class);
        }

        /**
         * @param \Illuminate\Database\Eloquent\Builder $query
         * @param int                                   $establishment_id
         *
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public function scopeWhereEstablishmentId(\Illuminate\Database\Eloquent\Builder $query,$establishment_id = 0){

            if($establishment_id != 0){
                $query->where('establishment_id', $establishment_id);
            }
            return $query;
        }

        /**
         * Devuelve el vendedor asociado, Si seller id es nulo, devolverá el usuario del campo user.
         * @return User
         */
        public function getSellerData(){
            if(!empty($this->seller_id)){
                return $this->seller;
            }
            return $this->user;

        }
    }
