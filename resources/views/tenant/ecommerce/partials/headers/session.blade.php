<style>
    .btn-logout {

        font-size: 25px;
      
    }

</style>
<div class="dropdown cart-dropdown" style="margin-left: 10px;">

    @guest
    <div class="header-contact">
        <a class="login-link" href="{{route('tenant_ecommerce_login')}}"><strong style="font-size: 16px;">LOG
                IN</strong></a>
    </div>
    @else
    <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
        data-display="static">
        <i class="icon-user fa-2x text-white"></i>
        <div class="dropdown-menu">
            <div class="dropdownmenu-wrapper">

                <div class="dropdown-cart-total">
                    <span>{{ Auth::user()->email }} </span>
                    <a href="#" role="menuitem" class="btn-logout" data-toggle="tooltip" data-placement="bottom" title="Cerrar Session"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-power-off"></i>
                    </a>

                </div>

                <!--<a type="button" class="btn" data-toggle="tooltip" data-placement="top"
                    title="Tooltip on top">
                    <i class="fas fa-power-off"></i>
                </a>-->

                <div class="dropdown-cart-action text-right">
                    <!--<a href="#" role="menuitem" class="btn-logout"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-power-off"></i>
                    </a>-->
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
