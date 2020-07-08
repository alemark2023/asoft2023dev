<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\DB;


class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bk:bd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database';

    protected $process;

    /**
     * Create a new command instance.
     *
     * @return void
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
        try {
            $today = now()->format('dmY');
            if (!is_dir(storage_path('backups'))) mkdir(storage_path('backups'));
            if (!is_dir(storage_path('backups/'.$today))) mkdir(storage_path('backups/'.$today));

            $dbs = DB::table('websites')->get()->toArray();
            $bd_admin = config('database.connections.mysql.database');

            foreach ($dbs as $db) {
                $this->comment('dump '.$db->uuid);
                $this->process = new Process(sprintf(
                    'mysqldump --compact --skip-comments --user=%s --password=%s %s > %s',
                    config('database.connections.mysql.username'),
                    config('database.connections.mysql.password'),
                    $db->uuid,
                    storage_path("backups/{$today}/{$db->uuid}.sql")
                ));
                $this->process->run();
            }

            $this->comment('dump '.$db->uuid);

            $this->process = new Process(sprintf(
                'mysqldump --compact --skip-comments --user=%s --password=%s %s > %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.database'),
                storage_path("backups/{$today}/{$bd_admin}.sql")
            ));
            $this->process->run();

            Log::info('Backup database success');
        } catch (ProcessFailedException $exception) {
            Log::error('Backup failed', $exception);
        }
    }
}
