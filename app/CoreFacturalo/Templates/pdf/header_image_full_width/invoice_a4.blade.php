@php
    use App\CoreFacturalo\Helpers\Template\TemplateHelper;$establishment = $document->establishment;
    $customer = $document->customer;
    $invoice = $document->invoice;
    $document_base = ($document->note) ? $document->note : null;

    //$path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
    $document_number = $document->series.'-'.str_pad($document->number, 8, '0', STR_PAD_LEFT);
    $accounts = \App\Models\Tenant\BankAccount::all();
    $configuration = \App\Models\Tenant\Configuration::first();

    if($document_base) {

        $affected_document_number = ($document_base->affected_document) ? $document_base->affected_document->series.'-'.str_pad($document_base->affected_document->number, 8, '0', STR_PAD_LEFT) : $document_base->data_affected_document->series.'-'.str_pad($document_base->data_affected_document->number, 8, '0', STR_PAD_LEFT);

    } else {

        $affected_document_number = null;
    }

    $payments = $document->payments;

    $document->load('reference_guides');

    $total_payment = $document->payments->sum('payment');
    $balance = ($document->total - $total_payment) - $document->payments->sum('change');

// Condicion de pago
    $condition = TemplateHelper::getDocumentPaymentCondition($document);
	// Pago/Coutas detalladas
    $paymentDetailed = TemplateHelper::getDetailedPayment($document,'d-m-Y')
@endphp
<html>
<head>
    {{--<title>{{ $document_number }}</title>--}}
    {{--<link href="{{ $path_style }}" rel="stylesheet" />--}}
</head>
<body>
@if($document->state_type->id == '11')
    <div class="company_logo_box" style="position: absolute; text-align: center; top:30%;">
        <img src="data:{{mime_content_type(public_path("status_images".DIRECTORY_SEPARATOR."anulado.png"))}};base64, {{base64_encode(file_get_contents(public_path("status_images".DIRECTORY_SEPARATOR."anulado.png")))}}" alt="anulado" class="" style="opacity: 0.6;">
    </div>
@endif

<table class="full-width" >
    @if(in_array($document->document_type_id, ['01', '03']))
    <tr>
        @if($configuration->header_image)
            <td width="75%" class="pr-2">
                <div class="company_logo_box">
                    <img style="width: 90%" height="100px" src="data:{{mime_content_type(public_path("storage/uploads/header_images/{$configuration->header_image}"))}};base64, {{base64_encode(file_get_contents(public_path("storage/uploads/header_images/{$configuration->header_image}")))}}" alt="{{$configuration->id}}" >
                    {{-- <img src="data:{{mime_content_type(public_path("storage/uploads/logos/{$company->logo}"))}};base64, {{base64_encode(file_get_contents(public_path("storage/uploads/logos/{$company->logo}")))}}" alt="{{$company->name}}" class="company_logo" style="max-width: 150px;"> --}}
                </div>
            </td>
            <td width="25%" class="border-box py-4 px-2 text-center">
                <h5>{{ 'RUC '.$company->number }}</h5>
                <h5 class="text-center">{{ $document->document_type->description }}</h5>
                <h3 class="text-center">{{ $document_number }}</h3>
            </td>
        @else
            <td width="75%">
                {{--<img src="{{ asset('logo/logo.jpg') }}" class="company_logo" style="max-width: 150px">--}}
            </td>
            <td width="25%" class="border-box py-4 px-2 text-center">
                <h5>{{ 'RUC '.$company->number }}</h5>
                <h5 class="text-center">{{ $document->document_type->description }}</h5>
                <h3 class="text-center">{{ $document_number }}</h3>
            </td>
        @endif
    </tr>
    @else

        @if($company->logo)
            <td width="20%">
                <div class="company_logo_box">
                    <img src="data:{{mime_content_type(public_path("storage/uploads/logos/{$company->logo}"))}};base64, {{base64_encode(file_get_contents(public_path("storage/uploads/logos/{$company->logo}")))}}" alt="{{$company->name}}" class="company_logo" style="max-width: 150px;">
                </div>
            </td>
        @else
            <td width="20%">
                {{--<img src="{{ asset('logo/logo.jpg') }}" class="company_logo" style="max-width: 150px">--}}
            </td>
        @endif
        <tr>

            <td>
                <div class="text-left">
                    <h4 class="">{{ $company->name }}</h4>
                    <h5>{{ 'RUC '.$company->number }}</h5>
                    <h6 style="text-transform: uppercase;">
                        {{ ($establishment->address !== '-')? $establishment->address : '' }}
                        {{ ($establishment->district_id !== '-')? ', '.$establishment->district->description : '' }}
                        {{ ($establishment->province_id !== '-')? ', '.$establishment->province->description : '' }}
                        {{ ($establishment->department_id !== '-')? '- '.$establishment->department->description : '' }}
                    </h6>

                    @isset($establishment->trade_address)
                        <h6>{{ ($establishment->trade_address !== '-')? 'D. Comercial: '.$establishment->trade_address : '' }}</h6>
                    @endisset

                    <h6>{{ ($establishment->telephone !== '-')? 'Central telefónica: '.$establishment->telephone : '' }}</h6>

                    <h6>{{ ($establishment->email !== '-')? 'Email: '.$establishment->email : '' }}</h6>

                    @isset($establishment->web_address)
                        <h6>{{ ($establishment->web_address !== '-')? 'Web: '.$establishment->web_address : '' }}</h6>
                    @endisset

                    @isset($establishment->aditional_information)
                        <h6>{{ ($establishment->aditional_information !== '-')? $establishment->aditional_information : '' }}</h6>
                    @endisset
                </div>
            </td>
        </tr>
        <td width="30%" class="border-box py-4 px-2 text-center">
            <h5 class="text-center">{{ $document->document_type->description }}</h5>
            <h3 class="text-center">{{ $document_number }}</h3>
        </td>

    @endif
