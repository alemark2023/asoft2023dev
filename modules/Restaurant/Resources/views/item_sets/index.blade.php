@extends('tenant.layouts.app')

@section('content')
    <tenant-restautant-item-sets-index
        :type-user="{{json_encode(Auth::user()->type)}}"
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-restautant-item-sets-index>
@endsection
