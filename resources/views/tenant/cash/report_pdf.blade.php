@php

$establishment = $cash->user->establishment;

$final_balance = 0;
$cash_income = 0;
$cash_final_balance = 0;
$cash_documents = $cash->cash_documents;

foreach ($cash_documents as $cash_document) {
    $final_balance += ($cash_document->document) ? $cash_document->document->total : $cash_document->sale_note->total;
}

$cash_final_balance = $final_balance + $cash->beginning_balance; 
$cash_income = ($final_balance > 0) ? ($cash_final_balance - $cash->beginning_balance) : 0; 

@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Reporte POS - {{$cash->user->name}} - {{$cash->date_opening}} {{$cash->time_opening}}</title>
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
                font-size: 12px;
            }
            
            thead {
                font-weight: bold;
                background: #0088cc;
                color: white;
                text-align: center;
            }
            .td-custom { line-height: 0.1em; }
        </style>
    </head>
    <body>
        <div>
            <p align="center" class="title"><strong>Reporte Punto de Venta</strong></p>
        </div>
        <div style="margin-top:20px; margin-bottom:20px;">
            <table> 
                <tr>
                    <td class="td-custom">
                        <p><strong>Empresa: </strong>{{$company->name}}</p>
                    </td>
                    <td class="td-custom">
                        <p><strong>Fecha reporte: </strong>{{date('Y-m-d')}}</p>
                    </td>
                </tr>
                <tr>
                    <td class="td-custom">
                        <p><strong>Ruc: </strong>{{$company->number}}</p>
                    </td>
                    <td class="td-custom">
                        <p><strong>Establecimiento: </strong>{{$establishment->address}} - {{$establishment->department->description}} - {{$establishment->district->description}}</p>
                    </td>
                </tr>
                
                <tr>
                    <td class="td-custom">
                        <p><strong>Vendedor: </strong>{{$cash->user->name}}</p>
                    </td>
                    <td class="td-custom">
                        <p><strong>Fecha y hora apertura: </strong>{{$cash->date_opening}} {{$cash->time_opening}}</p>
                    </td>
                </tr>
                <tr>
                    <td class="td-custom">
                        <p><strong>Estado de caja: </strong>{{($cash->state) ? 'Aperturada':'Cerrada'}}</p>
                    </td>
                    @if(!$cash->state)
                    <td class="td-custom">
                        <p><strong>Fecha y hora cierre: </strong>{{$cash->date_opening}} {{$cash->time_opening}}</p>
                    </td>
                    @endif
                </tr>
                <tr>
                
                    <td colspan="2" class="td-custom">
                        <p><strong>Montos de operación: </strong></p>
                    </td>
                </tr>
                <tr>
                    <td class="td-custom">
                        <p><strong>Saldo inicial: </strong>S/. {{round($cash->beginning_balance,2)}}</p>
                    </td>
                    <td  class="td-custom">
                        <p><strong>Ingreso: </strong>S/. {{round($cash_income,2)}} </p>
                    </td>
                </tr>
                <tr> 
                    <td  class="td-custom">
                        <p><strong>Saldo final: </strong>S/. {{round($cash_final_balance,2)}} </p>
                    </td>
                </tr>
            </table> 
        </div>
        @if($cash_documents->count())
            <div class="">
                <div class=" ">
                    <table class="">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipo Doc</th>
                                <th>Documento</th>
                                <th>F. Emisión</th>
                                <th>Cliente</th>
                                <th>RUC</th> 
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cash_documents as $key => $value)
                                <tr>
                                
                                    <td class="celda">{{ $loop->iteration }}</td>
                                    <td class="celda">{{ ($value->document_id) ? $value->document->document_type->description : 'NOTA DE VENTA'}}</td>
                                    <td class="celda">{{ ($value->document_id) ? "{$value->document->series}-{$value->document->number}" : "{$value->sale_note->prefix}-{$value->sale_note->id}"}}</td>
                                    <td class="celda">{{ ($value->document_id) ? $value->document->date_of_issue->format('Y-m-d') : $value->sale_note->date_of_issue->format('Y-m-d')}}</td>
                                    <td class="celda">{{ ($value->document_id) ? $value->document->customer->name : $value->sale_note->customer->name}}</td>
                                    <td class="celda">{{ ($value->document_id) ? $value->document->customer->number : $value->sale_note->customer->number}}</td>  
                                    <td class="celda">{{ ($value->document_id) ? $value->document->total : $value->sale_note->total}}</td>

                                </tr>
                            @endforeach
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
