<?php

namespace Modules\MobileApp\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Document;
use App\Http\Resources\Tenant\DocumentCollection;
use App\Models\Tenant\StateType;
use App\Http\Resources\Tenant\DocumentResource;


class DocumentController extends Controller
{
        
    /**
     *
     * @return array
     */
    public function record($id)
    {
        return new DocumentResource(Document::findOrFail($id));
    }

    
    /**
     *
     * @return array
     */
    public function tables()
    {
        $state_types = StateType::getDataApiApp();

        return compact('state_types');
    }
    
    
    /**
     * 
     * Listado de documentos
     *
     * @param  Request $request
     * @return DocumentCollection
     */
    public function records(Request $request)
    {
        $records = Document::filterRecordsAppApi($request);

        return new DocumentCollection($records->latest()->take(config('tenant.items_per_page'))->get());
    }

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
