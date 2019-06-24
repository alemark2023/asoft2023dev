@extends('tenant.layouts.app')

@section('content')
    <tenant-items-index :type-user="{{json_encode(Auth::user()->type)}}"></tenant-items-index>
@endsection
