<?php

namespace App\Http\ViewComposers\Tenant;

use App\Models\Tenant\Configuration;

class CompactSidebarViewComposer
{
    public function compose($view)
    {
        $view->vc_compact_sidebar = Configuration::first();
    }
}