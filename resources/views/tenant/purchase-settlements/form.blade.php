@extends('tenant.layouts.app')

@section('content')
 
    <tenant-purchase-settlements-form :order_id="{{ json_encode($order_id) }}" :type="{{json_encode($type)}}"></tenant-purchase-settlements-form>

@endsection