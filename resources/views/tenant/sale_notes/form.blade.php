@extends('tenant.layouts.app')

@section('content')
 
    <tenant-sale-notes-form :id="{{ json_encode($id) }}"></tenant-sale-notes-form>

@endsection