<?php

    namespace Modules\LogisticOperator\Http\Controllers;

    use App\Imports\YobelImport;
    use App\Models\Tenant\Configuration;
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
            $url = "http://yscmserver-test.yobelscm.biz:1973/TI_Logistics/WSYOB_RECEP_LOG/WSYOB_RECEP/CrearEmbarque";
            // testing
            $data = $this->config->setSecurity();
            $tem = LogisticYobel::find($id);
            $data = $tem->getDataToCrearEmbarque($data);


            $response = $this->sendData($url, $data);
            dd([
                'envio' => $data,
                'respuesta' => json_decode($response)
            ]);

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
                'yobel_response' => $data,
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
                'compania' => $compania,
                'usuario' => $usuario,
                'password' => $password,
            ])->first();
            if (empty($yobel)) {
                $err['message'] = 'No se encuentra la compañia';
                return $err;
            }


            $Mensaje = $data['Mensaje'] ?? [];

            $head = $Mensaje['Head'] ?? [];
            $id_mensaje = $head['id_mensaje'] ?? '';
            $sistema_origen = $head['sistema_origen'] ?? '';
            $fecha_origen = $head['fecha_origen'] ?? '';
            $tipo = $head['tipo'] ?? "CONFEMB";


            $body = $Mensaje['Body'] ?? [];
            $ConfEmbarque = $body['ConfEmbarque'] ?? [];
            $CEMCIA = $ConfEmbarque['CEMCIA'] ?? '';
            $CEMEMB = $ConfEmbarque['CEMEMB'] ?? '';

            $CEMFEC = $ConfEmbarque['CEMFEC'] ?? '';
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

                    /* "CEMLIN" => "1"
            "CEMCPR" => "PRD001"
            "CEMQTY" => "1"
            "CEMUMC" => "UN"
            "CEMLOT" => "LOT1"
            "CEMFFA" => "20181210"
            "CEMFVE" => "20201231"
            "CEMALX" => null
            "CEMA01" => null
            "CEMN01" => null*/

                }


            } else {
                $err['message'] = 'Hay datos para el embarque ' . $CEMEMB;

                return $err;
            }
            return $data;


        }
    }
