<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Compras</title>
        <style>
            html {
                font-family: sans-serif;
                font-size: 12px;
            }

            table {
                width: 100%;
                border-spacing: 0;
                border: 1px solid black;
            }

            .celda {
                text-align: center;
                padding: 5px;
                border: 0.1px solid black;
            }

            th {
                padding: 5px;
                text-align: center;
                border-color: #0088cc;
                border: 0.1px solid black;
            }

            .title {
                font-weight: bold;
                padding: 5px;
                font-size: 20px !important;
                text-decoration: underline;
            }

            p>strong {
                margin-left: 5px;
                font-size: 13px;
            }

            thead {
                font-weight: bold;
                background: #0088cc;
                color: white;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div>
            <p align="center" class="title"><strong>Reporte Compras</strong></p>
        </div>
        <div style="margin-top:20px; margin-bottom:20px;">
            <table>
                <tr>
                    <td>
                        <p><strong>Empresa: </strong>{{$company->name}}</p>
                    </td>
                    <td>
                        <p><strong>Fecha: </strong>{{date('Y-m-d')}}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Ruc: </strong>{{$company->number}}</p>
                    </td>
                    <td>
                        <p><strong>Establecimiento: </strong>{{$establishment->address}} - {{$establishment->department->description}} - {{$establishment->district->description}}</p>
                    </td>
                </tr>

                @inject('reportService', 'Modules\Report\Services\ReportService')
                <tr>
                    @if($filters['seller_id'])
                    <td>
                        <p><strong>Usuario: </strong>{{$reportService->getUserName($filters['seller_id'])}}</p>
                    </td>
                    @endif
                    @if($filters['person_id'])
                    <td>
                        <p><strong>Proveedor: </strong>{{$reportService->getPersonName($filters['person_id'])}}</p>
                    </td>
                    @endif
                </tr>

            </table>
        </div>
        @if(!empty($records))
            <div class="">
                <div class=" ">
                    @php
                        $acum_total_taxed=0;
                        $acum_total_igv=0;
                        $acum_total=0;

                        $acum_total_taxed_usd=0;
                        $acum_total_igv_usd=0;
                        $acum_total_usd=0;

                        $apply_conversion_to_pen = $filters['apply_conversion_to_pen'] == 'true';
                    @endphp
                    <table class="">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipo Doc</th>
                                <th>Número</th>
                                <th>F. Emisión</th>
                                <th class="">F. Vencimiento</th>
                                <th>Proveedor</th>
                                <th>RUC</th>
                                <th>Cliente</th>
                                <th class="">F. Pago</th>
                                <!-- <th class="" >T.Exonerado</th>
                                <th class="" >T.Inafecta</th>
                                <th class="" >T.Gratuito</th> -->
                                <th>Moneda</th>
                                <th>Total ISC</th>
                                <th>Total Gravado</th>
                                <th>Total IGV</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $key => $value)
                                <tr>


                                    <td class="celda">{{$loop->iteration}}</td>
                                    <td class="celda">{{$value->document_type->id}}</td>
                                    <td class="celda">{{$value->series}}-{{$value->number}}</td>
                                    <td class="celda">{{$value->date_of_issue->format('Y-m-d')}}</td>
                                    <td class="celda">{{$value->date_of_due->format('Y-m-d')}}</td>
                                    <td class="celda" style="text-transform:uppercase;">{{$value->supplier->name}}</td>
                                    <td class="celda">{{$value->supplier->number}}</td>
                                    <td class="celda">
                                        {{$value->customer ? $value->customer->name : ''}}<br>
                                        {{$value->customer ? $value->customer->identity_document_type->description : ''}} {{$value->customer ? $value->customer->number : ''}}
                                    </td>
                                    <td class="celda">
                                        {{-- {{isset($value->purchase_payments['payment_method_type']['description'])?$value->purchase_payments['payment_method_type']['description']:'-'}} --}}
                                        @foreach($value->payments as $pay)
                                            {{$pay->payment_method_type->description}}
                                        @endforeach
                                    </td>

                                    @if ($apply_conversion_to_pen && $value->isCurrencyTypeUsd())
                                        
                                        <td class="celda">{{$value->currency_type_id}} (Conv.)</td>
                                        <td class="celda">{{ $value->state_type_id == '11' ? 0 : $value->getConvertTotalIscToPen() }}</td>
                                        <td class="celda">{{ $value->state_type_id == '11' ? 0 : $value->getConvertTotalTaxedToPen() }}</td>
                                        <td class="celda">{{ $value->state_type_id == '11' ? 0 : $value->getConvertTotalIgvToPen() }}</td>
                                        <td class="celda">{{ $value->state_type_id == '11' ? 0 : $value->getConvertTotalToPen() }}</td>

                                    @else
                                        
                                        <td class="celda">{{$value->currency_type_id}}</td>
                                        <td class="celda">{{ $value->state_type_id == '11' ? 0 : $value->total_isc}}</td>
                                        <td class="celda">{{ $value->state_type_id == '11' ? 0 : $value->total_taxed}}</td>
                                        <td class="celda">{{ $value->state_type_id == '11' ? 0 : $value->total_igv}}</td>
                                        <td class="celda">{{ $value->state_type_id == '11' ? 0 : $value->total}}</td>
                                    @endif



                                    @php
                                        $value->total_taxed = (in_array($value->document_type_id,['01','03']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total_taxed;
                                        $value->total_igv = (in_array($value->document_type_id,['01','03']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total_igv;
                                        $value->total = (in_array($value->document_type_id,['01','03']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total;
                                        $state = $value->state_type_id;
                                    @endphp
                                </tr>


                                @php

                                    if($value->currency_type_id == 'PEN'){

                                        if($state == '11'){

                                            $acum_total += 0;
                                            $acum_total_taxed += 0;
                                            $acum_total_igv += 0;


                                        }else{

                                            $acum_total += $value->total;
                                            $acum_total_taxed += $value->total_taxed;
                                            $acum_total_igv += $value->total_igv;
                                        }

                                    }else if($value->currency_type_id == 'USD'){

                                        if($state == '11'){

                                            $acum_total_usd += 0;
                                            $acum_total_taxed_usd += 0;
                                            $acum_total_igv_usd += 0;

                                        }else{

                                            $acum_total_usd += $value->total;
                                            $acum_total_taxed_usd += $value->total_taxed;
                                            $acum_total_igv_usd += $value->total_igv;

                                        }

                                    }
                                @endphp

                            @endforeach

                            @if (!$apply_conversion_to_pen)
                                <tr>
                                    <td class="celda" colspan="10"></td>
                                    <td class="celda" >Totales PEN</td>
                                    <td class="celda">{{$acum_total_taxed}}</td>
                                    <td class="celda">{{$acum_total_igv}}</td>
                                    <td class="celda">{{$acum_total}}</td>
                                </tr>
                                <tr>
                                    <td class="celda" colspan="10"></td>
                                    <td class="celda" >Totales USD</td>
                                    <td class="celda">{{$acum_total_taxed_usd}}</td>
                                    <td class="celda">{{$acum_total_igv_usd}}</td>
                                    <td class="celda">{{$acum_total_usd}}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="callout callout-info">
                <p>No se encontraron registros.</p>
            </div>
        @endif
    </body>
</html>
