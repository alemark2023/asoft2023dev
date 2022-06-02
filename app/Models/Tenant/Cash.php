<?php

namespace App\Models\Tenant;

use Modules\Finance\Models\GlobalPayment;
use Modules\Pos\Models\CashTransaction;

/**
 * App\Models\Tenant\Cash
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\CashDocument[] $cash_documents
 * @property-read int|null $cash_documents_count
 * @property-read CashTransaction|null $cash_transaction
 * @property-read mixed $currency_type_id
 * @property-read mixed $number_full
 * @property-read \Illuminate\Database\Eloquent\Collection|GlobalPayment[] $global_destination
 * @property-read int|null $global_destination_count
 * @property-read \Illuminate\Database\Eloquent\Collection|GlobalPayment[] $global_payments
 * @property-read int|null $global_payments_count
 * @property-read \App\Models\Tenant\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Cash newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cash newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cash query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cash whereTypeUser()
 * @mixin \Eloquent
 */
class Cash extends ModelTenant
{
    protected $with = ['cash_documents'];

    protected $table = 'cash';

    protected $fillable = [
        'user_id',
        'date_opening',
        'time_opening',
        'date_closed',
        'time_closed',
        'beginning_balance',
        'final_balance',
        'income',
        'state',
        'reference_number',
        'apply_restaurant'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cash_documents()
    {
        return $this->hasMany(CashDocument::class);
    }

    /**
     * @param $query
     *
     * @return null
     */
    public function scopeWhereTypeUser($query)
    {
        /** @var \App\Models\Tenant\User $user */
        $user = auth()->user();
        return ($user->type === 'seller') ? $query->where('user_id', $user->id) : null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function global_destination()
    {
        return $this->morphMany(GlobalPayment::class, 'destination');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function global_payments()
    {
        return $this->morphToMany(GlobalPayment::class, 'destination');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cash_transaction()
    {
        return $this->hasOne(CashTransaction::class);
    }

    /**
     * @return string
     */
    public function getCurrencyTypeIdAttribute()
    {
        return 'PEN';
    }

    /**
     * @return string
     */
    public function getNumberFullAttribute()
    {

        if($this->cash_transaction){
            return "{$this->cash_transaction->description} - Caja chica POS".($this->reference_number ? ' N° '.$this->reference_number:'');
        }

        return '-';

    }

    public function scopeWhereActive($query)
    { 
        return $query->where([
            ['user_id', auth()->user()->id],
            ['state', true],
        ]);
    }

    public function cash_documents_credit()
    {
        return $this->hasMany(CashDocumentCredit::class);
    }
    

    /**
     * 
     * Obtener total de ingresos por tipo de documento
     *
     * @return array
     */
    public function getTotalsIncomeSummary()
    {

        $document_total_payments = $this->cash_documents()
                            ->whereHas('document')
                            ->get()
                            ->sum(function($row){
                                return $row->document->getTotalAllPayments();
                            });
        
        
        $sale_note_total_payments = $this->cash_documents()
                            ->whereHas('sale_note')
                            ->get()
                            ->sum(function($row){
                                return $row->sale_note->getTotalAllPayments();
                            });

        return [
            'document_total_payments' => $this->generalApplyNumberFormat($document_total_payments),
            'sale_note_total_payments' => $this->generalApplyNumberFormat($sale_note_total_payments),
        ];
        
    }
    
    
    /**
     * 
     * Obtener comprobantes y notas de venta ordenados para reporte ingresos en caja
     *
     * @return array
     */
    public function getIncomePaymentsData()
    {
        
        $documents = $this->cash_documents()
                        ->join('documents', 'documents.id', '=', 'cash_documents.document_id')
                        ->orderBy('documents.document_type_id')
                        ->orderBy('documents.created_at')
                        ->get();
        
        $sale_notes = $this->cash_documents()
                            ->join('sale_notes', 'sale_notes.id', '=', 'cash_documents.sale_note_id')
                            ->orderBy('sale_notes.created_at')
                            ->get();

        return [
            'documents' => $documents,
            'sale_notes' => $sale_notes,
        ];
        
    }


}
