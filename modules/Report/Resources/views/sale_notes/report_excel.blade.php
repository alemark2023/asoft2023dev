@php
    $enabled_sales_agents = App\Models\Tenant\Configuration::getRecordIndividualColumn('enabled_sales_agents');
@endphp
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Compras</title>
    </head>
    <body>
        <div>
            <h3 align="center" class="title"><strong>Reporte Nota de Venta</strong></h3>
        </div>
        <br>
        <div style="margin-top:20px; margin-bottom:15px;">
            <table>
                <tr>
                    <td>
                        <p><b>Empresa: </b></p>
                    </td>
                    <td align="center">
                        <p><strong>{{$company->name}}</strong></p>
                    </td>
                    <td>
                        <p><strong>Fecha: </strong></p>
                    </td>
                    <td align="center">
                        <p><strong>{{date('Y-m-d')}}</strong></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Ruc: </strong></p>
                    </td>
                    <td align="center">{{$company->number}}</td>

                    @inject('reportService', 'Modules\Report\Services\ReportService')
                    @if($filters['seller_id'])
                    <td>
                        <p><strong>Usuario: </strong></p>
                    </td>
                    <td align="center">
                        {{$reportService->getUserName($filters['seller_id'])}}
                    </td>
                    @endif
                </tr>
            </table>
        </div>
        <br>
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
                    @endphp

                    <table class="">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">Fecha Emisión</th>
                                <th class="text-center">Hora Emisión</th>
                                <th class="">Usuario/Vendedor</th>
                                <th>Cliente</th>
                                <th>Nota de Venta</th>
                                <th class="text-center">Estado pago</th>
                                <th>Estado</th>
                                <th class="text-center">Moneda</th>
                                <th class="text-center">Plataforma</th>
                                <th class="text-center">Orden de compra</th>
                                <th class="text-center">Region</th>
                                <th class="text-center">Comprobantes</th>
                                <th class="text-center">Fecha comprobante</th>
                                <th>Cotización</th>
                                <th>Caso</th>
                                
                                <th class="text-center">Productos</th>
                                <th class="text-right">Descuento</th>

                                <th class="text-right" >T.Exportación</th>
                                <th class="text-right" >T.Inafecta</th>
                                <th class="text-right" >T.Exonerado</th>
                                <th class="text-right">T.Gravado</th>
                                <th class="text-right">T.Igv</th>
                                <th class="text-right">Total</th>
                                @if ($enabled_sales_agents)
                                    <th>Agente</th>
                                    <th>Datos de referencia</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $key => $value)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$value->date_of_issue->format('Y-m-d')}}</td>
                                <td class="celda">{{$value->time_of_issue}}</td>
                                <td class="celda">{{$value->user->name}}</td>
                                <td>{{$value->customer->name}}</td>
                                <td>{{$value->number_full}}</td>
                                <td>
                                    {{$value->total_canceled ? 'Pagado':'Pendiente'}}
                                </td>
                                <td>{{$value->state_type->description}}</td>
                                <td>{{$value->currency_type_id}}</td>
                                <td class="celda">
                                    @foreach ($value->getPlatformThroughItems() as $platform)
                                        <label class="d-block">{{$platform->name}}</label>
                                    @endforeach
                                </td>
                                <td>{{$value->purchase_order}}</td>
                                <td>{{$value->customer->department->description}}</td>
                                @php
                                    $documents = $value->documents;
                                @endphp
                                <td>
                                    @foreach ($documents as $doc)
                                        <p class="d-block">{{$doc->number_full}}</p>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($documents as $doc)
                                        <p class="d-block">{{ $doc->date_of_issue->format('Y-m-d') }}</p>
                                    @endforeach
                                </td>
                                <td class="celda">{{ ($value->quotation) ? $value->quotation->number_full : '' }}</td>
                                <td class="celda">{{ isset($value->quotation->sale_opportunity) ? $value->quotation->sale_opportunity->number_full : '' }}</td>

                                <td>
                                    @foreach ($value->getItemsforReport() as $key => $item)
                                        - {{ $item['description'] }} / Cantidad: {{ $item['quantity'] }} 
                                        @if ($key < count($value->getItemsforReport()) - 1)
                                        <br/>
                                        @endif
                                    @endforeach
                                </td>

                                @if($value->state_type_id == '11')

                                    <td class="celda">0</td>
                                    <td class="celda">0</td>
                                    <td class="celda">0</td>
                                    <td class="celda">0</td>
                                    <td class="celda">0</td>
                                    <td class="celda">0</td>
                                    <td class="celda">0</td>

                                @else

                                    <td class="celda">{{ ($value->total_discount) }}</td>
                                    <td class="celda">{{ ($value->total_exportation) }}</td>
                                    <td class="celda">{{ $value->total_unaffected }}</td>
                                    <td class="celda">{{ $value->total_exonerated }}</td>
                                    <td class="celda">{{ $value->total_taxed}}</td>
                                    <td class="celda">{{ $value->total_igv}}</td>
                                    <td class="celda">{{ $value->total}}</td>

                                @endif
                                
                                @if ($enabled_sales_agents)
                                    <td>{{optional($value->agent)->search_description}}</td>
                                    <td>{{$value->reference_data}}</td>
                                @endif
                            </tr>

                            @php

                                if($value->currency_type_id == 'PEN'){

                                    if($value->state_type_id == '11'){

                                        $acum_total += 0;
                                        $acum_total_taxed += 0;
                                        $acum_total_igv += 0;

                                    }else{

                                        $acum_total += $value->total;
                                        $acum_total_taxed += $value->total_taxed;
                                        $acum_total_igv += $value->total_igv;

                                    }

                                }else if($value->currency_type_id == 'USD'){

                                    if($value->state_type_id == '11'){

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
                                <td class="celda" colspan="20"></td>
                                <td class="celda" >Totales PEN</td>
                                <td class="celda">{{$acum_total_taxed}}</td>
                                <td class="celda">{{$acum_total_igv}}</td>
                                <td class="celda">{{$acum_total}}</td>
                            </tr>
                            <tr>
                                <td class="celda" colspan="20"></td>
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
            <div>
                <p>No se encontraron registros.</p>
            </div>
        @endif
    </body>
</html>
