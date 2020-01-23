@extends('tenant.layouts.app')

@section('content')

    <inventory-form-masive :current_warehouse="{{$current_warehouse}}"></inventory-form-masive>

@endsection
