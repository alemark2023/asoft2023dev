@extends('tenant.layouts.app')

@section('content')

    <tenant-restautant-cash-index :type-user="{{json_encode(Auth::user()->type)}}"  ></tenant-restautant-cash-index>

@endsection
