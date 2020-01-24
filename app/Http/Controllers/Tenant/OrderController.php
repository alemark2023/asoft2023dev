<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
//use App\Http\Requests\Tenant\OrderRequest;
use App\Http\Resources\Tenant\OrderCollection;
use Exception;
use Illuminate\Http\Request;
use App\Models\Tenant\Order;



class OrderController extends Controller
{
    public function index()
    {
        return view('tenant.orders.index');
    }

    public function columns()
    {
        return [
            'id' => 'Codigo de Pedido',
            'number_document' => 'Comprobante Electronico',
        ];
    }

    public function records(Request $request)
    {
        $records = Order::where($request->column, 'like', "%{$request->value}%")->latest();
        
        return new OrderCollection($records->paginate(config('tenant.items_per_page')));
    }


}