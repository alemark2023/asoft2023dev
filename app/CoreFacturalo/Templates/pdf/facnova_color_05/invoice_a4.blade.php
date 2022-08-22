@php
    $establishment = $document->establishment;
    $customer = $document->customer;
    $invoice = $document->invoice;
    $document_base = ($document->note) ? $document->note : null;

    //$path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
    $document_number = $document->series.'-'.str_pad($document->number, 8, '0', STR_PAD_LEFT);
    //$accounts = \App\Models\Tenant\BankAccount::all();

    if($document_base) {

        $affected_document_number = ($document_base->affected_document) ? $document_base->affected_document->series.'-'.str_pad($document_base->affected_document->number, 8, '0', STR_PAD_LEFT) : $document_base->data_affected_document->series.'-'.str_pad($document_base->data_affected_document->number, 8, '0', STR_PAD_LEFT);

    } else {

        $affected_document_number = null;
    }

    $payments = $document->payments;

    $document->load('reference_guides');

    $total_payment = $document->payments->sum('payment');
    $balance = ($document->total - $total_payment) - $document->payments->sum('change');

    $bank_accounts = (new \App\CoreFacturalo\HelperFacturalo())->bank_accounts_format();

    //calculate items
    $allowed_items = 94 - (\App\Models\Tenant\BankAccount::all()->count())*2;
    $quantity_items = $document->items()->count();
    $cycle_items = $allowed_items - ($quantity_items * 3);
    $total_weight = 0;

    $configuration = \App\Models\Tenant\Configuration ::query()->first();

    $additional_data = $document->additional_data;

@endphp
<html>
<head>
    {{--<title>{{ $document_number }}</title>--}}
    {{--<link href="{{ $path_style }}" rel="stylesheet" />--}}
</head>
<body>
@if($document->state_type->id == '11')
    <div class="company_logo_box" style="position: absolute; text-align: center; top:30%;">
        <img
            src="data:{{mime_content_type(public_path("status_images".DIRECTORY_SEPARATOR."anulado.png"))}};base64, {{base64_encode(file_get_contents(public_path("status_images".DIRECTORY_SEPARATOR."anulado.png")))}}"
            alt="anulado" class="" style="opacity: 0.6;">
    </div>
@endif
<table class="full-width">
    <tr style="">
        <td width="70%" class="pl-3">
            @if($company->logo)
            <div class="border-bo2ex" style="position:absolute; top:40px; width: 340px;">
            <img src="data:{{mime_content_type(public_path("storage/uploads/logos/{$company->logo}"))}};base64, {{base64_encode(file_get_contents(public_path("storage/uploads/logos/{$company->logo}")))}}"
                alt="{{$company->name}}" class="company_logo" style="max-height: 100px; top: -5px; position: absolute;">
            </div>
            @endif
            <div class="border-bo3x" style="position:absolute; top:135px;  width: 500px;">
                {{ ($establishment->address !== '-')? $establishment->address : '' }}
                            {{ ($establishment->district_id !== '-')? ', '.$establishment->district->description : '' }}
                            {{ ($establishment->province_id !== '-')? ', '.$establishment->province->description : '' }} <br>

                {{ $establishment->telephone }}<br>
               {{ $establishment->web_address }}{{ ' Email: '.$establishment->email }}
            </div>
        </td>
        <td style="padding: 0!important;" class="align-top" width="30%" class="pl-3">
            <table class="full-width border-box" style="height:150px">
                <tr><td class="text-center" style="padding-top: 15px; padding-bottom: 10px;font-size: 16px;">{{ 'R.U.C. '.$company->number }}</td></tr>
                <tr><td class="text-center font-bold font-xlg bg-template" style="padding: 10px 0">{{ $document->document_type->description }}</td></tr>
                <tr><td class="text-center" style="padding-top: 12px; padding-bottom: 8px; font-size: 18px;">{{ $document_number }}</td></tr>
            </table>
        </td>
    </tr>
</table>

