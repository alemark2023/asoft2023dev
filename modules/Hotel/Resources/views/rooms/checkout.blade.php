@extends('tenant.layouts.app')

@section('content')
<tenant-hotel-rent-checkout :room='@json($room)' token="{{ $token }}" :customer='@json($customer)' :rent='@json($rent)'>
</tenant-hotel-rent-checkout>
@endsection
