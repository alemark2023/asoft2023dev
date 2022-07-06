<?php

namespace Modules\MobileApp\Models;

use App\Models\Tenant\ModelTenant;

class AppConfiguration extends ModelTenant
{

    protected $fillable = [
        'show_image_item',
        'print_format_pdf',
        'theme_color',
        'card_color',
    ];

    protected $casts = [
        'show_image_item' => 'bool',
    ];

    public function getRowResource()
    {
        return [
            'id' => $this->id,
            'show_image_item' => $this->show_image_item,
            'print_format_pdf' => $this->print_format_pdf,
            'theme_color' => $this->theme_color,
            'card_color' => $this->card_color,
        ];
    }


}