<table class="full-width" style="margin-top: 25px;">
    <tr>
        <td style="width: 65%; padding: 0!important;" class="align-top">
            <table class="full-width">
                <tr><td>RAZÓN SOCIAL</td><td style="width: 5px;">:</td><td class="text-upp">{{ $customer->name }}</td></tr>
                <tr><td>{{$customer->identity_document_type->description}}</td><td>:</td><td>{{ $customer->number }}</td></tr>
                @if ($customer->address !== '')
                <tr><td class="align-top">DIRECCIÓN</td><td class="align-top" style="width: 5px;">:</td><td class="text-upp">
                    {{ $customer->address }}
                    {{ ($customer->district_id !== '-')? ', '.$customer->district->description : '' }}
                    {{ ($customer->province_id !== '-')? ', '.$customer->province->description : '' }}
                    {{ ($customer->department_id !== '-')? '- '.$customer->department->description : '' }}
                </td></tr>
                @endif
                <tr><td>F.EMISIÓN</td><td style="width: 5px;">:</td><td>{{ (new \App\CoreFacturalo\HelperFacturalo())->date_of_issue_format($document->date_of_issue) }}</td></tr>
{{--                <tr><td>HORA EMISIÓN</td><td style="width: 5px;">:</td><td>{{ $document->time_of_issue }}</td></tr>--}}

    <tr>
    @if ($document->detraction)
        <td width="140px">B/S SUJETO A DETRACCIÓN</td>
        <td width="8px">:</td>
        @inject('detractionType', 'App\Services\DetractionTypeService')
        <td width="220px">{{$document->detraction->detraction_type_id}} - {{ $detractionType->getDetractionTypeDescription($document->detraction->detraction_type_id ) }}</td>

    @endif
    </tr>

    <tr>
        @if ($document->detraction)

            <td width="120px">N. CTA DETRACCIONES</td>
            <td width="8px">:</td>
            <td>{{ $document->detraction->bank_account}}</td>
        @endif
    </tr>
                @if(!is_null($document_base))
                <tr><td>DOC.AFECTADO</td><td style="width: 5px;">:</td><td>{{ $affected_document_number }}</td></tr>
                <tr><td>TIPO DE NOTA</td><td style="width: 5px;">:</td><td>{{ ($document_base->note_type === 'credit')?$document_base->note_credit_type->description:$document_base->note_debit_type->description }}</td></tr>
                <tr><td>DESCRIPCIÓN</td><td style="width: 5px;">:</td><td class="text-upp">{{ $document_base->note_description }}</td></tr>
                @endif
                @if($document->guides)
                <tr><td>GUÍA REMISIÓN</td><td style="width: 5px;">:</td><td>
                    @foreach ($document->guides as $item)
                        {{ $item->document_type_description }}: {{ $item->number }}<br/>
                    @endforeach
                </td></tr>
                @endif
                @if ($document->prepayments)
                <tr><td>ANTICIPOS</td><td style="width: 5px;">:</td><td>
                    @foreach($document->prepayments as $p)
                        {{$p->number}}<br/>
                    @endforeach
                </td></tr>
                @endif

            </table>
        </td>
        <td style="width: 35%; padding: 0!important;" class="align-top">
            <table class="full-width">
{{--                @if($invoice)--}}
{{--                <tr><td>F.VENCIMIENTO</td><td style="width: 5px;">:</td><td>{{ $invoice->date_of_due->format('d/m/Y') }}</td></tr>--}}
{{--                @endif--}}
                <tr><td>MONEDA</td><td style="width: 5px;">:</td><td class="text-upp">{{ $document->currency_type->description }}</td></tr>
                @if($document->purchase_order)
                <tr><td class="align-top">ORDEN DE COMPRA</td><td class="align-top" style="width: 5px;">:</td><td>{{ $document->purchase_order }}</td></tr>
                @endif
                @if($document->payments()->count() > 0)
                <tr><td>FORMA DE PAGO</td><td style="width: 5px;">:</td><td>{{ $document->payments()->first()->payment_method_type->description }} - {{ $document->currency_type_id }} {{ $document->payments()->first()->payment }}</td></tr>
                @endif
{{--                <tr><td class="align-top">CONDICIÓN DE PAGO</td><td class="align-top" style="width: 5px;">:</td><td>{{ $document->payment_condition->name }}</td></tr>--}}
{{--                @if(!is_null($additional_data) && $additional_data->condicion_de_pago)--}}
                <tr><td class="align-top">CONDICIÓN DE PAGO</td><td class="align-top" style="width: 5px;">:</td>
                    <td class="align-top">
                        @if(!is_null($additional_data) && $additional_data->condicion_de_pago)
                            {{ $additional_data->condicion_de_pago }}
                        @else
                            {{ $document->payment_condition->name }}
                        @endif
                    </td>
                </tr>
{{--                @endif--}}
                <tr><td class="align-top">VENDEDOR</td><td class="align-top" style="width: 5px;">:</td>
                    <td>
                        @if(!is_null($additional_data) && $additional_data->nombre_del_vendedor)
                            {{ $additional_data->nombre_del_vendedor }}
                        @else
                            {{ $document->user->name }}
                        @endif
                    </td>
                </tr>
    <tr>
        @if ($document->detraction)

            <td width="120px">P. DETRACCIÓN</td>
            <td width="8px">:</td>
            <td>{{ $document->detraction->percentage}}%</td>
        @endif
    </tr>
    <tr>
        @if ($document->detraction)
            <td width="120px">MONTO DETRACCIÓN</td>
            <td width="8px">:</td>
            <td>S/ {{ $document->detraction->amount}}</td>
        @endif
    </tr>
            </table>
        </td>
    </tr>
