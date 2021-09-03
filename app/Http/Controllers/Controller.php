<?php

namespace App\Http\Controllers;

use App\Models\Tenant\Person;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class Controller
 *
 * @package App\Http\Controllers
 * @mixin BaseController
 */
class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * Devuelve un cliente basado en el id
     * @param int $id
     *
     * @return array
     */
    public function searchClientById($id = 0){

        $customers = Person::whereType('customers')
            ->where('id',$id)
            ->get()
            ->transform(function($row) {
                /** @var Person $row */
                return  $row->getCollectionData();
            });

        return compact('customers');
    }
}
