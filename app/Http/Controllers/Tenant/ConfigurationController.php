<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Requests\Tenant\ConfigurationRequest;
use App\Http\Resources\Tenant\ConfigurationResource;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Item;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Tenant\FormatTemplate;

class ConfigurationController extends Controller
{
    public function create() {
        return view('tenant.configurations.form');
    }


    
    public function record() {
        $configuration = Configuration::first();
        $record = new ConfigurationResource($configuration);
        $formats = FormatTemplate::all();
        return  $formats;
    }
    
    public function store(ConfigurationRequest $request) {
        $id = $request->input('id');
        $configuration = Configuration::find($id);
        $configuration->fill($request->all());
        $configuration->save();
        
        return [
            'success' => true,
            'message' => 'Configuración actualizada'
        ];
    }

    
    public function icbper(Request $request) {


        DB::connection('tenant')->transaction(function () use($request){

            $id = $request->input('id');
            $configuration = Configuration::find($id);
            $configuration->amount_plastic_bag_taxes = $request->amount_plastic_bag_taxes;
            $configuration->save();
            

            $items = Item::get(['id','amount_plastic_bag_taxes']);

            foreach ($items as $item) { 

                $item->amount_plastic_bag_taxes = $configuration->amount_plastic_bag_taxes;
                $item->update();
            
            }

        });

        return [
            'success' => true,
            'message' => 'Configuración actualizada'
        ];
    }
}