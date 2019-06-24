<?php

namespace App\Console\Commands;

use Facades\App\Http\Controllers\Tenant\DocumentController;
use Illuminate\Console\Command;
use App\Traits\CommandTrait;
use App\Models\Tenant\{
    Configuration,
    Document
};

class QueryAllServerCommand extends Command
{
    use CommandTrait;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'offline:query-all';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Query all the documents in the online server';
    
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
        if (Configuration::firstOrFail()->cron) {
            if (!$this->isOffline()) {
                $this->info('The offline service is disabled');
                
                return;
            };
            
            $documents = Document::query()
                ->where('send_server', 1)
                ->where('state_type_id', '!=', '05')
                ->orWhere('query_status', '!=', '')
                ->get();
            
            foreach ($documents as $document) {
                try {
                    DocumentController::checkServer($document->id);
                    
                    $document->query_status = '';
                    $document->save();
                }
                catch (\Exception $e) {
                    $document->query_status = json_encode([
                        'message' => $e->getMessage(),
                        'payload' => $e
                    ]);
                    
                    $document->save();
                }
            }
        }
        else {
            $this->info('The crontab is disabled');
        }
        
        $this->info('The command is finished');
    }
}
