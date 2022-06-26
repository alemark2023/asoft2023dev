<?php

namespace Modules\MobileApp\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Cash;
use Modules\MobileApp\Http\Resources\Api\{
	CashCollection,
	CashResource,
};
use App\Http\Controllers\Tenant\CashController as CashControllerWeb;
use App\Http\Requests\Tenant\CashRequest;
use Modules\Pos\Http\Controllers\CashController as CashControllerWebPos;


class CashController extends Controller
{

    
    /**
     * 
     * Obtener caja
     *
     * @param  int $id
     * @return array
     */
    public function record($id)
    {
        return app(CashControllerWeb::class)->record($id);
    }

    
    /**
     * 
     * Registrar/Actualizar caja
     *
     * @param  CashRequest $request
     * @return array
     */
    public function store(CashRequest $request) 
    {
        if(!$request['user_id'])
        {
            $request['user_id'] = auth()->id();
        }
        
        return app(CashControllerWeb::class)->store($request);
    }


    /**
     * 
     * Validar si el usuario tiene caja aperturada
     *
     * @return array
     */
    public function checkOpenCash()
    {
        $data = app(CashControllerWeb::class)->opening_cash_check(auth()->id());

        if($data['cash']) return $this->generalResponse(true, 'No puede crear caja chica, por favor cierre caja chica para el usuario definido');

        return $this->generalResponse(false);
    }


    /**
     * 
     * Obtener registros paginados
     *
     * @param  Request $request
     * @return array
     */
	public function records(Request $request)
	{
        $records = Cash::whereFilterRecordsApi($request->input);

		return new CashCollection($records->paginate(config('tenant.items_per_page')));
	}

	
    /**
     * 
     * Cerrar caja, usa método del proceso por web
     *
     * @param  int $id
     * @return array
     */
    public function close($id)
    {
        return app(CashControllerWeb::class)->close($id);
    }


    /**
     * 
     * Eliminar registro, usa método del proceso por web
     *
     * @param  int $id
     * @return array
     */
    public function destroy($id)
    {
        return app(CashControllerWeb::class)->destroy($id);
    }

        
    /**
     * 
     *
     * @param  Request $request
     * @return array
     */
    public function email(Request $request) 
    {
        $request->validate(
            ['email' => 'required']
        );

        return app(CashControllerWebPos::class)->email($request);
    }

}
