<?php

namespace Modules\LevelAccess\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\LevelAccess\Models\SystemActivityLog;
use Modules\LevelAccess\Http\Resources\{
    SystemActivityLogCollection
};
use Carbon\Carbon;


class SystemActivityLogGeneralController extends Controller
{

    
    public function index()
    {
        return view('levelaccess::system_activity_logs.generals.index');
    }

    
    /**
     *
     * @return array
     */
    public function columns()
    {
        return [
            'date' => 'Fecha',
            'time' => 'Hora',
            'ip' => 'Ip',
        ];
    }

    
    /**
     *
     * @param  Request $request
     * @return SystemActivityLogCollection
     */
    public function records(Request $request)
    {
        $records = SystemActivityLog::filterRecords($request);

        return new SystemActivityLogCollection($records->latest()->paginate(config('tenant.items_per_page')));
    }

    
    /**
     * 
     * Verificar si el usuario requiere actualizacion de contraseña de acuerdo a la vigencia configurada
     *
     * @param  Request $request
     * @return array
     */
    public function checkLastPasswordUpdate(Request $request)
    {

        $quantity_month = $request->quantity_month_remember_change_password;
        $last_password_update = auth()->user()->last_password_update;

        $change_success = [
            'success' => true,
            'message' => 'Ha pasado mucho tiempo desde la última vez que modificó su contraseña, debe actualizarla.',
        ];

        if(!is_null($last_password_update))
        {
            $limit_date = Carbon::parse($last_password_update)->addMonths($quantity_month);
            $today = Carbon::now();
            
            if($limit_date->lte($today)) return $change_success;

            return [
                'success' => false
            ];
        }

        return $change_success;
    }


}
