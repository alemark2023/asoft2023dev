<?php

namespace App\Models\Tenant;

use Modules\Finance\Models\IncomePayment;
use Modules\Pos\Models\CashTransaction;
use Modules\Sale\Models\ContractPayment;
use Modules\Sale\Models\QuotationPayment;
use Modules\Sale\Models\TechnicalServicePayment;

class PaymentMethodType extends ModelTenant
{
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'description',
        'has_card',
        'charge',
        'number_days',
        'is_credit',
        'is_cash',
    ];

    /**
     * @return bool
     */
    public function isIsCash()
    {
        return (bool) $this->is_cash;
    }

    /**
     * @param int|bool $is_cash
     *
     * @return PaymentMethodType
     */
    public function setIsCash($is_cash = 0)
    {
        $this->is_cash = (bool) $is_cash;
        return $this;
    }

    /**
     * @return bool
     */
    public function isIsCredit()
    {
        return (bool) $this->is_credit;
    }

    /**
     * @param int|bool $is_cash
     *
     * @return PaymentMethodType
     */
    public function setIsCredit( $is_credit = 0)
    {
        $this->is_credit = (bool) $is_credit;
        return $this;
    }

    /**
     * @param $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNonCredit($query){
        return $query->where('is_credit',0);
    }

    /**
     * @param $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCredit($query){
        return $query->where('is_credit',1);
    }

    /**
     * Devuelve los metodos de pago como standandar. Se pueden excluir elementos por $exclude_method_types_id
     *
     * @param array $exclude_method_types_id Id de metodos a excluir
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getPaymentMethodTypes($exclude_method_types_id = []) {
        $exclude_method_types_id = array_merge(['08', '09'], $exclude_method_types_id);
        return self::whereNotIn('id', $exclude_method_types_id)
                   ->get()
                   ->transform(function ($row) {
                       $row->id = (string)$row->id;
                       $row->number_days = (int)$row->number_days;
                       $row->has_card = (bool)$row->has_card;
                       $row->is_credit = (bool)$row->is_credit;
                       $row->has_card = (bool)$row->has_card;
                       $row->is_cash = (bool)$row->is_cash;
                       $row->charge = (float)$row->charge;
                       $row->description = (string)$row->description;
                       return $row;
                   });
    }

    public function document_payments()
    {
        return $this->hasMany(DocumentPayment::class,  'payment_method_type_id');
    }

    public function sale_note_payments()
    {
        return $this->hasMany(SaleNotePayment::class,  'payment_method_type_id');
    }

    public function purchase_payments()
    {
        return $this->hasMany(PurchasePayment::class,  'payment_method_type_id');
    }

    public function quotation_payments()
    {
        return $this->hasMany(QuotationPayment::class,  'payment_method_type_id');
    }

    public function contract_payments()
    {
        return $this->hasMany(ContractPayment::class,  'payment_method_type_id');
    }

    public function income_payments()
    {
        return $this->hasMany(IncomePayment::class,  'payment_method_type_id');
    }

    public function cash_transactions()
    {
        return $this->hasMany(CashTransaction::class,  'payment_method_type_id');
    }

    public function technical_service_payments()
    {
        return $this->hasMany(TechnicalServicePayment::class,  'payment_method_type_id');
    }


    public function scopeWhereFilterPayments($query, $params)
    {

        return $query->with(['document_payments' => function($q) use($params){
                    $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
                        ->whereHas('associated_record_payment', function($p){
                            $p->whereStateTypeAccepted()->whereTypeUser();
                        });
                },
                'sale_note_payments' => function($q) use($params){
                    $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
                        ->whereHas('associated_record_payment', function($p){
                            $p->whereStateTypeAccepted()->whereTypeUser()
                                ->whereNotChanged();
                        });
                },
                'quotation_payments' => function($q) use($params){
                    $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
                        ->whereHas('associated_record_payment', function($p){
                            $p->whereStateTypeAccepted()->whereTypeUser()
                                ->whereNotChanged();
                        });
                },
                'contract_payments' => function($q) use($params){
                    $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
                        ->whereHas('associated_record_payment', function($p){
                            $p->whereStateTypeAccepted()->whereTypeUser()
                                ->whereNotChanged();
                        });
                },
                'purchase_payments' => function($q) use($params){
                    $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
                        ->whereHas('associated_record_payment', function($p){
                            $p->whereStateTypeAccepted()->whereTypeUser();
                        });
                },
                'income_payments' => function($q) use($params){
                    $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
                        ->whereHas('associated_record_payment', function($p){
                            $p->whereStateTypeAccepted()->whereTypeUser();
                        });
                },
                'cash_transactions' => function($q) use($params){
                    $q->whereBetween('date', [$params->date_start, $params->date_end]);
                },
                'technical_service_payments' => function($q) use($params){
                    $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
                        ->whereHas('associated_record_payment', function($p){
                            $p->whereTypeUser();
                        });
                }
                ]);

    }
}
