<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;
use Carbon\Carbon;

/**
 * Class SaleNote
 *
 * @package App\Models\Tenant
 * @mixin \App\Models\Tenant\ModelTenant
 */
class SaleNote extends ModelTenant
{
    protected $with = ['user', 'soap_type', 'state_type', 'currency_type', 'items', 'payments'];

    protected $fillable = [
        'user_id',
        'external_id',
        'establishment_id',
        'establishment',
        'soap_type_id',
        'state_type_id',

        'prefix',

        'date_of_issue',
        'time_of_issue',
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
        'total_canceled',
        'quotation_id',
        'order_note_id',
        'apply_concurrency',
        'type_period',
        'quantity_period',
        'automatic_date_of_issue',
        'enabled_concurrency',
        'series',
        'number',
        'paid',
        'payment_method_type_id',
        'license_plate',
        'observation',
        'reference_data',
        'plate_number',
        'purchase_order',
        'due_date',
        'total_plastic_bag_taxes',
        'additional_information',
        'document_id',
        'seller_id',
];

    protected $casts = [
        'date_of_issue' => 'date',
        'automatic_date_of_issue' => 'date',
        'due_date' => 'date',
    ];

    /**
     * Obtiene la fecha de vencimiento
     *
     * @return mixed
     */
    public function getDueDate()
    {
        return $this->due_date;
    }

    /**
     * Establece la fecha de vencimiento
     *
     * @param mixed $due_date
     *
     * @return SaleNote
     */
    public function setDueDate($due_date)
    {
        $this->due_date = $due_date;
        return $this;
    }


    public function getEstablishmentAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setEstablishmentAttribute($value)
    {
        $this->attributes['establishment'] = (is_null($value))?null:json_encode($value);
    }

    public function getCustomerAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setCustomerAttribute($value)
    {
        $this->attributes['customer'] = (is_null($value))?null:json_encode($value);
    }

    public function getChargesAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setChargesAttribute($value)
    {
        $this->attributes['charges'] = (is_null($value))?null:json_encode($value);
    }

    public function getDiscountsAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setDiscountsAttribute($value)
    {
        $this->attributes['discounts'] = (is_null($value))?null:json_encode($value);
    }

    public function getPrepaymentsAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setPrepaymentsAttribute($value)
    {
        $this->attributes['prepayments'] = (is_null($value))?null:json_encode($value);
    }

    public function getGuidesAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setGuidesAttribute($value)
    {
        $this->attributes['guides'] = (is_null($value))?null:json_encode($value);
    }

    public function getRelatedAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setRelatedAttribute($value)
    {
        $this->attributes['related'] = (is_null($value))?null:json_encode($value);
    }

    public function getPerceptionAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setPerceptionAttribute($value)
    {
        $this->attributes['perception'] = (is_null($value))?null:json_encode($value);
    }

    public function getDetractionAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setDetractionAttribute($value)
    {
        $this->attributes['detraction'] = (is_null($value))?null:json_encode($value);
    }

    public function getLegendsAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setLegendsAttribute($value)
    {
        $this->attributes['legends'] = (is_null($value))?null:json_encode($value);
    }

    public function getIdentifierAttribute()
    {
        return $this->prefix.'-'.$this->id;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function state_type()
    {
        return $this->belongsTo(StateType::class);
    }

    public function person() {
        return $this->belongsTo(Person::class, 'customer_id');
    }


    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }

    public function items()
    {
        return $this->hasMany(SaleNoteItem::class);
    }

    public function kardex()
    {
        return $this->hasMany(Kardex::class);
    }

    public function inventory_kardex()
    {
        return $this->morphMany(InventoryKardex::class, 'inventory_kardexable');
    }

