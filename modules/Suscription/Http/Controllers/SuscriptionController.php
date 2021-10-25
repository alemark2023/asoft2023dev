<?php

    namespace Modules\Suscription\Http\Controllers;

    use App\Http\Resources\Tenant\ItemCollection;
    use App\Http\Resources\Tenant\PersonCollection;
    use App\Http\Resources\Tenant\PersonResource;
    use App\Models\System\Configuration;
    use App\Models\Tenant\Catalogs\Country;
    use App\Models\Tenant\Catalogs\Department;
    use App\Models\Tenant\Catalogs\District;
    use App\Models\Tenant\Catalogs\IdentityDocumentType;
    use App\Models\Tenant\Catalogs\Province;
    use App\Models\Tenant\Item;
    use App\Models\Tenant\Person;
    use App\Models\Tenant\PersonType;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Illuminate\Routing\Controller;

    class SuscriptionController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return Response
         */
        public function index()
        {
            return view('suscription::index');
        }

        /**
         * Display a listing of the resource.
         *
         * @return Response
         */
        public function clients_index()
        {
            return view('suscription::clients.index');
        }

        /**
         * Display a listing of the resource.
         *
         * @return Response
         */
        public function services_index()
        {
            return view('suscription::services.index');
        }

        /**
         * Display a listing of the resource.
         *
         * @return Response
         */
        public function payments_index()
        {
            return view('suscription::payments.index');
        }

        /**
         * Display a listing of the resource.
         *
         * @return Response
         */
        public function plans_index()
        {
            return view('suscription::plans.index');
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return Response
         */
        public function create()
        {
            return view('suscription::create');
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         *
         * @return Response
         */
        public function store(Request $request)
        {
            //
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
            return view('suscription::show');
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
            return view('suscription::edit');
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


        /**
         * @return string[]
         */
        public function clientColumns()
        {
            return [
                'name' => 'Nombre',
                'number' => 'Número',
                'document_type' => 'Tipo de documento'
            ];
        }

        public function clientRecords(Request $request)
        {
            $type = 'customers';
            $records = Person::where($request->column, 'like', "%{$request->value}%")
                // ->where('type', $type)
                ->orderBy('name');

            return new PersonCollection($records->paginate(config('tenant.items_per_page')));
        }


        public function clientTables()
        {
            $countries = Country::whereActive()->orderByDescription()->get();
            $departments = Department::whereActive()->orderByDescription()->get();
            $provinces = Province::whereActive()->orderByDescription()->get();
            $districts = District::whereActive()->orderByDescription()->get();
            $identity_document_types = IdentityDocumentType::whereActive()->get();
            $person_types = PersonType::get();
            $locations = $this->getLocationCascade();
            $configuration = Configuration::first();
            $api_service_token = $configuration->token_apiruc == 'false' ? config('configuration.api_service_token') : $configuration->token_apiruc;

            return compact('countries', 'departments', 'provinces', 'districts', 'identity_document_types', 'locations', 'person_types', 'api_service_token');
        }


        // @todo Cambio a item

        /**
         * Devuelve un array para Privincia, distrito
         *
         * @return array
         */
        public function getLocationCascade()
        {
            $locations = [];
            $departments = Department::where('active', true)->get();
            foreach ($departments as $department) {
                $children_provinces = [];
                foreach ($department->provinces as $province) {
                    $children_districts = [];
                    foreach ($province->districts as $district) {
                        $children_districts[] = [
                            'value' => $district->id,
                            'label' => $district->id . " - " . $district->description
                        ];
                    }
                    $children_provinces[] = [
                        'value' => $province->id,
                        'label' => $province->description,
                        'children' => $children_districts
                    ];
                }
                $locations[] = [
                    'value' => $department->id,
                    'label' => $department->description,
                    'children' => $children_provinces
                ];
            }

            return $locations;
        }

        // @todo Cambio a item

        public function serviceColumns()
        {
            return [
                // 'name' => 'Nombre',
                // 'number' => 'Número',
                // 'document_type' => 'Tipo de documento'

                // 'index' => "#",
                'internal_id' => "Cód. Interno",
                'unit_type_id' => "Unidad",
                'name' => "Nombre",
                'description' => "Descripción",
                'model' => "Modelo",
                'brand' => "Marca",
                // 'item_code' => "Cód. SUNAT",
                'stock' => "Stock",
                'purchase_unit_price' => "P.Unitario (Venta)",
                'purchase_has_igv_description' => "P.Unitario (Compra)",
                'has_igv_description' => "Tiene Igv (Venta)",
// '' =>"Tiene Igv (Compra)",
            ];
        }


        // @todo Cambio a item

        public function serviceRecords(Request $request)
        {

            $records = $this->getServiceRecords($request);

            return new ItemCollection($records->paginate(config('tenant.items_per_page')));
        }

        /**
         * @param \Illuminate\Http\Request $request
         *
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public function getServiceRecords(Request $request){

            $records = Item::whereTypeUser()->whereNotIsSet();
            switch ($request->column) {
                case 'brand':
                    $records->whereHas('brand',function($q) use($request){
                        $q->where('name', 'like', "%{$request->value}%");
                    });
                    break;
                case 'active':
                    $records->whereIsActive();
                    break;

                case 'inactive':
                    $records->whereIsNotActive();
                    break;

                default:
                    if($request->has('column')) {
                        $filter = 'id';
                        if($request->column != 'index') $filter = $request->column;
                        $records->where($filter, 'like', "%{$request->value}%");
                    }
                    break;
            }
            $records->whereService();
            $filter = 'description';

            if($request->has('column')) {
                // $filter = 'id';

                if($request->column != 'index') {
                    $filter = $request->column;
                }

            }
            return                 $records->orderBy($filter);
            ;

        }

        // @todo Cambio a item

        public function serviceTables()
        {
            $countries = Country::whereActive()->orderByDescription()->get();
            $departments = Department::whereActive()->orderByDescription()->get();
            $provinces = Province::whereActive()->orderByDescription()->get();
            $districts = District::whereActive()->orderByDescription()->get();
            $identity_document_types = IdentityDocumentType::whereActive()->get();
            $person_types = PersonType::get();
            $locations = $this->getLocationCascade();
            $configuration = Configuration::first();
            $api_service_token = $configuration->token_apiruc == 'false' ? config('configuration.api_service_token') : $configuration->token_apiruc;

            return compact('countries', 'departments', 'provinces', 'districts', 'identity_document_types', 'locations', 'person_types', 'api_service_token');
        }

        public function serviceRecord(Request $request)
        {
            $record = new PersonResource(Person::findOrFail($request->person));

            return $record;
        }

        public function clientRecord(Request $request)
        {
            $record = new PersonResource(Person::findOrFail($request->person));

            return $record;
        }

    }
