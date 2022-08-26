<?php

namespace Modules\LevelAccess\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\LevelAccess\Models\SystemActivityLog;
use Modules\LevelAccess\Http\Resources\{
    SystemActivityLogCollection
};
use Modules\LevelAccess\Helpers\GetClientIpHelper;


class SystemActivityLogTransactionController extends Controller
{

    public function index()
    {
        return view('levelaccess::system_activity_logs.access.index');
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
        // $ip = (new GetClientIpHelper)->getClientIp();
        // dd($ip);

        // dd(request()->getClientIp());
        // // $getClientIp= new GetClientIpHelper();
        
        // $getClientIp = new GetClientIpHelper(array( "REMOTE_ADDR"           => "1.2.3.4",
        //                               "REMOTE_PORT"           => "",
        //                               "SERVER_ADDR"           => "1.1.1.1",
        //                               "X_FORWARDED_FOR"       => "2.3.4.5,1.2.3.4, 1.2.3.4" ));

        // $ip = $getClientIp->getClientIp();
        // $longIp = $getClientIp->getLongClientIp();
        // dd($getClientIp, $ip, $longIp);

        // if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        //     $ip = $_SERVER['HTTP_CLIENT_IP'];
        // } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //     $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        // } else {
        //     $ip = $_SERVER['REMOTE_ADDR'];
        // }
        // dd($ip, $_SERVER, empty($_SERVER['HTTP_CLIENT_IP']));
        // $records = SystemActivityLog::where($request->column, 'like', "%{$request->value}%")
        //                     ->latest();

        // return new SystemActivityLogCollection($records->paginate(config('tenant.items_per_page')));
    }


}
