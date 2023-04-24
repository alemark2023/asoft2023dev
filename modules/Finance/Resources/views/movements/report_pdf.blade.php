<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Movimientos de ingresos y egresos</title>
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
            <p align="center" class="title"><strong>Movimientos de ingresos y egresos</strong></p>
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
        @if(!empty($records))
            <div class="">
                <div class=" "> 
                    <table class="">
                        <thead>
                            <tr>
                                <th class="">#</th>
                                <th class="">Fecha</th>
                                <th class="">Adquiriente</th>
                                <th class="">N° Doc. Identidad</th>
                                <th class="">Tipo documento</th>
                                <th class="">Documento/Transacción</th>
                                <th class="">Detalle</th>
                                <th class="">Moneda</th>
                                <th class="">Tipo</th>
                                <th class="">Ingresos</th>
                                <th class="">Gastos</th>
                                <th class="">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $balance = 0;
                                $final_balance = 0;
                                $total_input = 0;
                                $total_output = 0;
                            @endphp
                            @foreach($records as $key => $value)
                                <tr>
                                    @php 
                                        $data_person = $value->data_person;
                                        $document_type = '';
                                        $items = [];

                                        if($value->payment->associated_record_payment->document_type){

                                            $document_type = $value->payment->associated_record_payment->document_type->description;
                                        
                                        }elseif(isset($value->payment->associated_record_payment->prefix)){
                                            
                                            $document_type = $value->payment->associated_record_payment->prefix;

                                        }


                                        $payment_method_type_description = '';

                                        if($value->payment->payment_method_type){
                                            $payment_method_type_description = $value->payment->payment_method_type->description;
                                        }else{
                                            $payment_method_type_description = $value->payment->expense_method_type->description;
                                        }

                                        $balance =  ($value->type_movement == 'input') ?  $balance + $value->payment->payment : $balance - $value->payment->payment;

                                        $total_input += ($value->type_movement == 'input') ? $value->payment->payment : 0;
                                        $total_output += ($value->type_movement == 'output') ? $value->payment->payment : 0;

                                        if(in_array($value->instance_type, ['expense', 'income'])){

                                            $items = $value->payment->associated_record_payment->items->transform(function($row, $key) {
                                                return [
                                                    'description' => $row->description 
                                                ];
                                            });
                                        }


                                    @endphp
                                    <td class="celda">{{$loop->iteration}}</td>
                                    <td class="celda">{{$value->payment->date_of_payment->format('Y-m-d')}}</td> 
                                    <td class="celda">{{$data_person->name}}</td>
                                    <td class="celda">{{$data_person->number}}</td>
                                    <td class="celda">{{ $document_type }}</td>
                                    <td class="celda">{{$value->payment->associated_record_payment->number_full}}</td>
                                    <td class="celda">
                                        @foreach ($items as $item)
                                            <p>- {{ $item['description'] }}</p>
                                        @endforeach
                                    </td>
                                    <td class="celda">{{$value->payment->associated_record_payment->currency_type_id}}</td>
                                    <td class="celda">{{$value->instance_type_description}}</td>

                                    <td class="celda"> {{ ($value->type_movement == 'input') ? "S/".number_format($value->payment->payment, 2, ".", "") : '-' }}</td>
                                    <td class="celda"> {{ ($value->type_movement == 'output') ? "S/".number_format($value->payment->payment, 2, ".", "") : '-' }}</td>
                                    <td class="celda">S/{{ $balance }}</td>
                                </tr>

                                 
                            @endforeach 
                        </tbody>                      
                        <tfoot>
                            <tr>
                                <td colspan="9" class="celda"></td>
                                <td class="celda">S/{{$total_input}}</td>
                                <td class="celda">S/{{$total_output}}</td>
                                <td class="celda">S/{{$total_input - $total_output}}</td>
                            </tr> 
                        </tfoot>
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
