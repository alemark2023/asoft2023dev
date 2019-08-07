<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
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
            </table>
        </div>
        @if(!empty($reports))
            <div class="">
                <div class=" ">
                    @php
                        $acum_total_taxed=0;
                        $acum_total_igv=0;
                        $acum_total=0;
                        $total_exonerado=0;
                        $total_inafecto=0;
                       
                    @endphp
                    <table class="">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipo sssDoc</th>
                                <th>Número</th>
                                <th>Fecha emisión</th>
                                <th>Documento Modifica</th>
                                <th>Cliente</th>
                                <th>RUC</th>
                                <th>Estado</th>
                                <th>Total Exonerado</th>
                                <th>Total Inafecto</th>
                                <th>Total Gravado</th>
                                <th>Total IGV</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $key => $value)
                                <tr>
                                    <td class="celda">{{$loop->iteration}}</td>
                                    <td class="celda">{{$value->document_type->id}}</td>
                                    <td class="celda">{{$value->series}}-{{$value->number}}</td>
                                    <td class="celda">{{$value->date_of_issue->format('Y-m-d')}}</td>
                                    <td class="celda">{{ $value->document_type->id == '07' ? $value->series.' - '.$value->number : '' }} </td>
                                    <td class="celda">{{$value->customer->name}}</td>
                                    <td class="celda">{{$value->customer->number}}</td>
                                    <td class="celda">{{$value->state_type->description}}</td>
                                    
                                    @php
                                     $signal = $value->document_type->short;
                                    @endphp


                                    @if($value->affectation_igv_type_id == '20' || $value->affectation_igv_type_id == '21')
                                         $total_exonerado += $value->total_value;
                                    @endif
                                     @if($value->affectation_igv_type_id == '30' || $value->affectation_igv_type_id == '31' || $value->affectation_igv_type_id == '32' || $value->affectation_igv_type_id == '33'
                                     || $value->affectation_igv_type_id == '34' || $value->affectation_igv_type_id == '35' || $value->affectation_igv_type_id == '36' || $value->affectation_igv_type_id == '37')
                                         $total_inafecto += $value->total_value;
                                    @endif

                                    <td class="celda">{{$signal == 'NC' ? "-" : ""  }}{{$total_exonerado}} </td>
                                    <td class="celda">{{$signal == 'NC' ? "-" : ""  }}{{$total_inafecto}}</td>
                                    <td class="celda">{{$signal == 'NC' ? "-" : ""  }}{{$value->total_taxed}}</td>
                                    <td class="celda">{{$signal == 'NC' ? "-" : ""  }}{{$value->total_igv}}</td>
                                    <td class="celda">{{$signal == 'NC' ? "-" : ""  }}{{$value->total}}</td>
                                </tr>
                            @php
                                $acum_total_taxed += $value->total_taxed;
                                $acum_total_igv += $value->total_igv;
                                $acum_total += $value->total;
                                $total_exonerado=0;
                                $total_inafecto=0;
                            @endphp
                            @endforeach
                            <tr>
                                <td colspan="6" class="celda"></td>
                                <td class="celda">Totales</td>
                                <td class="celda">{{$acum_total_taxed}}</td>
                                <td class="celda">{{$acum_total_igv}}</td>
                                <td class="celda">{{$acum_total}}</td>
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
