<?php

    namespace Modules\LogisticOperator\Http\Controllers;

    use App\Imports\YobelImport;
    use App\Models\Tenant\Configuration;
    use App\Models\Tenant\Item;
    use App\Models\Tenant\MigrationConfiguration;
    use Exception;
    use App\Imports\ItemsImport;
    use App\Models\Tenant\Order;
    use Carbon\Carbon;
    use GuzzleHttp\Client as ClientGuzzleHttp;
    use Hyn\Tenancy\Models\Website;
    use Illuminate\Contracts\View\Factory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Foundation\Application;
    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\ResourceCollection;
    use Illuminate\Http\Response;
    use Illuminate\Routing\Controller;
    use Illuminate\Support\Str;
    use Illuminate\View\View;
    use Maatwebsite\Excel\Excel;
    use Modules\LogisticOperator\Http\Resources\OrderCollection;
    use Modules\LogisticOperator\Models\LogisticYobel;
    use Modules\LogisticOperator\Models\LogisticYobelApi;
    use Modules\LogisticOperator\Models\YobelConfiguration;

    class YobelController extends Controller
    {


        /** @var string|null */
        protected $compania;
        /** @var string|null */
        protected $usuario;
        /** @var string|null */
        protected $password;
        /** @var YobelConfiguration */
        protected $config;

        /**
         * YobelController constructor.
         *
         * @param string|null $compania
         * @param string|null $usuario
         * @param string|null $password
         */
        public function __construct()
        {

            $config = YobelConfiguration::first();
            if (empty($config)) {
                $config = new YobelConfiguration();
            }
            $this->compania = $config->compania;
            $this->usuario = $config->usuario;
            $this->password = $config->password;
            $this->config = $config;
        }


        public static function cutString($string, $start = 0, $end = 0)
        {
            return substr($string, $start, $end);
        }

        public function crearCliente($id = 0)
        {
            $url = "http://yscmserver-test.yobelscm.biz:1973/TI_Logistics/WSYOB_RECEP_LOG/WSYOB_RECEP/CrearCliente";
            // testing
            $now = Carbon::now();
            $data = $this->config->setSecurity();


            $tem = LogisticYobel::find($id);
            $data = $tem->getDataToCrearCliente($data);

            $response = $this->sendData($url, $data);
            $responseObject = json_decode($response);
            $status = 0;

            if ($responseObject && $responseObject->CrearClienteResult && $responseObject->CrearClienteResult->resultado && $responseObject->CrearClienteResult->resultado == 'OK') {
                $status = 1;
            }
            $log = new LogisticYobelApi([
                'logistic_yobel_id' => $tem->id,
                'command' => 'CrearCliente',
                'yobel_response' => $response,
                'yobel_send' => json_encode($data),
                'status' => $status,
                'last_check' => $now,
            ]);
            $log->push();


            dd([
                'envio' => $data,
                'respuesta' => json_decode($response)
            ]);
        }

        protected function sendData($url = '', $data = [], $type = 'POST')
        {
            $curl = curl_init();
            $data = collect($data)->toJson();
            curl_setopt_array($curl, array (
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $type,
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => array (
                    'Content-Type: application/json',
                ),
                CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        }

        public function crearEmbarque($id = 0)
        {


            $t = LogisticYobel::where('order_id',$id)->first();
            $data  =$t->crearEmbarque();
            dd($data);

        }

        public function crearPedido($id = 0)
        {
            $url = "http://yscmserver-test.yobelscm.biz:1973/TI_Logistics/WSYOB_RECEP_LOG/WSYOB_RECEP/CrearPedido";
            $now = Carbon::now();

            $data = $this->config->setSecurity();
            $tem = LogisticYobel::find($id);
            $data = $tem->crearPedido($data);

            $response = $this->sendData($url, $data);
            $responseObject = json_decode($response);
            $status = 0;

            if ($responseObject && $responseObject->CrearPedidoResult && $responseObject->CrearPedidoResult->resultado && $responseObject->CrearPedidoResult->resultado == 'OK') {
                $status = 1;
            }
            $log = new LogisticYobelApi([
                'logistic_yobel_id' => $tem->id,
                'command' => 'CrearPedido',
                'yobel_response' => $response,
                'yobel_send' => json_encode($data),
                'status' => $status,
                'last_check' => $now,
            ]);
            $log->push();


            dd([
                'envio' => $data,
                'respuesta' => json_decode($response)
            ]);


        }

        public function records(Request $request)
        {
            $records = Order::where($request->column, 'like', "%{$request->value}%")->with('logistic_yobel')->latest();

            return new OrderCollection($records->paginate(config('tenant.items_per_page')));
        }


        public function import(Request $request)
        {
            if ($request->hasFile('file')) {
                try {
                    $import = new YobelImport();
                    $import->import($request->file('file'), null, Excel::XLSX);
                    $data = $import->getData();
                    return [
                        'success' => true,
                        'message' => __('app.actions.upload.success'),
                        'data' => $data
                    ];
                } catch (Exception $e) {
                    return [
                        'success' => false,
                        'message' => $e->getMessage()
                    ];
                }
            }
            return [
                'success' => false,
                'message' => __('app.actions.upload.error'),
            ];
        }


        /**
         * Retorna la vistsa para la configuracion de migracion avanzada en Nota de venta
         *
         * @return Factory|Application|View
         */
        public function SetAdvanceConfiguration()
        {
            $migrationConfiguration = $this->config;
            // modules/LogisticOperator/Resources/views/configuration/sale_notes.blade.php
            return view('logisticoperator::configuration.yobel', compact('migrationConfiguration'));

        }

        /**
         * Guarda los datos para la migracion de nota de venta
         *
         * @param Request $request
         *
         * @return array
         */
        public function SaveSetAdvanceConfiguration(Request $request)
        {

            $data = $request->all();
            $data['success'] = false;
            $data['compania'] = ($request->has('compania')) ? $request->compania : '';
            $data['usuario'] = ($request->has('usuario')) ? $request->usuario : '';
            $data['password'] = ($request->has('password')) ? $request->password : '';
            $data['is_active'] = (bool)(($request->has('is_active')) ? $request->is_active : false);

            if (auth()->user()->type !== 'admin') {
                $data['message'] = 'No puedes realizar cambios';
                return $data;
            }

            $this->config->compania = $data['compania'];
            $this->config->usuario = $data['usuario'];
            $this->config->password = $data['password'];
            $this->config->is_active = $data['is_active'];
            if (
                empty($data['compania']) ||
                empty($data['usuario']) ||
                empty($data['password'])
            ) {
                $this->config->is_active = false;
            }
            $this->config->push();

            $data['success'] = true;
            $data['message'] = 'Ha sido acualizado';
            return $data;

        }


        public function webServiceConfEmarque(Request $request)
        {
            $data = $request->all();
            \Log::debug("Datos de yobel \n\n".var_export($data,true));
            $err = [
                'success' => false,
                'message' => ''
            ];
            $seguridad = $data['Seguridad'] ?? [];
            $compania = $seguridad['compania'] ?? null;
            $usuario = $seguridad['usuario'] ?? null;
            $password = $seguridad['password'] ?? null;
            $now = Carbon::now();


            $log = new LogisticYobelApi([
                'logistic_yobel_id' => 0,
                'command' => 'webServiceConfEmarque',
                'yobel_response' => json_encode($data),
                'yobel_send' => '',
                'status' => 0,
                'last_check' => $now,
            ]);
            $log->push();



            if (
                empty($compania) ||
                empty($usuario) ||
                empty($password)
            ) {
                $err['message'] = 'No se encuentra la compañia COD-555';
                return $err;
            }

            $yobel = YobelConfiguration::where([
                'usuario' => $usuario,
                'password' => $password,
            ])->first();

            if (empty($yobel)) {
                $err['message'] = 'No se encuentra la compañia';
                return $err;
            }


            $Mensaje = $data['Mensaje'] ?? [];


            $body = $Mensaje['Body'] ?? [];
            $ConfEmbarque = $body['ConfEmbarque'] ?? [];
            $CEMEMB = $ConfEmbarque['CEMEMB'] ?? '';
            $Detalles = $ConfEmbarque['Detalles'] ?? [];
            if (!empty($CEMEMB)) {
                $logiscti = LogisticYobel::where('EMBNRO', $CEMEMB)->first();
                if (empty($logiscti)) {
                    $err['message'] = 'No se ha encontrado el embarque ' . $CEMEMB;
                    return $err;
                }
                $itemYobel = $logiscti->items;
                $collectionItem = collect([]);

                foreach ($itemYobel as $item) {
                    $collectionItem->push($item);
                }


                foreach ($Detalles as $item) {
                    $codItem = $item['CEMCPR'];
                    $te = $collectionItem->where('internal_id', $codItem)->first();
                    if (!empty($te)) {
                        $confirmado[] = $te;
                    }

                    /*
                    @todo validar como se comportan los items
             */

                }
                if($logiscti->confirmation_status  <= 1) {
                    $logiscti->confirmation_status = 1;
                    $logiscti->push();
                }

            } else {
                $err['message'] = 'Hay datos para el embarque ' . $CEMEMB;

                return $err;
            }
            return [
                'data'=>$data,
                'success'=>true,
                'message'=>"Se ha confirmado el embarque $CEMEMB",
            ];


        }
        public function webServiceConfPedido(Request $request)
        {
            $data = $request->all();
            \Log::debug("Datos de yobel \n\n".var_export($data,true));
            $err = [
                'success' => false,
                'message' => ''
            ];
            $seguridad = $data['Seguridad'] ?? [];
            $compania = $seguridad['compania'] ?? null;
            $usuario = $seguridad['usuario'] ?? null;
            $password = $seguridad['password'] ?? null;
            $now = Carbon::now();


            $log = new LogisticYobelApi([
                'logistic_yobel_id' => 1,
                'command' => 'webServiceConfPedido - omite el 1',
                'yobel_response' => json_encode($data),
                'yobel_send' => '',
                'status' => 0,
                'last_check' => $now,
            ]);

             $log->push();




            if (
                empty($compania) ||
                empty($usuario) ||
                empty($password)
            ) {
                $err['message'] = 'No se encuentra la compañia COD-555';
                return $err;
            }

            $yobel = YobelConfiguration::where([
                'usuario' => $usuario,
                'password' => $password,
            ])->first();

            if (empty($yobel)) {
                $err['message'] = 'No se encuentra la compañia';
                return $err;
            }

            $Mensaje = $data['Mensaje'] ?? [];
            $body = $Mensaje['Body'] ?? [];
            $ConfPedido = $body['ConfPedido'] ?? [];
            $CPINRO = $ConfPedido['CPINRO'] ?? '';
            $Detalles = $ConfPedido['Detalles'] ?? [];
            if (!empty($CPINRO)) {
                $logiscti = LogisticYobel::where('order', $CPINRO)->first();
                if (empty($logiscti)) {
                    $err['message'] = 'No se ha encontrado el pedido ' . $CPINRO;
                    return $err;
                }
                $itemYobel = $logiscti->items;
                $collectionItem = collect([]);

                foreach ($itemYobel as $item) {
                    $collectionItem->push($item);
                }


                foreach ($Detalles as $item) {
                    $codItem = $item['CPICPR'];
                    $te = $collectionItem->where('internal_id', $codItem)->first();
                    if (!empty($te)) {
                        $confirmado[] = $te;
                    }

                    /*
                    @todo validar como se comportan los items
                    */

                }
                if($logiscti->confirmation_status  <= 2) {
                    $logiscti->confirmation_status = 2;
                     $logiscti->push();
                }

            } else {
                $err['message'] = 'Hay datos para el pedido ' . $CPINRO;

                return $err;
            }
            return [
                'data'=>$data,
                'success'=>true,
                'message'=>"Se ha confirmado el pedido $CPINRO",
            ];


        }


        public function makeYobelPedido(Request $request){
            $data = [
                'success'=>false,
                'message'=>'',
            ];


            $orderNote = Order::find($request->order);
            $orderYobel = null;
            if(!empty($orderNote)){
                $orderYobel = LogisticYobel::where('order_id',$request->order)->first();
                if(empty($orderYobel)){
                    $orderYobel = new LogisticYobel([
                        'order_id'=>$request->order,
                        'person_id'=>$orderNote->customer_id,
                        'status'=>0,
                    ]);
                    $items = [];
                    foreach($orderNote->items as $item){
                        $items[] = (array)($item->item);
                    }
                    $orderYobel->items=$items;
                    $orderYobel->setOrder();
                    $orderYobel->push();
                }
                $items = [];

                foreach($orderNote->items as $item){
                    try {
                        $itemToSave = $item->item;

                    }catch (\ErrorException $e){
                        $itemToSave = Item::find($item->id);
                    }
                    $itemToSave->quantity = $item->quantity;

                    // $itemToSave->internal_id = $i->internal_id;
                    if(!empty($itemToSave->internal_id)) {
                        $items[] = (array)($itemToSave->toArray());
                    }
                }
                $orderYobel->items=$items;
                $orderYobel->push();
                $data['success'] = false;
                $currentStatus = (int)$orderYobel->status;
                $currentCofirmation = (int)$orderYobel->confirmation_status ;

                $data['message'] = 'El status ' . $currentStatus . ' es diferente de la confirmacion ' . $currentCofirmation;

                if(($currentCofirmation == $currentStatus )){
                    if ($currentStatus == 0 && $currentCofirmation == 0){
                        $orderYobel->crearEmbarque();
                        $data['success'] = true;
                        $data['message'] = 'Se ha creado el embarque';
                    }

                    if ($currentStatus == 1 && $currentCofirmation == 1){
                        $orderYobel->crearPedido();
                        $data['success'] = true;
                        $data['message'] = 'Se ha creado el pedido ' . "$currentCofirmation == (int)$currentStatus";
                    }
                }else{
                    $data['message'] = "Aun no ha sido confirmado ";

                }
            }else{
                $data['message']='No se encontró el registro';
            }
            return $data;

            return $orderYobel->toArray();
            return $request->all();
        }
    }
