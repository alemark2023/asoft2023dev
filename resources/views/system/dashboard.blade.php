@extends('system.layouts.app')

@section('content')

    <system-clients-index :delete-permission="{{json_encode($delete_permission)}}"></system-clients-index>

@endsection
