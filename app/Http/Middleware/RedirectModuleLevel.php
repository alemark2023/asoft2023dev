<?php

namespace App\Http\Middleware;

use Closure;

class RedirectModuleLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      
        $level = $request->user()->getLevel();
        $path = explode('/', $request->path());
        $levels = $request->user()->getLevels();
        // dd($levels);

        if(!$request->ajax()){

            if(count($levels)){
                // dd("w");

                if(count($levels) < 9){
                    // dd($levels);
                    
                    $group = $this->getGroup($path, $level);
                    // dd($group);

                    if($group){
                        if($this->getLevelByGroup($levels,$group) === 0){
                            return $this->redirectRoute($level);
                        }

                    }
                }

            }
        }

        return $next($request);

    }


    private function redirectRoute($level){

        switch ($level) {

            case 'new_document':
                return redirect()->route('tenant.documents.create');

            case 'list_document':
                return redirect()->route('tenant.documents.index');

            case 'document_not_sent':
                return redirect()->route('tenant.documents.not_sent');

            case 'document_contingengy':
                return redirect()->route('tenant.contingencies.index');

            case 'catalogs':
                return redirect()->route('tenant.items.index');

            case 'summary_voided':
                return redirect()->route('tenant.summaries.create');

            case 'quotations':
                return redirect()->route('tenant.quotations.create');

            case 'sale_notes':
                return redirect()->route('tenant.sale_notes.create');

            case 'incentives':
                return redirect()->route('tenant.incentives.create'); 

        }
    }



    private function getLevelByGroup($levels,$group){

        $levels_x_group  = $levels->filter(function ($module, $key) use($group){
            return $module->value === $group;
        });

        return $levels_x_group->count();
    }


    private function getGroup($path, $module){

        ///* Module Documents */
        // dd($path[1]);
        $group = null;

        if( isset($path[1]) ){

            if($path[0] == "documents" && $path[1] == "create"){
                $group = "new_document";
            }
            else if($path[0] == "documents"  && $path[1] == "not-sent" ){
                $group = "document_not_sent";
            }
            //customers
            else if($path[0] == "persons"  && $path[1] == "customers" ){
                $group = "catalogs";
            }
            else if($path[0] == "quotations" && $path[1] == "create"){
                $group = "quotations";
            }
            else if($path[0] == "quotations" && $path[1] == "edit"){
                $group = "quotations";
            }
            else if($path[0] == "sale-notes" && $path[1] == "create"){
                $group = "sale_notes";
            }

        }
        else if($path[0] == "documents"){
            $group = "list_document";
        }
        elseif($path[0] == "contingencies"){
            $group = "document_contingengy";
        }
        elseif(in_array($path[0], ["items", "categories", "brands"])){
            $group = "catalogs";
        }
        elseif(in_array($path[0], ["summaries", "voided"])){
            $group = "summary_voided";
        }
        elseif($path[0] == "quotations"){
            $group = "quotations";
        }
        elseif($path[0] == "sale-notes"){
            $group = "sale_notes";
        }
        elseif($path[0] == "incentives"){
            $group = "incentives";
        }
        else{
            $group = null;
        }

        return $group;
    }

}
