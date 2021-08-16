<?php

namespace Modules\Account\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Document;
use App\Models\Tenant\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FormatPleController extends Controller
{
 
    public function download(Request $request) {

        $type = $request->input('type');
        $month = $request->input('month');

        $d_start = Carbon::parse($month.'-01')->format('Y-m-d');
        $d_end = Carbon::parse($month.'-01')->endOfMonth()->format('Y-m-d');

        if ($type === 'sale') {

            $filename = 'Formato_Ple_Ventas_'.date('YmdHis');
            $records = $this->getSaleDocuments($d_start, $d_end);

        }else{
            
            $filename = 'Formato_Ple_Compras_'.date('YmdHis');
            $records = $this->getPurchaseDocuments($d_start, $d_end);
        }
    
        // dd($records);
        
        $temp = tempnam(sys_get_temp_dir(), 'txt');
        $file = fopen($temp, 'w+');

        foreach ($records as $record)
        {
            $line = implode('|', $record);
            fwrite($file, $line."|"."\r\n");
        }

        fclose($file);

        return response()->download($temp, $filename.'.txt');
    }


    private function getPurchaseDocuments($d_start, $d_end) {

        return Purchase::query()
                        ->whereBetween('date_of_issue', [$d_start, $d_end])
                        ->where('document_type_id', ['01', '03', '07', '08'])
                        ->orderBy('series')
                        ->orderBy('number')
                        ->get()
                        ->transform(function ($row) { 

                            $tot_discount_base = $row->discounts ? collect($row->discounts)->sum(function($discount){
                                    return $discount->discount_type_id == '02' ? $discount->amount : 0;
                                }) : 0;

                            return [
                                'period' => $row->date_of_issue->format('Ym').'00',
                                'seat' => '',
                                'code' => '',
                                'date_of_issue' => $row->date_of_issue->format('d/m/Y'),
                                'date_of_due' => $row->date_of_due->format('d/m/Y'),
                                'document_type_id' => $row->document_type_id,
                                'series' => $row->series,
                                'aedod' => null,

                                'number' => $row->number,
                                'ecoaiodocf' => null,

                                'supplier_identity_document_type_id' => $row->supplier->identity_document_type_id,
                                'supplier_number' => $row->supplier->number,
                                'supplier_name' => $row->supplier->name,

                                'total_taxed' => null,
                                'total_igv' => null,
                                'biagdcfse' => null,
                                'migvipm' => null,
                                'biagdcfse' => null,
                                'migvipm' => null,
                                'total_no_taxed' => null,
                                'total_isc' => $row->total_isc,
                                'total_plastic_bag_taxes' => $row->total_plastic_bag_taxes,
                                'total_base_other_charges' => '0.00',
                                'total' => $row->total,
                                'currency_type_id' => $row->currency_type_id,
                                'exchange_rate_sale' => $row->exchange_rate_sale,
                                'affected_document_date_of_issue' => null,
                                'affected_document_document_type_id' => null,
                                'affected_document_series' => null,
                                'cod_dep' => null,
                                'affected_document_number' => null,
                                'detraction_date_of_issue' => null,
                                'detraction_number' => null,

                                'mcpsr' => null,
                                'cbsa' => null,
                                'icposi' => null,
                                'et1' => null,
                                'et2' => null,
                                'et3' => null,
                                'et4' => null,
                                'icpcmp' => null,
                                'state' => "estado",
                            ];
                        });

    }

    
    private function getSaleDocuments($d_start, $d_end) {

        return Document::query()
                        ->whereBetween('date_of_issue', [$d_start, $d_end])
                        ->orderBy('series')
                        ->orderBy('number')
                        ->get()
                        ->transform(function ($row) { 

                            $note = $this->getDataNote($row);

                            $tot_discount_base = $row->discounts ? collect($row->discounts)->sum(function($discount){
                                    return $discount->discount_type_id == '02' ? $discount->amount : 0;
                                }) : 0;

                            return [
                                'period' => $row->date_of_issue->format('Ym').'00',
                                'seat' => '',
                                'code' => '',
                                'date_of_issue' => $row->date_of_issue->format('d/m/Y'),
                                'date_of_due' => in_array($row->document_type_id, ['01', '03']) ? $row->invoice->date_of_due->format('d/m/Y') : null,
                                'document_type_id' => $row->document_type_id,
                                'series' => $row->series,
                                'number' => $row->number,
                                'pertcemr' => '',
                                'customer_identity_document_type_id' => $row->customer->identity_document_type_id,
                                'customer_number' => $row->customer->number,
                                'customer_name' => $row->customer->name,
                                'total_exportation' => $row->total_exportation,
                                'total_taxed' => $row->total_taxed,
                                'tot_discount_base' => number_format($tot_discount_base,2, ".", ""),
                                'total_igv' => $row->total_igv,
                                'discount_igv' => '0.00',
                                'total_exonerated' => $row->total_exonerated,
                                'total_unaffected' => $row->total_unaffected,
                                'total_isc' => $row->total_isc,
                                'base_ivap' => '0.00',
                                'ivap' => '0.00',
                                'total_plastic_bag_taxes' => $row->total_plastic_bag_taxes,
                                'total_base_other_charges' => '0.00',
                                'total' => $row->total,
                                'currency_type_id' => $row->currency_type_id,
                                'exchange_rate_sale' => $row->exchange_rate_sale,
                                'affected_document_date_of_issue' => $note['affected_document_date_of_issue'],
                                'affected_document_document_type_id' => $note['affected_document_document_type_id'],
                                'affected_document_series' => $note['affected_document_series'],
                                'affected_document_number' => $note['affected_document_number'],
                                'icsose' => null,
                                'e1' => null,
                                'icpcmp' => null,
                                'state' => "estado",
                            ];
                        });

    }

    public function getDataNote($row){

        $note = [
            'affected_document_date_of_issue' => null,
            'affected_document_document_type_id' => null,
            'affected_document_series' => null,
            'affected_document_number' => null,
        ];

        if(in_array($row->document_type_id, ['07', '08'])){

            if($row->note->affected_document){

                $note['affected_document_date_of_issue'] = $row->note->affected_document->date_of_issue->format('d/m/Y');
                $note['affected_document_document_type_id'] = $row->note->affected_document->document_type_id;
                $note['affected_document_series'] = $row->note->affected_document->series;
                $note['affected_document_number'] = $row->note->affected_document->number;
            
            }else{

                $note['affected_document_date_of_issue'] = null;
                $note['affected_document_document_type_id'] = $row->note->data_affected_document->document_type_id;
                $note['affected_document_series'] = $row->note->data_affected_document->series;
                $note['affected_document_number'] = $row->note->data_affected_document->number;

            }

        }

        return $note;
    }

}
