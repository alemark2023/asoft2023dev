@extends('tenant.layouts.app')

@section('content')
    <tenant-quotations-edit :resource-id="{{json_encode($resourceId)}}" ></tenant-quotations-edit>
@endsection
