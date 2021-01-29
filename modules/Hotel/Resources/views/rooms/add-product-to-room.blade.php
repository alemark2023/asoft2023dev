@extends('tenant.layouts.app')

@section('content')
    <tenant-hotel-rent-add-product :rent='@json($rent)'></tenant-hotel-rent-add-product>
@endsection
