<?php

namespace Modules\Finance\Providers;

use App\Models\Tenant\{
    DocumentPayment,
    PurchasePayment,
};  

use Illuminate\Support\ServiceProvider;

class GlobalPaymentServiceProvider extends ServiceProvider
{

    public function register()
    {
    }
    
    public function boot()
    {
        $this->deleting_document_payment();
        $this->deleting_purchase_payment();
    }

    private function deleting_document_payment(){

    }

    private function deleting_purchase_payment()
    {

        PurchasePayment::deleting(function ($purchase_payment) {
            
            if($purchase_payment->global_payment){
                $purchase_payment->global_payment()->delete();
            }

        });
    }
 
}
