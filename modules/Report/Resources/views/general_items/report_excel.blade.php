<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>REPORTE PRODUCTOS</title>
    </head>
    <body> 
        @if(!empty($records))
            <div class="">
                <div class=" "> 
                    <table class="">
                        <thead>
                            <tr>
                                <th class="">FECHA DE EMISIÓN</th>
                                <th class="">TIPO DOCUMENTO</th>
                                <th class="">ID TIPO</th> 
                                <th class="">SERIE</th> 
                                <th class="">NÚMERO</th> 
                                <th class="">ANULADO</th> 
                                <th class="">DOC ENTIDAD TIPO DNI RUC</th> 
                                <th class="">DOC ENTIDAD NÚMERO</th> 
                                <th class="">DENOMINACIÓN ENTIDAD</th> 
                                <th class="">MONEDA</th> 
                                <th class="">TIPO DE CAMBIO</th> 
                                <th class="">UNIDAD DE MEDIDA</th> 
                                <th class="">CÓDIGO INTERNO</th> 
                                <th class="">DESCRIPCIÓN</th> 
                                <th class="">CANTIDAD</th> 
                                <th class="">COSTO UNIDAD</th> 
                                <th class="">VALOR UNITARIO</th> 
                                <th class="">PRECIO UNITARIO</th> 
                                <th class="">DESCUENTO</th> 
                                <th class="">SUBTOTAL</th> 
                                <th class="">TIPO DE IGV</th> 
                                <th class="">IGV</th> 
                                <th class="">TIPO DE ISC</th> 
                                <th class="">ISC</th> 
                                <th class="">IMPUESTO BOLSAS</th> 
                                <th class="">TOTAL</th>  
                            </tr>
                        </thead>
                        <tbody>
                            @if($type == 'sale')
                                @foreach($records as $key => $value)
                                <tr>
                                    <td class="celda">{{$value->document->date_of_issue->format('Y-m-d')}}</td> 
                                    <td class="celda">{{$value->document->document_type->description}}</td>
                                    <td class="celda">{{$value->document->document_type_id}}</td>
                                    <td class="celda">{{$value->document->series}}</td>
                                    <td class="celda">{{$value->document->number}}</td>
                                    <td class="celda">{{$value->document->state_type_id == '11' ? 'SI':'NO'}}</td>
                                    <td class="celda">{{$value->document->customer->identity_document_type->description}}</td>
                                    <td class="celda">{{$value->document->customer->number}}</td>
                                    <td class="celda">{{$value->document->customer->name}}</td>
                                    <td class="celda">{{$value->document->currency_type_id}}</td>
                                    <td class="celda">{{$value->document->exchange_rate_sale}}</td>
                                    <td class="celda">{{$value->item->unit_type_id}}</td>
                                    <td class="celda">{{$value->item->internal_id}}</td>
                                    <td class="celda">{{$value->item->description}}</td>
                                    <td class="celda">{{$value->quantity}}</td>

                                    <td class="celda">{{($value->relation_item) ? $value->relation_item->purchase_unit_price:0}}</td>

                                    <td class="celda">{{$value->unit_value}}</td>
                                    <td class="celda">{{$value->unit_price}}</td>

                                    <td class="celda">{{$value->total_discount}}</td>

                                    <td class="celda">{{$value->total_value}}</td>
                                    <td class="celda">{{$value->affectation_igv_type_id}}</td>
                                    <td class="celda">{{$value->total_igv}}</td>
                                    <td class="celda">{{$value->system_isc_type_id}}</td>
                                    <td class="celda">{{$value->total_isc}}</td>
                                    <td class="celda">{{$value->total_plastic_bag_taxes}}</td>
                                    
                                    <td class="celda">{{$value->total}}</td>
                                    
                                </tr> 
                                @endforeach
                            @else
                            
                                @foreach($records as $key => $value)
                                <tr>
                                    <td class="celda">{{$value->purchase->date_of_issue->format('Y-m-d')}}</td> 
                                    <td class="celda">{{$value->purchase->document_type->description}}</td>
                                    <td class="celda">{{$value->purchase->document_type_id}}</td>
                                    <td class="celda">{{$value->purchase->series}}</td>
                                    <td class="celda">{{$value->purchase->number}}</td>
                                    <td class="celda">{{$value->purchase->state_type_id == '11' ? 'SI':'NO'}}</td>
                                    <td class="celda">{{$value->purchase->supplier->identity_document_type->description}}</td>
                                    <td class="celda">{{$value->purchase->supplier->number}}</td>
                                    <td class="celda">{{$value->purchase->supplier->name}}</td>
                                    <td class="celda">{{$value->purchase->currency_type_id}}</td>
                                    <td class="celda">{{$value->purchase->exchange_rate_sale}}</td>
                                    <td class="celda">{{$value->item->unit_type_id}}</td>

                                    <td class="celda">{{$value->relation_item ? $value->relation_item->internal_id:''}}</td>

                                    <td class="celda">{{$value->item->description}}</td>
                                    <td class="celda">{{$value->quantity}}</td>

                                    <td class="celda"></td>

                                    <td class="celda">{{$value->unit_value}}</td>
                                    <td class="celda">{{$value->unit_price}}</td>

                                    <td class="celda">
                                    @if($value->discounts)
                                        {{collect($value->discounts)->sum('amount')}}
                                    @endif
                                    </td>

                                    <td class="celda">{{$value->total_value}}</td>
                                    <td class="celda">{{$value->affectation_igv_type_id}}</td>
                                    <td class="celda">{{$value->total_igv}}</td>
                                    <td class="celda">{{$value->system_isc_type_id}}</td>
                                    <td class="celda">{{$value->total_isc}}</td>
                                    <td class="celda">{{$value->total_plastic_bag_taxes}}</td>
                                    
                                    <td class="celda">{{$value->total}}</td>
                                    
                                </tr> 
                                @endforeach
                            @endif
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
