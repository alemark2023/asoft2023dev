<?php

    namespace Modules\LogisticOperator\Models;

    use App\Models\Tenant\Item;
    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\Order;
    use App\Models\Tenant\Person;
    use Carbon\Carbon;
    use ErrorException;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Support\Str;
    use Modules\Order\Models\OrderNote;
    use phpDocumentor\Reflection\DocBlock\Tags\Throws;

    /**
     * Class LogisticYobel
     *
     * @property int                           $id
     * @property int|null                      $status
     * @property int|null                      $confirmation_status
     * @property int|null                      $person_id
     * @property int|null                      $order_id
     * @property int|null                      $order_note_id
     * @property string|null                   $order
     * @property string|null                   $reference
     * @property string|null                   $gateway_code
     * @property string|null                   $PEDCCL  // Codigo de cliente
     * @property string|null                   $EMBPRV1 // Codigo de proveedor
     * @property string|null                   $EMBNRO // Codigo de embarque
     * @property string                        $items
     * @property Carbon|null                   $created_at
     * @property Carbon|null                   $updated_at
     * @property Person|null                   $person
     * @property Collection|LogisticYobelApi[] $logistic_yobel_apis
     * @mixin ModelTenant
     * @package App\Models
     * @property-read int|null                 $logistic_yobel_apis_count
     * @method static Builder|LogisticYobel newModelQuery()
     * @method static Builder|LogisticYobel newQuery()
     * @method static Builder|LogisticYobel query()
     */
    class LogisticYobel extends ModelTenant
    {
        use UsesTenantConnection;

        protected $table = 'logistic_yobel';
        protected $perPage = 25;
        public const status = [
            0=>'Creado',
            1=>'Embarque',
            2=>'Pedido',
        ];

        protected $casts = [
            'status' => 'int',
            'confirmation_status' => 'int',
            'order_note_id' => 'int',
            'person_id' => 'int',
            'order_id' => 'int'
        ];

        protected $fillable = [
            'person_id',
            'order_id',
            'order',
            'reference',
            'gateway_code',
            'status',
            'confirmation_status',
            'PEDCCL',
            'order_note_id',
            'items'
        ];

        public function setItemsAttribute($value)
        {
            $this->attributes['items'] = (null === $value) ? null : json_encode($value);
        }

        public function getItemsAttribute($value)
        {
            return (null === $value) ? null : (object)json_decode($value);
        }

        /**
         * @return BelongsTo
         */
        public function orders()
        {
            return $this->belongsTo(Order::class);
        }

        /**
         * @return BelongsTo
         */
        public function order_notes()
        {
            return $this->belongsTo(OrderNote::class);
        }

        /**
         * @return BelongsTo
         */
        public function person()
        {
            return $this->belongsTo(Person::class);
        }

        /**
         * @return HasMany
         */
        public function logistic_yobel_apis()
        {
            return $this->hasMany(LogisticYobelApi::class);
        }

        /**
         * @param string|null $PEDCCL
         *
         * @return LogisticYobel
         */
        public function setPEDCCL(?string $PEDCCL): LogisticYobel
        {
            $this->PEDCCL = $PEDCCL;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getEMBPRV1(): ?string
        {
            return $this->EMBPRV1;
        }

        /**
         * @param string|null $EMBPRV1
         *
         * @return LogisticYobel
         */
        public function setEMBPRV1(?string $EMBPRV1): LogisticYobel
        {
            $this->EMBPRV1 = $EMBPRV1;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getEMBNRO(): ?string
        {
            if(empty($this->EMBNRO)){
                $config = $this->getConfig();
                $cdoClient = $config->getCompania() . $this->order_id ."E". $this->order_note_id ."E". $this->person_id . Str::random(10);
                $this->EMBNRO = $cdoClient;
            }
            return $this->EMBNRO;
        }

        /**
         * @param string|null $EMBNRO
         *
         * @return LogisticYobel
         */
        public function setEMBNRO(?string $EMBNRO): LogisticYobel
        {
            $this->EMBNRO = $EMBNRO;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getOrder(): ?string
        {
            return $this->order;
        }

        /**
         * @param string|null $Order
         *
         * @return LogisticYobel
         */
        public function setOrder(?string $Order = null): LogisticYobel
        {
            if (empty($Order)) {
                $config = $this->getConfig();
                $Order = $config->getCompania() . $this->order_id . $this->order_note_id . $this->person_id . Str::random(10);
            }
            $Order = self::cutString($Order, 0, 10);

            $this->order = $Order;
            return $this;
        }

        /**
         * @return YobelConfiguration
         */
        protected function getConfig()
        {
            $config = YobelConfiguration::first();
            if (empty($config)) {
                $config = new YobelConfiguration();
            }
            return $config;
        }

        protected static function cutString($string, $start = 0, $end = 0)
        {
            return substr($string, $start, $end);
        }

        public function crearPedido()
        {
            $url = "http://yscmserver-test.yobelscm.biz:1973/TI_Logistics/WSYOB_RECEP_LOG/WSYOB_RECEP/CrearPedido";

            $config = $this->getConfig();
            $data = $config->setSecurity();
            $now = Carbon::now();
            $order = Order::find($this->order_id);
            if (empty($order)) {
                $order = OrderNote::find($this->order_note_id);
            }
            $customer = $this->getPEDCCL();
            if(empty($customer)){
                throw new \Exception("No hay codigo de cliente");

            }
            $items = $this->items;
            $this->crearProducto();

            $data['Mensaje']['Head']["id_mensaje"] = $order->external_id;
            $data['Mensaje']['Head']["sistema_origen"] = "SAP";
            $data['Mensaje']['Head']["fecha_origen"] = $now->format('Y-m-d') . "T" . $now->format('H:m:i');
            $data['Mensaje']['Head']["tipo"] = "RECPED";

            $totalItem = count((array)$items);
            $temp = [
                "PEDCIA" => $data['Seguridad']['compania'] ?? "IRX",
                "PEDFPR" => $now->format('Y-m-d'), //Fecha de proceso
                "PEDCTR" => $now->format('i'), //Cód. Transacción
                "PEDNRO" => $this->order, //Número de pedido
                "PEDCCL" => $customer, // Código destino (cliente)
                "PEDFCH" => $now->format('Y-m-d'), // Fecha de emisión del pedido
                "PEDTIT" => $totalItem, //Cantidad de Líneas del Detalle
                "PEDTUN" => "", // Sumatoria de cantidades del detalle - Se llena al final del for
                "PEDFEI" => "", // Fecha entrega Inicial
                "PEDHOI" => "", // Hora de entrega inicial
                "PEDFEF" => "",// Fecha Entrega final
                "PEDHOF" => "", // Hora Entrega Final
                "PEDNOC" => "", // Orden de Compra
                "PEDTDA" => "",// Tienda
                "PEDA01" => "", // Infomracion Adicional1
                "PEDN01" => "", // Numérico1
                "PEDF01" => "", // Detalles Flag1
                'Detalles' => [],
            ];
            $i = 1;
            $totalQty = 0;
            foreach ($items as $item) {
                $internalId = null;
                try{
                    $internalId = $item->internal_id;
                }catch (ErrorException $e){
                    $item =  Item::find($item->id);
                    $internalId = $item->internal_id;

                }
                $temp1 = [
                    "PEDAUX" => $i, //Número de Línea en el Pedido
                    "PEDCPR" => $item->internal_id, // Código de Producto
                    "PEDLOT" => "", // Lote de Producto
                    "PEDCTD" => $item->quantity, // Cantidad de venta
                    "PEDALM" => "", // Almacén Origen
                    "PEDSKU" => "", // SKU del cliente
                    "PEDUXE" => "", // Unidades máximas por empaque para Picking
                    "PEDA02" => "", //Adicional2
                    "PEDN02" => "", // Numérico2
                    "PEDF02" => "" // Detalles Flag2
                ];
                $totalQty += $item->quantity;
                $temp['Detalles'][] = $temp1;
                $i++;

            }
            $temp['PEDTUN'] = $totalQty;
            $data['Mensaje']['Body'] ['Pedidos'] [] = $temp;

            $response = $this->sendData($url, $data);
            $responseObject = json_decode($response);
            $status = 0;

            if ($responseObject && $responseObject->CrearPedidoResult && $responseObject->CrearPedidoResult->resultado && $responseObject->CrearPedidoResult->resultado == 'OK') {
                $status = 1;
                $this->status = 2;
            }
            $log = new LogisticYobelApi([
                'logistic_yobel_id' => $this->id,
                'command' => 'CrearPedido',
                'yobel_response' => $response,
                'yobel_send' => json_encode($data),
                'status' => $status,
                'last_check' => $now,
            ]);
            $log->push();


            if($status!== 1){
                throw new \Exception($response);
            }

            return $data;
        }

        /**
         * @return string|null
         */
        public function getPEDCCL(): ?string
        {
            $cod = $this->PEDCCL;
            if (empty($cod)) {
                $this->crearCliente();
            }
            return $this->PEDCCL;
        }

        /**
         * Valida si existe el codigo de cliente, si no existe, lo crea.
         *
         * @return $this
         */
        protected function crearCliente()
        {
            if (!empty($this->PEDCCL)) return $this;
            $url = "http://yscmserver-test.yobelscm.biz:1973/TI_Logistics/WSYOB_RECEP_LOG/WSYOB_RECEP/CrearCliente";
            $config = $this->getConfig();
            $data = $config->setSecurity();

            $now = Carbon::now();
            $nowYMD = $now->format('Y-m-d');

            $order = Order::find($this->order_id);
            if (empty($order)) {
                $order = OrderNote::find($this->order_note_id);
                $customer = $order->customer;
                $name = $customer->name;
                $address = $customer->address;
                $department_id = $customer->department_id;
                $department = optional($customer->department->description);
                $province_id = $customer->province_id;
                $province = optional($customer->province->description);
                $district_id = $customer->district_id;
                $district = optional($customer->district->description);
                $number = $customer->number;
                $tradeName = (empty($customer->trade_name)) ? $name : $customer->trade_name;
                $telephone = $customer->telephone;
                $email = $customer->email;
            } else {
                $customer = $order->customer;
                $name = $customer->apellidos_y_nombres_o_razon_social;
                $address = $customer->direccion;


                $department_id = $customer->department_id;
                $department = optional($customer->department->description);
                $province_id = $customer->province_id;
                $province = optional($customer->province->description);
                $district_id = $customer->district_id;
                $district = optional($customer->district->description);
                $number = $customer->number;
                $tradeName = $name;
                $telephone = $customer->telephone;
                $email = $customer->email;
            }

            // CMAR BORRAR @todo borrar
            $address = ' Testing Address ';

            if(empty($address)){
                throw new \Exception("No hay direccion de entrega");
            }


            $cdoClient = $config->getCompania() . $this->order_id ."C". $this->order_note_id ."C". $this->person_id . Str::random(10);


            $data['Mensaje']['Head']["id_mensaje"] = $order->external_id;
            $data['Mensaje']['Head']["sistema_origen"] = "SAP";
            $data['Mensaje']['Head']["fecha_origen"] = $now->format('Y-m-d') . "T" . $now->format('H:m:i');
            $data['Mensaje']['Head']["tipo"] = "RECCLIEN";

            $temp = [
                "CLICIA" => self::cutString($data['Seguridad']['compania'] ?? "IRX", 0, 3), // Comṕañia
                "CLIFPR" => $nowYMD, // Fecha del proceso
                "CLICCL" => $cdoClient, // Codigo de destino cliente, provee, serv tec
                "CLINBR" => self::cutString($name, 0, 100), // Nombre / Razon Social
                "CLIDIR" => self::cutString($address, 0, 200), // Dirección de Entrega de Mercadería
                "CLIUBG" => self::cutString($district_id, 0, 10), // Ubigeo Fiscal para entrega de documentos fiscales
                // "CLINBRF" => "RAZON SOCIAL FISCAL-RAZON SOCIAL FISCAL-RAZON SOCIAL FISCAL-", // Nombre o Razón Social Fiscal
                "CLINBRF" => self::cutString($tradeName, 0, 75), // nombre o Razón Social Fiscal
                "CLIDIRF" => self::cutString($address, 0, 70), // Dirección Fiscal para entrega de documentos fiscales
                "CLIUBF" => self::cutString($district_id, 0, 10), // Ubigeo Fiscal para entrega de documentos fiscales
                "CLIRUC" => self::cutString($number, 0, 15),
                "CLIREF" => self::cutString('', 0, 100), // "REFERENCIA DIRECCION ENTREGA-REFERENCIA DIRECCION ENTREGA- REFERENCIA DIRECCION ENTREGA-REFERENCIA DI"
                "CLIDNI" => self::cutString($number, 0, 15),
                "CLITLF" => self::cutString($telephone, 0, 20),
                "CLICEL" => self::cutString($email, 0, 20),
                "CLIDIS" => $district,
                "CLIPRV" => $province,
                "CLIDEP" => $department,
                "CLILAT" => "0",
                "CLILON" => "0"
            ];
            $data['Mensaje']['Body']['Clientes'][] = $temp;

            $response = $this->sendData($url, $data);
            $responseObject = json_decode($response);
            $status = 0;

            if ($responseObject && $responseObject->CrearClienteResult && $responseObject->CrearClienteResult->resultado && $responseObject->CrearClienteResult->resultado == 'OK') {
                $status = 1;
                $this->PEDCCL = $cdoClient;
                $this->push();
            }
            $log = new LogisticYobelApi([
                'logistic_yobel_id' => $this->id,
                'command' => 'CrearCliente',
                'yobel_response' => $response,
                'yobel_send' => json_encode($data),
                'status' => $status,
                'last_check' => $now,
            ]);
            $log->push();

            if($status!== 1){
                throw new \Exception($response);
            }
            return $this;
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

        protected function crearProducto(){
            $url = "http://yscmserver-test.yobelscm.biz:1973/TI_Logistics/WSYOB_RECEP_LOG/WSYOB_RECEP/CrearProductoHJ";
            $config = $this->getConfig();
            $data = $config->setSecurity();

            $now = Carbon::now();
            /** @var Order $order */
            $order = Order::find($this->order_id);
            if (empty($order)) {
                $order = OrderNote::find($this->order_note_id);
            }


            $data['Mensaje']['Head']["id_mensaje"] = $order->external_id;
            $data['Mensaje']['Head']["sistema_origen"] = "SAP";
            $data['Mensaje']['Head']["fecha_origen"] = $now->format('Y-m-d') . "T" . $now->format('H:m:i');
            $data['Mensaje']['Head']["tipo"] = "RECPRODU";
            $items = $this->items;

            foreach ($items as $item) {
                $itemDb = Item::find($item->id);
                $internalId = null;
                try{
                    $internalId = $item->internal_id;
                }catch (ErrorException $e){
                    $item =  $itemDb;
                    $internalId = $item->internal_id;

                }
                $unitId = 'NIU';
                try{
                    $unitId = $item->unit_type_id;
                }catch (ErrorException $e) {
                    $unitId = 'NIU';

                }
                $temp1 = [

                    "PRDCIA"=> self::cutString($data['Seguridad']['compania'] ?? "IRX", 0, 3), // Compañía
                    "PRDPRO"=> $internalId, // Código de producto
                    "PRDDES"=> $item->description, //Descripción del producto
                    "PRDFAM"=> "FAMILIA", //Familia
                    "PRDSFM"=> "SUBFAMILIA", //SUBFAMILIA
                    "PRDCB1"=> $itemDb->barcode, //Codigo de barras 1
                    "PRDCB2"=> $itemDb->barcode, //Codigo de barras 2
                    "PRDUM1"=> $unitId, // Unidad de medida 1
                    "PRDUM2"=> $unitId, //Unidad de medida 2

                    "PRDUX1"=> "1234567", //Unidades por empaque 1
                    "PRDLR1"=> "12345.55", //Largo_1
                    "PRDAN1"=> "12345.22", //Ancho_1
                    "PRDAL1"=> "12345.44", //Alto_1
                    "PRDPE1"=> "1234567.33", //Peso_1
                    "PRDVO1"=> "12345.999", //Volumen_1

                    "PRDUX2"=> "24", // Unidades por empaque 2
                    "PRDLR2"=> "", // Largo_2
                    "PRDAN2"=> "12.00", // Ancho_2
                    "PRDAL2"=> "2.00", // Alto_2
                    "PRDPE2"=> "50.00",// Peso_2
                    "PRDVO2"=> "312.000",// Volumen_2

                    "PRDFML"=> "0",// Flag de Lote
                    "PRDCSX"=> "$internalId",
                    "PRDSER"=> "0" // Flag de Control de Serie
                ];
                $data['Mensaje']['Body']['Productos'][] = $temp1;

            }

            $response = $this->sendData($url, $data);
            $responseObject = json_decode($response);
            $status = 0;

            if ($responseObject && $responseObject->CrearProductoHJResult && $responseObject->CrearProductoHJResult->resultado && $responseObject->CrearProductoHJResult->resultado == 'OK') {
                $status = 1;
                // $this->push();
            }
            $log = new LogisticYobelApi([
                'logistic_yobel_id' => $this->id,
                'command' => 'CrearProductoHJ',
                'yobel_response' => $response,
                'yobel_send' => json_encode($data),
                'status' => $status,
                'last_check' => $now,
            ]);
            $log->push();

            if($status!== 1){
                throw new \Exception($response);
            }
            return $data;
        }
        public function crearEmbarque()
        {
            $url = "http://yscmserver-test.yobelscm.biz:1973/TI_Logistics/WSYOB_RECEP_LOG/WSYOB_RECEP/CrearEmbarque";
            $config = $this->getConfig();
            $data = $config->setSecurity();

            $now = Carbon::now();
            /** @var Order $order */
            $order = Order::find($this->order_id);
            if (empty($order)) {
                $order = OrderNote::find($this->order_note_id);
            }

            $this->crearProveedor();
            $this->crearProducto();
            $cdoClient = $this->getEMBNRO();


            $data['Mensaje']['Head']["id_mensaje"] = $order->external_id;
            $data['Mensaje']['Head']["sistema_origen"] = "SAP";
            $data['Mensaje']['Head']["fecha_origen"] = $now->format('Y-m-d') . "T" . $now->format('H:m:i');
            $data['Mensaje']['Head']["tipo"] = "RECEMB";

            $temp = [];
            $temp["EMBCIA"] = $data['Seguridad']['compania'] ?? "IRX";
            $temp["EMBNRO"] = $cdoClient; // numero de embarque
            $temp["EMBFA1"] = $now->format('Y-m-d'); // fecha de arribo a bodega
            $temp["EMBOCP"] = "OC0001"; // numero de orden de compra
            $temp["EMBPRV1"] = $this->EMBPRV1; //codigo del proveedor
            $temp["EMBPOR"] = "PE"; // pais de origen
            $temp["EMBNCT"] = "0001"; // Numero de contenedor
            $temp["EMBA01"] = "";
            $temp["EMBN01"] = "";
            $items = $this->items;


            $i =1;
            foreach ($items as $item) {
                $internalId = null;
                try{
                    $internalId = $item->internal_id;
                }catch (ErrorException $e){
                    $item =  Item::find($item->id);
                    $internalId = $item->internal_id;

                }
                $unitId = 'NIU';
                try{
                    $unitId = $item->unit_type_id;
                }catch (ErrorException $e) {
                    $unitId = 'NIU';

                }
                    $temp1 = [
                    "EMBLIN" => $i,
                    "EMBPRO" => $internalId,
                    "EMBQTY" =>  $item->quantity,
                    "EMBUMC" =>  $item->unit_type_id,
                    "EMBLOT" => "",
                    "EMBFVE" => "20201231",
                    "EMBALX" => "",
                    "EMBA02" => "",
                    "EMBN02" => "",

                ];
                $temp['Detalles'][] = $temp1;
                $i++;

            }

            $data['Mensaje']['Body']['Embarques'][] = $temp;
            $response = $this->sendData($url, $data);
            $responseObject = json_decode($response);
            $status = 0;

            if ($responseObject && $responseObject->CrearEmbarqueResult && $responseObject->CrearEmbarqueResult->resultado && $responseObject->CrearEmbarqueResult->resultado == 'OK') {
                $status = 1;
                $this->status = 1;
                $this->EMBNRO = $cdoClient;
                $this->push();
            }
            $log = new LogisticYobelApi([
                'logistic_yobel_id' => $this->id,
                'command' => 'CrearEmbarque',
                'yobel_response' => $response,
                'yobel_send' => json_encode($data),
                'status' => $status,
                'last_check' => $now,
            ]);
            $log->push();

            if($status!== 1){
                throw new \Exception($response);
            }
            return $data;
        }

        protected function crearProveedor()
        {
            if (!empty($this->EMBPRV1)) return $this;
            $url = "http://yscmserver-test.yobelscm.biz:1973/TI_Logistics/WSYOB_RECEP_LOG/WSYOB_RECEP/CrearProveedor";
            $config = $this->getConfig();
            $data = $config->setSecurity();

            $now = Carbon::now();

            $order = Order::find($this->order_id);
            if (empty($order)) {
                $order = OrderNote::find($this->order_note_id);
                $customer = $order->customer;
                $name = $customer->name;
            } else {
                $customer = $order->customer;
                $name = $customer->apellidos_y_nombres_o_razon_social;
            }



            $cdoClient =self::cutString( $config->getCompania() . $this->order_id ."C". $this->order_note_id ."C". $this->person_id . Str::random(20),0,20);


            $data['Mensaje']['Head']["id_mensaje"] = $order->external_id;
            $data['Mensaje']['Head']["sistema_origen"] = "SAP";
            $data['Mensaje']['Head']["fecha_origen"] = $now->format('Y-m-d') . "T" . $now->format('H:m:i');
            $data['Mensaje']['Head']["tipo"] = "RECPROV";

            $temp = [
                "PRVCIA" => self::cutString($data['Seguridad']['compania'] ?? "IRX", 0, 3), // Comṕañia
                "PRVPRV" => $cdoClient, // Codigo de destino cliente, provee, serv tec
                "PRVNPR" => self::cutString($name, 0, 100), // Nombre / Razon Social

            ];
            $data['Mensaje']['Body']['Proveedores'][] = $temp;

            $response = $this->sendData($url, $data);
            $responseObject = json_decode($response);
            $status = 0;
            if ($responseObject && $responseObject->CrearProveedorResult && $responseObject->CrearProveedorResult->resultado && $responseObject->CrearProveedorResult->resultado == 'OK') {
                $status = 1;
                $this->EMBPRV1 = $cdoClient;
                $this->push();
            }
            $log = new LogisticYobelApi([
                'logistic_yobel_id' => $this->id,
                'command' => 'CrearProveedor',
                'yobel_response' => $response,
                'yobel_send' => json_encode($data),
                'status' => $status,
                'last_check' => $now,
            ]);
            $log->push();

            if($status!== 1){
                throw new \Exception($response);
            }
            return $this;
        }

    }
