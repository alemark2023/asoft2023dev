@extends('tenant.layouts.app')

@section('content')
    <tenant-hotel-rent :room='@json($room)'></tenant-hotel-rent>
@endsection