</table>
<table class="full-width mt-5">

    <tr>
        <td width="120px" style="text-align: top; vertical-align: top;">Cliente:</td>
        <td width="8px" style="text-align: top; vertical-align: top;">:</td>
        <td>{{ $customer->name }}</td>

        <td width="120px" style="text-align: top; vertical-align: top;">Fecha de emisión</td>
        <td width="8px" style="text-align: top; vertical-align: top;">:</td>
        <td style="text-align: top; vertical-align: top;">{{$document->date_of_issue->format('Y-m-d')}}</td>


    </tr>
    <tr>
        <td width="120px">{{ $customer->identity_document_type->description }}</td>
        <td width="8px">:</td>
        <td>{{$customer->number}}</td>

        @if($invoice)
            <td width="150px" style="text-align: top; vertical-align: top;">Fecha de vencimiento</td>
            <td width="8px" style="text-align: top; vertical-align: top;">:</td>
            <td style="text-align: top; vertical-align: top;">{{$invoice->date_of_due->format('Y-m-d')}}</td>
        @endif

    </tr>
    @if ($customer->address !== '')
    <tr>
        <td class="align-top">Dirección:</td>
        <td style="text-align: top; vertical-align: top;">:</td>
        <td>
            {{ $customer->address }}
            {{ ($customer->district_id !== '-')? ', '.$customer->district->description : '' }}
            {{ ($customer->province_id !== '-')? ', '.$customer->province->description : '' }}
            {{ ($customer->department_id !== '-')? '- '.$customer->department->description : '' }}
        </td>

    </tr>
    @endif


    @if ($document->detraction)
    <tr>

        <td width="120px">N. CTA DETRACCIONES</td>
        <td width="8px">:</td>
        <td>{{ $document->detraction->bank_account}}</td>


        <td width="140px">B/S SUJETO A DETRACCIÓN</td>
        <td width="8px">:</td>
        @inject('detractionType', 'App\Services\DetractionTypeService')
        <td width="220px">{{$document->detraction->detraction_type_id}} - {{ $detractionType->getDetractionTypeDescription($document->detraction->detraction_type_id ) }}</td>

    </tr>
    <tr>
        <td width="120px">MÉTODO DE PAGO</td>
        <td width="8px">:</td>
        <td width="220px">{{ $detractionType->getPaymentMethodTypeDescription($document->detraction->payment_method_id ) }}</td>

        <td width="120px">P. DETRACCIÓN</td>
        <td width="8px">:</td>
        <td>{{ $document->detraction->percentage}}%</td>
    </tr>

    <tr>
        <td width="120px">MONTO DETRACCIÓN</td>
        <td width="8px">:</td>
        <td>{{ $document->currency_type->symbol }} {{ $document->detraction->amount}}</td>

        @if($document->detraction->pay_constancy)
        <tr>
            <td colspan="3">
            </td>
            <td width="120px">CONSTANCIA DE PAGO</td>
            <td width="8px">:</td>
            <td>{{ $document->detraction->pay_constancy}}</td>
        </tr>
        @endif
    </tr>
    @endif

