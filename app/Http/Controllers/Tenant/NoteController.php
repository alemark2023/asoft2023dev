<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Document;

class NoteController extends Controller
{
    public function create($document_id)
    {
        $document_affected = Document::find($document_id);

        return view('tenant.documents.note', compact('document_affected'));
    }

    public function record($document_id)
    {
        $record = Document::find($document_id);

        return $record;
    }

    
}