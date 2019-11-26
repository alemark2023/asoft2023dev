@extends('tenant.layouts.app')

@section('content')
<div class="row">
    <tenant-ecommerce-configuration-info></tenant-ecommerce-configuration-info>
    <tenant-ecommerce-configuration-culqi></tenant-ecommerce-configuration-culqi>
    <tenant-ecommerce-configuration-paypal></tenant-ecommerce-configuration-paypal>

    <tenant-ecommerce-configuration-logo></tenant-ecommerce-configuration-logo>
   <!-- <tenant-ecommerce-configuration-social></tenant-ecommerce-configuration-social> -->

</div>
@endsection

