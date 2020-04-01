<?php

namespace Modules\Finance\Providers;

use App\Models\Tenant\{
    SaleNotePayment,
    DocumentPayment,
    PurchasePayment,
};  
use Modules\Sale\Models\QuotationPayment;
use Modules\Expense\Models\ExpensePayment;

use Illuminate\Support\ServiceProvider;

class GlobalPaymentServiceProvider extends ServiceProvider
{

    public function register()
    {
    }
    
    public function boot()
    {

        $this->deletingPayment(SaleNotePayment::class);
        $this->deletingPayment(DocumentPayment::class);
        $this->deletingPayment(PurchasePayment::class);
        $this->deletingPayment(QuotationPayment::class);
        $this->deletingPayment(ExpensePayment::class);

    }

    private function deletingPayment($model)
    {

        $model::deleting(function ($record) {
            
            if($record->global_payment){
                $record->global_payment()->delete();
            }

            if($record->payment_file){
                $record->payment_file()->delete();
            }

        });

    }
 
 
}
