<?php

namespace Modules\Finance\Models;

use App\Models\Tenant\{BankAccount, DocumentPayment, GlobalPaymentsRelations, PurchasePayment, SaleNotePayment, User};
use App\Models\Tenant\Cash;
use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\SoapType;
use Modules\Expense\Models\ExpensePayment;
use Modules\Pos\Models\CashTransaction;
use Modules\Sale\Models\ContractPayment;
use Modules\Sale\Models\QuotationPayment;
use Modules\Sale\Models\TechnicalServicePayment;

class GlobalPayment extends ModelTenant
{

    protected $fillable = [
        'soap_type_id',
        'destination_id',
        'destination_type',
        'payment_id',
        'payment_type',
        'user_id',
    ];

    protected static function boot() {
        parent::boot();
        static::creating(function ($model) {
        });
        static::saving(function ($model) {
        });
        static::updating(function ($model) {
        });
        static::created(function ($model) {
            self::SaveOnGlobalPaymentsRelations($model);
        });

        static::updated(function ($model) {
            self::SaveOnGlobalPaymentsRelations($model);
        });
    }

    /**
     * Guarda el modelo en las relaciones globales
     *
     * @param $model
     */
    public static function SaveOnGlobalPaymentsRelations(&$model){
        /** @var GlobalPayment $model */

        $data = [
            'global_payments_id'            => $model->id,
            'user_id'            => $model->user_id,
            'payment_type'            =>  $model->payment_type,
        ];
        if($model->destination_type === BankAccount::class){
            $data['bank_id'] = $model->destination_id;
        }else{
            $data['cash_id'] = $model->destination_id;
        }

        $payment_type = $model->payment_type;
        $payment_id = $model->payment_id;
        if($payment_type === DocumentPayment::class){
            $data['document_payment_id'] = $payment_id;
        }
        elseif($payment_type === ExpensePayment::class){
            $data['expense_payments_id'] = $payment_id;
        } elseif($payment_type === SaleNotePayment::class){
            $data['sale_note_payments_id'] = $payment_id;
        } elseif($payment_type === QuotationPayment::class){
            $data['quotation_payments_id'] = $payment_id;
        } elseif($payment_type === PurchasePayment::class){
            $data['purchase_payments_id'] = $payment_id;
        } elseif($payment_type === ContractPayment::class){
            $data['contract_payments_id'] = $payment_id;
        } elseif($payment_type === TechnicalServicePayment::class){
            $data['technical_service_payments_id'] = $payment_id;
        } elseif($payment_type === IncomePayment::class){
            $data['income_payments_id'] = $payment_id;
        } elseif($payment_type === CashTransaction::class){
            $data['cash_transactions_id'] = $payment_id;
        }else{
            $data = [];
        }
        if(!empty($data)) {
            $relation = GlobalPaymentsRelations::where($data)->first();
            if (empty($relation)) {
                $relation = new GlobalPaymentsRelations($data);
            }
            $relation->push();
        }

    }

    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    public function destination()
    {
        return $this->morphTo();
    }


    public function payment()
    {
        return $this->morphTo();
    }

    public function doc_payments()
    {
        return $this->belongsTo(DocumentPayment::class, 'payment_id')
                    ->wherePaymentType(DocumentPayment::class);
    }
    public function exp_payment()
    {
        return $this->belongsTo(ExpensePayment::class, 'payment_id')
                    ->wherePaymentType(ExpensePayment::class);
    }

    public function sln_payments()
    {
        return $this->belongsTo(SaleNotePayment::class, 'payment_id')
                    ->wherePaymentType(SaleNotePayment::class);
    }

    public function pur_payment()
    {
        return $this->belongsTo(PurchasePayment::class, 'payment_id')
                    ->wherePaymentType(PurchasePayment::class);
    }

    public function quo_payment()
    {
        return $this->belongsTo(QuotationPayment::class, 'payment_id')
                    ->wherePaymentType(QuotationPayment::class);
    }

    public function con_payment()
    {
        return $this->belongsTo(ContractPayment::class, 'payment_id')
                    ->wherePaymentType(ContractPayment::class);
    }

    public function inc_payment()
    {
        return $this->belongsTo(IncomePayment::class, 'payment_id')
                    ->wherePaymentType(IncomePayment::class);
    }

    public function cas_transaction()
    {
        return $this->belongsTo(CashTransaction::class, 'payment_id')
                    ->wherePaymentType(CashTransaction::class);
    }

    public function tec_serv_payment()
    {
        return $this->belongsTo(TechnicalServicePayment::class, 'payment_id')
                    ->wherePaymentType(TechnicalServicePayment::class);
    }

    public function getDestinationDescriptionAttribute()
    {
        return $this->destination_type === Cash::class ? 'CAJA GENERAL': "{$this->destination->bank->description} - {$this->destination->currency_type_id} - {$this->destination->description}";
    }

    public function getTypeRecordAttribute()
    {
        return $this->destination_type === Cash::class ? 'cash':'bank_account';
    }

