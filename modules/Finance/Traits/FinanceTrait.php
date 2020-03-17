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

        return collect($bank_accounts)->push($cash);

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

        }else{
            
            $cash_create = Cash::create([
                                    'user_id' => auth()->user()->id,
                                    'date_opening' => date('Y-m-d'),
                                    'time_opening' => date('H:i:s'),
                                    'date_closed' => null,
                                    'time_closed' => null,
                                    'beginning_balance' => 0,
                                    'final_balance' => 0,
                                    'income' => 0,
                                    'state' => true,
                                    'reference_number' => null
                                ]);

            return [
                'id' => 'cash',
                'cash_id' => $cash_create->id,
                'description' => "CAJA CHICA"
            ];

        }

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

    
    public function getBalanceByCash($cash){
 
        $document_payment = $this->getSumPayment($cash, DocumentPayment::class);
        $expense_payment = $this->getSumPayment($cash, ExpensePayment::class); 
        $sale_note_payment = $this->getSumPayment($cash, SaleNotePayment::class);
        $purchase_payment = $this->getSumPayment($cash, PurchasePayment::class); 

        $entry = $document_payment + $sale_note_payment;
        $egress = $expense_payment + $purchase_payment;
        
        $balance = $entry - $egress;

        return [

            'id' => 'cash',
            'description' => "CAJA CHICA",
            'expense_payment' => number_format($expense_payment,2, ".", ""),
            'sale_note_payment' => number_format($sale_note_payment,2, ".", ""),
            'document_payment' => number_format($document_payment,2, ".", ""),
            'purchase_payment' => number_format($purchase_payment,2, ".", ""),
            'balance' => number_format($balance,2, ".", "")
            
        ];

    }

    
    
    public function getBalanceByBankAcounts($bank_accounts){

        $records = $bank_accounts->map(function($row){

            $document_payment = $this->getSumPayment($row->global_destination, DocumentPayment::class);
            $expense_payment = $this->getSumPayment($row->global_destination, ExpensePayment::class); 
            $sale_note_payment = $this->getSumPayment($row->global_destination, SaleNotePayment::class);
            $purchase_payment = $this->getSumPayment($row->global_destination, PurchasePayment::class); 

            $entry = $document_payment + $sale_note_payment;
            $egress = $expense_payment + $purchase_payment;
            $balance = $entry - $egress;

            return [

                'id' => $row->id,
                'description' => "{$row->bank->description} - {$row->currency_type_id} - {$row->description}", 
                'expense_payment' => number_format($expense_payment,2, ".", ""),
                'sale_note_payment' => number_format($sale_note_payment,2, ".", ""),
                'document_payment' => number_format($document_payment,2, ".", ""),
                'purchase_payment' => number_format($purchase_payment,2, ".", ""),
                'balance' => number_format($balance,2, ".", "")
                
            ];

        }); 

        return $records;
        
    }

    public function getSumPayment($record, $model)
    {
        return $record->where('payment_type', $model)->sum(function($row){
            return $this->calculateTotalCurrencyType($row->payment->associated_record_payment, $row->payment->payment);
        });
    }
    

    public function calculateTotalCurrencyType($record, $payment)
    {
        return ($record->currency_type_id === 'USD') ? $payment * $record->exchange_rate_sale : $payment;
    }

    
    public function getRecordsByPaymentMethodTypes($payment_method_types)
    {
        
        $records = $payment_method_types->map(function($row){

            $document_payment = $this->getSumByPMT($row->document_payments);
            $sale_note_payment = $this->getSumByPMT($row->sale_note_payments);
            $purchase_payment = $this->getSumByPMT($row->purchase_payments); 

            return [

                'id' => $row->id,
                'description' => $row->description, 
                'expense_payment' => '-',
                'sale_note_payment' => 'S/ '.number_format($sale_note_payment,2, ".", ""),
                'document_payment' => 'S/ '.number_format($document_payment,2, ".", ""),
                'purchase_payment' => 'S/ '.number_format($purchase_payment,2, ".", "")
                
            ];

        }); 

        return $records;
    }

    
    public function getRecordsByExpenseMethodTypes($expense_method_types)
    {
        
        $records = $expense_method_types->map(function($row){

            // dd($row->expense_payments);
            $expense_payment = $this->getSumByPMT($row->expense_payments); 

            return [

                'id' => $row->id,
                'description' => $row->description, 
                'expense_payment' => 'S/ '.number_format($expense_payment,2, ".", ""),
                'sale_note_payment' => '-',
                'document_payment' => '-',
                'purchase_payment' => '-'
                
            ];

        }); 

        return $records;
    }

    public function getSumByPMT($records)
    {
        return $records->sum(function($row){
            return $this->calculateTotalCurrencyType($row->associated_record_payment, $row->payment);
        });
    }

}
