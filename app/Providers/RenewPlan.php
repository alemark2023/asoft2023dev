<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Tenant\Configuration;
use Carbon\Carbon;

class RenewPlan extends ServiceProvider
{
    
    public function register()
    {
    }

    
    public function boot()
    {
        // $this->renew_plan();
    }

    private function renew_plan(){
        
        // $configuration = Configuration::first();

        // $base_date = new Carbon($configuration->date_time_start); 
        
        // $base_date_add_month = (new Carbon($configuration->date_time_start))->addMonth();

        // $today = Carbon::now();

        // $difference = $today->diffInDays($base_date_add_month);
        // // dd($difference);

        // // dd($base_date, $today, $base_date_add_month);

        // $configuration->save();

    }


}
