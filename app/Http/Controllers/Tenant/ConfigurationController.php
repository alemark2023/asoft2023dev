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
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ConfigurationController extends Controller
{
    public function create() {
        return view('tenant.configurations.form');
    }

    public function addSeeder(){
       $reiniciar =  DB::connection('tenant')
                        ->table('format_templates')
                        ->truncate();
       $archivos = Storage::disk('core')->allDirectories('Templates/pdf');
       $colection = array();
       $valor = array();
     foreach($archivos as $valor){
        $lina = explode( '/', $valor);
        if(count($lina) <= 3){
        array_push($colection, $lina);
        }
       }

       foreach ($colection as $insertar) {
           $insertar =  DB::connection('tenant')
            ->table('format_templates')
            ->insert(['formats' => $insertar[2] ]);
       }

        return redirect()->route('tenant.advanced.index');
    }

    public function changeFormat(Request $request){
        $format = Configuration::first();
        $format->fill($request->all());
        $format->save();

        $config_format = config(['tenant.pdf_template' => $format->formats]);
        // $fp = fopen(base_path() .'/config/tenant.php' , 'w');
        // fwrite($fp, '<?php return ' . var_export(config('tenant'), true) . ';');
        // fclose($fp);
        return [
            'success' => true,
            'message' => 'Configuración actualizada'
        ];

    }

    public function getFormats(){
         $formats = DB::connection('tenant')->table('format_templates')->get();
         return $formats;
    }

    public function record() {
        $configuration = Configuration::first();
        $record = new ConfigurationResource($configuration);
        return  $record;
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
