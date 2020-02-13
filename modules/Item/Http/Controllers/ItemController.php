<?php

namespace Modules\Item\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Tenant\Item;
use Illuminate\Routing\Controller; 
use Picqer\Barcode\BarcodeGeneratorPNG;

class ItemController extends Controller
{

    public function generateBarcode($id)
    {

        $item = Item::findOrFail($id);
        
        $colour = [150,150,150];

        $generator = new BarcodeGeneratorPNG();
        
        $temp = tempnam(sys_get_temp_dir(), 'item_barcode');

        file_put_contents($temp, $generator->getBarcode($item->internal_id, $generator::TYPE_CODE_128, 5, 70, $colour));

        $headers = [
            'Content-Type' => 'application/png',
        ];

        return response()->download($temp, "{$item->internal_id}-{$item->description}.png", $headers);

    }
 
}
