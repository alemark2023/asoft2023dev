<?php

namespace Modules\Finance\Traits; 

use App\Models\Tenant\Cash;
use App\Models\Tenant\BankAccount;
use App\Models\Tenant\Company;

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

}
