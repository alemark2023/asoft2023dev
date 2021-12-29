@extends('tenant.layouts.app')

@section('content')
    <tenant-configurations-yobel
        :compania="'{{$migrationConfiguration->compania}}'"
        :usuario="'{{$migrationConfiguration->usuario}}'"
        :password="'{{$migrationConfiguration->password}}'"
        :is_active="'{{$migrationConfiguration->is_active}}'"
        :type-user="{{json_encode(Auth::user()->type)}}"
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-configurations-yobel>

@endsection
