@extends('tenant.layouts.app')

@section('content')
 
    <tenant-purchases-form :order_id="{{ json_encode($order_id) }}" :type="{{json_encode($type)}}"></tenant-purchases-form>

@endsection