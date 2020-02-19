@extends('tenant.layouts.app')

@section('content')

    <tenant-order-notes-index :type-user="{{json_encode(Auth::user()->type)}}"></tenant-order-notes-index>

@endsection