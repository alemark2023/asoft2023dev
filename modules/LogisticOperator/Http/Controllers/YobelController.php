<?php

    namespace Modules\LogisticOperator\Http\Controllers;

    use App\Imports\YobelImport;
    use Exception;
    use App\Imports\ItemsImport;
    use App\Models\Tenant\Order;
    use Carbon\Carbon;
    use GuzzleHttp\Client as ClientGuzzleHttp;
    use Hyn\Tenancy\Models\Website;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\ResourceCollection;
    use Illuminate\Http\Response;
    use Illuminate\Routing\Controller;
    use Illuminate\Support\Str;
    use Maatwebsite\Excel\Excel;
    use Modules\LogisticOperator\Http\Resources\OrderCollection;
    use Modules\LogisticOperator\Models\LogisticYobel;
    use Modules\LogisticOperator\Models\LogisticYobelApi;

    class YobelController extends Controller
    {


        /** @var string|null */
        protected $compania;
        /** @var string|null */
        protected $usuario;
        /** @var string|null */
        protected $password;

        /**
         * YobelController constructor.
         *
         * @param string|null $compania
         * @param string|null $usuario
         * @param string|null $password
         */
        public function __construct(?string $compania = '', ?string $usuario = '', ?string $password = '')
        {
            $this->compania = $compania;
            $this->usuario = $usuario;
            $this->password = $password;
            /// Testing
            $this->compania = 'PLT';
            $this->usuario = 'PEPLTUSR01';
            $this->password = 'Y0b3lPrb01';
        }


        protected function setSecurity(&$array)
        {
            $array['Seguridad']['compania'] = self::cutString($this->compania, 0, 3);
            $array['Seguridad']['usuario'] = self::cutString($this->usuario, 0, 10);
            $array['Seguridad']['password'] = self::cutString($this->password, 0, 10);
        }

        public static function cutString($string, $start = 0, $end = 0)
        {
            return substr($string, $start, $end);
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

        public function crearCliente($id = 0)
        {
            $url = "http://yscmserver-test.yobelscm.biz:1973/TI_Logistics/WSYOB_RECEP_LOG/WSYOB_RECEP/CrearCliente";
            // testing
            $now = Carbon::now();
            $data = [];
            $this->setSecurity($data);

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
                'envio'=>$data,
                'respuesta'=>json_decode($response)
            ]);
        }
        public function crearEmbarque($id = 0)
        {
            $url = "http://yscmserver-test.yobelscm.biz:1973/TI_Logistics/WSYOB_RECEP_LOG/WSYOB_RECEP/CrearEmbarque";
            // testing
            $data = [];
            $this->setSecurity($data);
            $tem = LogisticYobel::find($id);
            $data = $tem->getDataToCrearEmbarque($data);


            $response = $this->sendData($url, $data);
            dd([
                'envio'=>$data,
                'respuesta'=>json_decode($response)
            ]);

        }

        public function crearPedido($id = 0)
        {
            $url = "http://yscmserver-test.yobelscm.biz:1973/TI_Logistics/WSYOB_RECEP_LOG/WSYOB_RECEP/CrearPedido";
            $now = Carbon::now();

            $data = [];
            $this->setSecurity($data);
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
                'envio'=>$data,
                'respuesta'=>json_decode($response)
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

    }
