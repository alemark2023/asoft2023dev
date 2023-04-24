@php
    $establishment = $document->establishment;
    $customer = $document->customer;
    $invoice = $document->invoice;
    $document_base = ($document->note) ? $document->note : null;

    //$path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
    $document_number = $document->series.'-'.str_pad($document->number, 8, '0', STR_PAD_LEFT);
    $accounts = \App\Models\Tenant\BankAccount::all();

    if($document_base) {

        $affected_document_number = ($document_base->affected_document) ? $document_base->affected_document->series.'-'.str_pad($document_base->affected_document->number, 8, '0', STR_PAD_LEFT) : $document_base->data_affected_document->series.'-'.str_pad($document_base->data_affected_document->number, 8, '0', STR_PAD_LEFT);

    } else {

        $affected_document_number = null;
    }

    $payments = $document->payments;

    // $document->load('reference_guides');

    // $total_payment = $document->payments->sum('payment');
    // $balance = ($document->total - $total_payment) - $document->payments->sum('change');

@endphp

<body>
<br><br><br>
<table class="full-width pb-0">
    <tr>
        <td width="60%" class=""></td>
        <td width="40%" class="px-4 pb-0 text-center" style="padding-top: 30px;">
            <br>
            <br>
            <br>
            <br>
            <br>
            <div class="text-center text-bold font-bold" style="font-family: 'Lucida Console', arial;">{{ $document_number }}</div>
        </td>
    </tr>
</table>
<table class="p-0 text-bold font-bold" style="font-family: 'Lucida Console', arial;">
    <tr>
        <td>
            <p>Fecha de Emisión:</p>
            <p>{{ $document->date_of_issue->format('Y-m-d') }}</p>
        </td>
    </tr>
</table>

<table class="full-width p-0 text-bold font-bold" style="font-family: 'Lucida Console', arial;">
    <tr>
        <td width="6%">
            <br><br><br><br>
        </td>
        <td width="38%" class="align-top" style="text-transform: uppercase; line-height: 20px;">
            @php
                $district = \App\Models\Tenant\Catalogs\District::find($document->origin->location_id);
            @endphp
            {{ $document->origin->location_id }} - {{ $document->origin->address }}. {{ $district->description }}, {{ $district->province->description }}, {{ $district->province->department->description }}.
        </td>
        <td width="10%">
        </td>
        <td width="40%" class="align-top" style="text-transform: uppercase; line-height: 20px;">
            @php
                $district = \App\Models\Tenant\Catalogs\District::find($document->delivery->location_id);
            @endphp
            {{ $document->delivery->location_id }} - {{ $document->delivery->address }}. {{ $district->description }}, {{ $district->province->description }}, {{ $district->province->department->description }}.
        </td>
    </tr>
</table>
<table class="full-width p-0 mt-1 text-bold font-bold" style="font-family: 'Lucida Console', arial;">
    <tr>
        <td width="15%">
            <br><br><br>
        </td>
        <td width="40%" class="align-top pt-3">
            {{ $document->date_of_shipping->format('Y-m-d') }}
        </td>
        <td width="35%" colspan="2" class="align-top pt-2">
            <br>
            <p>{{ $customer->name }}</p>
        </td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td>{{ $customer->identity_document_type_id == 6 ? $customer->number : '' }}</td>
        <td>{{ $customer->identity_document_type_id == 1 ? $customer->number : '' }}</td>
    </tr>
</table>
<table class="full-width p-0 mt-4 text-bold font-bold" style="font-family: 'Lucida Console', arial;">
    <tr>
        <td width="15%">
            <br><br><br><br><br>
        </td>
        <td width="35%" class="align-top pt-2">
            {{ $document->license_plate }}
            <br><br><br>
            {{ $document->transport_mode_type_id === '02'?$document->driver->license:'' }}
        </td>
        <td width="10%">
        </td>
        <td width="40%" class="align-top pt-2">
            {{ $document->transport_mode_type_id === '01'?$document->dispatcher->name:'' }}
            <br><br><br>
            {{ $document->transport_mode_type_id === '01'?$document->dispatcher->number:'' }}
        </td>
    </tr>
</table>

</body>
