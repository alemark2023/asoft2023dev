<?php
namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Item;


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

    public function partialItem($id)
    {   
        $record = Item::find($id);
        return view('tenant.ecommerce.items.partial', compact('record'));
    }

    public function detailCart()
    {
        return view('tenant.ecommerce.cart.detail');
    }




   
      
}
