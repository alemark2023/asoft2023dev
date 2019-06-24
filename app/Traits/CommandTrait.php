<?php

namespace App\Traits;

trait CommandTrait
{
    /**
     * Is offline
     * @return boolean
     */
    private function isOffline() {
        return config('tenant.is_client');
    }
}
