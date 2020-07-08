@extends('system.layouts.app')

@section('content')
	<system-backup :disc-used="{{json_encode($disc_used)}}" :storage-size="{{json_encode($storage_size)}}"></system-backup>
@endsection