    public function payments()
    {
        return $this->hasMany(SaleNotePayment::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seller()
    {
        return $this->belongsTo(User::class);
    }

    public function getNumberToLetterAttribute()
    {
        $legends = $this->legends;
        $legend = collect($legends)->where('code', '1000')->first();
        return $legend->value;
    }

    public function getNumberFullAttribute()
    {
        $number_full = ($this->series && $this->number) ? $this->series.'-'.$this->number : $this->prefix.'-'.$this->id;

        return $number_full;
    }


    public function scopeWhereTypeUser($query)
    {
        $user = auth()->user();
        return ($user->type == 'seller') ? $query->where('user_id', $user->id) : null;
    }


    public function scopeWhereStateTypeAccepted($query)
    {
        return $query->whereIn('state_type_id', ['01','03','05','07','13']);
    }

    public function scopeWhereNotChanged($query)
    {
        return $query->where('changed', false);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function payment_method_type()
    {
        return $this->belongsTo(PaymentMethodType::class);
    }

    /**
     * Busca el ultimo numero basado en series y el prefijo.
     *
     * @param \App\Models\Tenant\SaleNote $model
     *
     * @return int
     */
    public static function getLastNumberByModel(SaleNote $model) {
        $sn = SaleNote::where(
            [
                'series' => $model->series,
                'prefix' => $model->prefix,
                // 'number',
            ])
                      ->select('number')
                      ->orderBy('number', 'desc')
                      ->first();
        $return = 0;
        if (!empty($sn)) {
            $return += $sn->number;
        }
        return $return + 1;
    }

    public static function FormatNumber($number,$decimal = 2){
        return number_format($number,$decimal);
    }

    /**
     * @return array
     */
    public function getCollectionData(){
        $total_paid = number_format($this->payments->sum('payment'), 2, '.', '');
        $total_pending_paid = number_format($this->total - $total_paid, 2, '.', '');
        $document_id = $this->document_id;
        // Normalmente, un documento tendrá el id de la NV,
        // cuando se hace un CPE a partir de varias NV,
        // se guarda el id del documento en el NV
        /** @var \Illuminate\Database\Eloquent\Collection $documents */
        $documents = $this->documents;
        if(!empty($document_id) && $documents->count() < 1){
            $documents = Document::where('id',$document_id)->get();
        }
        $total_documents = $documents->count();

        $btn_generate = ($total_documents > 0) ? false : true;
        $btn_payments = ($total_documents > 0) ? false : true;
        $due_date = (!empty($this->due_date)) ? $this->due_date->format('Y-m-d') : null;

        $this->seller_id = $this->user_id;
        $this->payments = $this->getTransformPayments();
        $message_text = '';
        if(!empty($this->number_full) && !empty($this->external_id)){
            $message_text = "Su comprobante de nota de venta {$this->number_full} ha sido generado correctamente, puede revisarlo en el siguiente enlace: ".
                url('')."/sale-notes/print/{$this->external_id}/a4".'';
        }

        return [
            'id'                           => $this->id,
            'soap_type_id'                 => $this->soap_type_id,
            'external_id'                  => $this->external_id,
            'date_of_issue'                => $this->date_of_issue->format('Y-m-d'),
            'identifier'                   => $this->identifier,
            'full_number'                  => $this->series.'-'.$this->number,
            'customer_name'                => $this->customer->name,
            'customer_number'              => $this->customer->number,
            'currency_type_id'             => $this->currency_type_id,
            'total_exportation'            => self::FormatNumber($this->total_exportation),
            'total_free'                   => self::FormatNumber($this->total_free),
            'total_unaffected'             => self::FormatNumber($this->total_unaffected),
            'total_exonerated'             => self::FormatNumber($this->total_exonerated),
            'total_taxed'                  => self::FormatNumber($this->total_taxed),
            'total_igv'                    => self::FormatNumber($this->total_igv),
            'total'                        => self::FormatNumber($this->total),
            'state_type_id'                => $this->state_type_id,
            'state_type_description'       => $this->state_type->description,
            'document_id'=>$this->document_id,
            'documents'                    => $documents->transform(function ($row) {
                /** @var \App\Models\Tenant\Document $row */
                return [
                    'id'          => $row->id,
                    'number_full' => $row->number_full,
                ];
            }),
            'btn_generate'                 => $btn_generate,
            'btn_payments'                 => $btn_payments,
            'changed'                      => (boolean)$this->changed,
            'enabled_concurrency'          => (boolean)$this->enabled_concurrency,
            'quantity_period'              => $this->quantity_period,
            'type_period'                  => $this->type_period,
            'apply_concurrency'            => (boolean)$this->apply_concurrency,
            'created_at'                   => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'                   => $this->updated_at->format('Y-m-d H:i:s'),
            'paid'                         => (bool)$this->paid,
            'total_canceled'               => (bool)$this->total_canceled,
            'license_plate'                => $this->license_plate,
            'total_paid'                   => $total_paid,
            'total_pending_paid'           => $total_pending_paid,
            'user_name'                    => ($this->user) ? $this->user->name : '',
            'quotation_number_full'        => ($this->quotation) ? $this->quotation->number_full : '',
            'sale_opportunity_number_full' => isset($this->quotation->sale_opportunity)
                ? $this->quotation->sale_opportunity->number_full : '',
            'number_full'                  => $this->number_full,
            'print_a4'                     => url('')."/sale-notes/print/{$this->external_id}/a4",
            'print_ticket' => url('')."/sale-notes/print/{$this->external_id}/ticket",
            'print_a5' => url('')."/sale-notes/print/{$this->external_id}/a5",
            'print_ticket_58' => url('')."/sale-notes/print/{$this->external_id}/ticket_58",
            'purchase_order'               => $this->purchase_order,
            'due_date'                     => $due_date,
            'sale_note' => $this,
            'seller_id' => $this->seller_id,
            'message_text' => $message_text,
            'serie' => $this->series,
            'number' => $this->number_full,
            // 'number' => $this->number,
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTransformPayments(){

        $payments =$this->payments()->get();
        return $payments->transform(function($row, $key){
            /** @var SaleNotePayment $row */
            return [
                'id' => $row->id,
                'sale_note_id' => $row->sale_note_id,
                'date_of_payment' => $row->date_of_payment->format('Y-m-d'),
                'payment_method_type_id' => $row->payment_method_type_id,
                'has_card' => $row->has_card,
                'card_brand_id' => $row->card_brand_id,
                'reference' => $row->reference,
                'payment' => $row->payment,
                'payment_method_type' => $row->payment_method_type,
                'payment_destination_id' => ($row->global_payment) ? ($row->global_payment->type_record == 'cash' ? 'cash':$row->global_payment->destination_id):null,
                'payment_filename' => ($row->payment_file) ? $row->payment_file->filename:null,
            ];
        });
    }
}
