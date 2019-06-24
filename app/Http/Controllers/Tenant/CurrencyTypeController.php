<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\CurrencyTypeRequest;
use App\Http\Resources\Tenant\CurrencyTypeCollection;
use App\Http\Resources\Tenant\CurrencyTypeResource;
use App\Models\Tenant\Catalogs\CurrencyType;

class CurrencyTypeController extends Controller
{
    public function records()
    {
        $records = CurrencyType::all();

        return new CurrencyTypeCollection($records);
    }

    public function record($id)
    {
        $record = new CurrencyTypeResource(CurrencyType::findOrFail($id));

        return $record;
    }

    public function store(CurrencyTypeRequest $request)
    {
        $id = $request->input('id');
        $currency_type = CurrencyType::firstOrNew(['id' => $id]);
        $currency_type->fill($request->all());
        $currency_type->save();

        return [
            'success' => true,
            'message' => ($id)?'Moneda editada con éxito':'Moneda registrada con éxito'
        ];
    }

    public function destroy($id)
    {
        $currency_type = CurrencyType::findOrFail($id);
        $currency_type->delete();

        return [
            'success' => true,
            'message' => 'Moneda eliminada con éxito'
        ];
    }
}