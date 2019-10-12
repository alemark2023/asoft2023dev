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
        {{-- @if($vc_company->soap_type_id == "01")
        <ul class="notifications">
            <span class="separator"></span>
            <li class="warning-demo"> 
                Demo
            </li> 
        </ul>
        @endif --}}
        @if($vc_document > 0)
        <ul class="notifications">
            <li class="open">
                                  
                <a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-file-alt"></i>                        
                    <span class="badge">{{ $vc_document }}</span>
                </a>
                <div class="dropdown-menu notification-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
                    <div class="notification-title bg-primary">Pendientes de envío</div>
                    <div class="content">
                        <ul>
                            <li>
                                <a href="{{route('tenant.documents.not_sent')}}" class="clearfix">
                                    <div class="image">
                                        <i class="fas fa-file-alt bg-primary"></i>
                                    </div>
                                    <span class="title">Tiene {{ $vc_document }} comprobante(s) pendientes de envío<span class="badge badge-warning"></span></span>
                                    <!-- <span class="message">Pendientes de envio a SUNAT/OSE</span> -->
                                </a>
                            </li>
                        </ul>
                    </div>
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