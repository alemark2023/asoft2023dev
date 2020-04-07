@extends('tenant.layouts.app')

@section('content')

    <tenant-sale-notes-index :soap-company="{{ json_encode($soap_company) }}"></tenant-sale-notes-index>

@endsection
