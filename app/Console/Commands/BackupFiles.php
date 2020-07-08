<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Zip;

class BackupFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bk:files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup storage tenants';

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
            $zip = Zip::create(storage_path('backups/'.$today.'storage.zip'));
            $zip->add(storage_path('app/tenancy/tenants/'));
            $zip->add(storage_path('backups/'.$today, true));
        } catch (Throwable $e) {
            Log::error('Backup failed', $e);
        }
    }
}
