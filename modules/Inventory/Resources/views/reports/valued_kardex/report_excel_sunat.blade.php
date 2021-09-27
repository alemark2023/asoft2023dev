<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type"
          content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kardex valorizado</title>
</head>
<body>
<div>
    {{-- <h3 align="center" class="title"><strong>FORMATO 13.1: "REGISTRO DE INVENTARIO PERMANENTE VALORIZADO - DETALLE DEL INVENTARIO VALORIZADO"</strong></h3> --}}
</div>
<br>
<div style="margin-top:20px; margin-bottom:15px;">
    <table>
        <tr >
            <td colspan="8">
                <p><b>FORMATO 13.1: "REGISTRO DE INVENTARIO PERMANENTE VALORIZADO - DETALLE DEL INVENTARIO VALORIZADO" </b></p>
            </td>  
        </tr> 
        <tr>
            <td>
                <p><b>PERÍODO: </b></p>
            </td>  
        </tr> 
        <tr>
            <td>
                <p><b>RUC: </b></p>
            </td>  
        </tr> 
        <tr>
            <td>
                <p><b>APELLIDOS Y NOMBRES, DENOMINACIÓN O RAZÓN SOCIAL:</b></p>
            </td>  
        </tr> 
        <tr>
            <td>
                <p><b>ESTABLECIMIENTO (1):</b></p>
            </td>  
        </tr> 
        <tr>
            <td>
                <p><b>CÓDIGO DE LA EXISTENCIA:</b></p>
            </td>  
        </tr> 
        <tr>
            <td>
                <p><b>TIPO (TABLA 5):</b></p>
            </td>  
        </tr> 
        <tr>
            <td>
                <p><b>DESCRIPCIÓN:</b></p>
            </td>  
        </tr> 
        <tr>
            <td>
                <p><b>CÓDIGO DE LA UNIDAD DE MEDIDA (TABLA 6):</b></p>
            </td>  
        </tr> 
        <tr>
            <td>
                <p><b>MÉTODO DE VALUACIÓN:</b></p>
            </td>  
        </tr> 
    </table>
    
    <table>
        <tr>
            <td colspan="4" align="center">
                <p><b>DOCUMENTO DE TRASLADO, COMPROBANTE DE PAGO, DOCUMENTO DE TRASLADO, COMPROBANTE DE PAGO, DOCUMENTO INTERNO O SIMILAR</b></p>
            </td> 
            <td rowspan="2" align="center">
                <p><b>TIPO DE OPERACIÓN (TABLA 12)</b></p>
            </td> 
            <td colspan="3" align="center">
                <p><b>ENTRADAS</b></p>
            </td> 
            <td colspan="3" align="center">
                <p><b>SALIDAS</b></p>
            </td> 
            <td colspan="3" align="center">
                <p><b>SALDO FINAL</b></p>
            </td> 
        </tr>  
        <tr>
            <td>
                <p><b>FECHA</b></p>
            </td>  
            <td>
                <p><b>TIPO (TABLA 10)</b></p>
            </td>  
            <td>
                <p><b>SERIE</b></p>
            </td>  
            <td>
                <p><b>NÚMERO</b></p>
            </td>  

            {{-- ENTRADAS --}}
            <td>
                <p><b>CANTIDAD</b></p>
            </td>  
            <td>
                <p><b>COSTO UNITARIO</b></p>
            </td>  
            <td>
                <p><b>COSTO TOTAL</b></p>
            </td>  

            
            {{-- SALIDAS --}}
            <td>
                <p><b>CANTIDAD</b></p>
            </td>  
            <td>
                <p><b>COSTO UNITARIO</b></p>
            </td>  
            <td>
                <p><b>COSTO TOTAL</b></p>
            </td>  

            
            {{-- SALDO --}}
            <td>
                <p><b>CANTIDAD</b></p>
            </td>  
            <td>
                <p><b>COSTO UNITARIO</b></p>
            </td>  
            <td>
                <p><b>COSTO TOTAL</b></p>
            </td>  


        </tr>  

        @php
            // dd($records);
            $balance_quantity = 0;
            $balance_total = 0;
            $balance_cost = 0;

        @endphp

        @foreach ($records as $row)
            <tr>
                <td>
                    {{ $row['date_of_issue'] }}
                </td>
                <td>
                    {{ $row['document_type_id'] }}     
                </td>
                <td>
                   {{ $row['series'] }}     
                </td>
                <td>
                   {{ $row['number'] }}     
                </td>
                <td>
                   {{ $row['operation_type'] }}     
                </td>

                {{-- ENTRADAS --}}
                <td>
                   {{ $row['input_quantity'] }}     
                </td>
                <td>
                   {{ $row['input_unit_price'] }}     
                </td>
                <td>
                   {{ $row['input_total'] }}     
                </td>


                {{-- SALIDAS --}}
                <td>
                   {{ $row['output_quantity'] }}     
                </td>
                <td>
                   {{ $row['output_unit_price'] }}     
                </td>
                <td>
                   {{ $row['output_total'] }}     
                </td>


                {{-- SALDO --}}
                @php

                    if($row['type'] == 'input'){

                        $balance_quantity +=  $row['input_quantity'];
                        $balance_total += $row['input_total'];

                    }else{

                        $balance_quantity -= $row['output_quantity'];
                        $balance_total -= $row['output_total'];

                    }
                    
                    $balance_cost = $balance_total / $balance_quantity;


                @endphp
                <td>
                    {{ $balance_quantity }}
                </td>
                <td>
                    {{ $balance_cost }}
                </td>
                <td>
                    {{ $balance_total }}
                </td>

            </tr>
        @endforeach

    </table>
</div>
<br> 
</body>
</html>
