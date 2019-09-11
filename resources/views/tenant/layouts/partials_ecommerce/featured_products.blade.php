  <h2 class="carousel-title">Featured Products</h2>



  <div class="home-featured-products owl-carousel owl-theme owl-dots-top">

     <!-- <h1>{{ count($items) }}</h1> -->

    @foreach ($items as $item)
     <div class="product">
          <figure class="product-image-container">
              <a href="product.html" class="product-image">
                  <img src="{{ asset('storage/uploads/items/'.$item->image) }}" alt="product">
              </a>
              <a href="{{ asset('porto-ecommerce/ajax/product-quick-view.html') }}" class="btn-quickview">Quick View</a>
          </figure>
          <div class="product-details">
              <div class="ratings-container">
                  <div class="product-ratings">
                      <span class="ratings" style="width:80%"></span><!-- End .ratings -->
                  </div><!-- End .product-ratings -->
              </div><!-- End .product-container -->
              <h2 class="product-title">
                  <a href="product.html">{{$item->name}}</a>
              </h2>
              <div class="price-box">
                  <span class="product-price">S/ {{ number_format($item->sale_unit_price, 2) }}</span>
              </div><!-- End .price-box -->

              <div class="product-action">
                  <a href="#" class="paction add-wishlist" title="Add to Wishlist">
                      <span>Add to Wishlist</span>
                  </a>

                  <a href="product.html" class="paction add-cart" title="Add to Cart">
                      <span>Add to Cart</span>
                  </a>

                  <a href="#" class="paction add-compare" title="Add to Compare">
                      <span>Add to Compare</span>
                  </a>
              </div><!-- End .product-action -->
          </div><!-- End .product-details -->
      </div><!-- End .product -->


    
       
    @endforeach
     

    
  </div><!-- End .featured-proucts -->
