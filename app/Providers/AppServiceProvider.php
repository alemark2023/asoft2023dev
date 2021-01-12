<?php

namespace App\Providers;

use App\Models\Tenant\Document;
use App\Observers\DocumentObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
	public function boot()
	{
		if (config('tenant.force_https')) {
			URL::forceScheme('https');
		}
		Document::observe(DocumentObserver::class);

		\DB::listen(function ($query) {
			logger()->info($query->sql . print_r($query->bindings, true));
		});
	}

	public function register()
	{
	}
}
