<?php

namespace Modules\Store\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Quotation;
use App\Models\Tenant\Series;

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
        $document_type_id = $person->identity_document_type_id === '6'?'01':'03';

        $series = Series::query()
            ->select('number')
            ->where('establishment_id', $rec['establishment_id'])
            ->where('document_type_id', $document_type_id)
            ->first();

        foreach ($rec['items'] as &$item) {
            $item['total_plastic_bag_taxes'] = 0;
            $item['attributes'] = ($item['attributes'])?(array)$item['attributes']:[];
            $item['charges'] = ($item['charges'])?(array)$item['charges']:[];
            $item['discounts'] = ($item['discounts'])?(array)$item['discounts']:[];
        }

        $rec['document_type_id'] = $document_type_id;
        $rec['operation_type_id'] = '0101';
        $rec['number'] = '#';
        $rec['date_of_issue'] = now()->format('Y-m-d');
        $rec['fee'] = [];
        $rec['charges'] = [];
        $rec['discounts'] = [];
        $rec['payments'] = [];
        $rec['guides'] = [];
        $rec['payment_condition_id'] = '01';
        $rec['series'] = $series->number;
        $rec['ubl_version'] = '2.1';
        $rec['unique_filename'] = '';
        $rec['user_rel_suscription_plan_id'] = 0;
        $rec['was_deducted_prepayment'] = 0;
        $rec['quotation_id'] = $table_id;
        $rec['quotation_id'] = $table_id;
        $rec['additional_information'] = $rec['description'];

        return [
            'success' => true,
            'data' => $rec
        ];
    }

    public function getItems()
    {

    }
}
