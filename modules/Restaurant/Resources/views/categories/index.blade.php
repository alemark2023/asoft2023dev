@extends('restaurant::layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <nav class="main-nav flex-grow-1">
            <ul class="menu sf-arrows sf-js-enabled" style="touch-action: pan-y;">
                @foreach ($categories as $category)
                    <li><a href="{{ route('tenant.restaurant.category', ['category_id' => $category->name]) }}">{{$category->name}}</a></li>
                @endforeach
            </ul>
        </nav>

        <div class="col-lg-12">
            <div class="my-3"></div><!-- margin -->
            <div class="row row-sm mt-4">
                @include('ecommerce::layouts.partials_ecommerce.list_products')
            </div>
            <div class="row float-right">
              <div class="col-md-12 col-lg-12">
                {{ $dataPaginate->links() }}
              </div>
            </div>
        </div>
    </div>
</div>
@endsection