    public function getInstanceTypeAttribute()
    {
        $instance_type = [
            DocumentPayment::class => 'document',
            SaleNotePayment::class => 'sale_note',
            PurchasePayment::class => 'purchase',
            ExpensePayment::class => 'expense',
            QuotationPayment::class => 'quotation',
            ContractPayment::class => 'contract',
            IncomePayment::class => 'income',
            CashTransaction::class => 'cash_transaction',
            TechnicalServicePayment::class => 'technical_service',
        ];

        return $instance_type[$this->payment_type];
    }

    public function getInstanceTypeDescriptionAttribute()
    {

        $description = null;

        switch ($this->instance_type) {
            case 'document':
                $description = 'CPE';
                break;
            case 'sale_note':
                $description = 'NOTA DE VENTA';
                break;
            case 'purchase':
                $description = 'COMPRA';
                break;
            case 'expense':
                $description = 'GASTO';
                break;
            case 'quotation':
                $description = 'COTIZACIÓN';
                break;
            case 'contract':
                $description = 'CONTRATO';
                break;
            case 'income':
                $description = 'INGRESO';
                break;
            case 'cash_transaction':
                $description = 'INGRESO';
                break;
            case 'technical_service':
                $description = 'SERVICIO TÉCNICO';
                break;
        }

        return $description;
    }


    public function getTypeMovementAttribute()
    {
        $type = null;

        switch ($this->instance_type) {

            case 'document':
            case 'sale_note':
            case 'quotation':
            case 'contract':
            case 'income':
            case 'cash_transaction':
            case 'technical_service':
                $type = 'input';
                break;
            case 'purchase':
            case 'expense':
                $type = 'output';
                break;

        }

        return $type;

    }


    public function getDataPersonAttribute(){

        $record = $this->payment->associated_record_payment;

        switch ($this->instance_type) {

            case 'document':
            case 'sale_note':
            case 'quotation':
            case 'contract':
            case 'technical_service':
                $person['name'] = $record->customer->name;
                $person['number'] = $record->customer->number;
                break;
            case 'purchase':
            case 'expense':
                $person['name'] = $record->supplier->name;
                $person['number'] = $record->supplier->number;
                break;
            case 'income':
                $person['name'] = $record->customer;
                $person['number'] = '';
            case 'cash_transaction':
                $person['name'] = '-';
                $person['number'] = '';
        }

        return (object) $person;
    }


    public function scopeWhereFilterPaymentType($query, $params) {
        // $query->where('created_at','>=',$params->date_start);

        $query->whereHas('doc_payments', function ($q) use ($params) {
            $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
              ->whereHas('associated_record_payment', function ($p) {
                  $p->whereStateTypeAccepted()->whereTypeUser();
              });

        });
        $query->OrWhereHas('exp_payment', function ($q) use ($params) {
            $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
              ->whereHas('associated_record_payment', function ($p) {
                  $p->whereStateTypeAccepted()->whereTypeUser();
              });

        });
        $query->OrWhereHas('sln_payments', function ($q) use ($params) {
            $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
              ->whereHas('associated_record_payment', function ($p) {
                  $p->whereStateTypeAccepted()->whereTypeUser()
                    ->whereNotChanged();
              });

        });
        $query->OrWhereHas('pur_payment', function ($q) use ($params) {
            $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
              ->whereHas('associated_record_payment', function ($p) {
                  $p->whereStateTypeAccepted()->whereTypeUser();
              });

        });
        $query->OrWhereHas('quo_payment', function ($q) use ($params) {
            $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
              ->whereHas('associated_record_payment', function ($p) {
                  $p->whereStateTypeAccepted()->whereTypeUser()
                    ->whereNotChanged();
              });

        });
        $query->OrWhereHas('con_payment', function ($q) use ($params) {
            $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
              ->whereHas('associated_record_payment', function ($p) {
                  $p->whereStateTypeAccepted()->whereTypeUser()
                    ->whereNotChanged();
              });

        });
        $query->OrWhereHas('inc_payment', function ($q) use ($params) {
            $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
              ->whereHas('associated_record_payment', function ($p) {
                  $p->whereStateTypeAccepted()->whereTypeUser();
              });

        });
        $query->OrWhereHas('cas_transaction', function ($q) use ($params) {
            $q->whereBetween('date', [$params->date_start, $params->date_end]);
        });
        $query->OrWhereHas('tec_serv_payment', function ($q) use ($params) {
            $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
              ->whereHas('associated_record_payment', function ($p) {
                  $p->whereTypeUser();
              });
        });

        return $query;

    }

     public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWhereDefinePaymentType($query, $payment_type)
    {

        if($payment_type === IncomePayment::class){
            return $query->whereIn('payment_type', [CashTransaction::class, $payment_type]);
        }

        return $query->wherePaymentType($payment_type);

    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder|null $query
     *
     * @return \Illuminate\Database\Eloquent\Builder|null
     */
    public function scopeJoinGlobalPaymentRelations($query) {
        $query->leftjoin('global_payment_relations', 'global_payments.id', '=', 'global_payment_relations.global_payments_id');
        return $query;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function global_payments_relations() {
        return $this->hasOne(GlobalPaymentsRelations::class, 'global_payments_id');
    }
}
