@extends('tenant.layouts.app')

@section('content')

    <tenant-logistic-operator-yobel-form
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-logistic-operator-yobel-form>

@endsection
