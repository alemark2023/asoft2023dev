<?php

namespace Modules\LevelAccess\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\LevelAccess\Models\SystemActivityLog;
use Modules\LevelAccess\Http\Resources\{
    SystemActivityLogCollection
};


class SystemActivityLogGeneralController extends Controller
{

    
    public function index()
    {
        return view('levelaccess::system_activity_logs.generals.index');
    }


    public function columns()
    {
        return [
            'date' => 'Fecha',
            'time' => 'Hora',
            'ip' => 'Ip',
        ];
    }


    public function records(Request $request)
    {
        $records = SystemActivityLog::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new SystemActivityLogCollection($records->paginate(config('tenant.items_per_page')));
    }


}
