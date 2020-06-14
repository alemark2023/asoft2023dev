<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
//use Illuminate\Support\Str;
//use App\Http\Requests\Tenant\OrderRequest;
use App\Http\Resources\Tenant\OrderCollection;
use App\Http\Resources\Tenant\OrderResource;
use Exception;
use Illuminate\Http\Request;
use App\Models\Tenant\Order;
use App\Models\Tenant\ItemWarehouse;
use App\Http\Resources\Tenant\ItemWarehouseCollection;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Company;
use App\CoreFacturalo\Facturalo;
use App\Models\Tenant\Configuration;
use App\CoreFacturalo\Template;
use Mpdf\Mpdf;
use Mpdf\HTMLParserMode;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;

class OrderController extends Controller
{

  use StorageDocument;

  protected $company;

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

    public function updateStatusOrders(Request $request)
    {
      
      if ($request->record['status_order_id'] == 3) {
        for ($i=0; $i <= count($request->discount)-1; $i++) {
          if (isset($request->discount[$i]['id'])) {
            $itemWarehouse = ItemWarehouse::where('id', $request->discount[$i]['id'])->first();

            //if ($itemWarehouse->stock >= $request->discount[$i]['cantidad']) {
              ItemWarehouse::where('id', $itemWarehouse->id)->update(['stock' => ($itemWarehouse->stock - $request->discount[$i]['cantidad'])]);

            //}
          }
        }
        Order::where('id', $request->record['id'])->update(['status_order_id' => $request->record['status_order_id']]);

        return [
          'message' => 'Estatus y Stock actualizado'
        ];
      }

      Order::where('id', $request->record['id'])->update(['status_order_id' => $request->record['status_order_id']]);
      return [
        'message' => 'Estatus actualizado'
      ];

    }

    public function searchWarehouse(Request $request)
    {
      $product = ItemWarehouse::whereIn('item_id', $request->item_id)->orderBy('item_id')->get();
      return new ItemWarehouseCollection($product);
    }
}
