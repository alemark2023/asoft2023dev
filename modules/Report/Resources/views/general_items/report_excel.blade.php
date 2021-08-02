<?php
function getLocationData($value, $type = 'sale')
{
    $customer = null;
    $district = '';
    $department = '';
    $province = '';
    $type_doc = null;
    if ($type == 'sale') {
        $type_doc = $value->document;
    }
    if (
        $value &&
        $type_doc &&
        $type_doc->customer
    ) {
        $customer = $type_doc->customer;
    }
    if ($customer != null) {
        if (
            $customer->district &&
            $customer->district->description
        ) {
            $district = $customer->district->description;
        }
        if (
            $customer->department &&
            $customer->department->description
        ) {
            $department = $customer->department->description;
        }
        if (
            $customer->province &&
            $customer->province->description
        ) {
            $province = $customer->province->description;
        }
    }
    return [
        'district' => $district,
        'department' => $department,
        'province' => $province,
    ];
}
?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type"
          content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REPORTE PRODUCTOS</title>
</head>
<body>
@if(!empty($records))
    <div>
        <div class=" ">
            <table>
                <thead>
                <tr>
                    @include('report::general_items.partials.report_excel_header',[
                                'document_type_id'=>$document_type_id,
                                'type'=>$type,
                            ])
                </tr>
                </thead>
                <tbody>
                @if($type == 'sale')
                    @if($document_type_id == '80')
                        @foreach($records as $key => $value)
                            <?php
                            if(isset($qty)) unset($qty);
                            /** @var \App\Models\Tenant\DocumentItem $value */
                            $series = '';
                            if (isset($value->item->lots)) {
                                $series_data = collect($value->item->lots)->where('has_sale', 1)->pluck('series')->toArray();
                                $series = implode(" - ", $series_data);
                            }
                            // $purchase_unit_price = 0;
                            // if($value->relation_item->purchase_unit_price > 0){
                            //     $purchase_unit_price = $value->relation_item->purchase_unit_price;
                            // }else{
                            //     $purchase_item = \App\Models\Tenant\PurchaseItem::select('unit_price')->where('item_id', $value->item_id)->latest('id')->first();
                            //     $purchase_unit_price = ($purchase_item) ? $purchase_item->unit_price : $value->unit_price;
                            // }
                            $total_item_purchase = \Modules\Report\Http\Resources\GeneralItemCollection::getPurchaseUnitPrice($value);
                            // $total_item_purchase = $purchase_unit_price * $value->quantity;
                            $utility_item = $value->total - $total_item_purchase;
                            /** @var \App\Models\Tenant\Item $item */
                            $item = $value->getModelItem();
                            $model = $item->model;
                            $document = $value->sale_note;
                            $platform = $item->getWebPlatformModel();
                            if ($platform !== null) {
                                $platform = $platform->name;
                            }
                            $pack = $item->getSetItems();
                            ?>
                            @include('report::general_items.partials.report_excel_body_sale_80',[
                                      'document_type_id'=>$document_type_id,
                                      'document'=>$document,
                                      'type'=>$type,
                                      'value'=>$value,
                                      'key'=>$key,
                                      'item'=>$item,
                                  ])
                            @if($pack !== null)
                                @foreach($pack as $item_pack)
                                    <?php
                                    /** @var \App\Models\Tenant\ItemSet $item_pack */
                                    $value->item = $item_pack->individual_item;
                                    /** @var \App\Models\Tenant\Item $item */
                                    $item = $value->item;
                                    $qty = $item_pack->quantity;
                                    ?>
                                    @include('report::general_items.partials.report_excel_body_sale_80',[
                                                                           'document_type_id'=>$document_type_id,
                                                                           'document'=>$document,
                                                                           'type'=>$type,
                                                                           'value'=>$value,
                                                                           'key'=>$key,
                                                                           'item'=>$item,
                                                                           'qty'=>$qty,
                                                                       ])
                                @endforeach
                            @endif
                        @endforeach
                    @else
                        @foreach($records as $key => $value)
                            <?php
                                if(isset($qty)) unset($qty);
                            /** @var \App\Models\Tenant\DocumentItem $value */
                            $series = '';
                            if (isset($value->item->lots)) {
                                $series_data = collect($value->item->lots)->where('has_sale', 1)->pluck('series')->toArray();
                                $series = implode(" - ", $series_data);
                            }
                            $total_item_purchase = \Modules\Report\Http\Resources\GeneralItemCollection::getPurchaseUnitPrice($value);
                            $utility_item = $value->total - $total_item_purchase;
                            $item = $value->getModelItem();
                            $model = $item->model;
                            /** @var  \App\Models\Tenant\Document $document */
                            $document = $value->document;
                            $purchseOrder = $document->purchase_order;
                            $platform = $item->getWebPlatformModel();
                            if ($platform !== null) {
                                $platform = $platform->name;
                            }
                            $pack = $item->getSetItems();
                            $item = $value->item;
                            $stablihsment = getLocationData($value, $type);
                            ?>
                            @include('report::general_items.partials.report_excel_body_sale',
                                    [
                                        'document_type_id'=>$document_type_id,
                                        'document'=>$document,
                                        'type'=>$type,
                                        'value'=>$value,
                                        'key'=>$key,
                                        'item'=>$item,
                                        'stablihsment'=>$stablihsment,
                                    ])
                            @if($pack !== null)
                                @foreach($pack as $item_pack)
                                    <?php
                                    /** @var \App\Models\Tenant\ItemSet $item_pack */
                                    $value->item = $item_pack->individual_item;
                                    /** @var \App\Models\Tenant\Item $item */
                                    $item = $value->item;
                                    $qty = $item_pack->quantity;
                                    // dd($item);
                                    ?>
                                    @include('report::general_items.partials.report_excel_body_sale',
                                                                       [
                                                                           'document_type_id'=>$document_type_id,
                                                                           'document'=>$document,
                                                                           'type'=>$type,
                                                                           'value'=>$value,
                                                                           'key'=>$key,
                                                                           'item'=>$item,
                                                                           'qty'=>$qty,
                                                                           'stablihsment'=>$stablihsment,
                                                                       ])
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @else
                    @foreach($records as  $value)
                        <?php
                        /** @var \App\Models\Tenant\SaleNoteItem  $value */
                        $purchase = $value->purchase;
                        ?>
                        @if($purchase !== null)
                        <tr>
                            <td class="celda">{{$purchase->date_of_issue->format('Y-m-d')}}</td>
                            <td class="celda">{{$purchase->document_type->description}}</td>
                            <td class="celda">{{$purchase->document_type_id}}</td>
                            <td class="celda">{{$purchase->series}}</td>
                            <td class="celda">{{$purchase->number}}</td>
                            <td class="celda">{{$purchase->state_type_id == '11' ? 'SI':'NO'}}</td>
                            <td class="celda">{{$purchase->supplier->identity_document_type->description}}</td>
                            <td class="celda">{{$purchase->supplier->number}}</td>
                            <td class="celda">{{$purchase->supplier->name}}</td>
                            <td class="celda">{{$purchase->currency_type_id}}</td>
                            <td class="celda">{{$purchase->exchange_rate_sale}}</td>
                            <td class="celda">{{$value->item->unit_type_id}}</td>
                            <td class="celda">{{$value->relation_item ? $value->relation_item->internal_id:''}}</td>
                            <td class="celda">{{$value->item->description}}</td>
                            <td class="celda">{{$value->quantity}}</td>
                            <td class="celda"></td>
                            <td class="celda"></td>
                            <td class="celda">{{$value->unit_value}}</td>
                            <td class="celda">{{$value->unit_price}}</td>
                            <td class="celda">
                                @if($value->discounts)
                                    {{collect($value->discounts)->sum('amount')}}
                                @endif
                            </td>
                            <td class="celda">{{$value->total_value}}</td>
                            <td class="celda">{{$value->affectation_igv_type_id}}</td>
                            <td class="celda">{{$value->total_igv}}</td>
                            <td class="celda">{{$value->system_isc_type_id}}</td>
                            <td class="celda">{{$value->total_isc}}</td>
                            <td class="celda">{{$value->total_plastic_bag_taxes}}</td>
                            <td class="celda">{{$value->total}}</td>
                            <td class="celda"></td>
                        </tr>
                        @endif
                    @endforeach
                @endif
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
