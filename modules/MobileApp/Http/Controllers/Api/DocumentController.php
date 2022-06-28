<?php

namespace Modules\MobileApp\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Document;


class DocumentController extends Controller
{
    
    /**
     * 
     * Obtener notificaciones
     * 
     * Comprobantes enviados/por enviar
     * Comprobantes pendientes de rectificación
     *
     * @return array
     */
    public function getNotifications()
    {
        
        $documents_not_sent = Document::whereNotSent()->count();
        $documents_regularize_shipping = Document::whereRegularizeShipping()->count();

        return [
            'success' => true,
            'data' => [
                'documents_not_sent' => $documents_not_sent,
                'documents_regularize_shipping' => $documents_regularize_shipping,
            ]
        ];
    }

}