</table>

{{--<table class="full-width mt-3">--}}
    {{--@if ($document->purchase_order)--}}
        {{--<tr>--}}
            {{--<td width="25%">Orden de Compra: </td>--}}
            {{--<td>:</td>--}}
            {{--<td class="text-left">{{ $document->purchase_order }}</td>--}}
        {{--</tr>--}}
    {{--@endif--}}
    {{--@if ($document->quotation_id)--}}
        {{--<tr>--}}
            {{--<td width="15%">Cotización:</td>--}}
            {{--<td class="text-left" width="85%">{{ $document->quotation->identifier }}</td>--}}
        {{--</tr>--}}
    {{--@endif--}}
{{--</table>--}}

@if ($document->guides)
<br/>
{{--<strong>Guías:</strong>--}}
<table>
    @foreach($document->guides as $guide)
        <tr>
            @if(isset($guide->document_type_description))
            <td>{{ $guide->document_type_description }}</td>
            @else
            <td>{{ $guide->document_type_id }}</td>
            @endif
            <td>:</td>
            <td>{{ $guide->number }}</td>
        </tr>
    @endforeach
</table>
@endif


<table class="full-width">
    <tr>
    <td width="50%">
        @if ($document->reference_guides)
            @if (count($document->reference_guides) > 0)
            <br/>
            Guias de remisión
            <table>
                @foreach($document->reference_guides as $guide)
                    <tr>
                        <td>{{ $guide->series }}</td>
                        <td>-</td>
                        <td>{{ $guide->number }}</td>
                    </tr>
                @endforeach
            </table>
            @else
                <br>
            @endif
        @endif
    </td>
    <td width="50%">
        <table>
            <tr>
                <td width="120px" style="text-align: right">Número de Placa</td>
                <td width="8px">:</td>
                <td>
                    @foreach($document->items as $row)
                        @if($row->attributes)
                            @foreach($row->attributes as $attr)
                                @php $actual = $attr @endphp
                                @if($attr->description == "Numero de Placa")
                                    @if (!$loop->first && $attr->value != $actual->value)
                                        {{ $attr->value }} {{ !$loop->first ?'-':''}}
                                    @else
                                        {{ $attr->value }}
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </td>
            </tr>
        </table>
    </td>
    </tr>
</table>

<table class="full-width mt-3">
    @if ($document->prepayments)
        @foreach($document->prepayments as $p)
        <tr>
            <td width="120px">ANTICIPO</td>
            <td width="8px">:</td>
            <td>{{$p->number}}</td>
        </tr>
        @endforeach
    @endif
    @if ($document->purchase_order)
        <tr>
            <td width="120px">ORDEN DE COMPRA</td>
            <td width="8px">:</td>
            <td>{{ $document->purchase_order }}</td>
        </tr>
    @endif
    @if ($document->quotation_id)
        <tr>
            <td width="120px">COTIZACIÓN</td>
            <td width="8px">:</td>
            <td>{{ $document->quotation->identifier }}</td>

            @isset($document->quotation->delivery_date)
                    <td width="120px">F. ENTREGA</td>
                    <td width="8px">:</td>
                    <td>{{ $document->quotation->getStringDeliveryDate()}}</td>
            @endisset
        </tr>

    @endif
    @isset($document->quotation->sale_opportunity)
        <tr>
            <td width="120px">O. VENTA</td>
            <td width="8px">:</td>
            <td>{{ $document->quotation->sale_opportunity->number_full}}</td>
        </tr>
    @endisset
    @if(!is_null($document_base))
    <tr>
        <td width="120px">DOC. AFECTADO</td>
        <td width="8px">:</td>
        <td>{{ $affected_document_number }}</td>
    </tr>
    <tr>
        <td>TIPO DE NOTA</td>
        <td>:</td>
        <td>{{ ($document_base->note_type === 'credit')?$document_base->note_credit_type->description:$document_base->note_debit_type->description}}</td>
    </tr>
    <tr>
        <td>DESCRIPCIÓN</td>
        <td>:</td>
        <td>{{ $document_base->note_description }}</td>
    </tr>
    @endif
