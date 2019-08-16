@extends('tenant.layouts.app')

@section('content')

    <tenant-persons-index :type-user="{{json_encode(Auth::user()->type)}}" :type="{{ json_encode($type) }}"
                            :api_service_token="{{ json_encode($api_service_token) }}"></tenant-persons-index>

@endsection