<?php

namespace App\Console\Commands;

use GuzzleHttp\Client as ClientGuzzleHttp;
use Illuminate\Console\Command;
use Hyn\Tenancy\Models\Website;
use App\Models\Tenant\{
    Configuration,
    Document,
    Company,
    User
};
use Carbon\Carbon;
use Auth;

class SummarySendCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'summary:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatic send of summaries';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $now = Carbon::now('America/Bogota');
        \Log::info($now->format('Y-m-d H:i:s').' summary:send -> Iniciando el comando');
        $this->info('The command was started');

        Auth::login(User::firstOrFail());

        if (Configuration::firstOrFail()->cron) {
            \Log::info($now->format('Y-m-d H:i:s').' summary:send -> Cron activado ');
            $hostname = Website::query()
                ->where('uuid', app(\Hyn\Tenancy\Environment::class)->tenant()->uuid)
                ->first()
                ->hostnames
                ->first();

            $documents = Document::query()
                ->select('date_of_issue')
                ->where([
                    'soap_type_id' => Company::firstOrFail()->active()->soap_type_id,
                    'state_type_id' => '01',
                    'group_id' => '02'
                ])
                ->groupBy('date_of_issue')
                ->get();
            \Log::info($now->format('Y-m-d H:i:s').' summary:send -> Iniciando el envio de documentos ');

            foreach ($documents as $document) {

                // if(file_exists(base_path(config('tenant.name_certificate_cron')))){
                //     $constructor_params = [
                //         'base_uri' => config('tenant.force_https') ? "https://{$hostname->fqdn}" : "http://{$hostname->fqdn}",
                //         'verify' => base_path(config('tenant.name_certificate_cron'))
                //     ];
                // }else{
                //     $constructor_params = [
                //         'base_uri' => config('tenant.force_https') ? "https://{$hostname->fqdn}" : "http://{$hostname->fqdn}"
                //     ];
                // }

                $constructor_params = [
                    'base_uri' => config('tenant.force_https') ? "https://{$hostname->fqdn}" : "http://{$hostname->fqdn}",
                    'verify' => false
                ];

                $clientGuzzleHttp = new ClientGuzzleHttp($constructor_params);
                \Log::info($now->format('Y-m-d H:i:s')." summary:send -> Enviando datos para \n".var_export($constructor_params,true));

                $response = $clientGuzzleHttp->post('/api/summaries', [
                    'http_errors' => false,
                    'headers' => [
                        'Authorization' => 'Bearer '.auth()->user()->api_token,
                        'Accept' => 'application/json',
                    ],
                    'form_params' => [
                        'fecha_de_emision_de_documentos' => Carbon::parse($document->date_of_issue)->format('Y-m-d'),
                        'codigo_tipo_proceso' => 1
                    ]
                ]);

                $res = json_decode($response->getBody()->getContents(), true);

                if (!$res['success']) {
                    \Log::info($now->format('Y-m-d H:i:s').' summary:send -> Ocurrio un error');
                    $this->info("{$document->date_of_issue} - {$res['message']}");
                }
            }
        }
        else {
            $this->info('The crontab is disabled');
        }
        \Log::info($now->format('Y-m-d H:i:s').' -> Comando finalizado');

        $this->info('The command is finished');
    }
}
