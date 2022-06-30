<?php

namespace Modules\MobileApp\Http\Controllers\Api;

use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\Http\Controllers\Controller;
use App\CoreFacturalo\Facturalo;
use App\Http\Controllers\Tenant\QuotationController;
use App\CoreFacturalo\Template;
use App\Models\Tenant\Company;
use Mpdf\Mpdf;
use Exception;


class DownloadController extends Controller
{
    

    /**
     * 
     * Retornar pdf en html
     *
     * @param  string $model
     * @param  string $external_id
     * @param  string $format
     * @return string
     */
    public function documentPrintPdf($model, $external_id, $format) 
    {
        $model = "App\\Models\\Tenant\\".ucfirst($model);
        $document = $model::where('external_id', $external_id)->first();

        if (!$document) throw new Exception("El código {$external_id} es inválido, no se encontro documento relacionado");

        $html = $this->getHtmlPdf($document, 'invoice', $format);

        // se reemplaza ancho para impresion desde app para tickets
        $size_width = $this->getSizeWidth($format);

        if($size_width)
        {
            $search_key = '<style>';
            $replace_size = "{$search_key} @media print { .page, .page-content, html, body, .framework7-root, .views, .view { height: auto !important; width: {$size_width}mm !important;}}";
    
            return str_replace($search_key, $replace_size, $html);
        }

        return $html;
    }
    

    /**
     * 
     * Obtener medida del formato ticket para asignar el valor a la impresión
     *
     * @param  string $format
     * @return float
     */
    public function getSizeWidth($format)
    {
        $size_width = null;

        switch ($format) {
            case 'ticket_50':
                $size_width = 45;
                break;
            
            case 'ticket_58':
                $size_width = 56;
                break;

            case 'ticket':
                $size_width = 78;
                break;
        }

        return $size_width;
    }


    /**
     * Reload Ticket
     * @param  string $document
     * @param  string $format
     * @return string
     */
    private function getHtmlPdf($document, $type, $format) 
    {
        return (new Facturalo)->createPdf($document, $type, $format, 'html');
    }

    
}
