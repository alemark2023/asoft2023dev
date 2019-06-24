<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\BankRequest;
use App\Http\Resources\Tenant\BankCollection;
use App\Http\Resources\Tenant\BankResource;
use App\Models\Tenant\Bank;

class BankController extends Controller
{
    public function records()
    {
        $records = Bank::all();

        return new BankCollection($records);
    }

    public function record($id)
    {
        $record = new BankResource(Bank::findOrFail($id));

        return $record;
    }

    public function store(BankRequest $request)
    {
        $id = $request->input('id');
        $bank = Bank::firstOrNew(['id' => $id]);
        $bank->fill($request->all());
        $bank->save();

        return [
            'success' => true,
            'message' => ($id)?'Banco editado con éxito':'Banco registrado con éxito'
        ];
    }

    public function destroy($id)
    {
        $bank = Bank::findOrFail($id);
        $bank->delete();

        return [
            'success' => true,
            'message' => 'Banco eliminado con éxito'
        ];
    }
}