</table>

{{--<table class="full-width mt-3">--}}
    {{--<tr>--}}
        {{--<td width="25%">Documento Afectado:</td>--}}
        {{--<td width="20%">{{ $document_base->affected_document->series }}-{{ $document_base->affected_document->number }}</td>--}}
        {{--<td width="15%">Tipo de nota:</td>--}}
        {{--<td width="40%">{{ ($document_base->note_type === 'credit')?$document_base->note_credit_type->description:$document_base->note_debit_type->description}}</td>--}}
    {{--</tr>--}}
    {{--<tr>--}}
        {{--<td class="align-top">Descripción:</td>--}}
        {{--<td class="text-left" colspan="3">{{ $document_base->note_description }}</td>--}}
    {{--</tr>--}}
{{--</table>--}}

<table class="full-width mt-10 mb-10">
    <thead class="">
    <tr class="bg-grey">
        <th class="border-top-bottom text-center py-2" width="8%">CANT.</th>
        <th class="border-top-bottom text-center py-2" width="8%">UNIDAD</th>
        <th class="border-top-bottom text-center py-2" width="12%">COD.</th>
        <th class="border-top-bottom text-left py-2">DESCRIPCIÓN</th>

        {{-- <th class="border-top-bottom text-center py-2" width="8%">LOTE</th>
        <th class="border-top-bottom text-center py-2" width="8%">SERIE</th> --}}

        <th class="border-top-bottom text-right py-2" width="12%">P.UNIT</th>
        {{-- <th class="border-top-bottom text-right py-2" width="8%">DTO.</th> --}}
        <th class="border-top-bottom text-right py-2" width="12%">TOTAL</th>
    </tr>
    </thead>
    <tbody>
    @foreach($document->items as $row)
        <tr>
            <td class="text-center align-top">
                @if(((int)$row->quantity != $row->quantity))
                    {{ $row->quantity }}
                @else
                    {{ number_format($row->quantity, 0) }}
                @endif
            </td>
            <td class="text-center align-top">{{ $row->item->unit_type_id }}</td>
            <td class="text-center align-top">{{ $row->item->internal_id }}</td>
            <td class="text-left align-top">
                @if($row->name_product_pdf)
                    {!!$row->name_product_pdf!!}
                @else
                    {!!$row->item->description!!}
                @endif

                @if (!empty($row->item->presentation)) {!!$row->item->presentation->description!!} @endif

                @if($row->attributes)
                    @foreach($row->attributes as $attr)
                        @if ($attr->description != "Numero de Placa")
                            <br/><span style="font-size: 9px">{!! $attr->description !!} : {{ $attr->value }}</span>
                        @endif
                    @endforeach
                @endif
                @if($row->discounts)
                    @foreach($row->discounts as $dtos)
                        <br/><span style="font-size: 9px">{{ $dtos->factor * 100 }}% {{$dtos->description }}</span>
                    @endforeach
                @endif

                @if($row->item->is_set == 1)
                 <br>
                 @inject('itemSet', 'App\Services\ItemSetService')
                    {{join( "-", $itemSet->getItemsSet($row->item_id) )}}
                @endif
            </td>
            {{-- <td class="text-center align-top">
                @inject('itemLotGroup', 'App\Services\ItemLotsGroupService')
                {{ $itemLotGroup->getLote($row->item->IdLoteSelected) }}

            </td> --}}
            {{-- <td class="text-center align-top">

                @isset($row->item->lots)
                    @foreach($row->item->lots as $lot)
                        @if( isset($lot->has_sale) && $lot->has_sale)
                            <span style="font-size: 9px">{{ $lot->series }}</span><br>
                        @endif
                    @endforeach
                @endisset

            </td> --}}
            <td class="text-right align-top">{{ number_format($row->unit_price, 2) }}</td>
            {{-- <td class="text-right align-top">
                @if($row->discounts)
                    @php
                        $total_discount_line = 0;
                        foreach ($row->discounts as $disto) {
                            $total_discount_line = $total_discount_line + $disto->amount;
                        }
                    @endphp
                    {{ number_format($total_discount_line, 2) }}
                @else
                0
                @endif
            </td> --}}
            <td class="text-right align-top">{{ number_format($row->total, 2) }}</td>
        </tr>
        <tr>
            <td colspan="6" class="border-bottom"></td>
        </tr>
    @endforeach



    @if ($document->prepayments)
        @foreach($document->prepayments as $p)
        <tr>
            <td class="text-center align-top">
                1
            </td>
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
        <tr>
            <td colspan="4" class="border-bottom"></td>
        </tr>
        @endforeach
    @endif

        @if($document->total_exportation > 0)
            <tr>
                <td colspan="5" class="text-right font-bold">OP. EXPORTACIÓN: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold">{{ number_format($document->total_exportation, 2) }}</td>
            </tr>
        @endif
        @if($document->total_free > 0)
            <tr>
                <td colspan="5" class="text-right font-bold">OP. GRATUITAS: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold">{{ number_format($document->total_free, 2) }}</td>
            </tr>
        @endif
        @if($document->total_unaffected > 0)
            <tr>
                <td colspan="5" class="text-right font-bold">OP. INAFECTAS: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold">{{ number_format($document->total_unaffected, 2) }}</td>
            </tr>
        @endif
        @if($document->total_exonerated > 0)
            <tr>
                <td colspan="5" class="text-right font-bold">OP. EXONERADAS: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold">{{ number_format($document->total_exonerated, 2) }}</td>
            </tr>
        @endif
        @if($document->total_taxed > 0)
            <tr>
                <td colspan="5" class="text-right font-bold">OP. GRAVADAS: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold">{{ number_format($document->total_taxed, 2) }}</td>
            </tr>
        @endif
         @if($document->total_discount > 0)
            <tr>
                <td colspan="5" class="text-right font-bold">{{(($document->total_prepayment > 0) ? 'ANTICIPO':'DESCUENTO TOTAL')}}: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold">{{ number_format($document->total_discount, 2) }}</td>
            </tr>
        @endif
        @if($document->total_plastic_bag_taxes > 0)
            <tr>
                <td colspan="5" class="text-right font-bold">ICBPER: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold">{{ number_format($document->total_plastic_bag_taxes, 2) }}</td>
            </tr>
        @endif
        <tr>
            <td colspan="5" class="text-right font-bold">IGV: {{ $document->currency_type->symbol }}</td>
            <td class="text-right font-bold">{{ number_format($document->total_igv, 2) }}</td>
        </tr>

        @if($document->perception)
            <tr>
                <td colspan="5" class="text-right font-bold"> IMPORTE TOTAL: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold">{{ number_format($document->total, 2) }}</td>
            </tr>
            <tr>
                <td colspan="5" class="text-right font-bold">PERCEPCIÓN: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold">{{ number_format($document->perception->amount, 2) }}</td>
            </tr>
            <tr>
                <td colspan="5" class="text-right font-bold">TOTAL A PAGAR: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold">{{ number_format(($document->total + $document->perception->amount), 2) }}</td>
            </tr>
        @else
            <tr>
                <td colspan="5" class="text-right font-bold">TOTAL A PAGAR: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold">{{ number_format($document->total, 2) }}</td>
            </tr>
        @endif

        @if($balance < 0)

            <tr>
                <td colspan="5" class="text-right font-bold">VUELTO: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold">{{ number_format(abs($balance),2, ".", "") }}</td>
            </tr>

        @endif



    </tbody>
