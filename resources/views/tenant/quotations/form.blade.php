@extends('tenant.layouts.app')

@section('content')
    <tenant-quotations-form :type-user="{{json_encode(Auth::user()->type)}}"></tenant-quotations-form>
@endsection
