@extends('tenant.layouts.app')

@section('content')

    <tenant-person-types-index :type-user="{{json_encode(Auth::user()->type)}}" :item-price-types="{{json_encode($item_price_types)}}" ></tenant-person-types-index>

@endsection