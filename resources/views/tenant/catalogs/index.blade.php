@extends('tenant.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-6 col-md-12 ui-sortable">
            <tenant-bank_accounts-index></tenant-bank_accounts-index>

            <tenant-currency-types-index></tenant-currency-types-index>

            <tenant-attribute_types-index></tenant-attribute_types-index>
        </div>
        <div class="col-lg-6 col-md-12 ui-sortable">
            <tenant-banks-index></tenant-banks-index>

            <tenant-unit_types-index></tenant-unit_types-index>
        </div>
    </div>
@endsection