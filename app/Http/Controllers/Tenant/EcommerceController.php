<?php
namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;

class EcommerceController extends Controller
{

   

    public function index()
    {
       

        return view('tenant.ecommerce.index');
    }

   
      
}
