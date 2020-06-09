@php
    $establishment = \App\Models\Tenant\Establishment::first();
    $customer = $document->customer;
    $typeDocument = \App\Models\Tenant\CatIdentityDocumentTypes::where('id', $customer->codigo_tipo_documento_identidad)->first();
@endphp
<html>
    <head>
    </head>
<body>
<table class="full-width">
    <tr>
        @if($company->logo)
            <td width="20%">
                <div class="company_logo_box">
                    <img src="data:{{mime_content_type(public_path("storage/uploads/logos/{$company->logo}"))}};base64, {{base64_encode(file_get_contents(public_path("storage/uploads/logos/{$company->logo}")))}}" alt="{{$company->name}}" class="company_logo" style="max-width: 150px;">
                </div>
            </td>
        @else
            <td width="20%">
            </td>
        @endif
        <td width="50%" class="pl-3">
            <div class="text-left">
                <h4 class="">{{ $company->name }}</h4>
                <h5>{{ 'RUC '.$company->number }}</h5>
                <h6>
                    {{ ($establishment->address !== '-')? $establishment->address : '' }}
                    {{ ($establishment->district_id !== '-')? ', '.$establishment->district->description : '' }}
                    {{ ($establishment->province_id !== '-')? ', '.$establishment->province->description : '' }}
                    {{ ($establishment->department_id !== '-')? '- '.$establishment->department->description : '' }}
                </h6>
                <h6>{{ ($establishment->email !== '-')? $establishment->email : '' }}</h6>
                <h6>{{ ($establishment->telephone !== '-')? $establishment->telephone : '' }}</h6>
            </div>
        </td>
        <td width="30%" class="border-box py-4 px-2 text-center">
            <h5 class="text-center">FACTURA</h5>
            <h3 class="text-center">{{ str_pad($document->id, 6, "0", STR_PAD_LEFT) }}</h3>
        </td>
    </tr>
</table>
<table class="full-width mt-5">
    <tr>
        <td width="15%">Cliente:</td>
        <td width="45%">{{ $customer->apellidos_y_nombres_o_razon_social }}</td>
        <td width="25%">Fecha de emisión:</td>
        <td width="15%">{{ now()->format('Y-m-d') }}</td>
    </tr>
    @if(isset($typeDocument->description))
        <tr>
            <td><p class="desc">{{ $typeDocument->description }}:</p></td>
            <td><p class="desc">{{ $customer->numero_documento }}</p></td>
        </tr>
    @endif
    <tr>
        <td>Teléfono:</td>
        <td>{{ $customer->telefono }}</td>
    </tr>
    <tr>
        <td class="align-top">Dirección:</td>
        <td colspan="3">
            {{ $customer->direccion }}
        </td>
    </tr>
    @if(isset($document->items[0]->exchange_rate_sale))
        <tr>
            <td><p class="desc">T/C:</p></td>
            <td><p class="desc">{{ $document->items[0]->exchange_rate_sale }}</p></td>
        </tr>
    @endif
</table>

<table class="full-width mt-10 mb-10">
    <thead class="">
    <tr class="bg-grey">
        <th class="border-top-bottom text-center py-2" width="8%">CANT.</th>
        <th colspan="5" class="border-top-bottom text-left py-2">DESCRIPCIÓN</th>
        <th class="border-top-bottom text-right py-2" width="12%">P.UNIT</th>
        <th class="border-top-bottom text-right py-2" width="12%">TOTAL</th>
    </tr>
    </thead>
    <tbody>
    @foreach($document->items as $row)
        <tr>
            <td class="text-center align-top">{{ (int)$row->cantidad }}</td>
            <td colspan="5" class="text-left">{{ $row->description }}</td>
            <td class="text-right align-top">{{ (isset($row->currency_type->symbol)) ? $row->currency_type->symbol : '' }} {{ number_format($row->sale_unit_price, 2) }}</td>
            <td class="text-right align-top">S/ {{ number_format($row->sub_total, 2) }}</td>
        </tr>
        <tr>
            <td colspan="8" class="border-bottom"></td>
        </tr>
    @endforeach
        <!-- tr>
            <td colspan="7" class="text-right font-bold">IGV: S/</td>
            <td class="text-right font-bold">{{ number_format($document->total_igv, 2) }}</td>
        </tr -->
        <tr>
            <td colspan="7" class="text-right font-bold">TOTAL A PAGAR: S/</td>
            <td class="text-right font-bold">{{ number_format($document->total, 2) }}</td>
        </tr>
    </tbody>
</table>
<table class="full-width">
<tr>
    <td><strong>PAGOS:</strong> </td>
</tr>
@php
    $payment = 0;
@endphp
<tr><td>- {{ now() }} - {{ $document->reference_payment }} - S/ {{ number_format($document->total - $payment, 2) }}</td></tr>
<tr><td><strong>SALDO:</strong> S/ {{ number_format($document->total - $payment, 2) }}</td></tr>

</table>
</body>
</html>
