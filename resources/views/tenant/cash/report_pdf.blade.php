@php

$establishment = $cash->user->establishment;

$final_balance = 0;
$cash_income = 0;
$cash_egress = 0;
$cash_final_balance = 0;
$cash_documents = $cash->cash_documents;

foreach ($cash_documents as $cash_document) {

    //$final_balance += ($cash_document->document) ? $cash_document->document->total : $cash_document->sale_note->total;
    
    if($cash_document->sale_note){
        $cash_income += $cash_document->sale_note->total;
        $final_balance += $cash_document->sale_note->total;
    }
    else if($cash_document->document){
        $cash_income += $cash_document->document->total;
        $final_balance += $cash_document->document->total;
    } 
    else if($cash_document->expense_payment){
        $cash_egress += $cash_document->expense_payment->payment;
        $final_balance -= $cash_document->expense_payment->payment;
    }

}

$cash_final_balance = $final_balance + $cash->beginning_balance; 
//$cash_income = ($final_balance > 0) ? ($cash_final_balance - $cash->beginning_balance) : 0; 

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
                        <p><strong>Fecha y hora cierre: </strong>{{$cash->date_closed}} {{$cash->time_closed}}</p>
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
                        <p><strong>Saldo inicial: </strong>S/. {{number_format($cash->beginning_balance,2)}}</p>
                    </td>
                    <td  class="td-custom">
                        <p><strong>Ingreso: </strong>S/. {{number_format($cash_income,2)}} </p>
                    </td>
                </tr>
                <tr> 
                    <td  class="td-custom">
                        <p><strong>Saldo final: </strong>S/. {{number_format($cash_final_balance,2)}} </p>
                    </td>
                    <td  class="td-custom">
                        <p><strong>Egreso: </strong>S/. {{number_format($cash_egress,2)}} </p>
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
                                <tr>
                                    @php
                                    

                                        $type_transaction =  null;
                                        $document_type_description = null;
                                        $number = null;
                                        $date_of_issue = null;
                                        $customer_name = null;
                                        $customer_number = null;
                                        $total = null;

                                        if($value->sale_note){

                                            $type_transaction =  'Venta';
                                            $document_type_description =  'NOTA DE VENTA';
                                            $number = $value->sale_note->identifier;
                                            $date_of_issue = $value->sale_note->date_of_issue->format('Y-m-d');
                                            $customer_name = $value->sale_note->customer->name;
                                            $customer_number = $value->sale_note->customer->number;
                                            $total = $value->sale_note->total;

                                        }
                                        else if($value->document){

                                            $type_transaction =  'Venta';
                                            $document_type_description =  $value->document->document_type->description;
                                            $number = $value->document->number_full;
                                            $date_of_issue = $value->document->date_of_issue->format('Y-m-d');
                                            $customer_name = $value->document->customer->name;
                                            $customer_number = $value->document->customer->number;
                                            $total = $value->document->total;

                                        }
                                        else if($value->expense_payment){
                                            
                                            $type_transaction =  'Gasto';
                                            $document_type_description =  $value->expense_payment->expense->expense_type->description;
                                            $number = $value->expense_payment->expense->number;
                                            $date_of_issue = $value->expense_payment->expense->date_of_issue->format('Y-m-d');
                                            $customer_name = $value->expense_payment->expense->supplier->name;
                                            $customer_number = $value->expense_payment->expense->supplier->number;
                                            $total = -$value->expense_payment->payment;

                                        } 

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
