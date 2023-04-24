<?php

namespace App\Models\Tenant;

use Modules\Finance\Models\GlobalPayment;
use Modules\Finance\Models\PaymentFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Modules\Payment\Models\PaymentLink;
use App\Traits\PaymentModelHelperTrait;


class DocumentPayment extends ModelTenant
{
    use PaymentModelHelperTrait;
    
    protected $with = ['payment_method_type', 'card_brand'];
    public $timestamps = false;

    protected $fillable = [
        'document_id',
        'date_of_payment',
        'payment_method_type_id',
        'has_card',
        'card_brand_id',
        'reference',
        'change',
        'payment',
        'payment_received',
    ];

    protected $casts = [
        'date_of_payment' => 'date',
        'payment_received' => 'bool',
    ];

    public function payment_method_type()
    {
        return $this->belongsTo(PaymentMethodType::class);
    }

    public function card_brand()
    {
        return $this->belongsTo(CardBrand::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    
    public function global_payment()
    {
        return $this->morphOne(GlobalPayment::class, 'payment');
    }

    public function associated_record_payment()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function payment_file()
    {
        return $this->morphOne(PaymentFile::class, 'payment');
    }

        
    /**
     * 
     * Filtros para obtener pagos en efectivo y con destino caja
     *
     * @param  Builder $query
     * @return Collection
     */
    public function scopeWhereFilterCashPayment($query)
    {
        return $query->where('payment_method_type_id', PaymentMethodType::CASH_PAYMENT_ID)
                    ->whereHas('global_payment', function($query){
                        return $query->where('destination_type', Cash::class);
                    });
    }

    
    /**
     * 
     * Obtener informacion del pago y registro origen relacionado
     *
     * @return array
     */
    public function getRowResourceCashPayment()
    {
        return [
            'type' => 'document',
            'type_transaction' => 'income',
            'type_transaction_description' => 'Venta',
            'date_of_issue' => $this->associated_record_payment->date_of_issue->format('Y-m-d'),
            'number_full' => $this->associated_record_payment->number_full,
            'acquirer_name' => $this->associated_record_payment->customer->name,
            'acquirer_number' => $this->associated_record_payment->customer->number,
            'currency_type_id' => $this->associated_record_payment->currency_type_id,
            'document_type_description' => $this->associated_record_payment->document_type->description,
            'payment_method_type_id' => $this->payment_method_type_id,
            'payment' => $this->associated_record_payment->isVoidedOrRejected() ? 0 : $this->payment,
        ];
    }
    

    public function payment_links()
    {
        return $this->morphMany(PaymentLink::class, 'payment');
    }

    
    /**
     * 
     * Retornar descripcion del pago
     *
     * @return string
     */
    public function getPaymentReceivedDescription()
    {
        $description = null;

        if(!is_null($this->payment_received))
        {
            $description = $this->payment_received ? 'SI' : 'NO';
        }

        return $description;
    }
    

    /**
     * 
     * Obtener relaciones necesarias o aplicar filtros para reporte pagos - finanzas
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeFilterRelationsPayments($query)
    {
        // \Log::info("document");
        return $query->generalPaymentsWithOutRelations()
                    ->with([
                        'payment_method_type' => function($payment_method_type){
                            $payment_method_type->select('id', 'description');
                        }, 
                    ]);
    }


    /**
     * 
     * Filtros para obtener pagos en efectivo
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeFilterCashPaymentWithoutDestination($query)
    {
        return $query->where('payment_method_type_id', PaymentMethodType::CASH_PAYMENT_ID);
    }

    
    /**
     * 
     * Filtros para obtener pagos con transferencia
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeFilterTransferPayment($query)
    {
        return $query->where('payment_method_type_id', PaymentMethodType::TRANSFER_PAYMENT_ID);
    }

}