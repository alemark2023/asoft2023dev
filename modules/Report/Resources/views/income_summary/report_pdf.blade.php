@php

$establishment = $cash->user->establishment;
 
$cash_documents = $cash->cash_documents;
 
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
            </table> 
        </div>
        @if($cash_documents->count())
            <div class="">
                <div class=" ">
                    <table class="">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipo transacción</th>
                                <th>Tipo documento</th>
                                <th>Documento</th>
                                <th>Fecha emisión</th>
                                <th>Cliente/Proveedor</th>
                                <th>N° Documento</th> 
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cash_documents as $key => $value)
                            
                                @php
                                    
                                    $type_transaction =  null;
                                    $document_type_description = null;
                                    $number = null;
                                    $date_of_issue = null;
                                    $customer_name = null;
                                    $customer_number = null;
                                    $total = null; 
                                    
                                @endphp

                                @if($value->sale_note)

                                    @foreach($value->sale_note->payments as $payment)
                                    <tr>
                                        @php
                                            $type_transaction =  'Venta';
                                            $document_type_description =  'NOTA DE VENTA';
                                            $number = $value->sale_note->identifier;
                                            $date_of_issue = $value->sale_note->date_of_issue->format('Y-m-d');
                                            $customer_name = $value->sale_note->customer->name;
                                            $customer_number = $value->sale_note->customer->number;
                                            $total = $payment->total;

                                        @endphp


                                        <td class="celda">{{ $loop->iteration }}</td>
                                        <td class="celda">{{ $type_transaction }}</td>
                                        <td class="celda">{{ $document_type_description }}</td>
                                        <td class="celda">{{ $number }}</td>
                                        <td class="celda">{{ $date_of_issue}}</td>
                                        <td class="celda">{{ $customer_name }}</td>
                                        <td class="celda">{{$customer_number }}</td>  
                                        <td class="celda">{{ number_format($total,2) }}</td>

                                    </tr>
                                    @endforeach

                                @elseif($value->document)
                                 
                                @endif
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
