<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Tenant\Purchase;  
use Modules\Expense\Models\Expense;
use App\Models\Tenant\Cash;
use App\Models\Tenant\CashDocument;

class CashServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->expense();
        // $this->purchase();
    }

    private function purchase(){

        Purchase::created(function ($purchase) { 

            $cash = self::getCash();
            $cash->cash_documents()->create(['purchase_id' => $purchase->id]);
 
        });
        
    }

    private function expense(){

        Expense::created(function ($expense) { 

            $cash = self::getCash();
            $cash->cash_documents()->create(['expense_id' => $expense->id]);

        });
        
    }
    
    
    private static function getCash(){

        return  Cash::where([['user_id',auth()->user()->id],['state',true]])->first();

    }
    

}
