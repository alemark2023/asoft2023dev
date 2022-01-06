@extends('tenant.layouts.app')

@section('content')

    <tenant-machine-type-form
        :id="{{$id}}"></tenant-machine-type-form>

@endsection
