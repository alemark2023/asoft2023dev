<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use App\Http\Resources\System\ClientCollection;
use App\Http\Resources\System\ClientResource;
use App\Http\Requests\System\ClientRequest;
use Hyn\Tenancy\Environment;
use App\Models\System\Client;
use App\Models\System\Module;
use App\Models\System\Plan;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Tenant\Configuration;

class ClientController extends Controller
{
    public function index()
    {
        return view('system.clients.index');
    }

    public function create()
    {
        return view('system.clients.form');
    }

    public function tables()
    {
        $url_base = '.'.config('tenant.app_url_base');
        $plans = Plan::all();
        $types = [['type' => 'admin', 'description'=>'Administrador'], ['type' => 'integrator', 'description'=>'Listar Documentos']];
        $modules = Module::orderBy('description')->get();

        return compact('url_base','plans','types', 'modules');
    }

    public function records()
    {

        $records = Client::latest()->get();
        foreach ($records as &$row) {
            $tenancy = app(Environment::class);
            $tenancy->tenant($row->hostname->website);
            // $row->count_doc = DB::connection('tenant')->table('documents')->count();
            $row->count_doc = DB::connection('tenant')->table('configurations')->first()->quantity_documents;
            $row->count_user = DB::connection('tenant')->table('users')->count();

            if($row->start_billing_cycle)
            {
                $day_start_billing = date_format($row->start_billing_cycle, 'j');
                $init = Carbon::parse( date('Y').'-'.((int)date('n') -1).'-'.$day_start_billing );
                $end = Carbon::parse(date('Y-m-d'));
                $row->count_doc_month = DB::connection('tenant')->table('documents')->whereBetween('date_of_issue', [ $init, $end  ])->count();

            }
        }
        return new ClientCollection($records);
    }

    public function record($id)
    {

        $client = Client::findOrFail($id);
        $tenancy = app(Environment::class);
        $tenancy->tenant($client->hostname->website);
        $client->modules = DB::connection('tenant')->table('module_user')->where('user_id', 1)->get();

        $record = new ClientResource($client);

        return $record;
    }

    public function charts()
    {
        $records = Client::all();
        $count_documents = [];
        foreach ($records as $row) {
            $tenancy = app(Environment::class);
            $tenancy->tenant($row->hostname->website);
            for($i = 1; $i <= 12; $i++)
            {
                $date_initial = Carbon::parse('2019-'.$i.'-1');
                $date_final = Carbon::parse('2019-'.$i.'-'.cal_days_in_month(CAL_GREGORIAN, $i, 2018));
                $count_documents[] = [
                    'client' => $row->number,
                    'month' => $i,
                    'count' => $row->count_doc = DB::connection('tenant')
                                                    ->table('documents')
                                                    ->whereBetween('date_of_issue', [$date_initial, $date_final])
                                                    ->count()
                ];
            }
        }

        $total_documents = collect($count_documents)->sum('count');

        $groups_by_month = collect($count_documents)->groupBy('month');
        $labels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'];
        $documents_by_month = [];
        foreach($groups_by_month as $month => $group)
        {
//            $labels[] = $month;
            $documents_by_month[] = $group->sum('count');
        }

        $line = [
            'labels' => $labels,
            'data' => $documents_by_month
        ];

        return compact('line', 'total_documents');
    }

