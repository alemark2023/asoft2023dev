<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Pagos</title>
    </head>
    <body>
        <div>
            <h3 align="center" class="title"><strong>Reporte Pagos</strong></h3>
        </div>
        <br>
        @if(!empty($records))
            <div class="">
                <div class=" ">
                    <table class="">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Ruc</th>
                                <th>Fecha</th>
                                <th>Factura</th>
                                <th class="">Nombre Comercial</th>

                                <th>Razon Social</th>
                                <th>Zona</th>
                                <th class="">Total Factura</th>
                                <th>Pago 1</th>
                                <th>Pago 2</th>

                                <th>Pago 3</th>
                                <th>Pago 4</th>
                                <th class="" >Referencia 1</th>
                                <th class="" >Referencia 2</th>
                                <th>Referencia 3</th>

                                <th>Referencia 4</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $key => $value)
                            <tr>
                                <td class="celda">{{$loop->iteration}}</td>
                                <td class="celda">{{$value->ruc}}</td>
                                <td class="celda">{{$value->date }}</td>
                                <td class="celda">{{$value->invoice }}</td>
                                <td class="celda">{{$value->comercial_name }}</td>


                                <td class="celda">{{$value->business_name }}</td>
                                <td class="celda">{{$value->zone }}</td>
                                <td class="celda">{{$value->total }}</td>
                                <td class="celda">{{$value->payment1 }}</td>
                                <td class="celda">{{$value->payment2 }}</td>

                                <td class="celda">{{$value->payment3 }}</td>
                                <td class="celda">{{$value->payment4 }}</td>
                                <td class="celda">{{$value->reference1 }}</td>
                                <td class="celda">{{$value->reference2 }}</td>
                                <td class="celda">{{$value->reference3 }}</td>

                                <td class="celda">{{$value->reference4 }}</td>

                            </tr>
                            @endforeach
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
