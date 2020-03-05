<?php

namespace Modules\Document\Traits; 

use App\Models\Tenant\Cash;
use App\Models\Tenant\BankAccount;

trait DocumentTrait
{ 

    public function getPaymentDestinations(){
        
        $bank_accounts = self::getBankAccounts();
        $cash = self::getCash();

        // dd($cash);

        return collect($bank_accounts)->merge($cash);

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


    private static function getCash(){

        return  Cash::where([['user_id',auth()->user()->id],['state',true]])->take(1)->get()->transform(function($row) {
            return [
                'id' => 'cash',
                'cash_id' => $row->id,
                'description' => ($row->reference_number) ? "Caja chica - {$row->reference_number}" : "Caja chica",
            ];
        });

    }

}
