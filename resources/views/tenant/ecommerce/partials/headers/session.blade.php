<div class="dropdown cart-dropdown">
    <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
    	<i class="icon-user fa-2x text-white"></i>
    </a>
    <div class="dropdown-menu">
        <div class="dropdownmenu-wrapper">

            <div class="dropdown-cart-products">

            </div><!-- End .cart-product -->

            <div class="dropdown-cart-total">
                @guest
                	<span>Invitado</span>
                @else
                	<span>{{ Auth::user()->email }}</span>
                @endguest
            </div><!-- End .dropdown-cart-total -->

            <div class="dropdown-cart-action">
                <a  href="{{ route('tenant_detail_cart') }}" class="btn">Ver Carrito</a>
                @guest
                	<a href="{{route('tenant_ecommerce_login')}}" class="btn">Ingresar</a>
                @else
                	<a role="menuitem" href="{{ route('logout') }}" class="btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Salir
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @endguest
            </div><!-- End .dropdown-cart-total -->
        </div><!-- End .dropdownmenu-wrapper -->
    </div><!-- End .dropdown-menu -->
</div><!-- End .dropdown -->
