<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Document;
use App\Models\Tenant\Kardex;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\Retention;
use App\Models\Tenant\Summary;
use App\Models\Tenant\Voided;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function create()
    {
        return view('tenant.options.form');
    }

    public function deleteDocuments(Request $request)
    {
        Summary::where('soap_type_id', '01')->delete();
        Voided::where('soap_type_id', '01')->delete();
        Purchase::where('soap_type_id', '01')->delete();
        Document::where('soap_type_id', '01')
                ->whereIn('document_type_id', ['07', '08'])->delete();
        Document::where('soap_type_id', '01')->delete();
        Retention::where('soap_type_id', '01')->delete();

        return [
            'success' => true,
            'message' => 'Documentos de prueba eliminados'
        ];
    }
}