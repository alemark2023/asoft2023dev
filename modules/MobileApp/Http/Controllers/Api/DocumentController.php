<?php

namespace Modules\MobileApp\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Document;
use App\Http\Resources\Tenant\DocumentCollection;


class DocumentController extends Controller
{
    
    public function records(Request $request)
    {

        $records = Document::whereTypeUser()
                            ->where(function($q) use($request){
                                $q->where('series', 'like', "%{$request->input}%" )
                                    ->orWhere('number','like', "%{$request->input}%");
                            })
                            ->where('document_type_id', $request->document_type_id)
                            ->latest()
                            ->take(config('tenant.items_per_page'));

        return new DocumentCollection($records->get());
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
