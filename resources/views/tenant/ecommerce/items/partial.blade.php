<div class="product-single-container product-single-default product-quick-view container">
    <div class="row">
        <div class="col-lg-6 col-md-6 product-single-gallery">
            <div class="product-slider-container product-item">
                <div class="product-single-carousel owl-carousel owl-theme">
                    <div class="product-item">
                        <img class="product-single-image" src="{{ asset('storage/uploads/items/'.$record->image) }}" data-zoom-image="{{ asset('storage/uploads/items/'.$record->image) }}"/>
                    </div>
                    <div class="product-item">
                        <img class="product-single-image" src="{{ asset('storage/uploads/items/'.$record->image_medium) }}" data-zoom-image="{{ asset('storage/uploads/items/'.$record->image_medium) }}"/>
                    </div>
                    <!--<div class="product-item">
                        <img class="product-single-image" src="{{ asset('porto_ecommerce/ajax/assets/images/products/zoom/product-3.html') }}" data-zoom-image="//porto_ecommerce/demo-6/ajax/assets/images/products/zoom/product-3-big.html"/>
                    </div>
                    <div class="product-item">
                        <img class="product-single-image" src="{{ asset('porto_ecommerce/ajax/assets/images/products/zoom/product-4.html') }}" data-zoom-image="/porto_ecommerce/demo-6/ajax/assets/images/products/zoom/product-4-big.html"/>
                    </div>-->
                </div>
                <!-- End .product-single-carousel -->
            </div>
            <div class="prod-thumbnail row owl-dots" id='carousel-custom-dots'>
                <div class="col-3 owl-dot">
                    <img src="{{ asset('storage/uploads/items/'.$record->image) }}"/>
                </div>
                <div class="col-3 owl-dot">
                    <img src="{{ asset('storage/uploads/items/'.$record->image_medium) }}"/>
                </div>
                <!--<div class="col-3 owl-dot">
                    <img src="{{ asset('porto_ecommerce/ajax/assets/images/products/zoom/product-2.html') }}" />
                </div>
                <div class="col-3 owl-dot">
                    <img src="{{ asset('porto_ecommerce/ajax/assets/images/products/zoom/product-3.html') }}" />
                </div>
                <div class="col-3 owl-dot">
                    <img src="{{ asset('porto_ecommerce/ajax/assets/images/products/zoom/product-4.html') }}" />
                </div>-->
            </div>
        </div><!-- End .col-lg-7 -->

        <div class="col-lg-6 col-md-6">
            <div class="product-single-details">
                <h1 class="product-title">{{ $record->name }}</h1>

                <div class="ratings-container">
                    <div class="product-ratings">
                        <span class="ratings" style="width:60%"></span><!-- End .ratings -->
                    </div><!-- End .product-ratings -->

                    <a href="#" class="rating-link">( 6 Reviews )</a>
                </div><!-- End .product-container -->

                <div class="price-box">
                    <span class="old-price">{{ number_format($record->sale_unit_price, 2) }}</span>
                    <span class="product-price">{{ number_format($record->sale_unit_price, 2) }}</span>
                </div><!-- End .price-box -->

                <div class="product-desc">
                    <p>{{$record->description}}</p>
                </div><!-- End .product-desc -->

                <!--<div class="product-filters-container">
                    <div class="product-single-filter">
                        <label>Colors:</label>
                        <ul class="config-swatch-list">
                            <li class="active">
                                <a href="#" style="background-color: #6085a5;"></a>
                            </li>
                            <li>
                                <a href="#" style="background-color: #ab6e6e;"></a>
                            </li>
                            <li>
                                <a href="#" style="background-color: #b19970;"></a>
                            </li>
                            <li>
                                <a href="#" style="background-color: #11426b;"></a>
                            </li>
                        </ul>
                    </div>
                </div> End .product-filters-container -->

                <div class="product-action">
                    <div class="product-single-qty">
                        <input class="horizontal-quantity form-control" type="text">
                    </div><!-- End .product-single-qty -->

                    <a href="#" data-product="{{ json_encode( $record ) }}" class="paction add-cart" title="Add to Cart">
                        <span>Agregar a Carrito</span>
                    </a>
                   <!--  <a href="#" class="paction add-wishlist" title="Add to Wishlist">
                        <span>Add to Wishlist</span>
                    </a>
                    <a href="#" class="paction add-compare" title="Add to Compare">
                        <span>Add to Compare</span>
                    </a> -->
                </div><!-- End .product-action -->

                <!--<div class="product-single-share">
                    <label>Share:</label>
                
                    <div class="addthis_inline_share_toolbox"></div>
                </div> End .product single-share -->
            </div><!-- End .product-single-details -->
        </div><!-- End .col-lg-5 -->
    </div><!-- End .row -->
</div><!-- End .product-single-container -->