<?php

namespace Modules\MobileApp\Models;

use App\Models\Tenant\ModelTenant;

class AppConfiguration extends ModelTenant
{

    protected $fillable = [
        'show_image_item',
    ];

    protected $casts = [
        'show_image_item' => 'bool',
    ];

    public function getRowResource()
    {
        return [
            'id' => $this->id,
            'show_image_item' => $this->show_image_item,
        ];
    }


}
