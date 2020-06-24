@php
    $establishment = $document->establishment;
    $customer = $document->customer;

    $document_number = $document->prefix.'-'.str_pad($document->id, 8, '0', STR_PAD_LEFT);
    $document_type_driver = App\Models\Tenant\Catalogs\IdentityDocumentType::findOrFail($document->driver->identity_document_type_id);
@endphp
<html>
<head>
</head>
<body>
<table class="full-width">
    <tr>
        @if($company->logo)
            <td width="10%">
                <img src="data:{{mime_content_type(public_path("storage/uploads/logos/{$company->logo}"))}};base64, {{base64_encode(file_get_contents(public_path("storage/uploads/logos/{$company->logo}")))}}" alt="{{$company->name}}" alt="{{ $company->name }}"  class="company_logo" style="max-width: 300px">
            </td>
        @else
            <td width="10%">
                {{--<img src="{{ asset('logo/logo.jpg') }}" class="company_logo" style="max-width: 150px">--}}
            </td>
        @endif
        <td width="50%" class="pl-3">
            <div class="text-left">
                <h3 class="">{{ $company->name }}</h3>
                <h4>{{ 'RUC '.$company->number }}</h4>
                <h5>
                    {{ ($establishment->address !== '-')? $establishment->address : '' }}
                    {{ ($establishment->district_id !== '-')? ', '.$establishment->district->description : '' }}
                    {{ ($establishment->province_id !== '-')? ', '.$establishment->province->description : '' }}
                    {{ ($establishment->department_id !== '-')? '- '.$establishment->department->description : '' }}
                </h5>
                <h5>{{ ($establishment->email !== '-')? $establishment->email : '' }}</h5>
                <h5>{{ ($establishment->telephone !== '-')? $establishment->telephone : '' }}</h5>
            </div>
        </td>
        <td width="40%" class="border-box p-4 text-center">
            <h4 class="text-center">ORDEN DE PEDIDO</h4>
            <h3 class="text-center">{{ $document_number }}</h3>
        </td>
    </tr>
</table>
<table class="full-width border-box mt-10 mb-10">
    <thead>
    <tr>
        <th class="border-bottom text-left">DESTINATARIO</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Razón Social: {{ $customer->name }}</td>
    </tr>
    <tr>
        <td>RUC: {{ $customer->number }}
                 {{ ($customer->district_id !== '-')? ', '.$customer->district->description : '' }}
                 {{ ($customer->province_id !== '-')? ', '.$customer->province->description : '' }}
                 {{ ($customer->department_id !== '-')? '- '.$customer->department->description : '' }}
        </td>
    </tr>
    <tr>
        <td>Dirección: {{ $customer->address }}</td>
    </tr>
    </tbody>
</table>
<table class="full-width border-box mt-10 mb-10">
    <thead>
    <tr>
        <th class="border-bottom text-left" colspan="2">ENVIO</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Fecha Emisión: {{ $document->date_of_issue->format('Y-m-d') }}</td>
        <td>Fecha Inicio de Traslado: {{ $document->date_of_shipping->format('Y-m-d') }}</td>
    </tr>
    <tr>
        <td>Motivo Traslado: {{ $document->transfer_reason_type->description }}</td>
        <td>Modalidad de Transporte: {{ $document->transport_mode_type->description }}</td>
    </tr>
    <tr>
        <td>Peso Bruto Total({{ $document->unit_type_id }}): {{ $document->total_weight }}</td>
        <td>Número de Bultos: {{ $document->packages_number }}</td>
    </tr>
    <tr>
        <td>P.Partida: {{ $document->origin->location_id[2] }} - {{ $document->origin->address }}</td>
        <td>P.Llegada: {{ $document->delivery->location_id[2] }} - {{ $document->delivery->address }}</td>
    </tr>
    </tbody>
</table>

<table class="full-width border-box mt-10 mb-10">
    <thead>
    <tr>
        <th class="border-bottom text-left" colspan="2">TRANSPORTE</th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td>Nombre y/o razón social: {{ $document->dispatcher->name }}</td>
            <td>{{ $document->dispatcher->identity_document_type->description }}: {{ $document->dispatcher->number }}</td>
        </tr>
        <tr>
            <td>Conductor: {{ $document->driver->name }} - {{ $document->driver->number }}</td>
            <td>Licencia: {{ $document->driver->license }}</td>
        </tr>
        <tr>
            <td>N° placa del vehiculo: {{ $document->license_plates->license_plate_1 }}</td>
            <td>N° registro: {{ $document->license_plates->register_number_1 }}</td>
        </tr>
        <tr>
            <td>N° placa semirremolque: {{ $document->license_plates->license_plate_2 }}</td>
            <td>N° registro: {{ $document->license_plates->register_number_2 }}</td>
        </tr>
    </tbody>
</table>

<table class="full-width border-box mt-10 mb-10">
    <thead class="">
    <tr>
        <th class="border-top-bottom text-center">Item</th>
        <th class="border-top-bottom text-center">Código</th>
        <th class="border-top-bottom text-left">Descripción</th>
        <th class="border-top-bottom text-center">Unidad</th>
        <th class="border-top-bottom text-right">Cantidad</th>
    </tr>
    </thead>
    <tbody>
    @foreach($document->items as $row)
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
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
        </tr>
    @endforeach
    </tbody>
</table>
<table class="full-width border-box mt-10 mb-10">
    <tr>
        <td class="text-bold border-bottom font-bold">OBSERVACIONES</td>
    </tr>
    <tr>
        <td>{{ $document->observations }}</td>
    </tr>
</table>

</body>
</html>
