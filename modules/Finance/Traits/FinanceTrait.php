<?php

namespace Modules\Finance\Traits; 

use App\Models\Tenant\Cash;
use App\Models\Tenant\BankAccount;
use App\Models\Tenant\Company;
use Carbon\Carbon;
use Modules\Expense\Models\ExpensePayment;
use App\Models\Tenant\{
    DocumentPayment,
    SaleNotePayment,
    PurchasePayment
};


trait FinanceTrait
{ 

    public function getPaymentDestinations(){
        
        $bank_accounts = self::getBankAccounts();
        $cash = $this->getCash();

        // dd($cash);

        return $cash ? collect($bank_accounts)->push($cash) :  $bank_accounts;

    }


    private static function getBankAccounts(){

        return BankAccount::get()->transform(function($row) {
            return [
                'id' => $row->id,
                'cash_id' => null,
                'description' => "{$row->bank->description} - {$row->currency_type_id} - {$row->description}",
            ];
        });

    }


    public function getCash(){

        $cash =  Cash::where([['user_id',auth()->user()->id],['state',true]])->first();

        if($cash){
            
            return [
                'id' => 'cash',
                'cash_id' => $cash->id,
                'description' => ($cash->reference_number) ? "CAJA CHICA - {$cash->reference_number}" : "CAJA CHICA",
            ];

        }

        return null;
    }

    public function createGlobalPayment($model, $row){

        $destination = $this->getDestinationRecord($row); 
        $company = Company::active();

        $model->global_payment()->create([
            'soap_type_id' => $company->soap_type_id,
            'destination_id' => $destination['destination_id'],
            'destination_type' => $destination['destination_type'],
        ]);

    }

    public function getDestinationRecord($row){
        
        if($row['payment_destination_id'] === 'cash'){

            $destination_id = $this->getCash()['cash_id'];
            $destination_type = Cash::class;

        }else{

            $destination_id = $row['payment_destination_id'];
            $destination_type = BankAccount::class;

        }

        return [
            'destination_id' => $destination_id,
            'destination_type' => $destination_type,
        ];
    }

    
    public function deleteAllPayments($payments){

        foreach ($payments as $payment) {
            $payment->delete();
        }

    }

    public function getCollectionPaymentTypes(){

        return [
            ['id'=> DocumentPayment::class, 'description' => 'COMPROBANTES (CPE)'],
            ['id'=> SaleNotePayment::class, 'description' => 'NOTAS DE VENTA'],
            ['id'=> PurchasePayment::class, 'description' => 'COMPRAS'],
            ['id'=> ExpensePayment::class, 'description' => 'GASTOS'],
        ];
    }

    public function getCollectionDestinationTypes(){

        return [
            ['id'=> Cash::class, 'description' => 'CAJA CHICA'],
            ['id'=> BankAccount::class, 'description' => 'CUENTA BANCARIA'],
        ];
    }

    public function getDatesOfPeriod($request){

        $period = $request['period'];
        $date_start = $request['date_start'];
        $date_end = $request['date_end'];
        $month_start = $request['month_start'];
        $month_end = $request['month_end'];
        
        $d_start = null;
        $d_end = null;

        switch ($period) {
            case 'month':
                $d_start = Carbon::parse($month_start.'-01')->format('Y-m-d');
                $d_end = Carbon::parse($month_start.'-01')->endOfMonth()->format('Y-m-d');
                break;
            case 'between_months':
                $d_start = Carbon::parse($month_start.'-01')->format('Y-m-d');
                $d_end = Carbon::parse($month_end.'-01')->endOfMonth()->format('Y-m-d');
                break;
            case 'date':
                $d_start = $date_start;
                $d_end = $date_start;
                break;
            case 'between_dates':
                $d_start = $date_start;
                $d_end = $date_end;
                break;
        }

        return [
            'd_start' => $d_start,
            'd_end' => $d_end
        ];
    }
}
