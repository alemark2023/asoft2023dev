<?php

namespace Modules\Document\Http\ViewComposers;
use App\Models\Tenant\Document;

class DocumentViewComposer
{
    public function compose($view)
    {
        $view->vc_document = Document::whereNotSent()->count();
    }
}