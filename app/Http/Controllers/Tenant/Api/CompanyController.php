<?php
namespace App\Http\Controllers\Tenant\Api;

use App\CoreFacturalo\Facturalo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\User;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Company;

class CompanyController extends Controller
{
   
    public function record(Request $request) {

        $user = new User();
        if(\Auth::user()){
            $user = \Auth::user();
        }

        $establishment_id =  $user->establishment_id;
        $establishments = Establishment::without(['country', 'department', 'province', 'district'])->where('id', $establishment_id)->get();
        $series = $user->getSeries();

        return [
            'series' => $series,
            'establishments' => $establishments,
            'company' =>  Company::active()
        ];

    }
}
