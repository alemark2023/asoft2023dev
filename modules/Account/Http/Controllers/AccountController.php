<?php

namespace Modules\Account\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Document;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Account\Exports\ReportAccountConcarExport;

class AccountController extends Controller
{
    public function index()
    {
        return view('account::account.index');
    }

    public function download(Request $request)
    {
        $type = $request->input('type');
        $month = $request->input('month');

        $d_start = Carbon::parse($month.'-01')->format('Y-m-d');
        $d_end = Carbon::parse($month.'-01')->endOfMonth()->format('Y-m-d');

        $records = $this->getDocuments($d_start, $d_end);

        $filename = 'Reporte_Concar_Ventas_'.date('YmdHis');
        if($type === 'concar') {
            $data = [
                'records' => $this->getStructureConcar($records),
            ];
            return (new ReportAccountConcarExport)
                ->data($data)
                ->download($filename.'.xlsx');
        }

        $records = $this->getStructureSiscont($records);

        $temp = tempnam(sys_get_temp_dir(), 'txt');
        $file = fopen($temp, 'w');
        foreach ($records as $record)
        {
            $line = implode('', $record);
            fwrite($file, $line.PHP_EOL);
        }
        fclose($file);

        return response()->download($temp, $filename.'.txt');
    }

    private function getDocuments($d_start, $d_end)
    {
        return Document::query()
                                ->whereBetween('date_of_issue', [$d_start, $d_end])
                                ->whereIn('document_type_id', ['01', '03'])
                                ->whereIn('currency_type_id', ['PEN'])
                                ->orderBy('series')
                                ->orderBy('number')
                                ->get();

    }

    private function getStructureConcar($documents)
    {
        $rows = [];
        foreach ($documents as $index => $row)
        {
            $date_of_issue = Carbon::parse($row->date_of_issue);
            $currency_type_id = ($row->currency_type_id === 'PEN')?'MN':'US';
            $document_type_id = ($row->document_type_id === '01')?'FT':'BV';
            $detail = $row->customer->name.', '.$document_type_id.' '.$row->number_full;

            $number_index = $date_of_issue->format('m').str_pad($index + 1, 4, "0", STR_PAD_LEFT);

            $rows[] = [
                'col_A' => '',
                'col_B' => '05',
                'col_C' => $number_index,
                'col_D' => $date_of_issue->format('d/m/Y'),
                'col_E' => $currency_type_id,
                'col_F' => 'POR VENTA',
                'col_G' => '',
                'col_H' => 'V',
                'col_I' => 'N',
                'col_J' => '',
                'col_K' => '121201',
                'col_L' => $row->customer->number,
                'col_M' => '',
                'col_N' => 'D',
                'col_O' => $row->total,
                'col_P' => '',
                'col_Q' => '',
                'col_R' => $document_type_id,
                'col_S' => $row->number_full,
                'col_T' => $row->date_of_issue->format('d/m/Y'),
                'col_U' => ($row->date_of_due)?$row->date_of_due->format('d/m/Y'):'',
                'col_V' => '',
                'col_W' => $detail,
            ];
            $rows[] = [
                'col_A' => '',
                'col_B' => '05',
                'col_C' => $number_index,
                'col_D' => $date_of_issue->format('d/m/Y'),
                'col_E' => $currency_type_id,
                'col_F' => 'POR VENTA',
                'col_G' => '',
                'col_H' => 'V',
                'col_I' => 'N',
                'col_J' => '',
                'col_K' => '401111',
                'col_L' => $row->customer->number,
                'col_M' => '',
                'col_N' => 'H',
                'col_O' => $row->total_igv,
                'col_P' => '',
                'col_Q' => '',
                'col_R' => $document_type_id,
                'col_S' => $row->number_full,
                'col_T' => $row->date_of_issue->format('d/m/Y'),
                'col_U' => '',
                'col_V' => '',
                'col_W' => $detail,
            ];
            $rows[] = [
                'col_A' => '',
                'col_B' => '05',
                'col_C' => $number_index,
                'col_D' => $date_of_issue->format('d/m/Y'),
                'col_E' => $currency_type_id,
                'col_F' => 'POR VENTA',
                'col_G' => '',
                'col_H' => 'V',
                'col_I' => 'N',
                'col_J' => '',
                'col_K' => '704101',
                'col_L' => $row->customer->number,
                'col_M' => '',
                'col_N' => 'H',
                'col_O' => $row->total_value,
                'col_P' => '',
                'col_Q' => '',
                'col_R' => $document_type_id,
                'col_S' => $row->number_full,
                'col_T' => $row->date_of_issue->format('d/m/Y'),
                'col_U' => '',
                'col_V' => '',
                'col_W' => 'POR VENTA',
            ];
        }
        return $rows;
    }

