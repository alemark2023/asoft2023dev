<?php

    namespace Modules\LogisticOperator\Models;

    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\Order;
    use App\Models\Tenant\Person;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Support\Str;

    /**
     * Class LogisticYobel
     *
     * @property int                           $id
     * @property int|null                      $person_id
     * @property int|null                      $order_id
     * @property string|null                   $order
     * @property string|null                   $reference
     * @property string|null                   $gateway_code
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

        protected $casts = [
            'person_id' => 'int',
            'order_id' => 'int'
        ];

        protected $fillable = [
            'person_id',
            'order_id',
            'order',
            'reference',
            'gateway_code',
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


        public function  crearPedido($data = []){

            $now = Carbon::now();
            /** @var Order $order */
            $order = Order::find($this->order_id);
            $customer = $order->customer;
            $items = $this->items;

            $data['Mensaje']['Head']["id_mensaje"] = $order->external_id;
            $data['Mensaje']['Head']["sistema_origen"] = "SAP";
            $data['Mensaje']['Head']["fecha_origen"] = $now->format('Y-m-d') . "T" . $now->format('H:m:i');
            $data['Mensaje']['Head']["tipo"] = "RECPED";

            $totalItem = count((array)$items);
            $temp = [
                "PEDCIA" => $data['Seguridad']['compania'] ?? "IRX",
                "PEDFPR" => "2018-12-14", //Fecha de proceso
                "PEDCTR" => "P1", //Cód. Transacción
                "PEDNRO" => "PEDIRX101", //Número de pedido
                "PEDCCL" => "CLIIRX01", // Código destino (cliente)
                "PEDFCH" => $now->format('Y-m-d'), // Fecha de emisión del pedido
                "PEDTIT" => $totalItem, //Cantidad de Líneas del Detalle
                "PEDTUN" => "30", // Sumatoria de cantidades del detalle
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
            $totalQty =0;
            foreach ($items as $item){
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
            $data['Mensaje']['Body'] ['Pedidos'] []= $temp;
            return $data;
        }
        public function getDataToCrearEmbarque($data = [])
        {

            $now = Carbon::now();
            /** @var Order $order */
            $order = Order::find($this->order_id);
            $customer = $order->customer;

            $data['Mensaje']['Head']["id_mensaje"] = $order->external_id;
            $data['Mensaje']['Head']["sistema_origen"] = "SAP";
            $data['Mensaje']['Head']["fecha_origen"] = $now->format('Y-m-d') . "T" . $now->format('H:m:i');
            $data['Mensaje']['Head']["tipo"] = "RECEMB";

            $temp = [];
            $temp["EMBCIA"] = $data['Seguridad']['compania'] ?? "IRX";
            $temp["EMBNRO"] = "IRXEMBPRB001"; // numero de embarque
            $temp["EMBFA1"] = "2018-12-14"; // fecha de arribo a bodega
            $temp["EMBOCP"] = "OC0001"; // numeor de orden de compra
             $temp["EMBPRV1"] = "PRV0001"; //cpdogp del proveedor
            $temp["EMBPOR"] = "CR"; // pais de origen
            $temp["EMBNCT"] = "0001"; // Numero de contenedor
            $temp["EMBA01"] = "";
            $temp["EMBN01"] = "";
            $temp["Detalles"][] = [
                "EMBLIN" => "1",
                "EMBPRO" => "PRD001",
                "EMBQTY" => "1",
                "EMBUMC" => "UN",
                "EMBLOT" => "",
                "EMBFVE" => "20201231",
                "EMBALX" => "",
                "EMBA02" => "",
                "EMBN02" => "",
            ];
            $temp["Detalles"][] = [
                "EMBLIN" => "2",
                "EMBPRO" => "PRD002",
                "EMBQTY" => "1",
                "EMBUMC" => "UN",
                "EMBLOT" => "",
                "EMBFVE" => "",
                "EMBALX" => "",
                "EMBA02" => "",
                "EMBN02" => ""
            ];

            $data['Mensaje']['Body']['Embarques'][] = $temp;


            $temp = [];

            $temp["EMBCIA"] = $data['Seguridad']['compania'] ?? "IRX";
            $temp["EMBNRO"] = "IRXEMBPRB002";
            $temp["EMBFA1"] = "2018-12-14";
            $temp["EMBOCP"] = "OC0002";
             $temp["EMBPRV1"] = "PRV0002";
            $temp["EMBPOR"] = "CR";
            $temp["EMBNCT"] = "0002";
            $temp["EMBA01"] = "";
            $temp["EMBN01"] = "";
            $temp["Detalles"][] = [
                "EMBLIN" => "1",
                "EMBPRO" => "PRD001",
                "EMBQTY" => "1",
                "EMBUMC" => "UN",
                "EMBLOT" => "",
                "EMBFVE" => "20201231",
                "EMBALX" => "",
                "EMBA02" => "",
                "EMBN02" => ""
            ];
            $temp["Detalles"][] = [
                "EMBLIN" => "2",
                "EMBPRO" => "PRD002",
                "EMBQTY" => "1",
                "EMBUMC" => "UN",
                "EMBLOT" => "",
                "EMBFVE" => "",
                "EMBALX" => "",
                "EMBA02" => "",
                "EMBN02" => ""
            ];
            $data['Mensaje']['Body']['Embarques'][] = $temp;
            return $data;
        }

        public function getDataToCrearCliente($data = [])
        {
            $now = Carbon::now();
            /** @var Order $order */
            $order = Order::find($this->order_id);
            $customer = $order->customer;


            $data['Mensaje']['Head']["id_mensaje"] = $order->external_id;
            $data['Mensaje']['Head']["sistema_origen"] = "SAP";
            $data['Mensaje']['Head']["fecha_origen"] = $now->format('Y-m-d') . "T" . $now->format('H:m:i');
            $data['Mensaje']['Head']["tipo"] = "RECCLIEN";
            $nowYMD = $now->format('Y-m-d');

            $temp = [
                "CLICIA" => self::cutString($data['Seguridad']['compania'] ?? "IRX", 0, 3), // Comṕañia
                "CLIFPR" => $nowYMD, // Fecha del proceso
                "CLICCL" => self::cutString($this->order, 0, 10), // Codigo de destino cliente, provee, serv tec
                "CLINBR" => self::cutString($customer->apellidos_y_nombres_o_razon_social, 0, 100), // Nombre / Razon Social
                "CLIDIR" => self::cutString($customer->direccion, 0, 200), // Dirección de Entrega de Mercadería
                "CLIUBG" => self::cutString($customer->district_id, 0, 10), // Ubigeo Fiscal para entrega de documentos fiscales
                // "CLINBRF" => "RAZON SOCIAL FISCAL-RAZON SOCIAL FISCAL-RAZON SOCIAL FISCAL-", // Nombre o Razón Social Fiscal
                "CLINBRF" => self::cutString($customer->apellidos_y_nombres_o_razon_social, 0, 75), // nombre o Razón Social Fiscal
                "CLIDIRF" => self::cutString($customer->direccion, 0, 70), // Dirección Fiscal para entrega de documentos fiscales
                "CLIUBF" => self::cutString($customer->district_id, 0, 10), // Ubigeo Fiscal para entrega de documentos fiscales
                "CLIRUC" => self::cutString($customer->number, 0, 15),
                "CLIREF" => self::cutString("REFERENCIA DIRECCION ENTREGA-REFERENCIA DIRECCION ENTREGA- REFERENCIA DIRECCION ENTREGA-REFERENCIA DI", 0, 100),
                // "CLIDNI" => "DNI456789012345",
                "CLIDNI" => self::cutString($customer->number, 0, 15),
                "CLITLF" => self::cutString($customer->telephone, 0, 20),
                "CLICEL" => self::cutString($customer->email, 0, 20),
                "CLIDIS" => optional($customer->district->description),
                "CLIPRV" => optional($customer->province->description),
                "CLIDEP" => optional($customer->department->description),
                 "CLILAT" => "0",
                 "CLILON" => "0"
            ];
            $data['Mensaje']['Body']['Clientes'][] = $temp;

            return $data;
        }


        protected static function cutString($string, $start = 0, $end = 0)
        {
            return substr($string, $start, $end);
        }
    }
