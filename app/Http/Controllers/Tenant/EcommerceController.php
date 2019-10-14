<?php
namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Item;
use App\Http\Resources\Tenant\ItemCollection;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant\User;





class EcommerceController extends Controller
{

    public function index()
    {
        return view('tenant.ecommerce.index');
    }

    public function item($id)
    {
        $row = Item::find($id);
        $record = (object)[
            'id' => $row->id,
            'description' => $row->description,
            'name' => $row->name,
            'second_name' => $row->second_name,
            'sale_unit_price' => $row->sale_unit_price,
            'image' =>  $row->image,
            'image_medium' => $row->image_medium,
            'image_small' => $row->image_small
        ];
        return view('tenant.ecommerce.items.record', compact('record'));
    }

    public function items()
    {
        $records = Item::where('apply_store', 1)->get();
        return view('tenant.ecommerce.items.index', compact('records'));
    }

    public function itemsBar()
    {
        $records = Item::where('apply_store', 1)->get();
        return new ItemCollection($records);
       
    }

    public function partialItem($id)
    {   
        $record = Item::find($id);
        return view('tenant.ecommerce.items.partial', compact('record'));
    }

    public function detailCart()
    {
        return view('tenant.ecommerce.cart.detail');
    }

    public function pay()
    {
        return view('tenant.ecommerce.cart.pay');
    }

    public function showLogin()
    {
        return view('tenant.ecommerce.user.login');
    }

    public function login(Request $request)
    {   
       
       
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
           return[
               'success' => true,
               'message' => 'Login Success'
           ];
        }
        else{
            return[
                'success' => false,
                'message' => 'Usuario o Password incorrectos'
            ];
        }

    }

    public function storeUser(Request $request)
    {
        try{

         
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->establishment_id = 1;
            $user->type = 'client';
            $user->api_token = str_random(50);
            $user->password = bcrypt($request->input('password'));
            $user->save();
            $user->modules()->sync([1]);

            $credentials = [ 'email' => $user->email, 'password' => $request->input('password') ];
            Auth::attempt($credentials);

            return [
                'success' => true,
                'message' => 'Usuario registrado'
            ];

        }catch(Exception $e)
        {
            return [
                'success' => false,
                'message' =>  $e->getMessage()
            ];
        }
       
    }




   
      
}
