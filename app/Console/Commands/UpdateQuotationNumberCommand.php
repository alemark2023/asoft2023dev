<?php

namespace App\Console\Commands;

use App\Models\Tenant\Quotation;
use Illuminate\Console\Command;

class UpdateQuotationNumberCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:quotation_number';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualización de número de cotización';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Hora de inicio: ' . date('Y-m-d H:i:s'));
        $this->info('Inicializando Proceso');

        Quotation::query()
            ->chunk(100, function ($documents) {
                foreach ($documents as $document) {
                    $filename = explode('-', $document->filename);
                    $document->update([
                        'series' => 'COT1',
                        'number' => $filename[1]
                    ]);
                }
            });

        $this->info('Finalizando Proceso');
        $this->info('Hora de término: ' . date('Y-m-d H:i:s'));
    }
}
