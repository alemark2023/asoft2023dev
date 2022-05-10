@extends('tenant.layouts.web')

@section('content')

    <tenant-public-payment-links-index
        :payment_link="{{json_encode($payment_link)}}"
        :company="{{json_encode($company)}}"
        :payment_configuration="{{json_encode($payment_configuration)}}"
        :total="{{json_encode($total)}}"
        :apply_conversion="{{json_encode($apply_conversion)}}"
    >
    </tenant-public-payment-links-index>

@endsection