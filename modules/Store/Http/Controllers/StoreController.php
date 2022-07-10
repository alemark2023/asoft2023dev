<?php

namespace Modules\Store\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Quotation;

class StoreController extends Controller
{
    public function tableToDocument($table ,$table_id)
    {
        $configuration = Configuration::query()->first();
        $is_contingency = 0;

        return view('tenant.documents.form', [
            'configuration' => $configuration,
            'is_contingency' => $is_contingency,
            'table_id' => $table_id,
            'table' => $table,
        ]);
    }

    public function getRecord($table, $table_id)
    {
        $record = Quotation::query()->with('person')->find($table_id);
        $person = $record->person;

        $rec = $record->toArray();
        $rec['document_type_id'] = $person->identity_document_type_id === '6'?'01':'03';
        $rec['operation_type_id'] = '0101';
        $rec['date_of_issue'] = now()->format('Y-m-d');
        $rec['fee'] = [];
        $rec['charges'] = [];
        $rec['discounts'] = [];
        $rec['payments'] = [];
        $rec['payment_condition_id'] = '01';

        return [
            'success' => true,
            'data' => $rec
        ];
    }

    public function getItems()
    {

    }
}
