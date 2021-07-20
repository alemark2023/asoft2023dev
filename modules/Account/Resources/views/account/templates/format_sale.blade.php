<style>
    .text-center {
        text-align: center;
    }
    .font-weight {
        font-weight: bold;
    }
</style>
@php
$col_span = 25;
@endphp
<table>
    <tr>
        <td colspan="{{ $col_span }}">{{ $company['name'] }}</td>
    </tr>
    <tr>
        <td colspan="{{ $col_span }}">{{ $company['number'] }}</td>
    </tr>
    {{--
    <tr>
        <td colspan="{{ $col_span }}">Moneda: {{$currency->description}}</td>
    </tr>
    --}}
    <tr>
        <td colspan="{{ $col_span }}" class="text-center font-weight">FORMATO 14.1 : "REGISTRO DE VENTAS E INGRESOS DEL
                                                                      PERIODO {{ $period }}"
        </td>
    </tr>
    <tr>
        <td colspan="2">
            NUMERO CORRELATIVO DEL REGISTRO O CUO.
        </td>
        <td>
            FECHA DE EMISION DEL COMPROBANTE DE PAGO O EMISION DEL DOCUMENTO
        </td>
        <td>
            FECHA VENC.
        </td>
        <td colspan="3">
            COMPROBANTE DE PAGO
        </td>
        <td colspan="3">
            INFORMACON DE CLIENTE
        </td>
        <td>
            VALOR<br/>FACTURADO<br/>EXPORTACION
        </td>
        <td>
            BASE<br/>IMPONIBLE<br/>GRAVADA
        </td>
        <td colspan="2">
            IMPORTE TOTAL
        </td>
        <td>
            ISC
        </td>
        <td>VENTA DIFERIDA</td>
        <td>
            IGV Y/O<br/>IMP.
        </td>
        <td>
            OTROS<br/>TRIBUTOS
        </td>
        <td>
            IMPORTE TOTAL
        </td>
        <td>
            TIPO DE<br/>CAMBIO
        </td>
        <td>
            MONEDA
        </td>
        <td colspan="4">
            REFERENCIA DEL COMPROBANTE O<br/>
            DOC. ORIGINAL QUE SE MODIFICA
        </td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td></td>
        <td></td>
        <td>TIPO</td>
        <td>SERIE</td>
        <td>NUMERO</td>
        <td>TIPO</td>
        <td>R.U.C.</td>
        <td>APELLIDOS Y NOMBRES</td>
        <td></td>
        <td></td>
        <td>EXONERADA</td>
        <td>INAFECTA</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>FECHA</td>
        <td>TIPO</td>
        <td>SERIE</td>
        <td>Nro.COMP.</td>
    </tr>
    @foreach($records as $row)
    <tr>
            <?php
            $date_of_issue = $row['date_of_issue'];
            $document_type_id = $row['document_type_id'];
            $total_exportation = 0;
            $total_taxed = 0;
            $total_exonerated = 0;
            $total_unaffected = 0;
            $total_plastic_bag_taxes = 0;
            $total_igv = 0;
            $total = 0;
            $state_type_id = $row['state_type_id'];
            $ok = 0;
            if (
                in_array($document_type_id, ['01', '03']) &&
                in_array($state_type_id, ['09', '11'])){
                // do nothing
                }else {
                $total_exportation = $row['total_exportation'];
                $total_taxed = $row['total_taxed'];
                $total_exonerated = $row['total_exonerated'];
                $total_unaffected = $row['total_unaffected'];
                $total_plastic_bag_taxes = $row['total_plastic_bag_taxes'];
                $total = $row['total'];
                $ok = 1;

            }
            ?>
        <td>06</td>
        <td>{{ $loop->iteration }}</td>
            <td>{{ $date_of_issue }}</td>
        <td></td>
            <td>{{ $document_type_id }}</td>
        <td>{{ $row['series'] }}</td>
        <td>{{ $row['number'] }}</td>
        <td>{{ $row['customer_identity_document_type_id'] }}</td>
        <td>{{ $row['customer_number'] }}</td>
        <td>{{ $row['customer_name'] }}</td>

            <td>{{ $total_exportation }}</td>

            <td>{{$total_taxed }}</td>
            <td>{{ $total_exonerated }}</td>
            <td>{{ $total_unaffected  }}</td>
            <td>{{ $total_plastic_bag_taxes }}</td>
        <td></td>
            <td>{{ $total_igv }}</td>
        <td></td>
            <td>{{ $total }}</td>

        <td>{{ $row['exchange_rate_sale'] }}</td>
        <td>{{ $row['currency_type_symbol'] }}</td>
        @if($row['affected_document'])
            <td>{{ $row['affected_document']['date_of_issue']}}</td>
            <td>{{ $row['affected_document']['document_type_id']}}</td>
            <td>{{ $row['affected_document']['series']}}</td>
            <td>{{ $row['affected_document']['number']}}</td>
        @else
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        @endif
    </tr>
    @endforeach
</table>
