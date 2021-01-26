@extends('tenant.layouts.app')

@section('content')
    <tenant-hotel-rooms :floors='@json($floors)' :categories='@json($categories)' :rooms='@json($rooms)'></tenant-hotel-rooms>
@endsection
