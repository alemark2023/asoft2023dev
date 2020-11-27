@php
    $establishment = $document->establishment;
    $customer = $document->customer;
    //$path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');

    $document_number = $document->series.'-'.str_pad($document->number, 8, '0', STR_PAD_LEFT);
    $document_type_driver = App\Models\Tenant\Catalogs\IdentityDocumentType::findOrFail($document->driver->identity_document_type_id);
    $document_type_dispatcher = App\Models\Tenant\Catalogs\IdentityDocumentType::findOrFail($document->dispatcher->identity_document_type_id);

@endphp
<html>
<head>
    {{--<title>{{ $document_number }}</title>--}}
    {{--<link href="{{ $path_style }}" rel="stylesheet" />--}}
</head>
<body>
<table class="full-width">
    <tr>
        <td width="10%"></td>
        <td width="50%" class="pl-3"></td>
        <td width="40%" class="border-box p-4 text-center">
            <h4 class="text-center">{{ $document->document_type->description }}</h4>
            <h4>{{ 'RUC '.$company->number }}</h4>
            <h3 class="text-center">{{ $document_number }}</h3>
        </td>
    </tr>
</table>
<table class="full-width">
    <thead>
        <tr>
            <th class="text-left">DATOS DEL TRASLADO</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Fecha de emisión: {{ $document->date_of_issue->format('Y-m-d') }}</td>
            <td>Fecha de traslado: {{ $document->date_of_shipping->format('Y-m-d') }}</td>
        </tr>
        <tr>
            <td>Motivo de traslado: {{ $document->transfer_reason_type->description }}</td>
            <td>Modalidad de traslado: {{ $document->transport_mode_type->description }}</td>
        </tr>
        <tr>
            <td>Peso Bruto Total de la Guía: ({{ $document->unit_type_id }}) {{ $document->total_weight }} </td>
            <td>Documento: {{ $document_number }}</td>
        </tr>
    </tbody>
</table>
<table class="full-width">
    <thead>
        <tr>
            <th class="text-left">DATOS DEL DESTINATARIO</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Apellidos y Nombre o Razón Social: {{ $customer->name }}</td>
        </tr>
        <tr>
            <td>R.U.C. / DNI: {{ $customer->number }}</td>
        </tr>
        <tr>
            <td>Punto de partida: </td>
            <td>Punto de llegada: </td>
        </tr>
        <tr>
            <td class="border-box half-width">{{ $document->origin->location_id }} - {{ $document->origin->address }}</td>
            <td class="border-box half-width">{{ $document->delivery->location_id }} - {{ $document->delivery->address }}</td>
        </tr>
    </tbody>
</table>
<table class="full-width mt-10">
    <thead>
        <tr>
            <th class="text-left">DATOS DEL TRANSPORTE</th>
            <th class="text-left">DATOS DEL CONDUCTOR</th>
            <th class="text-left">DATOS DEL TRANSPORTISTA</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="border-box">Placa: {{ $document->license_plate }}</td>
            @if($document->driver->license)
            <td class="border-box">Licencia: {{ $document->driver->license }}</td>
            @else
            <td class="border-box"></td>
             @endif
            <td class="border-box">Empresa: </td>
        </tr>
        <tr>
            <td class="border-box"></td>
            <td class="border-box">Conductor: {{ $document->driver->number }}</td>
            <td class="border-box">{{ $document_type_dispatcher->description }}: {{ $document->dispatcher->number }}</td>
        </tr>
    </tbody>
</table>
<table class="full-width border-box mt-10 mb-10">
    <thead class="">
    <tr>
        <th class="border-top-bottom text-center">Código</th>
        <th class="border-top-bottom text-left">Descripción del Articulo</th>
        <th class="border-top-bottom text-center">Unidad de medida</th>
        <th class="border-top-bottom text-right">Cantidad</th>
        <th class="border-top-bottom text-center">Peso Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($document->items as $row)
        <tr>
            <td class="text-center">{{ $row->item->internal_id }}</td>
            <td class="text-left">{{ $row->item->description }}</td>
            <td class="text-center">{{ $row->item->unit_type_id }}</td>
            <td class="text-right">
                @if(((int)$row->quantity != $row->quantity))
                    {{ $row->quantity }}
                @else
                    {{ number_format($row->quantity, 0) }}
                @endif
            </td>
            <td class="text-center">0.000</td>
        </tr>
    @endforeach
    </tbody>
</table>
<table class="full-width mt-10 mb-10">
    <tr>
        <td width="60%">
            <h5 class="font-bold">OBSERVACION</h5>
            <h5>{{ $document->observations }}</h5>
            <h5 class="text-center">________________________________________________________</h5>
        </td>
        <td width="40%" class="border-box p-4 text-center">
            <h4 class="text-center">___________________________________</h4>
            <h4>___________________________________</h4>
            <h5 class="text-center">CONFORMIDAD DEL CLIENTE</h5>
            <h5>DNI ______________________________________</h5>
        </td>
    </tr>
</table>


</body>
</html>
