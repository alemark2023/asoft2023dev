@extends('tenant.layouts.app')

@section('content')
    <tenant-dispatches-form :document="{{ json_encode($document) }}"></tenant-dispatches-form>
@endsection
