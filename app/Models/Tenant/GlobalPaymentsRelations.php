<?php

    namespace App\Models\Tenant;


    use Modules\Expense\Models\Expense;
    use Modules\Expense\Models\ExpensePayment;
    use Modules\Finance\Models\GlobalPayment;
    use Modules\Finance\Models\Income;
    use Modules\Finance\Models\IncomePayment;
    use Modules\Pos\Models\CashTransaction;
    use Modules\Sale\Models\Contract;
    use Modules\Sale\Models\ContractPayment;
    use Modules\Sale\Models\QuotationPayment;
    use Modules\Sale\Models\TechnicalService;
    use Modules\Sale\Models\TechnicalServicePayment;

    /**
     * Class GlobalPaymentsRelations
     *
     * @package App\Models\Tenant
     */
    class GlobalPaymentsRelations extends ModelTenant {
        //

        protected $table = 'global_payment_relations';
        protected $fillable = [
            'global_payments_id',
            'user_id',
            'payment_type',
            'state_type_id',
            'payment_method_type_id',
            'currency_type_id',
            'exchange_rate',
            'total',
            'date_of_payment',
            'bank_id',
            'cash_id',
            'document_id',
            'document_payment_id',
            'expenses_id',
            'expense_payments_id',
            'sale_notes_id',
            'sale_note_payments_id',
            'quotations_id',
            'quotation_payments_id',
            'purchases_id',
            'purchase_payments_id',
            'contracts_id',
            'contract_payments_id',
            'technical_services_id',
            'technical_service_payments_id',
            'income_id',
            'income_payments_id',
            'cash_transactions_id',
            'changed',
            'associated_record_payment_id',
        ];
        protected $casts = [
            'global_payments_id'            => 'int',
            'user_id'                       => 'int',
            'payment_type'                  => 'string',
            'state_type_id'                 => 'string',
            'payment_method_type_id'        => 'string',
            'currency_type_id'              => 'string',
            'exchange_rate'                 => 'float',
            'total'                         => 'float',
            'date_of_payment'               => 'date',
            'bank_id'                       => 'int',
            'cash_id'                       => 'int',
            'document_id'                   => 'int',
            'document_payment_id'           => 'int',
            'expenses_id'                   => 'int',
            'expense_payments_id'           => 'int',
            'sale_notes_id'                 => 'int',
            'sale_note_payments_id'         => 'int',
            'quotations_id'                 => 'int',
            'quotation_payments_id'         => 'int',
            'purchases_id'                  => 'int',
            'purchase_payments_id'          => 'int',
            'contracts_id'                  => 'int',
            'contract_payments_id'          => 'int',
            'technical_services_id'         => 'int',
            'technical_service_payments_id' => 'int',
            'income_id'                     => 'int',
            'income_payments_id'            => 'int',
            'cash_transactions_id'          => 'int',
            'changed'                       => 'bool',
            'associated_record_payment_id'  => 'int',
        ];

        protected static function boot() {
            parent::boot();
            static::creating(function ($model) {
                self::setGlobalPaymentsRelations($model);
            });
            static::saving(function ($model) {
                self::setGlobalPaymentsRelations($model);
            });
            static::updating(function ($model) {
                self::setGlobalPaymentsRelations($model);
            });
        }

        public static function setGlobalPaymentsRelations(&$model) {
            /** @var GlobalPaymentsRelations $model */

            /** Documentos */
            if (!empty($model->document_payment_id)) {
                $document_payment = DocumentPayment::find($model->document_payment_id);
                $model->document_id = $document_payment->document_id;
                $model->payment_method_type_id = $document_payment->payment_method_type_id;
                $model->date_of_payment = $document_payment->date_of_payment;
                $document = Document::find($model->document_id);
                $model->state_type_id = $document->state_type_id;
                $model->currency_type_id = $document->currency_type_id;
                $model->exchange_rate = $document->exchange_rate_sale;
                $model->total = $document_payment->payment;
            } elseif (!empty($model->expense_payments_id)) {
                $document_payment = ExpensePayment::find($model->expense_payments_id);
                $model->expense_id = $document_payment->expense_id;
                $model->payment_method_type_id = $document_payment->expense_method_type_id;
                $model->date_of_payment = $document_payment->date_of_payment;
                $document = Expense::find($model->expense_id);
                $model->state_type_id = $document->state_type_id;
                $model->currency_type_id = $document->currency_type_id;
                $model->exchange_rate = $document->exchange_rate_sale;
                $model->total = $document_payment->payment;
            } elseif (!empty($model->sale_note_payments_id)) {
                $document_payment = SaleNotePayment::find($model->sale_note_payments_id);

                $model->sale_notes_id = $document_payment->sale_note_id;
                $model->payment_method_type_id = $document_payment->payment_method_type_id;
                $model->date_of_payment = $document_payment->date_of_payment;
                $document = SaleNote::find($model->sale_notes_id);
                $model->state_type_id = $document->state_type_id;
                $model->currency_type_id = $document->currency_type_id;
                $model->exchange_rate = $document->exchange_rate_sale;
                $model->changed = $document->changed;
                $model->total = $document_payment->payment;
            } elseif (!empty($model->quotation_payments_id)) {
                $document_payment = QuotationPayment::find($model->quotation_payments_id);

                $model->quotations_id = $document_payment->quotation_id;
                $model->payment_method_type_id = $document_payment->payment_method_type_id;
                $model->date_of_payment = $document_payment->date_of_payment;
                $document = Quotation::find($model->quotations_id);
                $model->state_type_id = $document->state_type_id;
                $model->currency_type_id = $document->currency_type_id;
                $model->exchange_rate = $document->exchange_rate_sale;
                $model->total = $document_payment->payment;
                $model->changed = $document->changed;
            } elseif (!empty($model->purchase_payments_id)) {
                $document_payment = PurchasePayment::find($model->purchase_payments_id);

                $model->purchases_id = $document_payment->purchase_id;
                $model->payment_method_type_id = $document_payment->payment_method_type_id;
                $model->date_of_payment = $document_payment->date_of_payment;
                $document = Purchase::find($model->purchases_id);
                $model->state_type_id = $document->state_type_id;
                $model->currency_type_id = $document->currency_type_id;
                $model->exchange_rate = $document->exchange_rate_sale;
                $model->total = $document_payment->payment;
            } elseif (!empty($model->contract_payments_id)) {
                $document_payment = ContractPayment::find($model->contract_payments_id);
                $model->contracts_id = $document_payment->contract_id;
                $model->payment_method_type_id = $document_payment->payment_method_type_id;
                $model->date_of_payment = $document_payment->date_of_payment;
                $document = Contract::find($model->contracts_id);
                $model->state_type_id = $document->state_type_id;
                $model->currency_type_id = $document->currency_type_id;
                $model->exchange_rate = $document->exchange_rate_sale;
                $model->total = $document_payment->payment;
                $model->changed = $document->changed;
            } elseif (!empty($model->technical_service_payments_id)) {
                $document_payment = TechnicalServicePayment::find($model->technical_service_payments_id);

                $model->technical_services_id = $document_payment->technical_service_id;
                $model->payment_method_type_id = $document_payment->payment_method_type_id;
                $model->date_of_payment = $document_payment->date_of_payment;
                $document = TechnicalService::find($model->technical_services_id);
                $model->state_type_id = $document->state_type_id;
                $model->currency_type_id = $document->currency_type_id;
                $model->exchange_rate = $document->exchange_rate_sale;
                $model->total = $document_payment->payment;
            } elseif (!empty($model->income_payments_id)) {
                $document_payment = IncomePayment::find($model->income_payments_id);

                $model->income_id = $document_payment->income_id;
                $model->payment_method_type_id = $document_payment->payment_method_type_id;
                $model->date_of_payment = $document_payment->date_of_payment;
                $document = Income::find($model->income_id);
                $model->state_type_id = $document->state_type_id;
                $model->currency_type_id = $document->currency_type_id;
                $model->exchange_rate = $document->exchange_rate_sale;
                $model->total = $document_payment->payment;
            } elseif (!empty($model->cash_transactions_id)) {
                $document_payment = CashTransaction::find($model->cash_transactions_id);

                $model->cash_id = $document_payment->cash_id;
                $model->payment_method_type_id = $document_payment->payment_method_type_id;
                $model->date_of_payment = $document_payment->date;
                // $document = Cash::find($model->cash_id);
                // $model->state_type_id = $document->state_type_id;
                // $model->currency_type_id = $document->currency_type_id;
                // $model->exchange_rate = $document->exchange_rate_sale;
                $model->total = $document_payment->payment;
            }
        }

        /**
         * @param \Illuminate\Database\Eloquent\Builder $query
         * @param int                                   $global_payments_id
         *
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public function scopeWhereGlobalPayments($query, $global_payments_id) {
            return $query->where('global_payments_id', $global_payments_id);
        }

        /**
         * @return mixed
         */
        public function getGlobalPaymentsId() {
            return $this->global_payments_id;
        }

        /**
         * @param mixed $global_payments_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setGlobalPaymentsId($global_payments_id) {
            $this->global_payments_id = $global_payments_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getExchangeRate() {
            return $this->exchange_rate;
        }

        /**
         * @param mixed $exchange_rate
         *
         * @return GlobalPaymentsRelations
         */
        public function setExchangeRate($exchange_rate) {
            $this->exchange_rate = $exchange_rate;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getUserId() {
            return $this->user_id;
        }

        /**
         * @param mixed $user_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setUserId($user_id) {
            $this->user_id = $user_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getPaymentType() {
            return $this->payment_type;
        }

        /**
         * @param mixed $payment_type
         *
         * @return GlobalPaymentsRelations
         */
        public function setPaymentType($payment_type) {
            $this->payment_type = $payment_type;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getStateTypeId() {
            return $this->state_type_id;
        }

        /**
         * @param mixed $state_type_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setStateTypeId($state_type_id) {
            $this->state_type_id = $state_type_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getPaymentMethodTypeId() {
            return $this->payment_method_type_id;
        }

        /**
         * @param mixed $payment_method_type_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setPaymentMethodTypeId($payment_method_type_id) {
            $this->payment_method_type_id = $payment_method_type_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getCurrencyTypeId() {
            return $this->currency_type_id;
        }

        /**
         * @param mixed $currency_type_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setCurrencyTypeId($currency_type_id) {
            $this->currency_type_id = $currency_type_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getTotal() {
            return $this->total;
        }

        /**
         * @param mixed $total
         *
         * @return GlobalPaymentsRelations
         */
        public function setTotal($total) {
            $this->total = $total;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getDateOfPayment() {
            return $this->date_of_payment;
        }

        /**
         * @param mixed $date_of_payment
         *
         * @return GlobalPaymentsRelations
         */
        public function setDateOfPayment($date_of_payment) {
            $this->date_of_payment = $date_of_payment;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getBankId() {
            return $this->bank_id;
        }

        /**
         * @param mixed $bank_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setBankId($bank_id) {
            $this->bank_id = $bank_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getCashId() {
            return $this->cash_id;
        }

        /**
         * @param mixed $cash_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setCashId($cash_id) {
            $this->cash_id = $cash_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getDocumentId() {
            return $this->document_id;
        }

        /**
         * @param mixed $document_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setDocumentId($document_id) {
            $this->document_id = $document_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getDocumentPaymentId() {
            return $this->document_payment_id;
        }

        /**
         * @param mixed $document_payment_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setDocumentPaymentId($document_payment_id) {
            $this->document_payment_id = $document_payment_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getExpensesId() {
            return $this->expenses_id;
        }

        /**
         * @param mixed $expenses_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setExpensesId($expenses_id) {
            $this->expenses_id = $expenses_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getExpensePaymentsId() {
            return $this->expense_payments_id;
        }

        /**
         * @param mixed $expense_payments_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setExpensePaymentsId($expense_payments_id) {
            $this->expense_payments_id = $expense_payments_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getSaleNotesId() {
            return $this->sale_notes_id;
        }

        /**
         * @param mixed $sale_notes_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setSaleNotesId($sale_notes_id) {
            $this->sale_notes_id = $sale_notes_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getSaleNotePaymentsId() {
            return $this->sale_note_payments_id;
        }

        /**
         * @param mixed $sale_note_payments_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setSaleNotePaymentsId($sale_note_payments_id) {
            $this->sale_note_payments_id = $sale_note_payments_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getQuotationsId() {
            return $this->quotations_id;
        }

        /**
         * @param mixed $quotations_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setQuotationsId($quotations_id) {
            $this->quotations_id = $quotations_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getQuotationPaymentsId() {
            return $this->quotation_payments_id;
        }

        /**
         * @param mixed $quotation_payments_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setQuotationPaymentsId($quotation_payments_id) {
            $this->quotation_payments_id = $quotation_payments_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getPurchasesId() {
            return $this->purchases_id;
        }

        /**
         * @param mixed $purchases_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setPurchasesId($purchases_id) {
            $this->purchases_id = $purchases_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getPurchasePaymentsId() {
            return $this->purchase_payments_id;
        }

        /**
         * @param mixed $purchase_payments_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setPurchasePaymentsId($purchase_payments_id) {
            $this->purchase_payments_id = $purchase_payments_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getContractsId() {
            return $this->contracts_id;
        }

        /**
         * @param mixed $contracts_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setContractsId($contracts_id) {
            $this->contracts_id = $contracts_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getContractPaymentsId() {
            return $this->contract_payments_id;
        }

        /**
         * @param mixed $contract_payments_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setContractPaymentsId($contract_payments_id) {
            $this->contract_payments_id = $contract_payments_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getTechnicalServicesId() {
            return $this->technical_services_id;
        }

        /**
         * @param mixed $technical_services_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setTechnicalServicesId($technical_services_id) {
            $this->technical_services_id = $technical_services_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getTechnicalServicePaymentsId() {
            return $this->technical_service_payments_id;
        }

        /**
         * @param mixed $technical_service_payments_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setTechnicalServicePaymentsId($technical_service_payments_id) {
            $this->technical_service_payments_id = $technical_service_payments_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getIncomeId() {
            return $this->income_id;
        }

        /**
         * @param mixed $income_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setIncomeId($income_id) {
            $this->income_id = $income_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getIncomePaymentsId() {
            return $this->income_payments_id;
        }

        /**
         * @param mixed $income_payments_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setIncomePaymentsId($income_payments_id) {
            $this->income_payments_id = $income_payments_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getCashTransactionsId() {
            return $this->cash_transactions_id;
        }

        /**
         * @param mixed $cash_transactions_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setCashTransactionsId($cash_transactions_id) {
            $this->cash_transactions_id = $cash_transactions_id;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getAssociatedRecordPaymentId() {
            return $this->associated_record_payment_id;
        }

        /**
         * @param mixed $associated_record_payment_id
         *
         * @return GlobalPaymentsRelations
         */
        public function setAssociatedRecordPaymentId($associated_record_payment_id) {
            $this->associated_record_payment_id = $associated_record_payment_id;
            return $this;
        }


        /**
         * @param \Illuminate\Database\Eloquent\Builder|null $query
         *
         * @return \Illuminate\Database\Eloquent\Builder|null
         */
        public function scopeJoinGlobalPayment($query) {
            $query->join('global_payments', 'global_payments.id', '=', 'global_payment_relations.global_payments_id');
            return $query;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function global_payments() {
            return $this->belongsTo(GlobalPayment::class, 'global_payments_id');
        }


        /**
         * @param \Illuminate\Database\Eloquent\Builder|null $query
         *
         * @return \Illuminate\Database\Eloquent\Builder|null
         */
        public function scopeJoinUsers($query) {
            $query->join('users', 'users.id', '=', 'global_payment_relations.user_id');
            return $query;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function user() {
            return $this->belongsTo(User::class, 'user_id');
        }

        /**
         * @param \Illuminate\Database\Eloquent\Builder|null $query
         *
         * @return \Illuminate\Database\Eloquent\Builder|null
         */
        public function scopeJoinBank($query) {
            $query->join('bank_accounts', 'bank_accounts.id', '=', 'global_payment_relations.bank_id');
            return $query;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function bank() {
            return $this->belongsTo(BankAccount::class, 'bank_id');
        }


        /**
         * @param \Illuminate\Database\Eloquent\Builder|null $query
         *
         * @return \Illuminate\Database\Eloquent\Builder|null
         */
        public function scopeJoinPaymentMethodTypes($query) {
            $query->join('payment_method_types', 'payment_method_types.id', '=',
                         'global_payment_relations.payment_method_type_id');
            return $query;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function payment_method_type() {
            return $this->belongsTo(PaymentMethodType::class, 'payment_method_type_id');
        }


        /**
         * @param \Illuminate\Database\Eloquent\Builder|null $query
         *
         * @return \Illuminate\Database\Eloquent\Builder|null
         */
        public function scopeJoinCash($query) {
            $query->join('cash', 'cash.id', '=', 'global_payment_relations.cash_id');
            return $query;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function cash() {
            return $this->belongsTo(Cash::class, 'cash_id');
        }


        /**
         * @param \Illuminate\Database\Eloquent\Builder|null $query
         *
         * @return \Illuminate\Database\Eloquent\Builder|null
         */
        public function scopeJoinDocuments($query) {
            $query->join('documents', 'documents.id', '=', 'global_payment_relations.document_id');
            return $query;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function document() {
            return $this->belongsTo(Document::class, 'document_id');
        }


        /**
         * @param \Illuminate\Database\Eloquent\Builder|null $query
         *
         * @return \Illuminate\Database\Eloquent\Builder|null
         */
        public function scopeJoinDocumentPayments($query) {
            $query->join('document_payments', 'document_payments.id', '=',
                         'global_payment_relations.document_payment_id');
            return $query;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function document_payment() {
            return $this->belongsTo(DocumentPayment::class, 'document_payment_id');
        }

        /**
         * @param \Illuminate\Database\Eloquent\Builder|null $query
         *
         * @return \Illuminate\Database\Eloquent\Builder|null
         */
        public function scopeJoinExpense($query) {
            $query->join('expenses', 'expenses.id', '=', 'global_payment_relations.expenses_id');
            return $query;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function expanses() {
            return $this->belongsTo(Expense::class, 'expenses_id');
        }

        /**
         * @param \Illuminate\Database\Eloquent\Builder|null $query
         *
         * @return \Illuminate\Database\Eloquent\Builder|null
         */
        public function scopeJoinSaleNotes($query) {
            $query->join('sale_notes', 'sale_notes.id', '=', 'global_payment_relations.sale_notes_id');
            return $query;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function sale_notes() {
            return $this->belongsTo(SaleNote::class, 'sale_notes_id');
        }

        /**
         * @param \Illuminate\Database\Eloquent\Builder|null $query
         *
         * @return \Illuminate\Database\Eloquent\Builder|null
         */
        public function scopeJoinSaleNotePayments($query) {
            $query->join('sale_note_payments', 'sale_note_payments.id', '=',
                         'global_payment_relations.sale_note_payments_id');
            return $query;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function sale_note_payments() {
            return $this->belongsTo(SaleNotePayment::class, 'sale_note_payments_id');
        }

        /**
         * @param \Illuminate\Database\Eloquent\Builder|null $query
         *
         * @return \Illuminate\Database\Eloquent\Builder|null
         */
        public function scopeJoinQuotations($query) {
            $query->join('quotations', 'quotations.id', '=', 'global_payment_relations.quotations_id');
            return $query;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function quotations() {
            return $this->belongsTo(Quotation::class, 'quotations_id');
        }

        /**
         * @param \Illuminate\Database\Eloquent\Builder|null $query
         *
         * @return \Illuminate\Database\Eloquent\Builder|null
         */
        public function scopeJoinQuotationPayments($query) {
            $query->join('quotation_payments', 'quotation_payments.id', '=',
                         'global_payment_relations.quotation_payments_id');
            return $query;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function quotation_payments() {
            return $this->belongsTo(QuotationPayment::class, 'quotation_payments_id');
        }


        /**
         * @param \Illuminate\Database\Eloquent\Builder|null $query
         *
         * @return \Illuminate\Database\Eloquent\Builder|null
         */
        public function scopeJoinPurchases($query) {
            $query->join('purchases', 'purchases.id', '=', 'global_payment_relations.purchases_id');
            return $query;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function purchases() {
            return $this->belongsTo(Purchase::class, 'purchases_id');
        }


        /**
         * @param \Illuminate\Database\Eloquent\Builder|null $query
         *
         * @return \Illuminate\Database\Eloquent\Builder|null
         */
        public function scopeJoinPurchasePayments($query) {
            $query->join('purchase_payments', 'purchase_payments.id', '=',
                         'global_payment_relations.purchase_payments_id');
            return $query;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function purchase_payments() {
            return $this->belongsTo(PurchasePayment::class, 'purchase_payments_id');
        }

        /**
         * @param \Illuminate\Database\Eloquent\Builder|null $query
         *
         * @return \Illuminate\Database\Eloquent\Builder|null
         */
        public function scopeJoinContracts($query) {
            $query->join('contracts', 'contracts.id', '=', 'global_payment_relations.contracts_id');
            return $query;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function contracts() {
            return $this->belongsTo(Contract::class, 'contracts_id');
        }

        /**
         * @param \Illuminate\Database\Eloquent\Builder|null $query
         *
         * @return \Illuminate\Database\Eloquent\Builder|null
         */
        public function scopeJoinContractsPayments($query) {
            $query->join('contract_payments', 'contract_payments.id', '=',
                         'global_payment_relations.contract_payments_id');
            return $query;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function contract_payments() {
            return $this->belongsTo(ContractPayment::class, 'contract_payments_id');
        }

        /**
         * @param \Illuminate\Database\Eloquent\Builder|null $query
         *
         * @return \Illuminate\Database\Eloquent\Builder|null
         */
        public function scopeJoinTechnicalServices($query) {
            $query->join('technical_services', 'technical_services.id', '=',
                         'global_payment_relations.technical_services_id');
            return $query;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function technical_services() {
            return $this->belongsTo(TechnicalService::class, 'technical_services_id');
        }

        /**
         * @param \Illuminate\Database\Eloquent\Builder|null $query
         *
         * @return \Illuminate\Database\Eloquent\Builder|null
         */
        public function scopeJoinTechnicalServicePayments($query) {
            $query->join('technical_service_payments', 'technical_service_payments.id', '=',
                         'global_payment_relations.technical_service_payments_id');
            return $query;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function technical_service_paymentsd() {
            return $this->belongsTo(TechnicalServicePayment::class, 'technical_service_payments_id');
        }

        /**
         * @param \Illuminate\Database\Eloquent\Builder|null $query
         *
         * @return \Illuminate\Database\Eloquent\Builder|null
         */
        public function scopeJoinIncome($query) {
            $query->join('income', 'income.id', '=', 'global_payment_relations.income_id');
            return $query;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function income() {
            return $this->belongsTo(Income::class, 'income_id');
        }

        /**
         * @param \Illuminate\Database\Eloquent\Builder|null $query
         *
         * @return \Illuminate\Database\Eloquent\Builder|null
         */
        public function scopeJoinIncomePayments($query) {
            $query->join('income_payments', 'income_payments.id', '=', 'global_payment_relations.income_payments_id');
            return $query;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function income_payments() {
            return $this->belongsTo(IncomePayment::class, 'income_payments_id');
        }

        /**
         * @param \Illuminate\Database\Eloquent\Builder|null $query
         *
         * @return \Illuminate\Database\Eloquent\Builder|null
         */
        public function scopeJoinCashTransactions($query) {
            $query->join('cash_transactions', 'cash_transactions.id', '=',
                         'global_payment_relations.cash_transactions_id');
            return $query;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function cash_transactions() {
            return $this->belongsTo(CashTransaction::class, 'cash_transactions_id');
        }

        /**
         * @param \Illuminate\Database\Eloquent\Builder|null $query
         * @param \Illuminate\Support\Collection             $params
         *
         * @return \Illuminate\Database\Eloquent\Builder|null
         */
        public function scopeWhereFilterPaymentType($query, $params) {
            /*
            $query->whereIn('payment_type', [
                DocumentPayment::class,
                ExpensePayment::class,
                SaleNotePayment::class,
                PurchasePayment::class,
                QuotationPayment::class,
                TechnicalServicePayment::class,
                CashTransaction::class,
                IncomePayment::class,
                ContractPayment::class,
            ]);
            */
            $query->whereBetween('date_of_payment', [$params->date_start, $params->date_end]);

            // Documentos
            $query->orwhere(function ($q) {
                $q
                    ->where('payment_type', DocumentPayment::class)
                    ->whereIn('state_type_id', Document::getStateTypeAccepted())
                    ->WhereTypeUser();
            });
            // Gastos
            $query->orwhere(function ($q) {
                $q
                    ->where('payment_type', ExpensePayment::class)
                    ->whereIn('state_type_id', Expense::getStateTypeAccepted())
                    ->WhereTypeUser();
            });
            // Nota de venta
            $query->orwhere(function ($q) {
                $q
                    ->where('payment_type', SaleNotePayment::class)
                    ->whereIn('state_type_id', SaleNote::getStateTypeAccepted())
                    ->WhereTypeUser()
                    ->whereNotChanged();

            });
            // Conmpras
            $query->orwhere(function ($q) {
                $q
                    ->where('payment_type', PurchasePayment::class)
                    ->whereIn('state_type_id', Purchase::getStateTypeAccepted())
                    ->WhereTypeUser();

            });
            // Cotizaciones
            $query->orwhere(function ($q) {
                $q
                    ->where('payment_type', QuotationPayment::class)
                    ->whereIn('state_type_id', Quotation::getStateTypeAccepted())
                    ->WhereTypeUser()
                    ->whereNotChanged();

            });
            // Contratos
            $query->orwhere(function ($q) {
                $q
                    ->where('payment_type', ContractPayment::class)
                    ->whereIn('state_type_id', Contract::getStateTypeAccepted())
                    ->WhereTypeUser()
                    ->whereNotChanged();

            });
            // Ingresos
            $query->orwhere(function ($q) {
                $q
                    ->where('payment_type', IncomePayment::class)
                    ->whereIn('state_type_id', Income::getStateTypeAccepted())
                    ->WhereTypeUser();

            });
            // Transacciones de caja
            $query->orwhere(function ($q) {
                $q
                    ->where('payment_type', CashTransaction::class);

            });
            // Sevicio Tecnico
            $query->orwhere(function ($q) {
                $q
                    ->where('payment_type', TechnicalServicePayment::class)
                    ->whereNotNull('associated_record_payment_id');

            });


            $e = $query->toSql();
            $f = explode('?',$e);
            $b = $query->getBindings();
            $text = '';
            foreach($f as $i=>$o){
                $text .=$o;
                if(isset($b[$i])){
                    $text .="'".$b[$i]."'";
                }
            }
            dd([
                   $text,
                   $query->getBindings(),
                   $query->toSql()
               ]);
            return $query;
        }

        public function scopeWhereNotChanged($query) {
            return $query->where('changed', false);
        }

        public function scopeWhereTypeUser($query) {
            $user = auth()->user();
            return ($user->type == 'admin') ? null : $query->where('user_id', $user->id)->latest();
            // return ($user->type == 'seller') ? $query->where('user_id', $user->id) : null;
        }
    }
