<?php

namespace Modules\Document\Helpers;

use Exception;
use Carbon\Carbon;

class DocumentHelper
{
          
    /**
     * 
     * Obtener fecha de inicio y fin para filtrar documentos en base 
     * a la fecha de inicio del ciclo de facturacion (planes) del cliente
     *
     * Usado en: 
     * App\Http\Controllers\System\ClientController
     * 
     * @param  $start_billing_cycle
     * @return array
     */
    public static function getStartEndDateForFilterDocument($start_billing_cycle)
    { 
        
        $day_start_billing = date_format($start_billing_cycle, 'j');
        $day_now = (int) date('j');
        $end = Carbon::parse(date('Y-m-d'));

        if ($day_now <= $day_start_billing) {

            $init = Carbon::parse(date('Y') . '-' . ((int)date('n') - 1) . '-' . $day_start_billing);

        } else {

            $init = Carbon::parse(date('Y') . '-' . ((int)date('n')) . '-' . $day_start_billing);
            
        }

        return [
            'start_date' => $init,
            'end_date' => $end,
        ];

    }
 
}
