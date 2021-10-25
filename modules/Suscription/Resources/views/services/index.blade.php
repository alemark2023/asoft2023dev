@extends('tenant.layouts.app')

@section('content')


    <tenant-suscription-service-index
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-suscription-service-index>


@endsection
