<?php

    namespace App\Imports;

    use App\Models\Tenant\Catalogs\Country;
    use App\Models\Tenant\Catalogs\Department;
    use App\Models\Tenant\Catalogs\District;
    use App\Models\Tenant\Catalogs\DocumentType;
    use App\Models\Tenant\Catalogs\IdentityDocumentType;
    use App\Models\Tenant\Catalogs\Province;
    use App\Models\Tenant\Item;
    use App\Models\Tenant\Order;
    use App\Models\Tenant\Person;
    use App\Models\Tenant\PersonAddress;
    use App\Models\Tenant\Warehouse;
    use Illuminate\Support\Collection;
    use Illuminate\Support\Str;
    use Maatwebsite\Excel\Concerns\Importable;
    use Maatwebsite\Excel\Concerns\ToCollection;
    use Modules\Item\Models\Category;
    use Modules\Item\Models\Brand;
    use Modules\LogisticOperator\Models\LogisticYobel;
    use PhpOffice\PhpSpreadsheet\Shared\Date;


    class YobelImport implements ToCollection
    {
        use Importable;

        protected $data;

        public function collection(Collection $rows)
        {
            $total = count($rows);
            $registered = 0;
            unset($rows[0]);
            $orders = [];
            $logistic = [];
            $items = [];
            $numberOrders = [];
            foreach ($rows as $row) {
                $nombres = $row[2] ?? null;
                $apellidos = $row[3] ?? null;
                $telefono = $row[4] ?? null;
                $calle = $row[5] ?? null;
                $region = $row[6] ?? null;
                $distrito = $row[7] ?? null;
                $dni_ruc = $row[9] ?? null;
                $email = $row[10] ?? null;

                $tipoDoc = $row[0] ?? null;
                $numPedidoCliente = $row[1] ?? null;
                $metodoPago = $row[8] ?? 'efectivo';
                $internal_id = $row[11] ?? null;
                $quantity = $row[12] ?? null;
                // $total = $row[13]??null;
                $dscto = $row[14] ?? null;
                $stotal = $row[15] ?? null;
                $envio = $row[16] ?? null;
                $totalImport = $row[17] ?? null;
                $reference = $row[18] ?? null;
                $fVenta = $row[19] ?? null;
                $cod_pasarela = $row[20] ?? null;
                if ($tipoDoc == 'Factura') {
                    $tipoDoc = DocumentType::where('short', 'FT')->first();
                } elseif ($tipoDoc == 'Boleta') {
                    $tipoDoc = DocumentType::where('short', 'BV')->first();
                } else {
                    $tipoDoc = null;
                }
                $customer = Person::SearchCustomer($dni_ruc, $nombres, $email)->first();
                // Clientes
                if (empty($customer)) {
                    // Crear nuevo cliente
                    $customer = new Person([
                        'type' => 'customers',
                        'name' => "$apellidos, $nombres",
                        'number' => $dni_ruc,
                        'country_id' => 'PE',
                        'address' => $calle,
                        'email' => $email,
                        'telephone' => $telefono,

                    ]);
                    $customer->identity_document_type_id = IdentityDocumentType::where('description', 'Doc.trib.no.dom.sin.ruc')->first()->id;
                    if (strlen($dni_ruc) > 10) {
                        $customer->identity_document_type_id = IdentityDocumentType::where('description', 'RUC')->first()->id;
                    } elseif (strlen($dni_ruc) == 8) {
                        $customer->identity_document_type_id = IdentityDocumentType::where('description', 'DNI')->first()->id;
                    }
                    $province = Department::where('description', $region)->first();
                    if (!empty($province)) {
                        $customer->department_id = $province->id;
                    }
                    $district = District::where('description', $distrito)->first();
                    if (!empty($district)) {
                        $customer->district_id = $district->id;
                        $customer->province_id = $district->province_id;
                    }
                    $customer->contact = json_decode(json_encode(["full_name" => $customer->name, "phone" => $customer->telephone,]));
                    $customer->push();
                }


                $customerData = $customer->getCollectionData();
                $customerData['apellidos_y_nombres_o_razon_social'] = "$apellidos, $nombres";
                $customerData['telefono'] = $telefono;
                $customerData['correo_electronico'] = $email;
                $customerData['direccion'] = "$calle, $distrito, $region";

                $temp = [
                    'external_id' => Str::uuid()->toString(),
                    'customer' => $customerData,
                    'shipping_address' => "$calle, $distrito, $region",
                    // 'items',
                    'total' => $totalImport,
                    'reference_payment' => $metodoPago,
                    // 'document_external_id' => $numPedidoCliente,
                    //'number_document'=>,
                    'status_order_id' => 1,
                    // 'purchase'
                ];
                $item = Item::where('internal_id', $internal_id)->first();

                if (!empty($item)) {

                    if (!isset($items[$numPedidoCliente])) $items[$numPedidoCliente] = [];
                    if (!isset($orders[$numPedidoCliente])) $orders[$numPedidoCliente] = [];
                    if (!isset($logistic[$numPedidoCliente])) $logistic[$numPedidoCliente] = [];
                    $numberOrders[] = $numPedidoCliente;
                    $cItem = $item->getCollectionData();
                    $cItem['quantity'] = $quantity;
                    $items[$numPedidoCliente][] = $cItem;
                    // Solo se guardará si existe la data corerspondiente
                    $orders[$numPedidoCliente] = $temp;
                    $logistic[$numPedidoCliente] = [
                        'person_id' => $customer->id,

                        'order' => $numPedidoCliente,
                        'reference' => $reference,
                        // 'gateway_code',
                        // 'items'
                    ];

                }

            }
            $numberOrders = array_unique($numberOrders);

            foreach ($numberOrders as $item) {
                if (isset($orders[$item])) {


                    $toSave = $orders[$item];
                    $toSave['items'] = $items[$item];


                    $logisticToSave = $logistic[$item];
                    $orderYobel = LogisticYobel::firstOrNew($logisticToSave);
                    $orderYobel->items = ($toSave['items']);


                    if (empty($orderYobel->order_id)) {
                        $order = new Order($toSave);
                        $order->push();
                        $orderYobel->order_id = $order->id;
                    }
                    $orderYobel->push();
                    $total++;

                }

            }
            $this->data = compact('total', 'registered');

        }

        public function getData()
        {
            return $this->data;
        }
    }
