<?php

    namespace Modules\Suscription\Http\Controllers;

    use App\Http\Controllers\Tenant\PersonController;
    use App\Http\Requests\Tenant\PersonRequest;
    use App\Http\Resources\Tenant\PersonCollection;
    use App\Http\Resources\Tenant\PersonResource;
    use App\Models\System\Configuration;
    use App\Models\Tenant\Catalogs\Country;
    use App\Models\Tenant\Catalogs\Department;
    use App\Models\Tenant\Catalogs\District;
    use App\Models\Tenant\Catalogs\IdentityDocumentType;
    use App\Models\Tenant\Catalogs\Province;
    use App\Models\Tenant\Person;
    use App\Models\Tenant\PersonType;
    use Illuminate\Http\Request;

    class ClientSuscriptionController extends SuscriptionController
    {
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

        public function clientRecord(Request $request)
        {
            $person = Person::findOrFail($request->person);
            return ['data'=>$person->getCollectionData(true,true)];
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


        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
         */
        public function clients_index()
        {
            return view('suscription::clients.index');
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
         */
        public function create()
        {
            return view('suscription::create');
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param int $id
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
         */
        public function destroy($id)
        {
            //
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param int $id
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
         */
        public function edit($id)
        {
            return view('suscription::edit');
        }

        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
         */
        public function index()
        {
            return view('suscription::index');
        }

        /**
         * Show the specified resource.
         *
         * @param int $id
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
         */
        public function show($id)
        {
            return view('suscription::show');
        }



        /**
         * Almacena los datos de persona basado en el funcion amiento de su controlador
         *
         * @param PersonRequest $request
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
         */
        public function store(PersonRequest $request)
        {
            //
            $personController = new PersonController();
            $data  =  $personController->store($request);
            if(isset($data['id'])){
                $person = Person::find($data['id']);
                // @todo añadir hijos?

            }
            return $data;

        }

        /**
         * Update the specified resource in storage.
         *
         * @param Request $request
         * @param int     $id
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
         */
        public function update(Request $request, $id)
        {
            //
        }

    }
