<?php

use App\Models\Tenant\Document;
use App\CoreFacturalo\Helpers\Template\TemplateHelper;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Series;

$col_num=10;
    //dd($columns);
foreach ($columns as $value) {
    switch ($value->title) {
        case 'Opciones':
            $col_num=$col_num;
            break;
        case 'Total ISC':
            $col_num+=1;
            break;
        case 'Total Cargos':
            $col_num+=1;
            break;
        
        default:
            if ($value->visible) {
                $col_num+=1;
            }
            break;
    }
}

$document_types=DocumentType::OnlyAvaibleDocuments()->get();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
        <style>
            @page {
              margin: 5;
            }
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
            <p align="center" class="title"><strong>Reporte Documentos</strong></p>
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

                @inject('reportService', 'Modules\Report\Services\ReportService')
                <tr>
                    @if($filters['seller_id'])
                    <td>
                        <p><strong>Usuario: </strong>{{$reportService->getUserName($filters['seller_id'])}}</p>
                    </td>
                    @endif
                    @if($filters['person_id'])
                    <td>
                        <p><strong>Cliente: </strong>{{$reportService->getPersonName($filters['person_id'])}}</p>
                    </td>
                    @endif
                </tr>
            </table>
        </div>
        @if(!empty($records))
        <div class="">
            <div class=" ">
                @php
                    $acum_total_charges=0;
                    $acum_total_taxed=0;
                    $acum_total_igv=0;
                    $acum_total=0;
    
                    $serie_affec = '';
                    $acum_total_exonerado=0;
                    $acum_total_inafecto=0;
    
                    $acum_total_free=0;
    
                    $acum_total_taxed_usd = 0;
                    $acum_total_igv_usd = 0;
                    $acum_total_usd = 0;
    
                    $acum_documents=[];
                    $acum_series=[];
                    $clear_type=[];
                    $clear_series=[];
                    foreach ($records as $key => $value) {
                        $document_type = $value->getDocumentType();
                        $clear_type[] = $document_type->id;
                        $clear_serie[] = $value->series;
                        
                    }
                    $clear_type=array_unique($clear_type);
                    $clear_serie=array_unique($clear_serie);
                    $clear_type=array_values($clear_type);
                    $clear_series=array_values($clear_series);
                    
                    //dd($clear_serie);
                @endphp
                {{-- @foreach($document_types as $type) --}}
                
                @for ($i = 0; $i < count($clear_type); $i++)
                    @for ($c = 0; $c < count($document_types); $c++)
                        @if ($document_types[$c]->id==$clear_type[$i])
                            @php
                                $acum_documents[]=[
                                    'id'=>$document_types[$c],
                                    'description'=>$document_types[$c]->description,
                                ];
                                $series_document=Series::FilterDocumentType($document_types[$c]->id)->select('number')->get();
                                //dd($document_types[$c]->id==$clear_type[$i]);
                                $title=$document_types[$c]->description;
                                //dd($series_document);
                            @endphp
                            @for ($cs = 0; $cs < count($clear_serie); $cs++)
                            @for ($s = 0; $s < count($series_document); $s++)
                                @php
                                    $serie_type=$series_document[$s];
                                    
                                @endphp
                                @if ($serie_type['number']==$clear_serie[$cs])
                                    @php
    
                                        $serie_number=$serie_type['number'];
                                    @endphp
                    <h3>{{$title}} - {{$serie_number}}</h3>
                    <table class="">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th class="">Usuario/Vendedor</th>
                            <th>Tipo Doc</th>
                            <th>Número</th>
                            <th>Fecha emisión</th>
                            <th>Fecha Vencimiento</th>
                            <th>Doc. Afectado</th>
                            @if ($columns->guides->visible)
                                <th># Guía</th>
                            @endif
                            
                            <th>Cotización</th>
                            <th>Caso</th>
                            @if ($columns->district->visible)
                            <th>DIST</th>
                            @endif
                            @if ($columns->department->visible)
                            <th>DPTO</th>
                            @endif
                            @if ($columns->province->visible)
                            <th>PROV</th>
                            @endif
                            @if ($columns->client_direction->visible)
                            <th>Direccion de cliente</th>
                            @endif
                            <th>Cliente</th>
                            @if ($columns->ruc->visible)
                            <th>RUC</th>
                            @endif
                            
                            <th>Estado</th>
                            <th class="">Moneda</th>
                            @if ($columns->web_platforms->visible)
                            <th>Plataforma</th>
                            @endif
                            
                            <th>Orden de compra</th>
                            @if ($columns->note_sale->visible)
                            <th>Nota de venta</th>
                            @endif
                            @if ($columns->date_note->visible)
                            <th>Fecha N. Venta</th>
                            @endif
                            @if ($columns->payment_form->visible)
                            <th class="">Forma de pago</th>
                            @endif
                            @if ($columns->payment_method->visible)
                            <th> MÉTODO DE PAGO </th>
                            @endif
                            @if ($columns->total_charge->visible)
                            <th>Total Cargos</th>
                            @endif
                            <th>Total Exonerado</th>
                            <th>Total Inafecto</th>
                            <th>Total Gratuito</th>
                            <th>Total Gravado</th>
                            <th>Descuento total</th>
                            <th>Total IGV</th>
                            @if ($columns->total_isc->visible)
                            <th>Total ISC</th>
                            @endif
                            <th>Total</th>
                            <th>Total de productos</th>
        
                        </tr>
                        </thead>
                        <tbody>
                        {{-- @foreach($records as $key => $value) --}}
                        @for ($t = 0; $t < count($records); $t++)
                            
                            <?php
                            $value=$records[$t];
                            /** @var \App\Models\Tenant\Document|App\Models\Tenant\SaleNote  $value */
                            //$iteration = $loop->iteration;
                                            $userCreator = $value->user->name;
                            $document_type = $value->getDocumentType();
                            $seller = \App\CoreFacturalo\Helpers\Template\ReportHelper::getSellerData($value);
                            try{
                                $user = $seller->name;
                            }catch (ErrorException $e){
                                $user = '';
                            }
        
                            ?>
        
                            ?>
                            @if ($document_types[$c]->id==$document_type->id)
                            @if ($serie_type['number']==$value->series)
                            <tr>
                                <td class="celda">{{$t+1}}</td>
                                <td class="celda">
                                    @if($filters['user_type']==='CREADOR')
                                        {{$userCreator}}
                                    @else
                                        {{$user}}
                                    @endif
                                </td>
                                <td class="celda">{{$document_type->id}}</td>
                                <td class="celda">{{$value->series}}-{{$value->number}}</td>
                                <td class="celda">{{$value->date_of_issue->format('Y-m-d')}}</td>
                                <td class="celda">{{isset($value->invoice) ? $value->invoice->date_of_due->format('Y-m-d'):''}}</td>
                                @if(in_array($document_type->id,["07","08"]) && $value->note)
        
                                    @php
                                        $serie = ($value->note->affected_document) ? $value->note->affected_document->series : $value->note->data_affected_document->series;
                                        $number =  ($value->note->affected_document) ? $value->note->affected_document->number : $value->note->data_affected_document->number;
                                        $serie_affec = $serie.' - '.$number;
        
                                    @endphp
        
        
                                @endif
                                <td class="celda">{{$serie_affec }} </td>
                                @if ($columns->guides->visible)
                                <td class="celda">
                                    @if(!empty($value->guides))
                                        @foreach($value->guides as $guide)
                                            {{ $guide->number }}<br>
                                        @endforeach
                                    @endif
                                </td>
                                @endif
                                
                                <td class="celda">{{ ($value->quotation) ? $value->quotation->number_full : '' }}</td>
                                <td class="celda">{{ isset($value->quotation->sale_opportunity) ? $value->quotation->sale_opportunity->number_full : '' }}</td>
        
                                <?php $stablihsment = \App\CoreFacturalo\Helpers\Template\ReportHelper::getLocationData($value); ?>
                                @if ($columns->district->visible)
                                <td class="celda">{{$stablihsment['district']}}</td>
                                @endif
                                @if ($columns->department->visible)
                                <td class="celda">{{$stablihsment['department']}}</td>
                                @endif
                                @if ($columns->province->visible)
                                <td class="celda">{{$stablihsment['province']}}</td>
                                @endif
                                @if ($columns->client_direction->visible)
                                <td class="celda">{{$value->customer->address}}</td>
                                @endif
                                <td class="celda">{{$value->customer->name}}</td>
                                @if ($columns->ruc->visible)
                                <td class="celda">{{$value->customer->number}}</td>
                                @endif
                                <td class="celda">{{$value->state_type->description}}</td>
        
                                @php
                                    $signal = $document_type->id;
                                    $state = $value->state_type_id;
                                @endphp
        
                                <td class="celda">{{$value->currency_type_id}}</td>
                                @if ($columns->web_platforms->visible)
                                <td class="celda">
                                    @foreach ($value->getPlatformThroughItems() as $platform)
                                        <label class="d-block">{{$platform->name}}</label>
                                    @endforeach
                                </td>
                                @endif
                                
                                <td class="celda">{{$value->purchase_order}}</td>
        
                                @if($value->sale_note)
                                    @if ($columns->note_sale->visible)
                                    <td class="celda">{{ $value->sale_note->number_full }}</td>
                                    @endif
                                    @if ($columns->date_note->visible)
                                    <td class="celda">{{ $value->sale_note->date_of_issue->format('Y-m-d') }}</td>
                                    @endif
                                @else
                                    @if ($columns->note_sale->visible)
                                    <td class="celda"></td>
                                    @endif
                                    @if ($columns->date_note->visible)
                                    <td class="celda"></td>
                                    @endif
                                @endif
                                @if ($columns->payment_form->visible)
                                <td class="celda">
                                    {{ ($value->payments()->count() > 0) ? $value->payments()->first()->payment_method_type->description : ''}}
                                </td>
                                @endif
                                @if ($columns->payment_method->visible)
                                <td class="celda">
                                    @php
                                    $payments= [];
                                    if(
                                        get_class($value) == Document::class ||
                                        get_class($value) == SaleNote::class
                                    ){
                                        $payments = TemplateHelper::getDetailedPayment($value);
                                    }
                                    @endphp
        
                                    @foreach ($payments as $payment)
                                        @foreach ($payment as $pay)
                                            {{ $pay['description'] }}
                                            @if ($loop->count > 1 && !$loop->last)
                                                <br>
                                            @endif
                                        @endforeach
                                    @endforeach
        
                                </td>
                                @endif
        
                            <!-- <td class="celda">{{($signal == '07' || ($signal!='07' && $state =='11')) ? "-" : ""  }}{{$value->total_exonerated}} </td>
                                        <td class="celda">{{($signal == '07' || ($signal!='07' && $state =='11')) ? "-" : ""  }}{{$value->total_unaffected}}</td>
                                        <td class="celda">{{($signal == '07' || ($signal!='07' && $state =='11')) ? "-" : ""  }}{{$value->total_free}}</td>
        
                                        <td class="celda">{{($signal == '07' || ($signal!='07' && $state =='11')) ? "-" : ""  }}{{$value->total_taxed}}</td>
        
                                        <td class="celda">{{($signal == '07' || ($signal!='07' && $state =='11')) ? "-" : ""  }}{{$value->total_igv}}</td>
                                        <td class="celda">{{($signal == '07' || ($signal!='07' && $state =='11')) ? "-" : ""  }}{{$value->total}}</td> -->
        
                                @if($signal == '07')
        
                                    @if(in_array($value->state_type_id,['09','11']))
                                        @if ($columns->total_charge->visible)
                                            <td class="celda">0</td>
                                        @endif
                                            <td class="celda">0</td>
                                            <td class="celda">0</td>
                                            <td class="celda">0</td>
                                            <td class="celda">0</td>
                                            <td class="celda">0</td>
                                            <td class="celda">0</td>
                                            @if ($columns->total_isc->visible)
                                            <td class="celda">0</td>
                                            @endif
                                            <td class="celda">0</td>
                                        
                                    @else
                                        @if ($columns->total_charge->visible)
                                        <td class="celda">{{$signal == '07' ? "-" : ""  }}{{$value->total_charge}}</td>
                                        @endif
                                        <td class="celda">{{$signal == '07' ? "-" : ""  }}{{$value->total_exonerated}}</td>
                                        <td class="celda">{{$signal == '07' ? "-" : ""  }}{{$value->total_unaffected}}</td>
                                        <td class="celda">{{$signal == '07' ? "-" : ""  }}{{$value->total_free}}</td>
                                        <td class="celda">{{$signal == '07' ? "-" : ""  }}{{$value->total_taxed}}</td>
                                        <td class="celda">{{$value->total_discount}}</td>
                                        <td class="celda">{{$signal == '07' ? "-" : ""  }}{{$value->total_igv}}</td>
                                        @if ($columns->total_isc->visible)
                                        <td class="celda">{{$signal == '07' ? "-" : ""  }}{{$value->total_isc}}</td>
                                        @endif
                                        <td class="celda">{{$signal == '07' ? "-" : ""  }}{{$value->total}}</td>
                                    @endif
        
                                @else
                                    @if ($columns->total_charge->visible)
                                        <td class="celda">{{ (in_array($document_type->id,['01','03']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total_charge}}</td>
                                    @endif
                                    <td class="celda">{{ (in_array($document_type->id,['01','03']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total_exonerated}}</td>
                                    <td class="celda">{{ (in_array($document_type->id,['01','03']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total_unaffected}}</td>
                                    <td class="celda">{{ (in_array($document_type->id,['01','03']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total_free}}</td>
        
                                    <td class="celda">{{ (in_array($document_type->id,['01','03']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total_taxed}}</td>
                                    <td class="celda">{{ (in_array($document_type->id,['01','03']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total_discount}}</td>
                                    <td class="celda">{{ (in_array($document_type->id,['01','03']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total_igv}}</td>
                                    @if ($columns->total_isc->visible)
                                    <td class="celda">{{ (in_array($document_type->id,['01','03']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total_isc}}</td>
                                    @endif
                                    <td class="celda">{{ (in_array($document_type->id,['01','03']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total}}</td>
        
                                @endif
        
                                
        
                                @php
        
                                    $value->total_exonerated = (in_array($document_type->id,['01','03', '07']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total_exonerated;
                                    $value->total_unaffected = (in_array($document_type->id,['01','03', '07']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total_unaffected;
                                    $value->total_free = (in_array($document_type->id,['01','03', '07']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total_free;
        
                                    $value->total_taxed = (in_array($document_type->id,['01','03', '07']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total_taxed;
                                    $value->total_igv = (in_array($document_type->id,['01','03', '07']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total_igv;
                                    $value->total = (in_array($document_type->id,['01','03', '07']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total;
                                @endphp
        
                                @php
        
                                    $serie_affec =  '';
        
                                    $quality_item=0;
                                    foreach ($value->items as $itm) {
                                        $quality_item+=$itm->quantity;
                                    }
        
                                @endphp
                                <td>{{$quality_item}}</td>
                            </tr>
                            @php
                                if($value->currency_type_id == 'PEN'){
                                    /*$acum_total_taxed +=  $signal != '07' ? $value->total_taxed : -$value->total_taxed ;
                                    $acum_total_igv +=  $signal != '07' ? $value->total_igv : -$value->total_igv ;
                                    $acum_total += $signal != '07' ? $value->total : -$value->total ;*/
        
                                    /*$acum_total_exonerado += $signal != '07' ? $value->total_exonerated : -$value->total_exonerated ;
                                    $acum_total_inafecto += $signal != '07' ? $value->total_unaffected : -$value->total_unaffected ;
                                    $acum_total_free += $signal != '07' ? $value->total_free : -$value->total_free ;*/
        
        
                                    if(($signal == '07' && $state !== '11')){
        
                                        $acum_total += -$value->total;
                                        $acum_total_taxed += -$value->total_taxed;
                                        $acum_total_igv += -$value->total_igv;
        
                                        $acum_total_charges += -$value->total_charge;
                                        $acum_total_exonerado += -$value->total_exonerated;
                                        $acum_total_inafecto += -$value->total_unaffected;
                                        $acum_total_free += -$value->total_free;
        
        
                                    }elseif($signal != '07' && $state == '11'){
        
                                        $acum_total += 0;
                                        $acum_total_taxed += 0;
                                        $acum_total_igv += 0;
        
                                        $acum_total_charges += 0;
                                        $acum_total_exonerado += 0;
                                        $acum_total_inafecto += 0;
                                        $acum_total_free += 0;
        
                                    }else{
        
                                        $acum_total += $value->total;
                                        $acum_total_taxed += $value->total_taxed;
                                        $acum_total_igv += $value->total_igv;
        
                                        $acum_total_charges += $value->total_charge;
                                        $acum_total_exonerado += $value->total_exonerated;
                                        $acum_total_inafecto += $value->total_unaffected;
                                        $acum_total_free += $value->total_free;
                                    }
    
        
        
                                }else if($value->currency_type_id == 'USD'){
        
                                    if(($signal == '07' && $state !== '11')){
        
                                        $acum_total_usd += -$value->total;
                                        $acum_total_taxed_usd += -$value->total_taxed;
                                        $acum_total_igv_usd += -$value->total_igv;
        
        
        
                                    }elseif($signal != '07' && $state == '11'){
        
                                        $acum_total_usd += 0;
                                        $acum_total_taxed_usd += 0;
                                        $acum_total_igv_usd += 0;
        
        
                                    }else{
        
                                        $acum_total_usd += $value->total;
                                        $acum_total_taxed_usd += $value->total_taxed;
                                        $acum_total_igv_usd += $value->total_igv;
        
                                    }
        
        
                                }
                                
                            @endphp
                            
                            @endif
                            @endif
                        @endfor
                        <tr>
                            @php
                                //$acum_series=$acum_total;
                                //array_push($acum_series,['total'=>$acum_total]);
                            @endphp
                            <td colspan="{{$col_num}}"></td>
                        <!-- <td >Totales</td>
                                        <td>{{$acum_total_exonerado}}</td>
                                        <td>{{$acum_total_inafecto}}</td>
                                        <td>{{$acum_total_free}}</td> -->
                            <td>Totales PEN</td>
                            @if ($columns->total_charge->visible)
                            <td>{{number_format($acum_total_charges, 2)}}</td>
                            @endif
                            <td>{{number_format($acum_total_exonerado, 2)}}</td>
                            <td>{{number_format ($acum_total_inafecto, 2 )}}</td>
                            <td>{{number_format($acum_total_free, 2)}}</td>
        
                            <td>{{$acum_total_taxed}}</td>
                            <td></td>
                            <td>{{$acum_total_igv}}</td>
                            @if ($columns->total_isc->visible)
                            <td></td>
                            @endif
                            <td>{{$acum_total}}</td>
                        </tr>
                        <tr>
                            <td colspan="{{$col_num}}"></td>
                            <td>Totales USD</td>
                            @if ($columns->total_charge->visible)
                            <td></td>
                            @endif
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{$acum_total_taxed_usd}}</td>
                            <td></td>
                            <td>{{$acum_total_igv_usd}}</td>
                            @if ($columns->total_isc->visible)
                            <td></td>
                            @endif
                            <td>{{$acum_total_usd}}</td>
                        </tr>
        
                        </tbody>
                    </table>
                    @php
                    $acum_series[]=[
                        'document_id'=>$document_types[$c],
                        'number' =>$serie_type->number,
                        'total' => $acum_total,
                    ];
                @endphp     
                            @endif{{-- DOCUMENTOS SEGUN ID SERIE --}}
                            @endfor{{-- SERIES TOTALES --}}
                            @endfor{{-- SERIES TOTALES --}}
                            
                        @endif{{-- IGUALDAD DE ID --}}
                    @endfor{{-- LOS DOCUMENTOS --}}
                @endfor{{-- TIPOS DE DOCUMENTO --}}
                <h3>TOTAL POR SERIE Y DOCUMENTO</h3>
                @php
                    //dd($acum_series);
                @endphp
                <table>
                    <thead>
                        <tr>
                            <th>DOC</th>
                            <th class="">SERIE</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($acum_documents as $document)
                        @php
                            //dd($document['id']);
                        @endphp
                            @foreach ($acum_series as $serie)
                                @if ($document['id']==$serie['document_id'])
                                    <tr>
                                        <td>{{$document['description']}}</td>
                                        <td>{{$serie['number']}}</td>
                                        <td>{{$serie['total']}}</td>
                                    </tr>
                                @endif
                            @endforeach
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
