<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Reporte de Cuentas</title>
</head>
<body>
      <div style="margin-top:20px; margin-bottom:15px;">
            <h3 align="center" class="title"><strong>Reporte de Cuentas Por Cobrar</strong></h3>
             <table>
                <tr>
                    <td>
                        <p><b>Empresa: </b></p>
                    </td>
                    <td align="center">
                        <p><strong>{{$records[0]->trade_name}}</strong></p>
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
                    <td align="center">{{$records[0]->number}}</td>

                </tr>
            </table>
        </div>
          @if(!empty($records))
            <div class="">
                <div class=" ">
                    <table class="">
                        <thead>
                            <tr>
                                <th><h4><strong>#</strong></h4></th>
                                <th class="text-center"><h4><strong>Fecha Emisión</strong></h4></th>
                                <th><h4><strong>Número</strong></h4></th>
                                <th class="text-center"><h4><strong>Clientes</strong></h4></th>
                                <th class="text-center"><h4><strong>Por Cobrar</strong></h4></th>
                                <th class="text-center"><h4><strong>Total</strong></h4></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $key => $value)
                                    <tr>
                                        <td class="celda">{{$loop->iteration}}</td>
                                        <td class="celda">{{$value->date_of_issue}}</td>
                                        <td class="celda">
                                            {{$value->full_number}}
                                        </td>
                                        <td class="celda">{{$value->name}}</td>
                                         <td class="celda">{{$value->total_value}}</td>
                                        <td class="celda">{{$value->total}}</td>
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
