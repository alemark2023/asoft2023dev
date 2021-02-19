<?php

namespace App\Providers;

use App\Models\Tenant\Document;
use Illuminate\Support\Facades\DB;
use App\Observers\DocumentObserver;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	public function boot()
	{
		if (config('tenant.force_https')) {
			URL::forceScheme('https');
		}
		Document::observe(DocumentObserver::class);

        DB::listen(function($query) {
            Log::info(
                $query->sql,
                $query->bindings,
                $query->time
            );
        });
	}

	public function register()
	{
	}
}
