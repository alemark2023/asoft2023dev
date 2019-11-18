@extends('tenant.layouts.app')

@section('content')

    <tenant-dashboard-index :type-user="{{ json_encode(auth()->user()->type) }}" ></tenant-dashboard-index>

@endsection
