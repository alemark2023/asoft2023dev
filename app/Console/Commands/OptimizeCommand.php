<?php

namespace App\Console\Commands;

use App\Models\Tenant\Document;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\OutputInterface;

class OptimizeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:optimize';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'Table optimizer for database';

    /**
     * The console command description.
     *
     * @var string|null
     */
    protected $description = 'Optimize table/s of the database';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        Document::query()->update([
            'qr' => null
        ]);
        $this->info('Starting Optimization.');
        DB::connection('tenant')->statement("OPTIMIZE TABLE documents");
        $this->info(PHP_EOL.'Optimization Completed');
    }
}
