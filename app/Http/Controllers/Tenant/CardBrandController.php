<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller; 
use App\Models\Tenant\CardBrand;
use Illuminate\Http\Request;
use App\Http\Requests\Tenant\CardBrandRequest;

class CardBrandController extends Controller
{
    public function records()
    {
        $records = CardBrand::all();

        return $records;
    }

    public function record($id)
    {
        $record = CardBrand::findOrFail($id);

        return $record;
    }

    public function store(CardBrandRequest $request)
    {
        $id = $request->input('id');
        $is_update = $request->input('is_update');
        $card_brand = CardBrand::firstOrNew(['id' => $id]);
        $card_brand->fill($request->all());
        $card_brand->save();

        return [
            'success' => true,
            'message' => ($is_update) ? 'Tarjeta editada con éxito':'Tarjeta registrada con éxito',
            'id' => $card_brand->id
        ];
    }

    

    public function destroy($id)
    {
        $card_brand = CardBrand::findOrFail($id);
        $card_brand->delete();

        return [
            'success' => true,
            'message' => 'Tarjeta eliminada con éxito'
        ];
    }
}