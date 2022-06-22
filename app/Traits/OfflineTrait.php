<?php

namespace App\Traits;
use Modules\Offline\Models\OfflineConfiguration;

trait OfflineTrait
{
    private function getIsClient() {
        return OfflineConfiguration::query()->firstOrFail()->is_client;
    }

    private function getUrlServer() {
        return OfflineConfiguration::query()->firstOrFail()->url_server;
    }

    private function getTokenServer() {
        return OfflineConfiguration::query()->firstOrFail()->token_server;
    }

}
