 <header class="header">
     <div class="header-top">
         <div class="container">
             <div class="header-left header-dropdowns">
                 <div class="header-dropdown">
                     <a href="#">USD</a>
                     <div class="header-menu">
                         <ul>
                             <li><a href="#">EUR</a></li>
                             <li><a href="#">USD</a></li>
                         </ul>
                     </div><!-- End .header-menu -->
                 </div><!-- End .header-dropown -->

                 <div class="header-dropdown">
                     <a href="#"><img src="{{ asset('porto-ecommerce/assets/images/flags/en.png') }}"
                             alt="England flag">ENGLISH</a>
                     <div class="header-menu">
                         <ul>
                             <li><a href="#"><img src="{{ asset( 'porto-ecommerce/assets/images/flags/en.png' ) }}"
                                         alt="England flag">ENGLISH</a></li>
                             <li><a href="#"><img src="{{ asset( 'porto-ecommerce/assets/images/flags/fr.png' ) }}"
                                         alt="France flag">FRENCH</a></li>
                         </ul>
                     </div><!-- End .header-menu -->
                 </div><!-- End .header-dropown -->

                 <div class="dropdown compare-dropdown">
                     <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true"
                         aria-expanded="false" data-display="static">
                         <i class="icon-retweet"></i> Compare (2)
                     </a>

                     <div class="dropdown-menu">
                         <div class="dropdownmenu-wrapper">
                             <ul class="compare-products">
                                 <li class="product">
                                     <a href="#" class="btn-remove" title="Remove Product"><i
                                             class="icon-cancel"></i></a>
                                     <h4 class="product-title"><a href="product.html">Lady White Top</a></h4>
                                 </li>
                                 <li class="product">
                                     <a href="#" class="btn-remove" title="Remove Product"><i
                                             class="icon-cancel"></i></a>
                                     <h4 class="product-title"><a href="product.html">Blue Women Shirt</a></h4>
                                 </li>
                             </ul>

                             <div class="compare-actions">
                                 <a href="#" class="action-link">Clear All</a>
                                 <a href="#" class="btn btn-primary">Compare</a>
                             </div>
                         </div><!-- End .dropdownmenu-wrapper -->
                     </div><!-- End .dropdown-menu -->
                 </div><!-- End .dropdown -->
             </div><!-- End .header-left -->

             <div class="header-right">
                 <p class="welcome-msg">Default welcome msg! </p>

                 <div class="header-dropdown dropdown-expanded">
                     <a href="#">Links</a>
                     <div class="header-menu">
                         <ul>
                             <li><a href="my-account.html">MY ACCOUNT </a></li>
                             <li><a href="#">DAILY DEAL</a></li>
                             <li><a href="#">MY WISHLIST </a></li>
                             <li><a href="#">BLOG</a></li>
                             <li><a href="contact.html">Contact</a></li>
                                @guest
                              <li><a href="{{route('tenant_ecommerce_login')}}" class="login-link">LOG IN</a></li>
                              @else
                              <li><a href="#">{{ Auth::user()->email }}</a></li>
                              <li>
                                  <a role="menuitem" href="{{ route('logout') }}"
                                      onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                      <i class="fas fa-power-off"></i> @lang('app.buttons.logout')
                                  </a>
                                  <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                      @csrf
                                  </form>
                              </li>
                              @endguest
                         </ul>
                     </div><!-- End .header-menu -->
                 </div><!-- End .header-dropown -->
             </div><!-- End .header-right -->
         </div><!-- End .container -->
     </div><!-- End .header-top -->

     <div class="header-middle">
         <div class="container">
             <div class="header-left">
                 <a href="/ecommerce" class="logo">
                     <img src="{{ asset('porto-ecommerce/assets/images/logo.png') }}" alt="Porto Logo">
                 </a>
             </div><!-- End .header-left -->

             <div class="header-center">
                 <div class="header-search">
                     <a href="#" class="search-toggle" role="button"><i class="icon-magnifier"></i></a>
                     <form action="#" method="get">
                         <div class="header-search-wrapper">
                             <input type="search" class="form-control" name="q" id="q" placeholder="Search..." required>
                             <div class="select-custom">
                                 <select id="cat" name="cat">
                                     <option value="">All Categories</option>
                                     <option value="4">Fashion</option>
                                     <option value="12">- Women</option>
                                     <option value="13">- Men</option>
                                     <option value="66">- Jewellery</option>
                                     <option value="67">- Kids Fashion</option>
                                     <option value="5">Electronics</option>
                                     <option value="21">- Smart TVs</option>
                                     <option value="22">- Cameras</option>
                                     <option value="63">- Games</option>
                                     <option value="7">Home &amp; Garden</option>
                                     <option value="11">Motors</option>
                                     <option value="31">- Cars and Trucks</option>
                                     <option value="32">- Motorcycles &amp; Powersports</option>
                                     <option value="33">- Parts &amp; Accessories</option>
                                     <option value="34">- Boats</option>
                                     <option value="57">- Auto Tools &amp; Supplies</option>
                                 </select>
                             </div><!-- End .select-custom -->
                             <button class="btn" type="submit"><i class="icon-magnifier"></i></button>
                         </div><!-- End .header-search-wrapper -->
                     </form>
                 </div><!-- End .header-search -->
             </div><!-- End .headeer-center -->

             <div class="header-right">
                 <button class="mobile-menu-toggler" type="button">
                     <i class="icon-menu"></i>
                 </button>
                 <div class="header-contact">
                     <span>Call us now</span>
                     <a href="tel:#"><strong>+123 5678</strong></a>
                 </div><!-- End .header-contact -->

                 @include('tenant.layouts.partials_ecommerce.cart_dropdown')
             </div><!-- End .header-right -->
         </div><!-- End .container -->
     </div><!-- End .header-middle -->

     <div class="header-bottom sticky-header">
         <div class="container">
             <nav class="main-nav">
                 <ul class="menu sf-arrows">
                     {{--<li><a href="/ecommerce">Home</a></li>
                     <li>
                         <a href="#" class="sf-with-ul">Categories</a>
                         <div class="megamenu megamenu-fixed-width">
                             <div class="row">
                                 <div class="col-lg-8">
                                     <div class="row">
                                         <div class="col-lg-6">
                                             <div class="menu-title">
                                                 <a href="#">Variations 1<span class="tip tip-new">New!</span></a>
                                             </div>
                                             <ul>
                                                 <li><a href="category.html">Fullwidth Banner<span
                                                             class="tip tip-hot">Hot!</span></a></li>
                                                 <li><a href="category-banner-boxed-slider.html">Boxed Slider Banner</a>
                                                 </li>
                                                 <li><a href="category-banner-boxed-image.html">Boxed Image Banner</a>
                                                 </li>
                                                 <li><a href="category.html">Left Sidebar</a></li>
                                                 <li><a href="category-sidebar-right.html">Right Sidebar</a></li>
                                                 <li><a href="category-flex-grid.html">Product Flex Grid</a></li>
                                                 <li><a href="category-horizontal-filter1.html">Horizontal Filter1</a>
                                                 </li>
                                                 <li><a href="category-horizontal-filter2.html">Horizontal Filter2</a>
                                                 </li>
                                             </ul>
                                         </div><!-- End .col-lg-6 -->
                                         <div class="col-lg-6">
                                             <div class="menu-title">
                                                 <a href="#">Variations 2</a>
                                             </div>
                                             <ul>
                                                 <li><a href="#">Product List Item Types</a></li>
                                                 <li><a href="category-infinite-scroll.html">Ajax Infinite Scroll</a>
                                                 </li>
                                                 <li><a href="category.html">3 Columns Products</a></li>
                                                 <li><a href="category-4col.html">4 Columns Products <span
                                                             class="tip tip-new">New</span></a></li>
                                                 <li><a href="category-5col.html">5 Columns Products</a></li>
                                                 <li><a href="category-6col.html">6 Columns Products</a></li>
                                                 <li><a href="category-7col.html">7 Columns Products</a></li>
                                                 <li><a href="category-8col.html">8 Columns Products</a></li>
                                             </ul>
                                         </div><!-- End .col-lg-6 -->
                                     </div><!-- End .row -->
                                 </div><!-- End .col-lg-8 -->
                                 <div class="col-lg-4">
                                     <div class="banner">
                                         <a href="#">
                                             <img src="{{ asset('porto-ecommerce/assets/images/menu-banner-2.jpg')}}" alt="Menu banner">
                                         </a>
                                     </div><!-- End .banner -->
                                 </div><!-- End .col-lg-4 -->
                             </div>
                         </div><!-- End .megamenu -->
                     </li>
                     <li class="megamenu-container active">
                         <a href="{{route('tenant.ecommerce.item.index')}}" class="sf-with-ul">Products</a>
                         <div class="megamenu">
                             <div class="row">
                                 <div class="col-lg-8">
                                     <div class="row">
                                         <div class="col-lg-4">
                                             <div class="menu-title">
                                                 <a href="#">Variations</a>
                                             </div>
                                             <ul>
                                                 <li><a href="product.html">Horizontal Thumbnails</a></li>
                                                 <li><a href="product-full-width.html">Vertical Thumbnails<span
                                                             class="tip tip-hot">Hot!</span></a></li>
                                                 <li><a href="product.html">Inner Zoom</a></li>
                                                 <li><a href="product-addcart-sticky.html">Addtocart Sticky</a></li>
                                                 <li><a href="product-sidebar-left.html">Accordion Tabs</a></li>
                                             </ul>
                                         </div><!-- End .col-lg-4 -->
                                         <div class="col-lg-4">
                                             <div class="menu-title">
                                                 <a href="#">Variations</a>
                                             </div>
                                             <ul>
                                                 <li><a href="product-sticky-tab.html">Sticky Tabs</a></li>
                                                 <li><a href="product-simple.html">Simple Product</a></li>
                                                 <li><a href="product-sidebar-left.html">With Left Sidebar</a></li>
                                             </ul>
                                         </div><!-- End .col-lg-4 -->
                                         <div class="col-lg-4">
                                             <div class="menu-title">
                                                 <a href="#">Product Layout Types</a>
                                             </div>
                                             <ul>
                                                 <li><a href="product.html">Default Layout</a></li>
                                                 <li><a href="product-extended-layout.html">Extended Layout</a></li>
                                                 <li><a href="product-full-width.html">Full Width Layout</a></li>
                                                 <li><a href="product-grid-layout.html">Grid Images Layout</a></li>
                                                 <li><a href="product-sticky-both.html">Sticky Both Side Info<span
                                                             class="tip tip-hot">Hot!</span></a></li>
                                                 <li><a href="product-sticky-info.html">Sticky Right Side Info</a></li>
                                             </ul>
                                         </div><!-- End .col-lg-4 -->
                                     </div><!-- End .row -->
                                 </div><!-- End .col-lg-8 -->
                                 <div class="col-lg-4">
                                     <div class="banner">
                                         <a href="#">
                                             <img src="{{ asset( 'porto-ecommerce/assets/images/menu-banner.jpg' ) }}" alt="Menu banner"
                                                 class="product-promo">
                                         </a>
                                     </div><!-- End .banner -->
                                 </div><!-- End .col-lg-4 -->
                             </div><!-- End .row -->
                         </div><!-- End .megamenu -->
                     </li>
                     <li>
                         <a href="#" class="sf-with-ul">Pages</a>

                         <ul>
                             <li><a href="cart.html">Shopping Cart</a></li>
                             <li><a href="#">Checkout</a>
                                 <ul>
                                     <li><a href="checkout-shipping.html">Checkout Shipping</a></li>
                                     <li><a href="checkout-shipping-2.html">Checkout Shipping 2</a></li>
                                     <li><a href="checkout-review.html">Checkout Review</a></li>
                                 </ul>
                             </li>
                             <li><a href="#">Dashboard</a>
                                 <ul>
                                     <li><a href="dashboard.html">Dashboard</a></li>
                                     <li><a href="my-account.html">My Account</a></li>
                                 </ul>
                             </li>
                             <li><a href="about.html">About Us</a></li>
                             <li><a href="#">Blog</a>
                                 <ul>
                                     <li><a href="blog.html">Blog</a></li>
                                     <li><a href="single.html">Blog Post</a></li>
                                 </ul>
                             </li>
                             <li><a href="contact.html">Contact Us</a></li>
                             <li><a href="#" class="login-link">Login</a></li>
                             <li><a href="forgot-password.html">Forgot Password</a></li>
                         </ul>
                     </li>
                     <li><a href="#" class="sf-with-ul">Features</a>
                         <ul>
                             <li><a href="#">Header Types</a></li>
                             <li><a href="#">Footer Types</a></li>
                         </ul>
                     </li>--}}

                     @foreach ($items as $item)
                        <li><a href="#">{{ $item->name }}</a></li>
                    @endforeach
                    
                 </ul>
             </nav>
         </div><!-- End .header-bottom -->
     </div><!-- End .header-bottom -->
 </header><!-- End .header -->

 <div class="modal fade" id="moda-succes-add-product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <!--<div class="modal-header ">
                  <h5 class="modal-title" id="exampleModalLabel"></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>-->
              <div class="modal-body">

                  <div class="alert alert-success" role="alert">
                        <i class="icon-ok"></i> Tu producto se agregó al carrito
                  </div>
              </div>
              <div class="modal-footer">

                  <button type="button" class="btn btn-warning" data-dismiss="modal">Seguir Comprando</button>
              </div>
          </div>
      </div>
  </div>
