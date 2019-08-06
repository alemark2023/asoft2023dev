@extends('tenant.layouts.app')

@section('content')

    <tenant-documents-note :user="{{ json_encode(auth()->user()) }}" :document="{{ json_encode($document) }}"></tenant-documents-note>

@endsection