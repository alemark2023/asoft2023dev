@extends('tenant.layouts.app')

@section('content')
    <tenant-hotel-rent-checkout :rent='@json($rent)'></tenant-hotel-rent-checkout>
@endsection
