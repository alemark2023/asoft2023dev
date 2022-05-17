@extends('tenant.layouts.app')

@section('content')

    <tenant-sale-notes-index
        :soap-company="{{ json_encode($soap_company) }}"
        :type-user="{{ json_encode(auth()->user()->type) }}"
        :user-permission-override-cpe="{{ json_encode(auth()->user()->permission_override_cpe) }}"
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-sale-notes-index>

@endsection
