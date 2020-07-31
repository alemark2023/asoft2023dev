<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Ifsnop\Mysqldump as IMysqldump;

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
            if (!is_dir(storage_path('app/backups'))) mkdir(storage_path('app/backups'));
            if (!is_dir(storage_path('app/backups/'.$today))) mkdir(storage_path('app/backups/'.$today));

            $dbs = DB::table('websites')->get()->toArray();
            $bd_admin = config('database.connections.mysql.database');

            $dbConfig = config('database.connections.' . config('tenancy.db.system-connection-name', 'system'));
            $var = Arr::first(Arr::wrap($dbConfig['host'] ?? ''));

            $dump = new IMysqldump\Mysqldump('mysql:host='.$var.';dbname='.config('database.connections.mysql.database'), config('database.connections.mysql.username'), config('database.connections.mysql.password'));
            // $dump = new IMysqldump\Mysqldump('mysql:host=172.20.0.2;dbname='.config('database.connections.mysql.database'), config('database.connections.mysql.username'), config('database.connections.mysql.password'));
            $dump->start(storage_path("app/backups/{$today}/{$bd_admin}.sql"));

            // foreach ($dbs as $db) {
            //     $this->comment('dump '.$db->uuid);
            //     $this->process = new Process(sprintf(
            //         'mysqldump --compact --skip-comments --user=%s --password=%s %s > %s 2>&1',
            //         config('database.connections.mysql.username'),
            //         config('database.connections.mysql.password'),
            //         $db->uuid,
            //         storage_path("app/backups/{$today}/{$db->uuid}.sql")
            //     ));

            //     // $command = 'mysqldump --opt -u '.config('database.connections.mysql.username').' -p'.config('database.connections.mysql.password').' '.$db->uuid.' > '.storage_path("app/backups/{$today}/{$db->uuid}.sql");

            //     // $this->process = new Process($command);

            //     $this->process->run();
            // }

            // $this->comment('dump '.$db->uuid);

            // $this->process = new Process(sprintf(
            //     'mysqldump --compact --skip-comments --user=%s --password=%s %s > %s 2>&1',
            //     config('database.connections.mysql.username'),
            //     config('database.connections.mysql.password'),
            //     config('database.connections.mysql.database'),
            //     storage_path("app/backups/{$today}/{$bd_admin}.sql")
            // ));
            // dd($this->process);
            // $this->process->run();

            Log::info('Backup database success');
        } catch (ProcessFailedException $exception) {
            Log::error('Backup failed', $exception);
        }
    }
}
