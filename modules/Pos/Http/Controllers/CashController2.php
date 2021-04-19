<?php

namespace Modules\Pos\Http\Controllers;

use App\Models\Tenant\Document;
use App\Models\Tenant\DocumentPayment;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\SaleNotePayment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Tenant\Cash;
use App\Models\Tenant\Company;
use App\Models\Tenant\PaymentMethodType;
use Modules\Sale\Models\TechnicalService;
use Modules\Sale\Models\TechnicalServicePayment;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Modules\Pos\Exports\ReportCashExport;
use Modules\Pos\Mail\CashEmail;
use Illuminate\Support\Facades\Mail;

class CashController2 extends Controller
{

    public function email(Request $request)
    {

        $request->validate(
            ['email' => 'required']
        );

        $company = Company::active();
        $email = $request->input('email');

        Mail::to($email)->send(new CashEmail($company, $this->getPdf($request->cash_id)));

        return [
            'success' => true
        ];
    }


    private function getPdf($cash)
    {
        $cash = Cash::query()->with('global_destination')->findOrFail($cash);
        $company = Company::query()->first();

        $methods_payment = PaymentMethodType::all()->transform(function ($row) {
            return [
                'id' => $row->id,
                'name' => $row->description,
                'sum' => 0
            ];
        });

        set_time_limit(0);

        $quantity_rows = 100; //$cash->cash_documents()->count();

        $global_payments = $cash->global_destination;

        //$global_payments->where('payment_type', SaleNotePayment::class);

        $data = [];
        foreach ($global_payments as $record) {
            if ($record->payment_type === DocumentPayment::class) {
                $document_payment = DocumentPayment::query()->find($record->payment_id);
                $document = $document_payment->document;
                $res = $this->validateStateTypeAndCurrencyType($document);
                if ($res['success']) {
                    $data['document_type'][] = [
                        'number' => $document->series.'-'.$document->number,
                        'data' => $record,
                        'total' => $res['total'],
                        'payment' => $document_payment->payment
                    ];
                }
            }
            if ($record->payment_type === SaleNotePayment::class) {
                $sale_note_payment = SaleNotePayment::query()->find($record->payment_id);
                $res = $this->validateStateTypeAndCurrencyType($sale_note_payment->sale_note);
                if ($res['success']) {
                    $data['sale_note_payment'][] = [
                        'data' => $record,
                        'total' => $res['total']
                    ];
                }
            }
            if ($record->payment_type === TechnicalServicePayment::class) {
                $technical_service_payment = TechnicalServicePayment::query()->find($record->payment_id);
                $res = $technical_service_payment->technical_service;
                $data['technical_service_payment'][] = [
                    'data' => $record,
                    'total' => $res->cost
                ];
            }
        }

        dd($data);

        $html = view('pos::cash.report_pdf_ticket',
            compact("cash", "company", "methods_payment"))->render();

        $width = 78;

        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [
                $width,
                190 +
                ($quantity_rows * 8)
            ],
            'margin_top' => 5,
            'margin_right' => 5,
            'margin_bottom' => 5,
            'margin_left' => 5
        ]);

        $pdf->WriteHTML($html);

        return $pdf->output('', 'S');
    }

    private function validateStateTypeAndCurrencyType($record)
    {
        if (in_array($record->state_type_id, ['01', '03', '05', '07', '13'])) {
            if ($record->currency_type_id === 'PEN') {
                $total = $record->total;
            } else {
                $total = $record->total * $record->exchange_rate_sale;
            }
            return [
                'success' => true,
                'total' => $total,
            ];
        }
        return [
            'success' => false
        ];
    }

    public function reportTicket($cash)
    {

        $temp = tempnam(sys_get_temp_dir(), 'cash_pdf_ticket');
        file_put_contents($temp, $this->getPdf($cash));

        return response()->file($temp);

    }


    public function reportExcel($cash)
    {

        $cash = Cash::findOrFail($cash);
        $company = Company::first();

        $methods_payment = collect(PaymentMethodType::all())->transform(function ($row) {
            return (object)[
                'id' => $row->id,
                'name' => $row->description,
                'sum' => 0
            ];
        });

        set_time_limit(0);
        $filename = "Reporte_POS - {$cash->user->name} - {$cash->date_opening} {$cash->time_opening}";

        return (new ReportCashExport)
            ->cash($cash)
            ->company($company)
            ->methods_payment($methods_payment)
            ->download($filename . '.xlsx');

    }


}
