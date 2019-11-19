<?php

namespace App\Models\Tenant;

class ConfigurationEcommerce extends ModelTenant
{
    protected $table = "configuration_ecommerce";

    protected $fillable = [
        'information_contact_name',
        'information_contact_email',
        'information_contact_phone',
        'script_paypal',
        'token_private_culqui',
        'token_public_culqui'

    ];

}
