@extends('tenant.layouts.app')

@section('content')
    <tenant-dispatches-form :document="{{ json_encode($document) }}"
                            :dispatch="{{ json_encode($dispatch) }}"></tenant-dispatches-form>
@endsection
