<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Document;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\Quotation;
use App\Models\Tenant\Kardex;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\Retention;
use App\Models\Tenant\Perception;
use App\Models\Tenant\Summary;
use App\Models\Tenant\Voided;
use Illuminate\Http\Request;
use App\Models\Tenant\Configuration;
use Modules\Expense\Models\Expense;

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

        $quantity = Document::where('soap_type_id', '01')->count();

        Document::where('soap_type_id', '01')
        ->whereIn('document_type_id', ['07', '08'])->delete();        
        Document::where('soap_type_id', '01')->delete();

        $this->update_quantity_documents($quantity);

        Retention::where('soap_type_id', '01')->delete();
        Perception::where('soap_type_id', '01')->delete();
        SaleNote::where('soap_type_id', '01')->delete();
        Quotation::where('soap_type_id', '01')->delete();
        Expense::where('soap_type_id', '01')->delete();

        return [
            'success' => true,
            'message' => 'Documentos de prueba eliminados'
        ];
    }


    private function update_quantity_documents($quantity)
    {  
        $configuration = Configuration::first();
        $configuration->quantity_documents -= $quantity; 
        $configuration->save();        
    }
    
}