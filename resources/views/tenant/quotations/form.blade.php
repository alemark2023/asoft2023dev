@extends('tenant.layouts.app')

@section('content')
    <tenant-quotations-form
        :record-id="{{json_encode($id)}}"
        :type-user="{{json_encode(Auth::user()->type)}}"
        :sale-opportunity-id="{{json_encode($sale_opportunity_id)}}"
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-quotations-form>
@endsection
