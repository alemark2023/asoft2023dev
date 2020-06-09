@php
    $customer = $document->customer;
    $establishment = \App\Models\Tenant\Establishment::first();
    $typeDocument = \App\Models\Tenant\CatIdentityDocumentTypes::where('id', $customer->codigo_tipo_documento_identidad)->first();
@endphp
<html>
    <head>
    </head>
<body>

@if($company->logo)
    <div class="text-center company_logo_box pt-5">
        <img src="data:{{mime_content_type(public_path("storage/uploads/logos/{$company->logo}"))}};base64, {{base64_encode(file_get_contents(public_path("storage/uploads/logos/{$company->logo}")))}}" alt="{{$company->name}}" class="company_logo_ticket contain">
    </div>
@endif
<table class="full-width">
    <tr>
        <td class="text-center"><h4>{{ $company->name }}</h4></td>
    </tr>
    <tr>
        <td class="text-center"><h5>{{ 'RUC '.$company->number }}</h5></td>
    </tr>
    <tr>
        <td class="text-center">
            {{ ($establishment->address !== '-')? $establishment->address : '' }}
            {{ ($establishment->district_id !== '-')? ', '.$establishment->district->description : '' }}
            {{ ($establishment->province_id !== '-')? ', '.$establishment->province->description : '' }}
            {{ ($establishment->department_id !== '-')? '- '.$establishment->department->description : '' }}
        </td>
    </tr>
    <tr>
        <td class="text-center">{{ ($establishment->email !== '-')? $establishment->email : '' }}</td>
    </tr>
    <tr>
        <td class="text-center pb-3">{{ ($establishment->telephone !== '-')? $establishment->telephone : '' }}</td>
    </tr>
    <tr>
        <td class="text-center pt-3 border-top"><h4>FACTURA</h4></td>
    </tr>
    <tr>
        <td class="text-center pb-3 border-bottom"><h3>{{ str_pad($document->id, 6, "0", STR_PAD_LEFT) }}</h3></td>
    </tr>
</table>
<table class="full-width">
    <tr>
        <td width="" class="pt-3"><p class="desc">F. Emisión:</p></td>
        <td width="" class="pt-3"><p class="desc">{{ now()->format('Y-m-d') }}</p></td>
    </tr>
    <tr>
        <td class="align-top"><p class="desc">Cliente:</p></td>
        <td><p class="desc">{{ $customer->apellidos_y_nombres_o_razon_social }}</p></td>
    </tr>
    @if(isset($typeDocument->description))
        <tr>
            <td><p class="desc">{{ $typeDocument->description }}:</p></td>
            <td><p class="desc">{{ $customer->numero_documento }}</p></td>
        </tr>
    @endif
    <tr>
        <td><p class="desc">Teléfono:</p></td>
        <td><p class="desc">{{ $customer->telefono }}</p></td>
    </tr>
    <tr>
        <td class="align-top"><p class="desc">Dirección:</p></td>
        <td>
            <p class="desc">
                {{ $customer->direccion }}
            </p>
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
    <tr>
        <th class="border-top-bottom desc-9 text-left">CANT.</th>
        <th colspan="2" class="border-top-bottom desc-9 text-left">DESCRIPCIÓN</th>
        <th class="border-top-bottom desc-9 text-right">P.UNIT</th>
        <th class="border-top-bottom desc-9 text-right">TOTAL</th>
    </tr>
    </thead>
    <tbody>
    @foreach($document->items as $row)
        <tr>
            <td class="text-center desc-9 align-top">{{ (int)$row->cantidad }}</td>
            <td colspan="2" class="text-left desc-9 align-top">{{ $row->description }}</td>
            <td class="text-right desc-9 align-top">{{ (isset($row->currency_type->symbol)) ? $row->currency_type->symbol : '' }} {{ number_format($row->sale_unit_price, 2) }}</td>
            <td class="text-right desc-9 align-top">S/ {{ number_format($row->sub_total, 2) }}</td>
        </tr>
        <tr>
            <td colspan="5" class="border-bottom"></td>
        </tr>
    @endforeach
        <!-- tr>
            <td colspan="4" class="text-right font-bold desc">IGV: S/</td>
            <td class="text-right font-bold desc">{{ number_format($document->total_igv, 2) }}</td>
        </tr -->
        <tr>
            <td colspan="4" class="text-right font-bold desc">TOTAL A PAGAR: S/</td>
            <td class="text-right font-bold desc">{{ number_format($document->total, 2) }}</td>
        </tr>
    </tbody>
</table>
<table class="full-width">
    <tr>

        @foreach(array_reverse((array) $document->legends) as $row)
            <tr>
                @if ($row->code == "1000")
                    <td class="desc pt-3">Son: <span class="font-bold">{{ $row->value }} {{ $document->currency_type->description }}</span></td>
                    @if (count((array) $document->legends)>1)
                    <tr><td class="desc pt-3"><span class="font-bold">Leyendas</span></td></tr>
                    @endif
                @else
                    <td class="desc pt-3">{{$row->code}}: {{ $row->value }}</td>
                @endif
            </tr>
        @endforeach
    </tr>


</table>
<table class="full-width">
    <tr><td><strong>PAGOS:</strong> </td></tr>
    @php
        $payment = 0;
    @endphp
    <tr><td>- {{ now() }} - {{ $document->reference_payment }} - S/ {{ number_format($document->total - $payment, 2) }}</td></tr>
    <tr><td><strong>SALDO:</strong> S/ {{ number_format($document->total - $payment, 2) }}</td></tr>
</table>
</body>
</html>
