<?php

namespace Modules\Item\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Tenant\Item;
use Illuminate\Routing\Controller; 
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

class ItemController extends Controller
{

    public function generateBarcode($id)
    {

        $item = Item::findOrFail($id);

        $mpdf = new \Mpdf\Mpdf();

        $mpdf->writeBarcode('978-1234-567-890');

        return $mpdf->Output();
        
        dd($item);
    }

    public function displayPNGBase64($value, $w = 150, $level = 'L', $background = [255, 255, 255], $color = [0, 0, 0], $filename = null, $quality = 0)
    {
        $qrCode = new QrCode($value, $level);
        $output = new Output\Png();
        $base64 = base64_encode($output->output($qrCode, $w, $background, $color, $quality));
        return ($base64);
    }
}
