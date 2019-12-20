<?php

namespace Modules\LevelAccess\Http\ViewComposers;

use Modules\LevelAccess\Models\ModuleLevel;

class ModuleLevelViewComposer
{
    public function compose($view)
    {
        $modules = auth()->user()->levels()->pluck('value')->toArray();

        if(count($modules) > 0) {
            $view->vc_modules = $modules;
        } else {
            $view->vc_modules = ModuleLevel::all()->pluck('value')->toArray();
        }
    }
}
