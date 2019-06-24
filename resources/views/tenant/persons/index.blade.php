@extends('tenant.layouts.app')

@section('content')

    <tenant-persons-index :type="{{ json_encode($type) }}"></tenant-persons-index>

@endsection