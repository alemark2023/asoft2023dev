<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>REPORTE GENERAL PEDIDOS</title>
    </head>
    <body>
        <div>
            <p align="center" class="title"><strong>REPORTE GENERAL PEDIDOS</strong></p>
        </div>
        <br>
        @if(!empty($records))
            <div class="">
                <div class=" ">
                    <table class="">
                        <thead>
                            <tr>
                                <th class="">Número</th>
                                <th  class="celda">Cliente</th>
                                <th  class="celda">Dirección</th>
                                <th  class="celda">Total</th>
                                <th  class="celda" >Estado</th>
                                <th  class="celda" >Devolución</th>
                                <th  class="celda" >Total venta</th>
                                <th  class="celda">Tipo de pago</th>
                                <th  class="celda">Repartidor</th>
                                <th  class="celda" >Motivo</th>
                                <th  class="celda">Detalle</th>
                                <th  class="celda">Codigo interno</th>
                                <th  class="celda">Marca</th>
                                <th  class="celda">Cantidad de producto</th>
                                <th  class="celda">Precio unitario</th>
                                <th  class="celda">descuento</th>
                                <th  class="celda">Documento Asociado</th>
                                <th  class="celda">Guia de remision</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $serie_document="";
                                $discount_description="";
                                $total_prev=0;
                                $guide_remision=0;

                                $acum_total_taxed=0;
                                $acum_total_igv=0;
                                $acum_total=0;
                            
                                $serie_affec = '';
                                $acum_total_exonerado=0;
                                $acum_total_inafecto=0;

                                $acum_total_free=0;

                                dd($records);
                            @endphp
                            @foreach($records as $key => $value)
                            @php
                                $items_order=0;
                                $acum_price=0;
                                $acum_discount=0;

                                $acum_total = $value->total_value;
                                $acum_total_taxed = $value->total_taxed;
                                $acum_total_igv = $value->total_igv;

                                
                                $acum_total_exonerado = $value->total_exonerated;
                                $acum_total_inafecto = $value->total_unaffected;
                                $acum_total_free = $value->total_free;

                                $total_prev = $acum_total+$acum_total_igv+$acum_total_exonerado+$acum_total_free+$acum_total_inafecto;

                                $state_type_description=$value->state_type->description;
                                if (!empty($value->dispatches) && count($value->dispatches) != 0) {
                                    $state_type_description = 'Despachado';
                                    // #596
                                }
                            @endphp
                                <tr>
                                    <td class="celda">{{$loop->iteration}}</td>
                                    <td class="celda" >{{ $value->customer->name }}</td>
                                    <td  class="celda">{{$value->shipping_address}}</td>
                                    <td  class="celda" >{{$total_prev}}</td>
                                    <td  class="celda">{{$state_type_description}}</td>
                                    <td class="celda" >{{ $value->total_discount }}</td>
                                    <td class="celda" >{{ $value->total }}</td>
                                    <td class="celda" >{{ $value->payment_method_type->description }}</td>
                                    <td class="celda" >{{ $value->user->name }}</td>
                                    @foreach ($value->discounts as $dis)
                                        @php
                                            $discount_description = $dis->description;
                                        @endphp
                                    @endforeach
                                    <td  class="celda">{{$discount_description}}</td>
                                    <td  class="celda">{{$value->observation}}</td>
                                    @foreach ($value->documents as $doc)
                                        @php
                                            if($doc->order_note_id==$value->id){
                                                $serie_document=$doc->series;
                                            }
                                        @endphp
                                    @endforeach
                                    @foreach ($value->sale_notes as $not)
                                        @php 
                                            if($not->order_note_id==$value->id){
                                            $serie_document=$not->series;
                                            }
                                        @endphp
                                    @endforeach
                                    <td  class="celda">{{$value->prefix."-".$value->id}}</td>
                                    <td  class="celda"></td>
                                    @foreach ($value->items as $itm)
                                        @php 
                                            if($itm->order_note_id==$value->id){
                                                $items_order+=$itm->quantity;
                                                $acum_price+=($itm->unit_price*$itm->quantity);
                                                $acum_discount+=$itm->total_discount;
                                            }
                                        @endphp
                                    @endforeach
                                    <td  class="celda">{{$items_order}}</td>
                                    <td  class="celda">{{$acum_price}}</td>
                                    <td  class="celda">{{$acum_discount}}</td>
                                    <td  class="celda">{{$serie_document}}</td>
                                    @foreach ($value->dispatches as $disp)
                                    <td  class="celda">{{$disp->series}}</td>
                                    @endforeach
                                    
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