</table>
<table class="full-width" style="margin-top: 10px;">
    <thead>
    <tr class="">
        <th style="width: 70px;" class="border-top-bottom text-center py-1 cell-solid-rl bg-template">CÓDIGO</th>
        <th style="width: 50px;" class="border-top-bottom text-center py-1 cell-solid-r bg-template">CANT.</th>
        <th style="width: 50px;" class="border-top-bottom text-center py-1 cell-solid-r bg-template">UND.<br/>MED.</th>
        <th style=""    class="border-top-bottom text-center py-1 cell-solid-r bg-template">DESCRIPCIÓN</th>
        <th style="width: 70px;" class="border-top-bottom text-center py-1 cell-solid-r bg-template">PRECIO<br/>DE VENTA<br/>UNITARIO</th>
        <th style="width: 50px;" class="border-top-bottom text-center py-1 cell-solid-r bg-template">DSCTO.</th>
        <th style="width: 70px;" class="border-top-bottom text-center py-1 cell-solid-r bg-template">PRECIO<br/>DE VENTA<br/>TOTAL</th>
    </tr>
    </thead>
    <tbody class="">
    @foreach($document->items as $row)
        <tr>
            <td class="text-center align-top cell-solid-rl">{{ $row->item->internal_id }}</td>
            <td class="text-center align-top cell-solid-r">
                @if(((int)$row->quantity != $row->quantity))
                    {{ $row->quantity }}
                @else
                    {{ number_format($row->quantity, 0) }}
                @endif
            </td>
            <td class="text-center align-top cell-solid-r">{{ $row->item->unit_type_id }}</td>
            <td class="text-left align-top cell-solid-r">
                @if($row->name_product_pdf)
                    {!!$row->name_product_pdf!!}
                @else
                    {!!$row->item->description!!}
                @endif
                @if (!empty($row->item->presentation)) {!!$row->item->presentation->description!!} @endif
                @if($row->attributes)
                    @foreach($row->attributes as $attr)
                        @if($attr->attribute_type_id === '5032')
                            @php
                                $total_weight += $attr->value * $row->quantity;
                            @endphp
                        @endif
                        <br/><span style="font-size: 9px">{!! $attr->description !!} : {{ $attr->value }}</span>
                    @endforeach
                @endif
                @if($row->item->is_set == 1)
                    <br/>
                    @inject('itemSet', 'App\Services\ItemSetService')
                    {{join( "-", $itemSet->getItemsSet($row->item_id) )}}
                @endif
            </td>
            <td class="text-right align-top cell-solid-r">{{ number_format($row->unit_price, 2) }}</td>
            <td class="text-right align-top cell-solid-r">
                @if($row->discounts)
                    @php
                        $total_discount_line = 0;
                        foreach ($row->discounts as $disto) {
                            $total_discount_line = $total_discount_line + $disto->amount;
                        }
                    @endphp
                    {{ number_format($total_discount_line, 2) }}
                @endif
            </td>
            <td class="text-right align-top cell-solid-r">{{ number_format($row->total, 2) }}</td>
        </tr>
    @endforeach
    @if ($document->prepayments)
        @foreach($document->prepayments as $p)
        <tr>
            <td class="text-center align-top"></td>
            <td class="text-center align-top">1</td>
            <td class="text-center align-top">NIU</td>
            <td class="text-left align-top">
                ANTICIPO: {{($p->document_type_id == '02')? 'FACTURA':'BOLETA'}} NRO. {{$p->number}}
            </td>
            <td class="text-right align-top">-{{ number_format($p->total, 2) }}</td>
            <td class="text-right align-top">
                0
            </td>
            <td class="text-right align-top">-{{ number_format($p->total, 2) }}</td>
        </tr>
        {{--
        <tr>
            <td colspan="7" class="border-bottom"></td>
        </tr>--}}
        @endforeach
    @endif
    @for($i = 0; $i < $cycle_items; $i++)
    <tr>
        <td class="cell-solid-rl"></td>
        <td class="cell-solid-r"></td>
        <td class="cell-solid-r"></td>
        <td class="cell-solid-r"></td>
        <td class="cell-solid-r"></td>
        <td class="cell-solid-r"></td>
        <td class="cell-solid-r"></td>
    </tr>
    @endfor

    <tr>
        <td colspan="4" class="cell-solid-rl cell-solid-top align-top" style="padding: 0!important;" rowspan="7">
            <table class="full-width">
                <tr>
                    <td class="align-top">
                        @if(!is_null($document->additional_information) && count($document->additional_information) > 0)
                        OBSERVACIONES:
                        @foreach($document->additional_information as $information)
                            @if ($information)
                                <br/>{{ $information }}
                            @endif
                        @endforeach
                        @endif
                    </td>
                    <td style="width: 96px" class="text-center">
                        <img src="data:image/png;base64, {{ $document->qr }}" class="p-0 m-0" style="width: 90px;"/><br>
                    </td>
                </tr>
            </table>
        </td>
        <td class="text-left cell-solid-r cell-solid-top" colspan="2">TOTAL ANTICIPO</td>
        <td class="cell-solid-r cell-solid-top align-middle pa-none" style="padding: 0!important;">
            <table class="full-width"><tr><td class="text-left">{{$document->currency_type->symbol}}</td><td class="text-right">{{ number_format($document->total_prepayment, 2) }}</td></tr></table>
        </td>
    </tr>
    <tr>
        <td class="text-left cell-solid-r cell-solid-top" colspan="2">OP. GRAVADA</td>
        <td class="cell-solid-r cell-solid-top align-middle pa-none" style="padding: 0!important;">
            <table class="full-width"><tr><td class="text-left">{{$document->currency_type->symbol}}</td><td class="text-right">{{ number_format($document->total_taxed, 2) }}</td></tr></table>
        </td>
    </tr>
    <tr>
        <td class="text-left cell-solid-r cell-solid-top" colspan="2">OP. INAFECTAS</td>
        <td class="cell-solid-r cell-solid-top align-middle pa-none" style="padding: 0!important;">
            <table class="full-width"><tr><td class="text-left">{{$document->currency_type->symbol}}</td><td class="text-right">{{ number_format($document->total_unaffected, 2) }}</td></tr></table>
        </td>
    </tr>
    <tr>
        <td class="text-left cell-solid-r cell-solid-top" colspan="2">OP. EXONERADAS</td>
        <td class="cell-solid-r cell-solid-top align-middle pa-none" style="padding: 0!important;">
            <table class="full-width"><tr><td class="text-left">{{$document->currency_type->symbol}}</td><td class="text-right">{{ number_format($document->total_exonerated, 2) }}</td></tr></table>
        </td>
    </tr>
    <tr>
        <td class="text-left cell-solid-r cell-solid-top" colspan="2">OP. GRATUITAS</td>
        <td class="cell-solid-r cell-solid-top align-middle pa-none" style="padding: 0!important;">
            <table class="full-width"><tr><td class="text-left">{{$document->currency_type->symbol}}</td><td class="text-right">{{ number_format($document->total_free, 2) }}</td></tr></table>
        </td>
    </tr>
    <tr>
        <td class="text-left cell-solid-r cell-solid-top" colspan="2">TOTAL DCTOS.</td>
        <td class="cell-solid-r cell-solid-top align-middle pa-none" style="padding: 0!important;">
            <table class="full-width"><tr><td class="text-left">{{$document->currency_type->symbol}}</td><td class="text-right">{{ number_format($document->total_discount, 2) }}</td></tr></table>
        </td>
    </tr>
    <tr>
        <td class="text-left cell-solid-r cell-solid-top" colspan="2">I.G.V.</td>
        <td class="cell-solid-r cell-solid-top align-middle pa-none" style="padding: 0!important;">
            <table class="full-width"><tr><td class="text-left">{{$document->currency_type->symbol}}</td><td class="text-right">{{ number_format($document->total_igv, 2) }}</td></tr></table>
        </td>
    </tr>
    <tr>
        <td colspan="4" class="cell-solid-rl cell-solid-top">HASH: {{ $document->hash }}</td>
        <td class="text-left cell-solid-r cell-solid-top" colspan="2">ICBPER</td>
        <td class="cell-solid-r cell-solid-top align-middle pa-none" style="padding: 0!important;">
            <table class="full-width"><tr><td class="text-left">{{$document->currency_type->symbol}}</td><td class="text-right">{{ number_format($document->total_plastic_bag_taxes, 2) }}</td></tr></table>
        </td>
    </tr>
    <tr>
        <td colspan="4" class="cell-solid-rl cell-solid-top cell-solid-bottom text-upp">
            SON:
            @foreach(array_reverse( (array) $document->legends) as $row)
                @if ($row->code == "1000")
                    {{ $row->value }} {{ $document->currency_type->description }}
                @else
                    {{$row->code}}: {{ $row->value }}
                @endif
            @endforeach
        </td>
        <td class="text-left cell-solid-r cell-solid-top cell-solid-bottom" colspan="2">TOTAL A PAGAR</td>
        <td class="cell-solid-r cell-solid-top cell-solid-bottom align-middle" style="padding: 0!important;">
            <table class="full-width"><tr><td class="text-left">{{$document->currency_type->symbol}}</td><td class="text-right">{{ number_format($document->total, 2) }}</td></tr></table>
        </td>
    </tr>
    </tbody>
