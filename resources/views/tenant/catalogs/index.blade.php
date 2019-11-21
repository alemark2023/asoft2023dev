@extends('tenant.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-6 col-md-12 ui-sortable">
            <tenant-bank_accounts-index :type-user="{{json_encode(Auth::user()->type)}}" ></tenant-bank_accounts-index>

            <tenant-currency-types-index :type-user="{{json_encode(Auth::user()->type)}}"></tenant-currency-types-index>

            <tenant-attribute_types-index :type-user="{{json_encode(Auth::user()->type)}}"></tenant-attribute_types-index>
        </div>
        <div class="col-lg-6 col-md-12 ui-sortable">
            <tenant-banks-index :type-user="{{json_encode(Auth::user()->type)}}" ></tenant-banks-index>

            <tenant-unit_types-index :type-user="{{json_encode(Auth::user()->type)}}" ></tenant-unit_types-index>
            
            <tenant-detraction_types-index :type-user="{{json_encode(Auth::user()->type)}}" ></tenant-detraction_types-index>
        </div>
        <div class="col-lg-6 col-md-12 ui-sortable">
            <tenant-card-brands-index :type-user="{{json_encode(Auth::user()->type)}}" ></tenant-card-brands-index>

        </div>
    </div>
@endsection