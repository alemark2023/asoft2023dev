<style>
    .btn-logout{
        color: brown;
        background: darkgrey !important;
    }
</style>
<div class="dropdown cart-dropdown" style="margin-left: 10px;">

    @guest
    <div class="header-contact">
        <a class="login-link" href="{{route('tenant_ecommerce_login')}}"><strong style="font-size: 16px;">LOG IN</strong></a>
    </div>
    @else
    <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
            <i class="icon-user fa-2x text-white"></i>
    <div class="dropdown-menu">
        <div class="dropdownmenu-wrapper">

            <div class="dropdown-cart-total">
                <span>{{ Auth::user()->email }}</span>
            </div>

            <div class="dropdown-cart-action text-right">
                <a role="menuitem" href="{{ route('logout') }}" class="btn btn-logout"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Cerrar Sesión
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

            </div>
        </div>
    </div>

    @endguest





    {{--<a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
    	<i class="icon-user fa-2x text-white"></i>
    </a>
    <div class="dropdown-menu">
        <div class="dropdownmenu-wrapper">
           
            <div class="dropdown-cart-total">
                @guest
                	<span>Invitado</span>
                @else
                	<span>{{ Auth::user()->email }}</span>
    @endguest
</div><!-- End .dropdown-cart-total -->

<div class="dropdown-cart-action text-right">

    @guest
    <a href="{{route('tenant_ecommerce_login')}}" class="btn">Ingresar</a>
    @else
    <a role="menuitem" href="{{ route('logout') }}" class="btn"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Salir
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    @endguest
</div>
</div>
</div> --}}
</div>