    public function update(Request $request)
    {
        try
        {

            $client = Client::findOrFail($request->id);
            $client->plan_id = $request->plan_id;
            $client->save();

            $plan = Plan::find($request->plan_id);

            $tenancy = app(Environment::class);
            $tenancy->tenant($client->hostname->website);
            DB::connection('tenant')->table('configurations')->where('id', 1)->update(['plan' => json_encode($plan)]);

            //modules
            DB::connection('tenant')->table('module_user')->where('user_id', 1)->delete();

            $array_modules = [];

            foreach ($request->modules as $module) {
                if($module['checked']){
                    $array_modules[] = ['module_id' => $module['id'], 'user_id' => 1];
                }
            }
            DB::connection('tenant')->table('module_user')->insert($array_modules);
            //modules

            return [
                'success' => true,
                'message' => 'Cliente Actualizado satisfactoriamente'
            ];

        }catch(Exception $e)
        {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];

        }

    }

    public function store(ClientRequest $request)
    {

        $subDom = strtolower($request->input('subdomain'));
        $uuid = config('tenant.prefix_database').'_'.$subDom;
        $fqdn = $subDom.'.'.config('tenant.app_url_base');

        $website = new Website();
        $hostname = new Hostname();
        $this->validateWebsite($uuid, $website);

        DB::connection('system')->beginTransaction();
        try {
            $website->uuid = $uuid;
            app(WebsiteRepository::class)->create($website);
            $hostname->fqdn = $fqdn;
            app(HostnameRepository::class)->attach($hostname, $website);

            $tenancy = app(Environment::class);
            $tenancy->tenant($website);

            $token = str_random(50);

            $client = new Client();
            $client->hostname_id = $hostname->id;
            $client->token = $token;
            $client->email = strtolower($request->input('email'));
            $client->name = $request->input('name');
            $client->number = $request->input('number');
            $client->plan_id = $request->input('plan_id');
            $client->locked_emission = $request->input('locked_emission');
            $client->save();

            DB::connection('system')->commit();
        }
        catch (Exception $e) {
            DB::connection('system')->rollBack();
            app(HostnameRepository::class)->delete($hostname, true);
            app(WebsiteRepository::class)->delete($website, true);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

        DB::connection('tenant')->table('companies')->insert([
            'identity_document_type_id' => '6',
            'number' => $request->input('number'),
            'name' => $request->input('name'),
            'trade_name' => $request->input('name'),
            'soap_type_id' => '01'
        ]);

        $plan = Plan::findOrFail($request->input('plan_id'));

        DB::connection('tenant')->table('configurations')->insert([
            'send_auto' => true,
            'locked_emission' =>  $request->input('locked_emission'),
            'locked_tenant' =>  false,
            'locked_users' =>  false,
            'limit_documents' =>  $plan->limit_documents,
            'limit_users' =>  $plan->limit_users,
            'plan' => json_encode($plan),
            'date_time_start' =>  date('Y-m-d H:i:s'),
            'quantity_documents' =>  0,
        ]);


        $establishment_id = DB::connection('tenant')->table('establishments')->insertGetId([
            'description' => 'Oficina Principal',
            'country_id' => 'PE',
            'department_id' => '15',
            'province_id' => '1501',
            'district_id' => '150101',
            'address' => '-',
            'email' => $request->input('email'),
            'telephone' => '-',
            'code' => '0000'
        ]);

        // DB::connection('tenant')->table('warehouses')->insertGetId([
        //     'establishment_id' => $establishment_id,
        //     'description' => 'Almacén - '.'Oficina Principal',
        // ]);

        DB::connection('tenant')->table('series')->insert([
            ['establishment_id' => 1, 'document_type_id' => '01', 'number' => 'F001'],
            ['establishment_id' => 1, 'document_type_id' => '03', 'number' => 'B001'],
            ['establishment_id' => 1, 'document_type_id' => '07', 'number' => 'FC01'],
            ['establishment_id' => 1, 'document_type_id' => '07', 'number' => 'BC01'],
            ['establishment_id' => 1, 'document_type_id' => '08', 'number' => 'FD01'],
            ['establishment_id' => 1, 'document_type_id' => '08', 'number' => 'BD01'],
            ['establishment_id' => 1, 'document_type_id' => '20', 'number' => 'R001'],
            ['establishment_id' => 1, 'document_type_id' => '09', 'number' => 'T001'],
        ]);


        $user_id = DB::connection('tenant')->table('users')->insert([
            'name' => 'Administrador',
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'api_token' => $token,
            'establishment_id' => $establishment_id,
            'type' => $request->input('type'),
            'locked' => true
        ]);


        if($request->input('type') == 'admin'){

            $array_modules = [];

            foreach ($request->modules as $module) {
                if($module['checked']){
                    $array_modules[] = ['module_id' => $module['id'], 'user_id' => $user_id];
                }
            }
            DB::connection('tenant')->table('module_user')->insert($array_modules);

            // DB::connection('tenant')->table('module_user')->insert([
            //     ['module_id' => 1, 'user_id' => $user_id],
            //     ['module_id' => 2, 'user_id' => $user_id],
            //     ['module_id' => 3, 'user_id' => $user_id],
            //     ['module_id' => 4, 'user_id' => $user_id],
            //     ['module_id' => 5, 'user_id' => $user_id],
            //     ['module_id' => 6, 'user_id' => $user_id],
            //     ['module_id' => 7, 'user_id' => $user_id],
            //     ['module_id' => 8, 'user_id' => $user_id],
            //     ['module_id' => 9, 'user_id' => $user_id],
            //     ['module_id' => 10, 'user_id' => $user_id],
            //     ['module_id' => 11, 'user_id' => $user_id],
            // ]);

        }else{

            DB::connection('tenant')->table('module_user')->insert([
                ['module_id' => 1, 'user_id' => $user_id],
                ['module_id' => 3, 'user_id' => $user_id],
                ['module_id' => 5, 'user_id' => $user_id],
            ]);

        }

        return [
            'success' => true,
            'message' => 'Cliente Registrado satisfactoriamente'
        ];
    }

    public function validateWebsite($uuid, $website){

        $exists = $website::where('uuid', $uuid)->first();

        if($exists){
            throw new Exception("El subdominio ya se encuentra registrado");
        }

    }

    public function renewPlan(Request $request){

        // dd($request->all());
        $client = Client::findOrFail($request->id);
        $tenancy = app(Environment::class);
        $tenancy->tenant($client->hostname->website);

        DB::connection('tenant')->table('billing_cycles')->insert([
            'date_time_start' => date('Y-m-d H:i:s'),
            'renew' => true,
            'quantity_documents' => DB::connection('tenant')->table('configurations')->where('id', 1)->first()->quantity_documents,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::connection('tenant')->table('configurations')->where('id', 1)->update(['quantity_documents' =>0]);


        return [
            'success' => true,
            'message' => 'Plan renovado con exito'
        ];

    }


    public function lockedUser(Request $request){

        $client = Client::findOrFail($request->id);
        $client->locked_users = $request->locked_users;
        $client->save();

        $tenancy = app(Environment::class);
        $tenancy->tenant($client->hostname->website);
        DB::connection('tenant')->table('configurations')->where('id', 1)->update(['locked_users' => $client->locked_users]);

        return [
            'success' => true,
            'message' => ($client->locked_users) ? 'Limitar creación de usuarios activado' : 'Limitar creación de usuarios desactivado'
        ];

    }


    public function lockedEmission(Request $request){

        $client = Client::findOrFail($request->id);
        $client->locked_emission = $request->locked_emission;
        $client->save();

        $tenancy = app(Environment::class);
        $tenancy->tenant($client->hostname->website);
        DB::connection('tenant')->table('configurations')->where('id', 1)->update(['locked_emission' => $client->locked_emission]);

        return [
            'success' => true,
            'message' => ($client->locked_emission) ? 'Limitar emisión de documentos activado' : 'Limitar emisión de documentos desactivado'
        ];

    }


    public function lockedTenant(Request $request){

        $client = Client::findOrFail($request->id);
        $client->locked_tenant = $request->locked_tenant;
        $client->save();

        $tenancy = app(Environment::class);
        $tenancy->tenant($client->hostname->website);
        DB::connection('tenant')->table('configurations')->where('id', 1)->update(['locked_tenant' => $client->locked_tenant]);

        return [
            'success' => true,
            'message' => ($client->locked_tenant) ? 'Cuenta bloqueada' : 'Cuenta desbloqueada'
        ];

    }



    public function destroy($id)
    {
        $client = Client::find($id);

        $hostname = Hostname::find($client->hostname_id);
        $website = Website::find($hostname->website_id);

        app(HostnameRepository::class)->delete($hostname, true);
        app(WebsiteRepository::class)->delete($website, true);

        return [
            'success' => true,
            'message' => 'Cliente eliminado con éxito'
        ];
    }

    public function password($id)
    {
        $client = Client::find($id);
        $website = Website::find($client->hostname->website_id);
        $tenancy = app(Environment::class);
        $tenancy->tenant($website);
        DB::connection('tenant')->table('users')
            ->where('id', 1)
            ->update(['password' => bcrypt($client->number)]);

        return [
            'success' => true,
            'message' => 'Clave cambiada con éxito'
        ];
    }

    public function startBillingCycle(Request $request)
    {
        $client = Client::findOrFail($request->id);
        $client->start_billing_cycle = $request->start_billing_cycle;
        $client->save();

        return [
            'success' => true,
            'message' => ($client->start_billing_cycle) ? 'Ciclo de Facturacion definido.' : 'No se pudieron guardar los cambios.'
        ];
    }



}
