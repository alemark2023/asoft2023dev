<?php

namespace App\Services;
use App\Models\Tenant\User;

class UserAdminService
{

    public function getUserAdmin()
    {
        return User::first();
    }


}
