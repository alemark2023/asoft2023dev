@extends('tenant.layouts.app')

@section('content')
    <tenant-salud-specialty
        :type-user="{{json_encode(Auth::user()->type)}}"
    ></tenant-salud-specialty>
@endsection
