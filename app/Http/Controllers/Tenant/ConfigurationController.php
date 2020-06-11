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
use App\Models\Tenant\Catalogs\AffectationIgvType;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

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

        return [
            'success' => true,
            'message' => 'Configuración actualizada'
        ];
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

    public function pdfTemplates(){
        return view('tenant.advanced.pdf_templates');
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


    public function tables()
    {

        $affectation_igv_types = AffectationIgvType::whereActive()->get();

        return compact('affectation_igv_types');

    }

    public function visualDefaults()
    {
        $defaults = [
            'bg' => 'light',
            'header' => 'light',
            'sidebars' => 'light',
        ];
        $configuration = Configuration::first();
        $configuration->visual = $defaults;
        $configuration->save();

        return [
            'success' => true,
            'message' => 'Configuración actualizada'
        ];
    }

    public function visualSettings(Request $request)
    {
        $visuals = [
            'bg' => $request->bg,
            'header' => $request->header,
            'sidebars' => $request->sidebars,
        ];

        $configuration = Configuration::find(1);
        $configuration->visual = $visuals;
        $configuration->save();

        return [
            'success' => true,
            'message' => 'Configuración actualizada'
        ];

    }

    public function getSystemPhone()
    {
        $configuration = Configuration::first();
        $ws = $configuration->enable_whatsapp;

        $current = url('/phone');
        $parse_current = parse_url($current);
        $explode_current = explode('.', $parse_current['host']);
        $app_url = config('app.url');
        if(!array_key_exists('port', $parse_current)){
            $path = $app_url.$parse_current['path'];
        }else{
            $path = $app_url.':'.$parse_current['port'].$parse_current['path'];
        }

        $http = new Client(['verify' => false]);
        $response = $http->request('GET', $path);
        if($response->getStatusCode() == '200'){
            $body = $response->getBody();

            $configuration->phone_whatsapp = $body;
            $configuration->save();
        }
        return 'error';
    }

    
    public function uploadFile(Request $request)
    {
        if ($request->hasFile('file')) {

            $configuration = Configuration::first();
            
            
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $name = date('Ymd').'_'.$configuration->id.'.'.$ext;
         
            
            request()->validate(['file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048']);
            
            $file->storeAs('public/uploads/header_images', $name);


            $configuration->header_image = $name;

            $configuration->save();

            return [
                'success' => true,
                'message' => __('app.actions.upload.success'),
                'name' => $name,
            ];
        }
        return [
            'success' => false,
            'message' =>  __('app.actions.upload.error'),
        ];
    }

}
