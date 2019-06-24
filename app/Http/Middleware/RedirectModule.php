<?php

namespace App\Http\Middleware;

use Closure;

class RedirectModule
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

        $module = $request->user()->getModule();
        $path = explode('/', $request->path());
        $modules = $request->user()->getModules();

        if(! $request->ajax()){

            if(count($modules)){
                
                if(count($modules) < 5){

                    $group = $this->getGroup($path, $module);
                    if($group){
    
                        if($this->getModuleByGroup($modules,$group) === 0){ 
                            return $this->redirectRoute($module);                    
                        }     
    
                    }
                }                
                                   
            }
        }
 
        return $next($request);
 
    }

    
    private function redirectRoute($module){

        switch ($module) {

            case 'documents':
                return redirect()->route('tenant.documents.create');                
            
            case 'purchases':
                return redirect()->route('tenant.purchases.index');                

            case 'advanced':
                return redirect()->route('tenant.retentions.index');            

            case 'reports':
                return redirect()->route('tenant.reports.index');           

            case 'configuration':
                return redirect()->route('tenant.companies.create');
             
        }
    }

    

    private function getModuleByGroup($modules,$group){

        $modules_x_group  = $modules->filter(function ($module, $key) use($group){
            return $module->value === $group;
        }); 

        return $modules_x_group->count();
    }


    private function getGroup($path, $module){
         
        ///* Module Documents */
        
        if($path[0] == "documents"){
            $group = "documents";

        }
        elseif($path[0] == "dashboard"){
            $group = "documents";
            
        }
        elseif($path[0] == "quotations"){
            $group = "documents";
            
        }
        elseif($path[0] == "items"){
            $group = "documents";
            
        }
        elseif($path[0] == "summaries"){
            $group = "documents";
            
        }
        elseif($path[0] == "voided"){
            $group = "documents";
            
        }

        ///* Module purchases  */

        elseif($path[0] == "purchases"){
            $group = "purchases";
            
        }

        ///* Module advanced */

        elseif($path[0] == "retentions"){
            $group = "advanced";
            
        }
        elseif($path[0] == "dispatches"){
            $group = "advanced";
            
        }
        elseif($path[0] == "perceptions"){
            $group = "advanced";
            
        }

        ///* Module reports */

        elseif($path[0] == "reports"){
            $group = "reports";
            
        }

        ///* Module configuration */

        elseif($path[0] == "users"){
            $group = "configuration";
            
        }
        elseif($path[0] == "establishments"){
            $group = "configuration";
            
        }
        elseif($path[0] == "companies"){
            
            $group = "configuration";

            if(count($path) > 0 && $path[1] == "uploads" && $module == "documents"){
                $group = "documents";
            }
            
        }
        elseif($path[0] == "catalogs"){
            $group = "configuration";
            
        }
        elseif($path[0] == "advanced"){
            $group = "configuration";
            
        }
        
        ///* Determinate type person */

        elseif($path[0] == "persons"){
            if($path[1] == "suppliers"){
                $group = "purchases"; 

            }elseif($path[1] == "customers"){
                $group = "documents"; 

            }else{
                $group = null;
            } 
        }else{
            $group = null;
        } 
        
        return $group;
    }
 
}
