@extends('tenant.layouts.app')

@section('content')

    <tenant-purchase-orders-form :id="{{ json_encode($id) }}"></tenant-purchase-orders-form>

@endsection