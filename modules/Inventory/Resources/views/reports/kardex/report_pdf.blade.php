<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Kardex</title>
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
            <p align="center" class="title"><strong>Reporte Kardex</strong></p>
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
        @if(!empty($reports))
            <div class="">
                <div class=" ">
                    <table class="">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha y hora</th>
                                <th>Tipo transacción</th>
                                <th>Número</th>
                                <th>Entrada</th>
                                <th>Salida</th>
                                <th>Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $key => $value)
                                <tr>
                                    <td class="celda">{{$loop->iteration}}</td>
                                    <td class="celda">{{$value->created_at}}</td>
                                    <td class="celda">

                                        @switch($value->inventory_kardexable_type)
                                            @case($models[0])
                                                {{($value->quantity < 0) ? "Venta":"Anulación"}}
                                                @break
                                            @case($models[1])
                                                {{"Compra"}}                                                    
                                                @break 
                                                
                                            @case($models[2])
                                                {{"Nota de venta"}}                                                    
                                                @break  

                                            @case($models[3])
                                                {{$value->inventory_kardexable->description}}                                                    
                                                @break  
                                        @endswitch

                                        
                                    </td>
                                    <td class="celda">
                                        @switch($value->inventory_kardexable_type)
                                            @case($models[0])
                                                {{ "{$value->inventory_kardexable->series}-{$value->inventory_kardexable->number}" }}
                                                @break
                                            @case($models[1])
                                                {{"{$value->inventory_kardexable->series}-{$value->inventory_kardexable->number}"}}                                                    
                                                @break 
                                                
                                            @case($models[2])
                                                {{  "{$value->inventory_kardexable->prefix}-{$value->inventory_kardexable->id}" }}                                                    
                                                @break  

                                            @case($models[3])
                                                {{"-"}}                                                 
                                                @break  
                                        @endswitch

                                    </td>
                                    <td class="celda">
                                        @switch($value->inventory_kardexable_type) 

                                            @case($models[0])
                                                {{ ($value->quantity > 0) ?  $value->quantity:"-"}}
                                                @break
                                            @case($models[1])
                                                {{  $value->quantity }}                                                    
                                                @break 
                                                
                                            @case($models[3])
                                                {{ ($value->inventory_kardexable->type == 1) ? $value->quantity : "-" }}                                                    
                                                @break  

                                            @default
                                                {{"-"}}                                                 
                                                @break  
                                        @endswitch
                                    </td>
                                    <td class="celda">
                                    
                                        @switch($value->inventory_kardexable_type) 
                                            @case($models[0])
                                                {{ ($value->quantity < 0) ?  $value->quantity:"-" }}                                                    
                                                @break  
                                            @case($models[2])
                                                {{  $value->quantity }}                                                    
                                                @break      
                                            @case($models[3])
                                                {{ ($value->inventory_kardexable->type == 2 || $value->inventory_kardexable->type == 3) ? $value->quantity : "-" }}                                                    
                                                @break  
                                            @default
                                                {{"-"}}                                                 
                                                @break  
                                        @endswitch
                                    
                                    </td>
                                    @php                        
                                        $balance += $value->quantity;     
                                    @endphp
                                    <td class="celda">{{number_format($balance, 4)}}</td>
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
