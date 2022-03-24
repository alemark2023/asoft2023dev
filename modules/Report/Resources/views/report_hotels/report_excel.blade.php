<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type"
          content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible"
          content="ie=edge">
    <title>RH</title>
</head>
<body>
<div>
    <h3 align="center"
        class="title"><strong>Reporte de Habitaciones de Hoteles</strong></h3>
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

        </tr>
    </table>
</div>
<br>
@if(!empty($records))
    <div class="">
        <div class=" ">
            <table class="">
                <thead>
                <tr>
                    {{-- <th>#</th>
                    <th>Nombres y Apellidos</th>
                    <th> Habitación</th>
                    <th>Status de pago</th>
                    <th>Status checkout</th>
                    <th>Fecha de entrada</th>
                    <th>Hora de entrada</th>
                    <th>Fecha de salida</th>
                    <th>Hora de salida</th> --}}
                    <th># numero</th>
                    <th>nombres y apellidos</th>
                    <th>sexo</th>
                    <th>nacionalidad</th>
                    <th>pais o region de residencia</th>
                    <th>tipo de documento</th>
                    <th>numero de documento</th>
                    <th>fecha de ingreso</th>
                    <th>fecha de salida</th>
                    <th>numero de habitacion asignada</th>
                    <th>tarifa</th>
                    <th>arribos</th>
                    <th>habitacion noche ocupadas</th>
                    <th>permoctaciones</th>
                    <th>motivo</th>
                </tr>
                </thead>
                <tbody>
                @foreach($records as $key => $value)
                    <?php
                    /** @var \Modules\Hotel\Models\HotelRent $value */


                    $customer = $value->customer;
                    $room = $value->room;
                    ?>
                    <tr>

                        <td>{{ $key+1 }}</td>
                        <td>{{$customer->name}}</td>
                        <td></td>{{-- SEXO --}}
                        <td></td>{{-- NACIONALIDAD --}}
                        <td></td>{{-- PAIS RESIDENCIA --}}
                        <td></td>{{-- TIPO DOCUMENTO --}}
                        <td>{{ $customer->number }}</td>{{-- # DOCUMENTO --}}
                        {{-- <td>{{$room->name ?? ''}}</td>
                        <td>{{ $value ->payment_status === "PAID" ? "Pagado" : "Debe" }}</td>
                        <td>{{$value ->status}}</td> --}}
                        <td>{{$value ->input_date}}</td>
                        <td>{{$value ->input_time}}</td>
                        <td>{{$value ->output_date}}</td>
                        <td>{{$value ->output_time}}</td>
                        <td>{{$room->name ?? ''}}</td>{{-- # HABITACION ASIGNADA --}}
                        <td></td>{{-- TARIFA --}}
                        <td></td>{{-- PAIS ARRIBOS --}}
                        <td></td>{{-- HABITACION NOCHE OCUPADAS --}}
                        <td></td>{{-- PERMOCTACIONES --}}
                        <td>{{ $value->notes }}</td>{{-- MOTIVO --}}


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
