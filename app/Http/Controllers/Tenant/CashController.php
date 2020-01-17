<?php
namespace App\Http\Controllers\Tenant;

use App\Imports\ItemsImport;
use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\AttributeType;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\Catalogs\UnitType;
use App\Models\Tenant\User;
use App\Models\Tenant\Company;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\CashRequest;
use App\Http\Resources\Tenant\CashCollection;
use App\Http\Resources\Tenant\CashResource;
use App\Models\Tenant\Cash;
use App\Models\Tenant\CashDocument;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Barryvdh\DomPDF\Facade as PDF;

class CashController extends Controller
{
    public function index()
    {
        return view('tenant.cash.index');
    }

    public function columns()
    {
        return [
            'income' => 'Ingresos',
            // 'expense' => 'Egresos',
        ];
    }

    public function records(Request $request)
    {
        $records = Cash::where($request->column, 'like', "%{$request->value}%")
                        ->whereTypeUser()        
                        ->orderBy('date_opening');

        
        return new CashCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function create()
    {
        return view('tenant.items.form');
    }

    public function tables()
    {
        $user = auth()->user();
        $type = $user->type;
        $users = array();

        switch($type)
        {
            case 'admin':
                $users = User::where('type', 'seller')->get();
                $users->push($user);
                break;
            case 'seller':
                $users = User::where('id', $user->id)->get();
                break;
        }

        return compact('users', 'user');
    }

    public function opening_cash()
    {

        $cash = Cash::where([['user_id', auth()->user()->id],['state', true]])->first();

        return compact('cash');
    }

    public function opening_cash_check($user_id)
    {
        $cash = Cash::where([['user_id', $user_id],['state', true]])->first();
        return compact('cash');
    }


    public function record($id)
    {
        $record = new CashResource(Cash::findOrFail($id));

        return $record;
    }

    public function store(CashRequest $request) {
       
        $id = $request->input('id');        
        $cash = Cash::firstOrNew(['id' => $id]);
        $cash->fill($request->all());

        if(!$id){
            $cash->date_opening = date('Y-m-d'); 
            $cash->time_opening = date('H:i:s'); 
        }

        $cash->save();         
        
        return [
            'success' => true,
            'message' => ($id)?'Caja actualizada con éxito':'Caja aperturada con éxito'
        ];

    }

    public function close($id) {
       
        $cash = Cash::findOrFail($id);

        // dd($cash->cash_documents); 

        $cash->date_closed = date('Y-m-d'); 
        $cash->time_closed = date('H:i:s'); 
        
        $final_balance = 0;
        $income = 0;

        foreach ($cash->cash_documents as $cash_document) {


            if($cash_document->sale_note){
                
                $final_balance += ($cash_document->sale_note->currency_type_id == 'PEN') ? $cash_document->sale_note->total : ($cash_document->sale_note->total * $cash_document->sale_note->exchange_rate_sale);
                
                // $final_balance += $cash_document->sale_note->total;

            }
            else if($cash_document->document){

                $final_balance += ($cash_document->document->currency_type_id == 'PEN') ? $cash_document->document->total : ($cash_document->document->total * $cash_document->document->exchange_rate_sale);

                // $final_balance += $cash_document->document->total;

            }
            else if($cash_document->expense_payment){

                $final_balance -= ($cash_document->expense_payment->expense->currency_type_id == 'PEN') ? $cash_document->expense_payment->payment:($cash_document->expense_payment->payment  * $cash_document->expense_payment->expense->exchange_rate_sale);

                // $final_balance -= $cash_document->expense_payment->payment;

            }

            // else if($cash_document->purchase){
            //     $final_balance -= $cash_document->purchase->total;
            // }
            // else if($cash_document->expense){
            //     $final_balance -= $cash_document->expense->total;
            // }

        }

        $cash->final_balance = round($final_balance + $cash->beginning_balance, 2); 
        $cash->income = round($final_balance, 2); 
        $cash->state = false;          
        $cash->save();         
        
        return [
            'success' => true,
            'message' => 'Caja cerrada con éxito',
        ];

    }


    public function cash_document(Request $request) {
               
        $cash = Cash::where([['user_id',auth()->user()->id],['state',true]])->first();
        $cash->cash_documents()->create($request->all());
          
        return [
            'success' => true,
            'message' => 'Venta con éxito',
        ];
    }

    
    public function destroy($id)
    {
        $cash = Cash::findOrFail($id);
        $cash->delete();

        return [
            'success' => true,
            'message' => 'Caja eliminada con éxito'
        ];
    }


    public function report($cash) {
       
        $cash = Cash::findOrFail($cash);
        $company = Company::first();
        // dd($cash);

        set_time_limit(0); 
        
        $pdf = PDF::loadView('tenant.cash.report_pdf', compact("cash", "company"));

        $filename = "Reporte_POS - {$cash->user->name} - {$cash->date_opening} {$cash->time_opening}";
        
        return $pdf->download($filename.'.pdf');
    }

   

}