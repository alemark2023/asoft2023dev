<?php

use Modules\DocumentaryProcedure\Models\DocumentaryGuidesNumber;
?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type"
          content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible"
          content="ie=edge">
    <title>Document</title>
</head>
<body>
<div>
    <h3 align="center"
        class="title"><strong>Reporte Tramite</strong></h3>
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
            <td>
                <p><strong>Establecimiento: </strong></p>
            </td>
            <td align="center">{{$establishment->address}} - {{$establishment->department->description}}
                                                           - {{$establishment->district->description}}</td>
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
                    <th>#</th>
                    <th>Numero de expediente</th>
                    <th>Fecha/Hora registro</th>
                    <th>Datos del cliente</th>
                    <th>Trámite</th>
                    <th>Descripcion Trámite</th>
                    <th>Número Etapa</th>
                    <th>Número de seguimiento</th>
                    <th>Etapa</th>
                    <th>Status de Etapa</th>
                    <th>Fecha de fin</th>
                </tr>
                </thead>
                <tbody>
                @foreach($records as $key => $value)
                    <?php
                    $sender = $value['sender'] ?? null;
                    $documentary_process = $value['documentary_process'] ?? null;
                    $guides = $value['guides'] ?? null;
                    $totalGuides = count($guides);
                    ?>
                    @foreach($guides as $key2 => $guide)
                        <?php
                        /** @var DocumentaryGuidesNumber $guide */

                        $status = $guide->documentary_guides_number_status;
                        $office = $guide->doc_office;
                        ?>

                        <tr>
                            <td
                                class="text-right">{{ $key + 1 }}</td>
                            <td>{{ $value['invoice'] }}</td>
                            <td>{{ $value['date_register'] }}
                                - {{ $value['time_register'] }}</td>
                            <td>{{ $sender->name }}</td>
                            <td>
                                <span
                                >
                                     {{ $documentary_process['name']??'' }}
                                </span>
                            </td>
                            <td>
                                <span
                                >
                                     {{ $documentary_process['description']??'' }}
                                </span>
                            </td>

                            <td>{{$key2 + 1}}</td>
                            <td>{{$guide->guide}}</td>
                            <td>{{$office->name}}</td>
                            <td>{{$status->name}}</td>
                            <td>{{$guide->date_end}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
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
