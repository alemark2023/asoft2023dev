<?php

    namespace Modules\Production\Http\Controllers;

    use App\CoreFacturalo\Requests\Inputs\Common\PersonInput;
    use App\Models\Tenant\Catalogs\AffectationIgvType;
    use App\Models\Tenant\Catalogs\AttributeType;
    use App\Models\Tenant\Catalogs\ChargeDiscountType;
    use App\Models\Tenant\Catalogs\CurrencyType;
    use App\Models\Tenant\Catalogs\OperationType;
    use App\Models\Tenant\Catalogs\PriceType;
    use App\Models\Tenant\Catalogs\SystemIscType;
    use App\Models\Tenant\Company;
    use App\Models\Tenant\Configuration;
    use App\Models\Tenant\Establishment;
    use App\Models\Tenant\Item;
    use App\Models\Tenant\Person;
    use App\Models\Tenant\Warehouse;
    use App\Traits\OfflineTrait;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Illuminate\Routing\Controller;
    use Illuminate\Support\Str;
    use Modules\Expense\Exports\ExpenseExport;
    use Modules\Expense\Http\Resources\MillCollection;
    use Modules\Expense\Models\Expense;
    use Modules\Expense\Models\ExpenseMethodType;
    use Modules\Expense\Models\ExpenseReason;
    use Modules\Expense\Models\ExpenseType;
    use Modules\Finance\Traits\FinanceTrait;
    //use Modules\Inventory\Traits\InventoryTrait;
    use Modules\Production\Models\Mill;
    use Modules\Production\Models\MillItem;
    use Modules\Inventory\Models\InventoryTransaction;
    use Modules\Inventory\Models\Inventory;



    class MillController extends Controller
    {
        //use InventoryTrait;
        use FinanceTrait;
        use OfflineTrait;

        public static function merge_inputs($inputs)
        {

            $company = Company::active();

            $values = [
                'user_id' => auth()->id(),
                'state_type_id' => $inputs['id'] ? $inputs['state_type_id'] : '05',
                'soap_type_id' => $company->soap_type_id,
                'external_id' => $inputs['id'] ? $inputs['external_id'] : Str::uuid()->toString(),
                'supplier' => PersonInput::set($inputs['supplier_id']),
            ];

            $inputs->merge($values);

            return $inputs->all();
        }

        public function columns()
        {
            return [

                'name' => 'Numero de registro',
                'date_start' => 'Fecha de inicio',
                // 'time_start'=> '',
                'date_end' => 'Fecha de fin',
                // 'time_end'=> '',
            ];
        }

        /**
         * Display a listing of the resource.
         *
         * @return Response
         */
        public function index()
        {
            return view('production::mill.index');
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return Response
         */

        public function create($id = null)
        {
            return view('production::mill.form', compact('id'));
        }
        /*
        public function store(MillRequest $request)
        {

            $data = self::merge_inputs($request);
            // dd($data);

            $expense = DB::connection('tenant')->transaction(function () use ($data) {

                // $doc = Expense::create($data);
                $doc = Expense::updateOrCreate(['id' => $data['id']], $data);

                $doc->items()->delete();

                foreach ($data['items'] as $row)
                {
                    $doc->items()->create($row);
                }

                $this->deleteAllPayments($doc->payments);

                foreach ($data['payments'] as $row)
                {
                    $record_payment = $doc->payments()->create($row);

                    if($row['expense_method_type_id'] == 1){
                        $row['payment_destination_id'] = 'cash';
                    }

                    $this->createGlobalPayment($record_payment, $row);
                }

                return $doc;
            });

            return [
                'success' => true,
                'data' => [
                    'id' => $expense->id,
                ],
            ];
        }
        */

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         *
         * @return Response
         */
        public function store(Request $request)
        {
            $model = Mill::firstOrNew(['id' => null]);
            $model->fill($request->all());
            $model->save();
            
            $warehouse_id = $request['warehouse_id'];


            foreach ($request->items as $item) {

                $mill_item = new MillItem();
                $mill_item->fill($item);
                $mill_item->save();

                //$id = $item['id'];
                $quantity = $item['quantity'];
                //$presentation = $item['presentation'];

                $inventory_transaction = InventoryTransaction::findOrFail('100'); //debe ser ingreso por molino

                $inventory = new Inventory();
                $inventory->type = null;
                $inventory->description = $inventory_transaction->name;
                $inventory->item_id = $mill_item->item_id;
                $inventory->warehouse_id = $warehouse_id;
                $inventory->quantity = $quantity;
                $inventory->inventory_transaction_id = $inventory_transaction->id;
                $inventory->save();


            }

        }

        /**
         * Show the specified resource.
         *
         * @param int $id
         *
         * @return Response
         */
        public function show($id)
        {
            return view('production::show');
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param int $id
         *
         * @return Response
         */
        public function edit($id)
        {
            return view('production::edit');
        }

        /**
         * Update the specified resource in storage.
         *
         * @param Request $request
         * @param int     $id
         *
         * @return Response
         */
        public function update(Request $request, $id)
        {
            //
        }

        /*
        public function tables() {
            $warehouses = Warehouse::all();
            return compact('warehouses');

        }
        */

        /**
         * Remove the specified resource from storage.
         *
         * @param int $id
         *
         * @return Response
         */
        public function destroy($id)
        {
            //
        }

        public function tables()
        {
            $suppliers = $this->table('suppliers');
            $establishment = Establishment::where('id', auth()->user()->establishment_id)->first();
            $currency_types = CurrencyType::whereActive()->get();
            $expense_types = ExpenseType::get();
            $expense_method_types = ExpenseMethodType::all();
            $expense_reasons = ExpenseReason::all();
            $payment_destinations = $this->getBankAccounts();

            return compact('suppliers', 'establishment', 'currency_types', 'expense_types', 'expense_method_types', 'expense_reasons', 'payment_destinations');
        }

        public function table($table)
        {
            switch ($table) {
                case 'suppliers':

                    $suppliers = Person::whereType('suppliers')->orderBy('name')->get()->transform(function ($row) {
                        return [
                            'id' => $row->id,
                            'description' => $row->number . ' - ' . $row->name,
                            'name' => $row->name,
                            'number' => $row->number,
                            'identity_document_type_id' => $row->identity_document_type_id,
                            'identity_document_type_code' => $row->identity_document_type->code
                        ];
                    });
                    return $suppliers;
                case 'items':

                    // $warehouse = Warehouse::where('establishment_id', auth()->user()->establishment_id)->first();

                    $items = Item::orderBy('description')->whereIsActive()
                        ->ForProduction()
                        // ->NotForProduction()
                        // ->with(['warehouses' => function($query) use($warehouse){
                        //     return $query->where('warehouse_id', $warehouse->id);
                        // }])
                        ->take(20)->get();

                    $this->ReturnItem($items);

                    return $items;

                    break;
                default:
                    return [];

                    break;
            }
        }
        public function ReturnItem( &$item)
    {
        $configuration = Configuration::first();
        $establishment_id = auth()->user()->establishment_id;
        $warehouse = \Modules\Inventory\Models\Warehouse::where('establishment_id', $establishment_id)->first();

        $item->transform(function ($row) use ($configuration, $warehouse) {
            /** @var \App\Models\Tenant\Item $row */
            return $row->getDataToItemModal($warehouse, false, true);
        });
    }

        public function records()
        {
            $records = Mill::query();
            return new MillCollection($records->paginate(config('tenant.items_per_page')));

        }

        public function record($id)
        {
            $record = new ExpenseResource(Expense::findOrFail($id));

            return $record;
        }

        public function voided($record)
        {
            try {
                $expense = Expense::findOrFail($record);
                $expense->state_type_id = 11;
                $expense->save();
                return [
                    'success' => true,
                    'data' => [
                        'id' => $expense->id,
                    ],
                    'message' => 'Gasto anulado exitosamente',
                ];
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'data' => [
                        'id' => $record,
                    ],
                    'message' => 'Falló al anular',
                ];
            }
        }

        public function excel(Request $request)
        {

            $records = Expense::where($request->column, 'like', "%{$request->value}%")
                ->whereTypeUser()
                ->latest()
                ->get();
            // dd($records);

            $establishment = auth()->user()->establishment;
            $balance = new ExpenseExport();
            $balance
                ->records($records)
                ->establishment($establishment);

            // return $balance->View();
            return $balance->download('Expense_' . Carbon::now() . '.xlsx');

        }

        public function item_tables() {

            $items = $this->table('items');
            // $items = SearchItemController::getItemToContract();
            $categories = [];
            $affectation_igv_types = AffectationIgvType::whereActive()->get();
            $system_isc_types = SystemIscType::whereActive()->get();
            $price_types = PriceType::whereActive()->get();
            $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
            $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
            $attribute_types = AttributeType::whereActive()->orderByDescription()->get();

            $operation_types = OperationType::whereActive()->get();
            $is_client = $this->getIsClient();

            return compact(
                'items',
                'categories',
                'affectation_igv_types',
                'system_isc_types',
                'price_types',
                'discount_types',
                'charge_types',
                'attribute_types',
                'operation_types',
                'is_client'
            );
        }
    }
