<?php

namespace Modules\Dashboard\Helpers;

use App\Models\Tenant\Establishment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardView
{
    public static function getEstablishments()
    {
        return Establishment::all()->transform(function($row) {
            return [
                'id' => $row->id,
                'name' => $row->description
            ];
        });
    }

    public static function getUnpaid($request)
    {
        $establishment_id = $request['establishment_id'];
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
                $d_end = Carbon::parse($month_end.'-01')->endOfMonth()->format('Y-m-d');
                break;
            case 'between_months':
                $d_start = Carbon::parse($month_start.'-01')->format('Y-m-d');
                $d_end = Carbon::parse($month_end.'-01')->endOfMonth()->format('Y-m-d');
                break;
            case 'date':
                $d_start = $date_start;
                $d_end = $date_end;
                break;
            case 'between_dates':
                $d_start = $date_start;
                $d_end = $date_end;
                break;
        }

        /*
         * Documents
         */
        $document_payments = DB::table('document_payments')
            ->select('document_id', DB::raw('SUM(payment) as total_payment'))
            ->groupBy('document_id');

        $documents = DB::connection('tenant')
            ->table('documents')
            ->join('persons', 'persons.id', '=', 'documents.customer_id')
            ->leftJoinSub($document_payments, 'payments', function ($join) {
                $join->on('documents.id', '=', 'payments.document_id');
            })
            ->select(DB::raw("documents.id as id, ".
                             "DATE_FORMAT(documents.date_of_issue, '%d/%m/%Y') as date_of_issue, ".
                             "persons.name as customer_name, ".
                             "CONCAT(documents.series,'-',documents.number) AS number_full, ".
                             "documents.total as total, ".
                             "IFNULL(payments.total_payment, 0) as total_payment, ".
                             "'document' AS 'type'"))
            ->where('documents.establishment_id', $establishment_id)
            ->whereBetween('documents.date_of_issue', [$d_start, $d_end]);

        /*
         * Sale Notes
         */
        $sale_note_payments = DB::table('sale_note_payments')
            ->select('sale_note_id', DB::raw('SUM(payment) as total_payment'))
            ->groupBy('sale_note_id');

        $sale_notes = DB::connection('tenant')
            ->table('sale_notes')
            ->join('persons', 'persons.id', '=', 'sale_notes.customer_id')
            ->leftJoinSub($sale_note_payments, 'payments', function ($join) {
                $join->on('sale_notes.id', '=', 'payments.sale_note_id');
            })
            ->select(DB::raw("sale_notes.id as id, ".
                             "DATE_FORMAT(sale_notes.date_of_issue, '%d/%m/%Y') as date_of_issue, ".
                             "persons.name as customer_name, ".
                             "sale_notes.filename as number_full, ".
                             "sale_notes.total as total, ".
                             "IFNULL(payments.total_payment, 0) as total_payment, ".
                             "'sale_note' AS 'type'"))
            ->where('sale_notes.establishment_id', $establishment_id)
            ->where('sale_notes.changed', false)
            ->whereBetween('sale_notes.date_of_issue', [$d_start, $d_end])
            ->where('sale_notes.total_canceled', false);

        $records = $documents->union($sale_notes)->get();

        return collect($records)->transform(function($row) {
            $total_to_pay = (float)$row->total - (float)$row->total_payment;
//            if($total_to_pay > 0) {
                return [
                    'id' => $row->id,
                    'date_of_issue' => $row->date_of_issue,
                    'customer_name' => $row->customer_name,
                    'number_full' => $row->number_full,
                    'total' => (float) $row->total,
                    'total_to_pay' => $total_to_pay,
                    'type' => $row->type,
                ];
//            }
        });
    }
}