    private function getStructureSiscont($documents)
    {
        $rows = [];
        foreach ($documents as $index => $row)
        {
            $date_of_issue = Carbon::parse($row->date_of_issue);
            $currency_type_id = ($row->currency_type_id === 'PEN')?'S':'D';
            $document_type_id = ($row->document_type_id === '01')?'01':'03';
            $detail = substr($row->customer->name.', '.$document_type_id.' '.$row->number_full, 1, 30);

            $number_index = $date_of_issue->format('m').str_pad($index + 1, 4, "0", STR_PAD_LEFT);

            $rows[] = [
                'col_001_002' => '01',
                'col_003_006' => $number_index,
                'col_007_014' => $date_of_issue->format('d/m/y'),
                'col_015_024' => 'CUENTA 1',
                'col_025_036' => str_pad($row->total, 12, ' ', STR_PAD_LEFT),
                'col_037_037' => 'D',
                'col_038_038' => $currency_type_id,
                'col_039_048' => str_pad($row->exchange_rate_sale, 10,' ', STR_PAD_LEFT),
                'col_049_050' => $document_type_id,
                'col_051_070' => $row->series.'-'.str_pad($row->number, 15,' ', STR_PAD_LEFT),
                'col_071_078' => str_pad(($row->date_of_due)?$row->date_of_due->format('d/m/y'):'', 8,' ', STR_PAD_LEFT),
                'col_079_089' => str_pad($row->customer->number, 11, ' ', STR_PAD_LEFT),
                'col_090_099' => str_pad('', 10, ' ', STR_PAD_LEFT),
                'col_100_103' => str_pad('', 4, ' ', STR_PAD_LEFT),
                'col_104_113' => str_pad('', 10, ' ', STR_PAD_LEFT),
                'col_114_114' => 'V',
                'col_115_122' => $date_of_issue->format('d/m/y'),
                'col_123_134' => str_pad($row->total, 12, ' ', STR_PAD_LEFT),
                'col_135_146' => str_pad('', 12, ' ', STR_PAD_LEFT),
                'col_147_158' => str_pad('', 12, ' ', STR_PAD_LEFT),
                'col_159_170' => str_pad('', 12, ' ', STR_PAD_LEFT),
                'col_171_182' => str_pad($row->total_igv, 12, ' ', STR_PAD_LEFT),
                'col_183_193' => str_pad($row->customer->number, 11, ' ', STR_PAD_LEFT),
                'col_194_194' => str_pad('', 1, ' ', STR_PAD_LEFT),
                'col_195_234' => str_pad($row->customer->number, 11, ' ', STR_PAD_LEFT),
                'col_235_264' => str_pad($detail, 30, ' ', STR_PAD_LEFT),
                'col_265_265' => $row->customer->identity_document_type_id,
                'col_266_268' => str_pad('', 3, ' ', STR_PAD_LEFT),
                'col_269_288' => str_pad('', 20, ' ', STR_PAD_LEFT),
                'col_289_308' => str_pad('', 20, ' ', STR_PAD_LEFT),
                'col_309_328' => str_pad('', 20, ' ', STR_PAD_LEFT),
                'col_329_348' => str_pad('', 20, ' ', STR_PAD_LEFT),
                'col_349_350' => str_pad('', 2, ' ', STR_PAD_LEFT),
                'col_351_358' => str_pad('', 8, ' ', STR_PAD_LEFT),

            ];
        }
        return $rows;
    }
}