</table>
<table class="full-width">
    <tr>
        <td width="65%" style="text-align: top; vertical-align: top;">
            @foreach(array_reverse( (array) $document->legends) as $row)
                @if ($row->code == "1000")
                    <p style="text-transform: uppercase;">Son: <span class="font-bold">{{ $row->value }} {{ $document->currency_type->description }}</span></p>
                    @if (count((array) $document->legends)>1)
                        <p><span class="font-bold">Leyendas</span></p>
                    @endif
                @else
                    <p> {{$row->code}}: {{ $row->value }} </p>
                @endif

            @endforeach
            <br/>
            @if ($document->detraction)
            <p>
                <span class="font-bold">
                Operación sujeta al Sistema de Pago de Obligaciones Tributarias
                </span>
            </p>
            <br/>
            @endif
            @if ($customer->department_id == 16)
                <br/><br/><br/>
                <div>
                    <center>
                        Representación impresa del Comprobante de Pago Electrónico.
                        <br/>Esta puede ser consultada en:
                        <br/><b>{!! url('/buscar') !!}</b>
                        <br/> "Bienes transferidos en la Amazonía
                        <br/>para ser consumidos en la misma".
                    </center>
                </div>
                <br/>
            @endif
            @foreach($document->additional_information as $information)
                @if ($information)
                    @if ($loop->first)
                        <strong>Información adicional</strong>
                    @endif
                    <p>{{ $information }}</p>
                @endif
            @endforeach
            <br>
            @if(in_array($document->document_type->id,['01','03']))
                @foreach($accounts as $account)
                    <p>
                    <span class="font-bold">{{$account->bank->description}}</span> {{$account->currency_type->description}}
                    <span class="font-bold">N°:</span> {{$account->number}}
                    @if($account->cci)
                    <span class="font-bold">CCI:</span> {{$account->cci}}
                    @endif
                    </p>
                @endforeach
            @endif
        </td>
    </tr>
    <tr>
        <td width="100%" class="text-left">
            <img src="data:image/png;base64, {{ $document->qr }}" style="margin-right: -10px;" />
            <p style="font-size: 9px">Código Hash: {{ $document->hash }}</p>
        </td>
    </tr>
