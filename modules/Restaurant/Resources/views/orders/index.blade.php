@extends('tenant.layouts.app')

@section('content')
    <tenant-restautant-orders-index :user="{{ json_encode(auth()->user()) }}"></tenant-restautant-orders-index>
@endsection
