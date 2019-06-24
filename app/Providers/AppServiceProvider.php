<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function boot() {
        if (config('tenant.force_https')) URL::forceScheme('https');
    }
    
    public function register() {
        
    }
}