</table>
{{-- Condicion de pago  Crédito / Contado --}}
<table class="full-width">
    <tr>
        <td>
            <strong>CONDICIÓN DE PAGO: {{ $condition }} </strong>
        </td>
    </tr>
</table>

@if($document->payment_method_type_id)
    <table class="full-width">
        <tr>
            <td>
                <strong>MÉTODO DE PAGO: </strong>{{ $document->payment_method_type->description }}
            </td>
        </tr>
    </table>
@endif

@if(!empty($paymentDetailed))
    @foreach($paymentDetailed as $detailed)
        <table class="full-width">
            <tr>
                <td>
                    <strong>
                        {{ isset($paymentDetailed['CUOTA'])?'Cuotas:':'Pagos:' }}
                    </strong>
                </td>
            </tr>
            @foreach($detailed as $row)
                <tr>
                    <td>&#8226;
                        {{ $row['description']  }}
                        {{ isset($paymentDetailed['CUOTA'])?' / FECHA: ':' - ' }}
                        {{ $row['reference']  }}
                        {{ isset($paymentDetailed['CUOTA'])?' / Monto: ':'' }}
                        {{ $row['symbol']  }}
                        {{ number_format( $row['amount'], 2) }}
                    </td>
                    @endforeach
                </tr>
        </table>
    @endforeach
@endif
@if($document->user)
     <br>
    <table class="full-width">
        <tr>
            <td>
                <strong>Vendedor:</strong>
            </td>
        </tr>

                <tr>
                    <td>{{ $document->user->name }}</td>
                </tr>

        </tr>

    </table>

@endif
@if ($document->terms_condition)
        <br>
        <table class="full-width">
            <tr>
                <td>
                    <h6 style="font-size: 12px; font-weight: bold;">Términos y condiciones del servicio</h6>
                    {!! $document->terms_condition !!}
                </td>
            </tr>
        </table>
    @endif
</body>
</html>
