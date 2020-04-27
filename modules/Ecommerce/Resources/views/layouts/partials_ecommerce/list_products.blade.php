@foreach ($dataPaginate as $item)

    <div class="col-6 col-md-4">
        <div class="product">
            <figure class="product-image-container">
                <a href="/ecommerce/item/{{ $item->id }}" class="product-image">
                    <img src="{{ asset('storage/uploads/items/'.$item->image) }}" alt="product">
                </a>
                <a href="{{route('item_partial', ['id' => $item->id])}}" class="btn-quickview">Vista Rápida</a>
                {{-- <span class="product-label label-sale">-20%</span> --}}
                @if(json_encode($item->is_new) == 1)
                    <span class="product-label label-hot">New</span>
                @endif
            </figure>
            <div class="product-details">
                <div class="ratings-container">
                    <div class="product-ratings">
                        <span class="ratings" style="width:0%"></span>
                    </div>
                </div>
                {{ $item->internal_id }}
                <h2 class="product-title">
                    <a href="/ecommerce/item/{{ $item->id }}">{{$item->name}}</a>
                    <!-- <label style="cursor:pointer">{{$item->name}}</label> -->
                </h2>
                <div class="price-box">
                    <!-- <span class="old-price">S/ {{ number_format( ($item->sale_unit_price * 1.2 ) , 2 )}}</span> -->
                    <span class="product-price">S/ {{ number_format($item->sale_unit_price, 2) }}</span>
                </div>
                <div class="product-action">
                    <a href="#" class="paction add-cart" data-product="{{ json_encode( $item ) }}" title="Add to Cart">
                        <span>Agregar a Carrito</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

@endforeach
