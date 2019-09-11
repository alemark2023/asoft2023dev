<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from portotheme.com/html/porto_ecommerce/demo-6/ by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 07 Sep 2019 03:39:38 GMT -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Porto - Bootstrap eCommerce Template</title>

    <meta name="keywords" content="HTML5 Template" />
    <meta name="description" content="Porto - Bootstrap eCommerce Template">
    <meta name="author" content="SW-THEMES">
        
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('porto-ecommerce/assets/images/icons/favicon.ico') }}">

    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="{{ asset('porto-ecommerce/assets/css/bootstrap.min.css') }}">

    <!-- Main CSS File -->
    <link rel="stylesheet" href="{{ asset('porto-ecommerce/assets/css/style.min.css') }}">
</head>
<body>
    <div class="page-wrapper">
      
        @include('tenant.layouts.partials_ecommerce.header')
        <main class="main">
            @include('tenant.layouts.partials_ecommerce.info_boxes')

            <div class="container">
                <div class="row">
                    <div class="col-lg-9">
                        
                         @include('tenant.layouts.partials_ecommerce.home_slider')

                        <div class="row">
                            <div class="col-md-4">
                                <div class="banner banner-image">
                                    <a href="#">
                                        <img src="{{ asset('porto-ecommerce/assets/images/banners/banner-1.jpg' ) }}" alt="banner">
                                    </a>
                                </div><!-- End .banner -->
                            </div><!-- End .col-md-4 -->

                            <div class="col-md-4">
                                <div class="banner banner-image">
                                    <a href="#">
                                        <img src="{{ asset('porto-ecommerce/assets/images/banners/banner-2.jpg' ) }}"
                                            alt="banner">
                                    </a>
                                </div><!-- End .banner -->
                            </div><!-- End .col-md-4 -->

                            <div class="col-md-4">
                                <div class="banner banner-image">
                                    <a href="#">
                                        <img src="{{ asset('porto-ecommerce/assets/images/banners/banner-3.jpg' ) }}"
                                            alt="banner">
                                    </a>
                                </div><!-- End .banner -->
                            </div><!-- End .col-md-4 -->
                        </div><!-- End .row -->

                        <div class="mb-3"></div><!-- margin -->

                        @include('tenant.layouts.partials_ecommerce.featured_products')

                        <div class="mb-6"></div><!-- margin -->
                        
                        <div class="row">
                            @include('tenant.layouts.partials_ecommerce.products_main')
                        </div><!-- End .row -->

                        <div class="mb-3"></div><!-- margin -->

                        <div class="row">

                            @include('tenant.layouts.partials_ecommerce.features_box')
                           
                        </div><!-- End .row -->
                    </div><!-- End .col-lg-9 -->

                    <aside class="sidebar-home col-lg-3 order-lg-first">
                        <div class="side-menu-container">
                            <h2>CATEGORIES</h2>
                            @include('tenant.layouts.partials_ecommerce.sidemenu')
                            
                        </div><!-- End .side-menu-container -->
                        <div class="widget widget-banners">
                            <div class="widget-banners-slider owl-carousel owl-theme">
                                <div class="banner banner-image">
                                    <a href="#">
                                        <img src="{{ asset('porto-ecommerce/assets/images/banners/banner-sidebar.jpg') }}"
                                            alt="banner">
                                    </a>
                                </div><!-- End .banner -->

                                <div class="banner banner-image">
                                    <a href="#">
                                        <img src="{{ asset('porto-ecommerce/assets/images/banners/banner-sidebar-2.jpg') }}"
                                            alt="banner">
                                    </a>
                                </div><!-- End .banner -->
                            </div><!-- End .banner-slider -->
                        </div><!-- End .widget -->

                        <div class="widget widget-newsletters">
                            <h3 class="widget-title">Newsletter</h3>
                            <p>Get all the latest information on Events, Sales and Offers. </p>
                            <form action="#">
                                <div class="form-group">
                                    <input type="email" class="form-control" id="wemail">
                                    <label for="wemail"><i class="icon-envolope"></i>Email Address</label>
                                </div><!-- Endd .form-group -->
                                <input type="submit" class="btn btn-block" value="Subscribe Now">
                            </form>
                        </div><!-- End .widget -->

                        <div class="widget widget-testimonials">
                           
                            @include('tenant.layouts.partials_ecommerce.testimonials')
                        </div><!-- End .widget -->

                        <div class="widget">
                            @include('tenant.layouts.partials_ecommerce.news')
                        </div><!-- End .widget -->
                    </aside><!-- End .col-lg-3 -->
                </div><!-- End .row -->
            </div><!-- End .container -->

            <div class="mb-4"></div><!-- margin -->
        </main><!-- End .main -->

        <footer class="footer">
             @include('tenant.layouts.partials_ecommerce.footer')
        </footer><!-- End .footer -->
    </div><!-- End .page-wrapper -->

    <div class="mobile-menu-overlay"></div><!-- End .mobil-menu-overlay -->

    <div class="mobile-menu-container">
        <div class="mobile-menu-wrapper">
            <span class="mobile-menu-close"><i class="icon-cancel"></i></span>
            <nav class="mobile-nav">
                <ul class="mobile-menu">
                    <li class="active"><a href="index-2.html">Home</a></li>
                    <li>
                        <a href="category.html">Categories</a>
                        <ul>
                            <li><a href="category.html">Full Width Banner</a></li>
                            <li><a href="category-banner-boxed-slider.html">Boxed Slider Banner</a></li>
                            <li><a href="category-banner-boxed-image.html">Boxed Image Banner</a></li>
                            <li><a href="category.html">Left Sidebar</a></li>
                            <li><a href="category-sidebar-right.html">Right Sidebar</a></li>
                            <li><a href="category-flex-grid.html">Product Flex Grid</a></li>
                            <li><a href="category-horizontal-filter1.html">Horizontal Filter 1</a></li>
                            <li><a href="category-horizontal-filter2.html">Horizontal Filter 2</a></li>
                            <li><a href="#">Product List Item Types</a></li>
                            <li><a href="category-infinite-scroll.html">Ajax Infinite Scroll<span class="tip tip-new">New</span></a></li>
                            <li><a href="category.html">3 Columns Products</a></li>
                            <li><a href="category-4col.html">4 Columns Products</a></li>
                            <li><a href="category-5col.html">5 Columns Products</a></li>
                            <li><a href="category-6col.html">6 Columns Products</a></li>
                            <li><a href="category-7col.html">7 Columns Products</a></li>
                            <li><a href="category-8col.html">8 Columns Products</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="product.html">Products</a>
                        <ul>
                            <li>
                                <a href="#">Variations</a>
                                <ul>
                                    <li><a href="product.html">Horizontal Thumbnails</a></li>
                                    <li><a href="product-full-width.html">Vertical Thumbnails<span class="tip tip-hot">Hot!</span></a></li>
                                    <li><a href="product.html">Inner Zoom</a></li>
                                    <li><a href="product-addcart-sticky.html">Addtocart Sticky</a></li>
                                    <li><a href="product-sidebar-left.html">Accordion Tabs</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">Variations</a>
                                <ul>
                                    <li><a href="product-sticky-tab.html">Sticky Tabs</a></li>
                                    <li><a href="product-simple.html">Simple Product</a></li>
                                    <li><a href="product-sidebar-left.html">With Left Sidebar</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">Product Layout Types</a>
                                <ul>
                                    <li><a href="product.html">Default Layout</a></li>
                                    <li><a href="product-extended-layout.html">Extended Layout</a></li>
                                    <li><a href="product-full-width.html">Full Width Layout</a></li>
                                    <li><a href="product-grid-layout.html">Grid Images Layout</a></li>
                                    <li><a href="product-sticky-both.html">Sticky Both Side Info<span class="tip tip-hot">Hot!</span></a></li>
                                    <li><a href="product-sticky-info.html">Sticky Right Side Info</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Pages<span class="tip tip-hot">Hot!</span></a>
                        <ul>
                            <li><a href="cart.html">Shopping Cart</a></li>
                            <li>
                                <a href="#">Checkout</a>
                                <ul>
                                    <li><a href="checkout-shipping.html">Checkout Shipping</a></li>
                                    <li><a href="checkout-shipping-2.html">Checkout Shipping 2</a></li>
                                    <li><a href="checkout-review.html">Checkout Review</a></li>
                                </ul>
                            </li>
                            <li><a href="about.html">About</a></li>
                            <li><a href="#" class="login-link">Login</a></li>
                            <li><a href="forgot-password.html">Forgot Password</a></li>
                        </ul>
                    </li>
                    <li><a href="blog.html">Blog</a>
                        <ul>
                            <li><a href="single.html">Blog Post</a></li>
                        </ul>
                    </li>
                    <li><a href="contact.html">Contact Us</a></li>
                    <li><a href="#">Special Offer!<span class="tip tip-hot">Hot!</span></a></li>
                    <li><a href="#">Buy Porto!</a></li>
                </ul>
            </nav><!-- End .mobile-nav -->

            <div class="social-icons">
                <a href="#" class="social-icon" target="_blank"><i class="icon-facebook"></i></a>
                <a href="#" class="social-icon" target="_blank"><i class="icon-twitter"></i></a>
                <a href="#" class="social-icon" target="_blank"><i class="icon-instagram"></i></a>
            </div><!-- End .social-icons -->
        </div><!-- End .mobile-menu-wrapper -->
    </div><!-- End .mobile-menu-container -->

    <div class="newsletter-popup mfp-hide" id="newsletter-popup-form" style="background-image: url(assets/images/newsletter_popup_bg.jpg)">
        <div class="newsletter-popup-content">
            <img src="assets/images/logo-black.png" alt="Logo" class="logo-newsletter">
            <h2>BE THE FIRST TO KNOW</h2>
            <p>Subscribe to the Porto eCommerce newsletter to receive timely updates from your favorite products.</p>
            <form action="#">
                <div class="input-group">
                    <input type="email" class="form-control" id="newsletter-email" name="newsletter-email" placeholder="Email address" required>
                    <input type="submit" class="btn" value="Go!">
                </div><!-- End .from-group -->
            </form>
            <div class="newsletter-subscribe">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" value="1">
                        Don't show this popup again
                    </label>
                </div>
            </div>
        </div><!-- End .newsletter-popup-content -->
    </div><!-- End .newsletter-popup -->

    <a id="scroll-top" href="#top" title="Top" role="button"><i class="icon-angle-up"></i></a>

    <!-- Plugins JS File -->
    <script src="{{ asset('porto-ecommerce/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('porto-ecommerce/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('porto-ecommerce/assets/js/plugins.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('porto-ecommerce/assets/js/main.min.js') }}"></script>
</body>

<!-- Mirrored from portotheme.com/html/porto_ecommerce/demo-6/ by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 07 Sep 2019 03:39:54 GMT -->
</html>