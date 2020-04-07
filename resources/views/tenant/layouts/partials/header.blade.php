<header class="header">
    <div class="logo-container">
        <a href="{{route('tenant.dashboard.index')}}" class="logo pt-2 pt-md-0">

            @if($vc_company->logo)
                <img src="{{ asset('storage/uploads/logos/'.$vc_company->logo) }}" alt="Logo" />
            @else
                <img src="{{asset('logo/700x300.jpg')}}" alt="Logo" />
            @endif
        </a>
        <div class="d-md-none toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>
    <div class="header-right">
        @if($vc_company->soap_type_id == "01")
        <a href="@if(in_array('configuration', $vc_modules)){{route('tenant.companies.create')}}@else # @endif">
        <div class="switch switch-sm switch-primary" data-toggle="tooltip" data-placement="bottom" title="SUNAT: ENTORNO DE DEMOSTRACIÓN, pulse para ir a configuración">
            <div class="ios-switch off">
                <div class="on-background background-fill"></div>
                <div class="state-background background-fill">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 9px; position: absolute; color: #fff;">DEMO</span></div>
                <div class="handle"></div>
            </div>
            <input type="checkbox" name="switch" data-plugin-ios-switch="" checked="checked" style="display: none;">
        </div>
        </a>
        @elseif($vc_company->soap_type_id == "02")
        <a href="@if(in_array('configuration', $vc_modules)){{route('tenant.companies.create')}}@else # @endif">
        <div class="switch switch-sm switch-success"  data-toggle="tooltip" data-placement="bottom" title="SUNAT: ENTORNO DE PRODUCCIÓN, pulse para ir a configuración">
            <div class="ios-switch on">
                <div class="on-background background-fill"><span class="text-white ml-1" style="font-size: 9px;">&nbsp;&nbsp;PROD.</span></div>
                <div class="state-background background-fill"></div>
                <div class="handle"></div>
            </div>
            <input type="checkbox" name="switch" data-plugin-ios-switch="" checked="checked" style="display: none;">
        </div>
        </a>
        @else
        <a href="@if(in_array('configuration', $vc_modules)){{route('tenant.companies.create')}}@else # @endif">
        <div class="switch switch-sm switch-info"  data-toggle="tooltip" data-placement="bottom" title="INTERNO: ENTORNO DE PRODUCCIÓN, pulse para ir a configuración">
            <div class="ios-switch on">
                <div class="on-background background-fill"><span class="text-white ml-1" style="font-size: 9px;">&nbsp;&nbsp;INTERNO</span></div>
                <div class="state-background background-fill"></div>
                <div class="handle"></div>
            </div>
            <input type="checkbox" name="switch" data-plugin-ios-switch="" checked="checked" style="display: none;">
        </div>
        </a>
        @endif

        @if($vc_document > 0)
        <span class="separator"></span>
        <ul class="notifications">
            <li class="open">

                <a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell text-secondary"></i>
                    <span class="badge badge-red">{{ $vc_document }}</span>
                </a>
                <div class="dropdown-menu notification-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
                    <a href="{{route('tenant.documents.not_sent')}}">
                        <div class="notification-title">
                            <span class="float-right badge badge-default"><i class="fas fa-arrow-right"></i></span>Comprobantes pendientes de envío
                        </div>
                    </a>
                    {{-- <div class="content">
                    </div> --}}
                </div>
            </li>
        </ul>
        @endif

        <span class="separator"></span>
        <div id="userbox" class="userbox">
            <a href="#" data-toggle="dropdown">
                <figure class="profile-picture">
                    {{-- <img src="{{asset('img/%21logged-user.jpg')}}" alt="Profile" class="rounded-circle" data-lock-picture="img/%21logged-user.jpg" /> --}}
                    <div class="border rounded-circle text-center" style="width: 25px;"><i class="fas fa-user"></i></div>
                </figure>
                <div class="profile-info" data-lock-name="{{ $vc_user->email }}" data-lock-email="{{ $vc_user->email }}">
                    <span class="name">{{ $vc_user->name }}</span>
                    <span class="role">{{ $vc_user->email }}</span>
                </div>
                <i class="fa custom-caret"></i>
            </a>
            <div class="dropdown-menu">
                <ul class="list-unstyled mb-2">
                    <li class="divider"></li>
                    <li>
                        {{--<a role="menuitem" href="#"><i class="fas fa-user"></i> Perfil</a>--}}
                        <a role="menuitem" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-power-off"></i> @lang('app.buttons.logout')
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

<div class="container d-none d-sm-block">
    <div id="switcher-top" class="d-flex justify-content-center switcher-hover">
        <span class="text-white py-0 px-5 text-center"><i class="fas fa-plus fa-fw"></i>Acceso Rápido</span>
    </div>
</div>
<div class="container d-none d-sm-block">
    <div id="switcher-list" class="d-flex justify-content-center switcher-hover">
        <div class="row">
            <div class="px-3"><a class="py-3" href="{{ route('tenant.documents.create') }}"><i class="fas fa-fw fa-file-invoice" aria-hidden="true"></i> Nuevo Comprobante</a></div>
            <div class="px-3"><a class="py-3" href="{{ route('tenant.pos.index') }}"><i class="fas fa-fw fa-cash-register" aria-hidden="true"></i> POS</a></div>
            <div style="min-width: 220px;"></div>
            <div class="px-3"><a class="py-3" href="{{ route('tenant.companies.create') }}"><i class="fas fa-fw fa-industry" aria-hidden="true"></i> Empresa</a></div>
            <div class="px-3"><a class="py-3" href="{{ route('tenant.establishments.index') }}"><i class="fas fa-fw fa-warehouse" aria-hidden="true"></i> Establecimientos</a></div>
        </div>
    </div>
</div>
