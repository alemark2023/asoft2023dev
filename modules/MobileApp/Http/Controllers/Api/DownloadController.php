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

        return $this->getHtmlPdf($document, 'invoice', $format);
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
