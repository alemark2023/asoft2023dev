<aside class="sidebar-product col-lg-3 padding-left-lg mobile-sidebar">
    <div class="sidebar-wrapper">
        <div class="widget widget-brand">
            <!--<a href="#">
                <img src="{{ asset('porto-ecommerce/assets/images/product-brand.png') }}" alt="brand name">
            </a>-->
        </div><!-- End .widget -->

        <div class="widget widget-info">
            <ul>
                <li>
                    <i class="icon-shipping"></i>
                    <h4>FREE<br>SHIPPING</h4>
                </li>
                <li>
                    <i class="icon-us-dollar"></i>
                    <h4>100% MONEY<br>BACK GUARANTEE</h4>
                </li>
                <li>
                    <i class="icon-online-support"></i>
                    <h4>ONLINE<br>SUPPORT 24/7</h4>
                </li>
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

        @include('tenant.layouts.partials_ecommerce.widget_products')
    </div>
</aside><!-- End .col-md-3 -->
