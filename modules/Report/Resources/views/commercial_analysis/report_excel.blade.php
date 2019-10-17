<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>RH</title>
    </head>
    <body>
        <div>
            <h3 align="center" class="title"><strong>Reporte Análisis comercial</strong></h3>
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
                                <th>#</th>
                                <th class="">Cliente</th>
                                <th class="">Documento</th>
                                <th class="">Zona</th>
                                <th class="">Celular</th> 
                                <th class="">Primera compra</th> 
                                <th class="">Ultima compra</th> 
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $key => $value)
                            @php
                            
                                $customer = $value;
                                $documents = $value->documents;

                                $country =($customer->country_id)? $customer->country->description : '' ;
                                $district = ($customer->district_id)? '-'.$customer->district->description : '' ;
                                $province = ($customer->province_id)? '-'.$customer->province->description : '' ;
                                $department = ($customer->department_id)? '-'.$customer->department->description : '' ;
                                $zone = "{$country} {$department} {$province} {$district}";

                                $first_document = ($documents) ? $documents->first():'-';
                                $last_document = ($documents) ? $documents->last():'-';
                            @endphp
                            <tr>
                                <td>{{$loop->iteration}}</td> 
                                <td>{{$customer->name}}</td>
                                <td>{{ $customer->identity_document_type->description}} - {{ $customer->number }}<br/></td> 
                                <td>{{$zone}}</td> 
                                <td>{{$customer->telephone}}</td> 
                                <td>{{($first_document) ? $first_document->series  . '-'. $first_document->number :'-'}}</td> 
                                <td>{{($last_document) ? $last_document->series  . '-'. $last_document->number :'-'}}</td> 
                        
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
