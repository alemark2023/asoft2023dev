@extends('tenant.layouts.app')

@section('content')
    <tenant-hotel-rent-checkout
        :room='@json($room)'
        :customer='@json($customer)'
        :rent='@json($rent)'
        :payment-method-types='{{ $payment_method_types }}'
        :payment-destinations='{{ $payment_destinations }}'
        :all-series='{{ $series }}'
        :document-types-invoice='{{ $document_types_invoice }}'
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
        :affectation-igv-types='{{ $affectation_igv_types }}'
    >
    </tenant-hotel-rent-checkout>
@endsection
