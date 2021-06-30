@extends('tenant.layouts.app')

@section('content')
    <tenant-documentary-offices
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
        :etapas='@json($offices)'
        :parents='@json($parents)'
        :users='@json($users)'
    ></tenant-documentary-offices>
@endsection
