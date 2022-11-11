<?php

namespace Modules\LevelAccess\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Tenant\{
    User,
};


class UserPermissionController extends Controller
{
    
    /**
     * 
     * Permisos de los modulos y submodulos por usuario
     *
     * @param  int $id
     * @return array
     */
    public function getWebUserPermissions($id)
    {
        $user = User::findOrFail($id);

        return $user->getWebPermissionsByUser();
    }

}
