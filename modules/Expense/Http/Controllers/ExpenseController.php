<?php

namespace Modules\Expense\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Expense\Models\Expense;
use Modules\Expense\Models\ExpenseType;
use Modules\Expense\Models\ExpenseItem;
use Modules\Expense\Http\Resources\ExpenseCollection;
use Modules\Expense\Http\Resources\ExpenseResource;
use Modules\Expense\Http\Requests\ExpenseRequest;
use Illuminate\Support\Str;
use App\Models\Tenant\Person;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\CoreFacturalo\Requests\Inputs\Common\PersonInput;
use App\Models\Tenant\Establishment;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
     
    public function index()
    {
        return view('expense::expenses.index');
    }

     
    public function create()
    {
        return view('expense::expenses.form');
    }

    public function columns()
    {
        return [
            'number' => 'Número',
            'date_of_issue' => 'Fecha de emisión',
        ];
    }


    public function records(Request $request)
    {
        $records = Expense::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new ExpenseCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function tables()
    {
        $suppliers = $this->table('suppliers');
        $establishment = Establishment::where('id', auth()->user()->establishment_id)->first();    
        $currency_types = CurrencyType::whereActive()->get();
        $expense_types = ExpenseType::get();        

        return compact('suppliers', 'establishment','currency_types', 'expense_types');
    }

     

    public function record($id)
    {
        $record = new ExpenseResource(Expense::findOrFail($id));

        return $record;
    }

    public function store(ExpenseRequest $request)
    {
        $data = self::merge_inputs($request);

        $expense = DB::connection('tenant')->transaction(function () use ($data) {

            $doc = Expense::create($data);
            foreach ($data['items'] as $row)
            {
                $doc->items()->create($row);
            }     

            return $doc;
        });       
 
        return [
            'success' => true,
            'data' => [
                'id' => $expense->id,
            ],
        ];
    }

    public static function merge_inputs($inputs)
    {
        $values = [
            'user_id' => auth()->id(),
            'external_id' => Str::uuid()->toString(),
            'supplier' => PersonInput::set($inputs['supplier_id']), 
        ]; 

        $inputs->merge($values);

        return $inputs->all();
    }

    public function table($table)
    {
        switch ($table) {
            case 'suppliers':

                $suppliers = Person::whereType('suppliers')->orderBy('name')->get()->transform(function($row) {
                    return [
                        'id' => $row->id,
                        'description' => $row->number.' - '.$row->name,
                        'name' => $row->name,
                        'number' => $row->number,
                        'identity_document_type_id' => $row->identity_document_type_id,
                        'identity_document_type_code' => $row->identity_document_type->code
                    ];
                });
                return $suppliers;

                break; 
            default:

                return [];

                break;
        } 
    }

    
}
