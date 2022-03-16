<?php

namespace App\Models\Tenant;

class DownloadTray extends ModelTenant
{
    protected $table = 'download_tray';

    protected $fillable = [
        'user_id',
        'module',
        'format',
        'file_name',
        'status', 
        'date_init',
        'date_end',
        'payload_request',
        'path',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
