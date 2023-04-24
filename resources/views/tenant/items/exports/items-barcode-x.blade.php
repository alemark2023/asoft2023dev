<!DOCTYPE html>
<html lang="es">
    <head>
        <style>

        </style>
    </head>
    <body>
        @if(!empty($record))
            <div class="" >
                <div class=" " >
                    <table class="table" width="100%">
                        @php
                            function withoutRounding($number, $total_decimals) {
                                $number = (string)$number;
                                if($number === '') {
                                    $number = '0';
                                }
                                if(strpos($number, '.') === false) {
                                    $number .= '.';
                                }
                                $number_arr = explode('.', $number);

                                $decimals = substr($number_arr[1], 0, $total_decimals);
                                if($decimals === false) {
                                    $decimals = '0';
                                }

                                $return = '';
                                if($total_decimals == 0) {
                                    $return = $number_arr[0];
                                } else {
                                    if(strlen($decimals) < $total_decimals) {
                                        $decimals = str_pad($decimals, $total_decimals, '0', STR_PAD_RIGHT);
                                    }
                                    $return = $number_arr[0] . '.' . $decimals;
                                }
                                return $return;
                            }
                        @endphp
                        @for($i=0; $i < $stock; $i+=3)
                        <tr>
                            @for($j=0; $j < $format; $j++)
                                @if($format == 1)
                                    <td class="celda" width="100%" style="text-align: center; padding-top: 0px; padding-bottom: 10px; font-size: 9px; vertical-align: top; width: 100%;">
                                        <tr>
                                            <td colspan="2" width="80%" style="text-align: center;">
                                                {{ $record->name }}
                                            </td>
                                            <td>
                                                PRECIO
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;">
                                                MOD: {{ $record->model }}
                                            </td>
                                            <td>
                                                COD: {{ $record->internal_id }}
                                            </td style="text-align: center;">
                                            <td rowspan="2">
                                                {{withoutRounding($record->sale_unit_price, 2)}} {{$record->currency_type->symbol}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="text-align: center;">
                                                <p>
                                                    @php
                                                        $colour = [0,0,0];
                                                        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                                                        echo '<img style="width:110px; max-height: 40px;" src="data:image/png;base64,' . base64_encode($generator->getBarcode($record->barcode, $generator::TYPE_CODE_128, 2, 80, $colour)) . '">';
                                                    @endphp
                                                </p>
                                                <p>{{$record->barcode}}</p>
                                            </td>
                                        </tr>
                                    </td>
                                @elseif($format == 2)
                                    <td class="celda" width="50%" style="text-align: center; padding-bottom: 10px; font-size: 9px; vertical-align: top;">
                                        <table width="100%" class="table">
                                            <tr>
                                                <td colspan="2" style="text-align: left; padding-left: 10%;">
                                                    <b>{{ $record->name ? $record->name : $record->description }}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                @php
                                                    $show_price = \App\Models\Tenant\Configuration::first()->isShowPriceBarcodeTicket();
                                                @endphp
                                                <td style="text-align: left; padding-left: 10%;">
                                                    MOD: {{ $record->model }}<br>
                                                    COD: {{ $record->internal_id }}
                                                </td>
                                                @if($show_price)
                                                    <td>
                                                        <strong>{{ $record->currency_type->symbol }} {{ round($record->sale_unit_price, 2) }}</strong>
                                                    </td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <p>
                                                        @php
                                                            $colour = [0,0,0];
                                                            $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                                                            echo '<img style="width:110px; max-height: 40px;" src="data:image/png;base64,' . base64_encode($generator->getBarcode($record->barcode, $generator::TYPE_CODE_128, 2, 80, $colour)) . '">';
                                                        @endphp
                                                    </p>
                                                    <p>{{$record->barcode}}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                @else
                                <td class="celda" width="33%" style="text-align: center; padding-top: 10px; padding-bottom: 10px; font-size: 9px; vertical-align: top;">
                                    <p>{{ $record->internal_id }}</p>
                                    <p>
                                        @php
                                            $colour = [0,0,0];
                                            $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                                            echo '<img style="width:110px; max-height: 40px;" src="data:image/png;base64,' . base64_encode($generator->getBarcode($record->barcode, $generator::TYPE_CODE_128, 1, 60, $colour)) . '">';
                                        @endphp
                                    </p>
                                    <p>{{$record->barcode}}</p>
                                </td>
                                @endif
                            @endfor
                        </tr>
                        @endfor
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
