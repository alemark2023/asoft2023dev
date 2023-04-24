<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
        <style>
            @page {
              margin: 5;
            }
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
            <p align="center" class="title"><strong>Reporte Documentos</strong></p>
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
                        <p><strong>Cliente: </strong>{{$reportService->getPersonName($filters['person_id'])}}</p>
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

                        $serie_affec = '';

                        $acum_total_exonerado=0;
                        $acum_total_inafecto=0;

                        $acum_total_free=0;

                        $acum_total_taxed_usd=0;
                        $acum_total_igv_usd=0;
                        $acum_total_usd=0;
                    @endphp
                    <table class="" style="font-size:10px;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="">Usuario/Vendedor</th>
                                <th>Tipo Doc</th>
                                <th>Número</th>
                                <th>Fecha emisión</th>
                                <th>Fecha Vencimiento</th>
                                <th>Doc. Afectado</th>

                                <th># Guía</th>

                                <th>DIST</th>
                                <th>DPTO</th>
                                <th>PROV</th>

                                <th>Cliente</th>
                                <th>RUC</th>
                                <th>Estado</th>
                                <th class="">Moneda</th>
                                <th>Plataforma</th>
                                <th>Orden de compra</th>
                                <!-- <th>Total Exonerado</th>
                                <th>Total Inafecto</th>
                                 <th>Total Gratutio</th> -->
                                <th>Total Gravado</th>

                                <th>Total IGV</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $key => $value)
                                <?php
                    /** @var \App\Models\Tenant\Document|App\Models\Tenant\SaleNote  $value */
                                    $iteration = $loop->iteration;
                                    // $user = $value->user->name;
                    $document_type = $value->getDocumentType();
                                $seller = \App\CoreFacturalo\Helpers\Template\ReportHelper::getSellerData($value);
                                try{
                                    $user = $seller->name;
                                }catch (ErrorException $e){
                                    $user = '';
                                }


                                ?>

                                <tr>
                        <td class="celda">{{$iteration}}</td>
                        <td class="celda">{{$user}}</td>
                                    <td class="celda">{{$document_type->id}}</td>
                                    <td class="celda">{{$value->series}}-{{$value->number}}</td>
                                    <td class="celda">{{$value->date_of_issue->format('Y-m-d')}}</td>
                                    <td class="celda">{{isset($value->invoice)?$value->invoice->date_of_due->format('Y-m-d'):''}}</td>
                                        @if(in_array($document_type->id,["07","08"]) && $value->note)

                                            @php
                                                $serie = ($value->note->affected_document) ? $value->note->affected_document->series : $value->note->data_affected_document->series;
                                                $number =  ($value->note->affected_document) ? $value->note->affected_document->number : $value->note->data_affected_document->number;
                                                $serie_affec = $serie.' - '.$number;

                                            @endphp


                                        @endif

                                    <td class="celda">{{  $serie_affec }} </td>
                                    <td class="celda">
                                        @if(!empty($value->guides))
                                            @foreach($value->guides as $guide)
                                                {{ $guide->number }}<br>
                                            @endforeach
                                        @endif
                                    </td>
                                    <?php $stablihsment = \App\CoreFacturalo\Helpers\Template\ReportHelper::getLocationData($value); ?>
                                        <td class="celda">{{$stablihsment['district']}}</td>
                                    <td class="celda">{{$stablihsment['department']}}</td>
                                    <td class="celda">{{$stablihsment['province']}}</td>

                                    <td class="celda">{{$value->customer->name}}</td>
                                    <td class="celda">{{$value->customer->number}}</td>
                                    <td class="celda">{{$value->state_type->description}}</td>

                                    <td class="celda">{{$value->currency_type_id}}</td>
                                    <td class="celda">
                                        @foreach ($value->getPlatformThroughItems() as $platform)
                                            <label class="d-block">{{$platform->name}}</label>
                                        @endforeach
                                    </td>
                                    <td class="celda">{{ $value->purchase_order }}</td>
                                    @php
                                     $signal = $document_type->id;
                                     $state = $value->state_type_id;
                                    @endphp




                                    <!-- <td class="celda">{{$signal == '07' ? "-" : ""  }}{{$value->total_exonerated}}</td>
                                    <td class="celda">{{$signal == '07' ? "-" : ""  }}{{$value->total_unaffected}}</td>
                                    <td class="celda">{{$signal == '07' ? "-" : ""  }}{{$value->total_free}}</td> -->


                                    @if($signal == '07')

                                        @if(in_array($value->state_type_id,['09','11']))
                                            <td class="celda">0</td>
                                            <td class="celda">0</td>
                                            <td class="celda">0</td>
                                        @else
                                            <td class="celda">{{$signal == '07' ? "-" : ""  }}{{$value->total_taxed}}</td>
                                            <td class="celda">{{$signal == '07' ? "-" : ""  }}{{$value->total_igv}}</td>
                                            <td class="celda">{{$signal == '07' ? "-" : ""  }}{{$value->total}}</td>
                                        @endif

                                    @else
                                        <td class="celda">{{ (in_array($document_type->id,['01','03']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total_taxed}}</td>
                                        <td class="celda">{{ (in_array($document_type->id,['01','03']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total_igv}}</td>
                                        <td class="celda">{{ (in_array($document_type->id,['01','03']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total}}</td>

                                    @endif



                                    @php
                                        $value->total_taxed = (in_array($document_type->id,['01','03', '07']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total_taxed;
                                        $value->total_igv = (in_array($document_type->id,['01','03', '07']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total_igv;
                                        $value->total = (in_array($document_type->id,['01','03', '07']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total;
                                    @endphp
                                </tr>
                                @php


                                    $serie_affec =  '';
                                @endphp


                                <!-- <tr>
                                    <td colspan="7" class="celda"></td>
                                    <td class="celda">Totales</td>
                                    <td class="celda">{{$acum_total_exonerado}}</td>
                                    <td class="celda">{{$acum_total_inafecto}}</td>
                                    <td class="celda">{{$acum_total_free}}</td>
                                    <td class="celda">{{$value->total_taxed}}</td>
                                    <td class="celda">{{$value->total_igv}}</td>
                                    <td class="celda">{{$value->total}}</td>
                                </tr> -->
                                @php

                                    if($value->currency_type_id == 'PEN'){

                                        /*$acum_total_taxed +=  $signal != '07' ? $value->total_taxed : -$value->total_taxed ;
                                        $acum_total_igv +=  $signal != '07' ? $value->total_igv : -$value->total_igv ;
                                        $acum_total += $signal != '07' ? $value->total : -$value->total ;*/

                                        if(($signal == '07' && $state !== '11')){

                                            $acum_total += -$value->total;
                                            $acum_total_taxed += -$value->total_taxed;
                                            $acum_total_igv += -$value->total_igv;


                                        }elseif($signal != '07' && $state == '11'){

                                            $acum_total += 0;
                                            $acum_total_taxed += 0;
                                            $acum_total_igv += 0;


                                        }else{

                                            $acum_total += $value->total;
                                            $acum_total_taxed += $value->total_taxed;
                                            $acum_total_igv += $value->total_igv;
                                        }

                                    }else if($value->currency_type_id == 'USD'){

                                        if(($signal == '07' && $state !== '11')){

                                            $acum_total_usd += -$value->total;
                                            $acum_total_taxed_usd += -$value->total_taxed;
                                            $acum_total_igv_usd += -$value->total_igv;

                                        }elseif($signal != '07' && $state == '11'){

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
                            <tr>
                                <td class="celda" colspan="16"></td>
                                <td class="celda" >Totales PEN</td>
                                <td class="celda">{{$acum_total_taxed}}</td>
                                <td class="celda">{{$acum_total_igv}}</td>
                                <td class="celda">{{$acum_total}}</td>
                            </tr>
                            <tr>
                                <td class="celda" colspan="16"></td>
                                <td class="celda" >Totales USD</td>
                                <td class="celda">{{$acum_total_taxed_usd}}</td>
                                <td class="celda">{{$acum_total_igv_usd}}</td>
                                <td class="celda">{{$acum_total_usd}}</td>
                            </tr>
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
