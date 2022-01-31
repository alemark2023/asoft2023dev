<?php


    namespace Modules\Order\Imports;


    use App\CoreFacturalo\Requests\Inputs\DocumentInput;
    use App\Http\Controllers\Tenant\DocumentController;
    use App\Http\Requests\Tenant\DocumentRequest;

    // use App\Models\System\PaymentMethodType;
    use App\Models\Tenant\Catalogs\Department;
    use App\Models\Tenant\Catalogs\District;
    use App\Models\Tenant\Catalogs\DocumentType;
    use App\Models\Tenant\Catalogs\IdentityDocumentType;
    use App\Models\Tenant\Document;
    use App\Models\Tenant\ExchangeRate;
    use App\Models\Tenant\Item;
    use App\Models\Tenant\PaymentMethodType;
    use App\Models\Tenant\Person;
    use App\Models\Tenant\Series;
    use App\Models\Tenant\User;
    use App\Models\Tenant\Warehouse;
    use Auth;
    use Carbon\Carbon;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Collection;
    use Illuminate\Support\Str;
    use Log;
    use Maatwebsite\Excel\Concerns\Importable;
    use Maatwebsite\Excel\Concerns\ToCollection;

    use Modules\Order\Http\Controllers\OrderNoteController;
    use Modules\Order\Http\Requests\OrderNoteRequest;
    use Modules\Order\Models\ConfigurationMiTiendaPe;
    use Modules\Order\Models\MiTiendaPe;
    use Modules\Order\Models\OrderNote;
    use Modules\Order\Models\OrderNoteItem;
    use SoapFault;


    class MiTiendaPeImport implements ToCollection
    {
        use Importable;

        protected $data;
        /** @var ConfigurationMiTiendaPe  */
        protected $configuration;
        /** @var User */
        protected  $user ;

        public function collection(Collection $rows)
        {
            libxml_disable_entity_loader(false);

            $configurationMiTienda = ConfigurationMiTiendaPe::first();
            if(empty($configurationMiTienda)){
                $configurationMiTienda = new ConfigurationMiTiendaPe();
            }
            $this->configuration = $configurationMiTienda;

            $exchangeRate = ExchangeRate::where('date', Carbon::now()->format('Y-m-d'))->first();
            if (empty($exchangeRate)) {
                $exchangeRate = ExchangeRate::where('date', '<', Carbon::now()->format('Y-m-d'))->orderBy('date', 'desc')->first();
            }
            $exchangeRateValue = $exchangeRate->sale_original;
            $currentUser = Auth::user();
            if (empty($currentUser)) {
                $currentUser = new User();
            }
            $this->user = $currentUser;
            $warehouse = Warehouse::where('establishment_id', $currentUser->establishment_id)->first();
            $total = count($rows);
            $registered = 0;
            unset($rows[0]);
            $orders = [];
            $numberOrders = [];
            $idsg = 0;
            foreach ($rows as $row) {
                $documentType = $row[0] ?? null;
                $miTiendaPeOrder = $row[1] ?? null;
                $names = $row[2] ?? null;
                $lastnames = $row[3] ?? null;
                $phone = $row[4] ?? null;
                $street = $row[5] ?? null;
                $region = $row[6] ?? null;
                $district = $row[7] ?? null;
                $paymentType = $row[8] ?? 'Contado';
                $identificationNumber = $row[9] ?? null;
                $email = $row[10] ?? null;


                $payment_method_types = PaymentMethodType::where('description', $paymentType)->first();
                if (empty($payment_method_types)) {
                    $payment_method_types = PaymentMethodType::where('description', 'Contado')->first();
                }

                $internal_id = $row[11] ?? null;
                $quantity = $row[12] ?? null;

                $amountWithOutIGV = $row[13] ?? 0; // precio sin igv
                $total_igv = $row[14] ?? 0; // igv del producto
                $igv = $row[15] ?? 18; // igv
                if (!is_numeric($igv)) {
                    $igv = 18;
                }
                $discount = $row[16] ?? null;
                // $total = $row[13]??null;
                $stotal = $row[17] ?? null;
                $shipping = $row[18] ?? null;
                $totalImport = $row[19] ?? null;
                $reference = $row[20] ?? null;
                $saleDate = $row[21] ?? null;
                $transaction_code = $row[22] ?? null;
                if (empty($identificationNumber)) {
                    continue;
                    // si no hay numero de idtenficacion de cliente, se continua
                }
                if (!empty($saleDate)) {
                    $saleDate = Carbon::createFromFormat('d/m/Y', $saleDate)->format('Y-m-d');
                }


                $documentType = ucfirst($documentType);
                if ($documentType == 'Factura') {
                    $documentType = DocumentType::where('short', 'FT')->first();
                } elseif ($documentType == 'Boleta') {
                    $documentType = DocumentType::where('short', 'BV')->first();
                }
                $customer = Person::SearchCustomer($identificationNumber, $names, $email)->first();
                // Clientes
                if (empty($customer)) {
                    // Crear nuevo cliente
                    $customer = new Person([
                        'type' => 'customers',
                        'name' => "$lastnames, $names",
                        'number' => $identificationNumber,
                        'country_id' => 'PE',
                        'address' => $street,
                        'email' => $email,
                        'telephone' => $phone,

                    ]);
                    $customer->identity_document_type_id = IdentityDocumentType::where('description', 'Doc.trib.no.dom.sin.ruc')->first()->id;
                    if (strlen($identificationNumber) > 10) {
                        $customer->identity_document_type_id = IdentityDocumentType::where('description', 'RUC')->first()->id;
                    } elseif (strlen($identificationNumber) == 8) {
                        $customer->identity_document_type_id = IdentityDocumentType::where('description', 'DNI')->first()->id;
                    }
                    $province = Department::where('description', $region)->first();
                    if (!empty($province)) {
                        $customer->department_id = $province->id;
                    }
                    $district = District::where('description', $district)->first();
                    if (!empty($district)) {
                        $customer->district_id = $district->id;
                        $customer->province_id = $district->province_id;
                    }
                    $customer->contact = json_decode(json_encode(["full_name" => $customer->name, "phone" => $customer->telephone,]));
                    $customer->push();
                }


                $customerData = $customer->getCollectionData();
                $customerData['apellidos_y_nombres_o_razon_social'] = "$lastnames, $names";
                $customerData['telefono'] = $phone;
                $customerData['correo_electronico'] = $email;
                $customerData['direccion'] = "$street, $district, $region";
                $payment_method_type_id = empty($payment_method_types) ? '10' : $payment_method_types->id;
                $temp = [
                    "prefix" => "PD", // nota, usar una configuracion para este caso CMAR
                    'transaction_code' => $transaction_code,
                    'exchange_rate_sale' => $exchangeRateValue,
                    'sale_original' => $exchangeRateValue,
                    'external_id' => Str::uuid()->toString(),
                    'customer_id' => $customerData['id'],
                    'customer' => $customerData,
                    'shipping_address' => $customerData['direccion'],
                    "reference_payment" => empty($payment_method_types) ? 'Contado' : $payment_method_types->description,
                    'establishment_id' => $this->configuration->establishment_id,
                    'user_id' => $this->user->id,
                    'currency_type_id' => $this->configuration->currency_type_id??'PEN',
                    "purchase_order" => $miTiendaPeOrder,
                    'date_of_issue' => $saleDate,
                    'time_of_issue' => Carbon::now()->format('H:i:s'),
                    // 'purchase'
                    "operation_type_id" => null,
                    "date_of_due" => $saleDate,
                    "delivery_date" => null,
                    "charges" => [],
                    "attributes" => [],
                    "guides" => [],
                    "payment_method_type_id" => $payment_method_type_id,
                    "additional_information" => null,
                    "actions" => [
                        "format_pdf" => "a4",
                    ],

                    "discounts" => [],
                    "total_prepayment" => 0,
                    "total_charge" => 0,
                    "total_discount" => (float)$discount,
                    "total_exportation" => 0,
                    "total_free" => 0,
                    "total_taxed" => 0,
                    "total_unaffected" => 0,
                    "total_igv_free" => 0,
                    "total_igv" => 0,
                    "total_base_isc" => 0,
                    "total_isc" => 0,
                    "total_base_other_taxes" => 0,
                    "total_other_taxes" => 0,
                    "total_taxes" => 0,
                    'total' => $totalImport,

                    'total_exonerated' => $totalImport,
                    "document_type_id" => $documentType,
                    // "payment_method" => $payment_method_types,
                    "total_value" => $totalImport,
                ];
                $item = Item::where('internal_id', $internal_id)->first();

                if (!empty($item)) {


                    if (!isset($orders[$miTiendaPeOrder])) $orders[$miTiendaPeOrder] = [];
                    $numberOrders[] = $miTiendaPeOrder;
                    if ($quantity <= 0) $quantity = 1;

                    $cItem = $item->getDataToItemModal($warehouse, false, false);
                    $cItem['has_igv'] = (array)$cItem['has_igv'];
                    $cItem['lots'] = (array)$cItem['lots'];

                    $cItem['quantity'] = $quantity;
                    $itemTo = [
                        'item_id' => $item->id,
                        'item' => (array)$cItem,
                        'quantity' => $quantity,
                        'currency_type_id' => 'PEN',
                        'affectation_igv_type_id' => $item->sale_affectation_igv_type_id,
                        'affectation_igv_type' => $item->sale_affectation_igv_type,
                        'total_base_isc' => 0,
                        'percentage_isc' => 0,
                        'total_isc' => 0,
                        'total_base_other_taxes' => 0,
                        'percentage_other_taxes' => 0,
                        'total_other_taxes' => 0,
                        'total_plastic_bag_taxes' => 0,
                        'attributes' => [],
                        'discounts' => [],
                        'charges' => [],
                        'price_type_id' => '01',
                        'total_charge' => 0,

                        'total_taxes' => $total_igv,
                        'unit_value' => ($amountWithOutIGV / $quantity),
                        'total_base_igv' => $amountWithOutIGV,
                        'percentage_igv' => $igv,
                        'total_igv' => $total_igv,
                        'unit_price' => ($amountWithOutIGV / $quantity),
                        'total_value' => ($amountWithOutIGV / $quantity),
                        'total_discount' => (float)$discount,
                        'total' => $amountWithOutIGV + $total_igv,


                    ];
                    $itemTo['unit_type_id'] = $cItem['unit_type_id'];
                    $temp['items'] = [$itemTo];

                    if (isset($orders[$miTiendaPeOrder])) {
                        // se busca los item de la orden, para sumar sus totales
                        $checkfield = [
                            'total',
                            'total_discount',
                            'total_taxes',
                            'unit_value',
                            'total_base_igv',
                            'percentage_igv',
                            'total_igv',
                            'unit_price',
                            'total_value',
                        ];

                        foreach ($checkfield as $field) {
                            if (isset($orders[$miTiendaPeOrder][$field])) {
                                $orders[$miTiendaPeOrder][$field] += $itemTo[$field];
                            }
                        }


                    }
                    $orders[$miTiendaPeOrder] = $temp;
                }

            }

            $numberOrders = array_unique($numberOrders);
            foreach ($numberOrders as $item) {
                $checkItem = MiTiendaPe::where('order_number', $item)->first();
                if (isset($orders[$item]) && empty($checkItem)) {
                    $toSave = $orders[$item];
                    $request = new OrderNoteRequest();
                    $request->merge($toSave);
                    $orderNotecontroller = new OrderNoteController();
                    $result = $orderNotecontroller->store($request);
                    if (isset($result['success']) && $result['success'] === true) {
                        $order = $orderNotecontroller->getOrderNote();
                        $orderId = $order->id;
                        $document = new Document();

                        if (isset($toSave['document_type_id']) && !empty($toSave['document_type_id'])) {
                            $document = $this->setDocument($toSave, $order);
                        }

                        $miTiendaPe = new MiTiendaPe([
                            'order_number' => $item,
                            'transaction_code' => $toSave['transaction_code'],
                            'order_note_id' => $orderId,
                            'document_id' => $document->id,
                        ]);
                        $miTiendaPe->push();
                        $total++;
                    } else {
                        Log::debug(" No se pudo guardar por que no fue exitoso \n" . json_encode($toSave));
                    }
                }


            }
            $this->data = compact('total', 'registered');

        }

        /**
         * Genera los datos para documento.
         *
         * @param array          $data
         * @param OrderNote|null $orderNote
         * @param User|null      $user
         *
         * @return Document
         */
        public function setDocument($data = [], OrderNote $orderNote = null): Document
        {

            /** @var DocumentType $documentType */
            $documentType = $data['document_type_id'];

            $data["order_note_id"] = $orderNote->id;
            $data["document_type_id"] = $documentType->id;
            if($data["document_type_id"] == '03' || $data["document_type_id"] == '01'){
                $data["series_id"] = $this->configuration->series_document_bt_id;
                $data["series"] = Series::find($data["series_id"])->number;
            }else{
                return new Document();

            }
            $data["establishment_id"] =  $this->configuration->establishment_id;
            $data["number"] = "#";
            $data["operation_type_id"] = '0101';
            $data["prefix"] = null;
            $data['type'] = 'invoice';
            $data["payments"][] = [
                "id" => null,
                "document_id" => null,
                "date_of_payment" => $data['date_of_issue'],
                "payment_method_type_id" => $data['payment_method_type_id'],
                "payment_destination_id" => $this->configuration->payment_destination_id,
                "reference" => null,
                "payment" => $data['total'],
            ];

            $items = $data['items'];
            $newItems = [];
            foreach ($items as $item) {
                $item['item'] = (array)$item['item'];
                $item['item']['lots'] = (array)$item['item']['lots'];
                $newItems[] = $item;
            }
            $data['items'] = $newItems;

            $data = DocumentInput::set($data);
            $documentController = new DocumentController();

            $documentRequest = new DocumentRequest();
            $documentRequest->merge($data);
            try {
                $request = $documentController->store($documentRequest);
            } catch (SoapFault $e) {
                $request = [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
            $document = new Document();
            if (isset($request['success']) && $request['success'] == true) {
                $document = Document::find($request['data']['id']);
            }
            return $document;

        }

        public function getData()
        {
            return $this->data;
        }

    }
