<aside class="sidebar-product col-lg-3 padding-left-lg mobile-sidebar">
    <div class="sidebar-wrapper">
        <div class="widget widget-brand">
            <!--<a href="#">
                <img src="{{ asset('porto-ecommerce/assets/images/product-brand.png') }}" alt="brand name">
            </a>-->
        </div><!-- End .widget -->

        <div class="widget widget-info">
            <ul>
                @if($information->tag_shipping)
                    <li>
                        <i class="icon-shipping"></i>
                        <h4>{!!$information->tag_shipping!!}</h4>
                    </li>
                @endif
                @if($information->tag_dollar)
                <li>
                    <i class="icon-us-dollar"></i>
                    <h4>{!!$information->tag_dollar!!}</h4>
                </li>
                @endif
                @if($information->tag_support)
                <li>
                    <i class="icon-online-support"></i>
                    <h4>{!!$information->tag_support!!}</h4>
                </li>
                @endif
            </ul>
        </div><!-- End .widget -->

        <div class="widget widget-banner">
            <div class="banner banner-image">
                <a href="#">
                    <img src="{{ asset('porto-ecommerce/assets/images/banners/banner-sidebar.jpg') }}"
                        alt="Banner Desc">
                </a>
            </div><!-- End .banner -->
        </div><!-- End .widget -->

        @include('ecommerce::layouts.partials_ecommerce.widget_products')
    </div>
</aside><!-- End .col-md-3 -->