</table>
@if(count($bank_accounts) > 0)
<table class="full-width border-box" style="margin-top: 10px;">
    <tbody>
    <tr>
        <td class="cell-solid-r cell-solid-bottom">BANCOS</td>
        <td class="cell-solid-r cell-solid-bottom">DÓLARES</td>
        <td class="cell-solid-r cell-solid-bottom">SOLES</td>
    </tr>
    @foreach($bank_accounts as $account)
        <tr>
            <td class="text-left cell-solid-r cell-solid-bottom align-top">{{ $account['bank_name'] }}</td>
            <td class="text-left cell-solid-r cell-solid-bottom">
                @foreach($account['bank_accounts_usd'] as $row)
                    {{ $row['number'] }}
                    @if($row['cci'] !== '' && !is_null($row['cci']))
                        <br/>CCI: {{ $row['cci'] }}
                    @endif
                @endforeach
            </td>
            <td class="text-left cell-solid-bottom">
                @foreach($account['bank_accounts_pen'] as $row)
                    {{ $row['number'] }}
                    @if($row['cci'] !== '' && !is_null($row['cci']))
                        <br/>CCI: {{ $row['cci'] }}
                    @endif
                @endforeach
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endif
{{--@include('template::partials.payment_condition')--}}
@if($configuration->terms_condition_sale)
    <table style="margin-top: 10px;">
        <tr><td>TÉRMINOS Y CONDICIONES:</td></tr>
        <tr><td>{!! $configuration->terms_condition_sale !!}</td></tr>
    </table>
@endif
</body>
</html>
