@php
    $path = explode('/', request()->path());
    $path[1] = (array_key_exists(1, $path)> 0)?$path[1]:'';
    $path[2] = (array_key_exists(2, $path)> 0)?$path[2]:'';
    $path[0] = ($path[0] === '')?'documents':$path[0];

@endphp

<aside id="sidebar-left" class="sidebar-left">
    <div class="sidebar-header">
        <div class="sidebar-title">
            Menu
        </div>
        <div class="sidebar-toggle d-none d-md-block" data-toggle-class="sidebar-left-collapsed" data-target="html"
             data-fire-event="sidebar-left-toggle">
            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>
    <div class="nano">
        <div class="nano-content">
            <nav id="menu" class="nav-main" role="navigation">
                <ul class="nav nav-main">
                   <!-- <li class="{{ ($path[0] === 'ecommerce')?'nav-active':'' }}">
                        <a class="nav-link" href="{{ route('tenant.ecommerce.index') }}">
                            <i class="fas fa-chart-line" aria-hidden="true"></i>
                            <span>Ecommerce</span>
                        </a>
                    </li> -->



                    @if(in_array('dashboard', $vc_modules))
                    <li class="{{ ($path[0] === 'dashboard')?'nav-active':'' }}">
                        <a class="nav-link" href="{{ route('tenant.dashboard.index') }}">
                            <span class="float-right badge badge-red badge-danger mr-3">Nuevo</span>
                            <i class="fas fa-tachometer-alt" aria-hidden="true"></i>
                            <span>DASHBOARD</span>
                        </a>
                    </li>
                    @endif

                    @if(in_array('documents', $vc_modules))
                    <li class="
                        nav-parent
                        {{ ($path[0] === 'documents')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'items')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'persons' && $path[1] === 'customers')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'summaries')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'voided')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'quotations')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'sale-notes')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'contingencies')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'brands')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'categories')?'nav-active nav-expanded':'' }}

                        ">
                        <a class="nav-link" href="#">
                            <i class="fas fa-file-invoice" aria-hidden="true"></i>
                            <span>VENTAS</span>
                        </a>
                        <ul class="nav nav-children" style="">
                            @if(auth()->user()->type != 'integrator')

                                @if(in_array('pos', $vc_modules))
                                   <!-- <li class="{{ ($path[0] === 'cash'  )?'nav-active':'' }}">
                                        <a class="nav-link" href="{{route('tenant.cash.index')}}">
                                            Caja chica
                                        </a>
                                    </li>
                                    <li class="{{ ($path[0] === 'pos'  )?'nav-active':'' }}">
                                        <a class="nav-link" href="{{route('tenant.pos.index')}}">
                                            Punto de venta (POS)
                                        </a>
                                    </li> -->
                                @endif

                                @if(in_array('documents', $vc_modules))
                                    <li class="{{ ($path[0] === 'documents' && $path[1] === 'create')?'nav-active':'' }}">
                                        <a class="nav-link" href="{{route('tenant.documents.create')}}">
                                            Nuevo comprobante electrónico
                                        </a>
                                    </li>
                                @endif

                            <!--<li class="{{ ($path[0] === 'documents' && $path[1] === 'create')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.documents.create_tensu')}}">
                                    Nuevo comprobante Tensu
                                </a>
                            </li>-->
                            @endif

                            @if(in_array('documents', $vc_modules))
                                <li class="{{ ($path[0] === 'documents' && $path[1] != 'create' && $path[1] != 'not-sent')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.documents.index')}}">
                                        Listado de comprobantes
                                    </a>
                                </li>
                            @endif

                            @if(in_array('documents', $vc_modules))
                                <li class="{{ ($path[0] === 'documents' && $path[1] === 'not-sent')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.documents.not_sent')}}">
                                        Comprobantes no enviados
                                    </a>
                                </li>
                            @endif

                            @if(auth()->user()->type != 'integrator' && in_array('documents', $vc_modules))
                            <li class="{{ ($path[0] === 'contingencies' )?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.contingencies.index')}}">
                                    Documentos de contingencia
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'items')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.items.index')}}">
                                    Productos
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'categories')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.categories.index')}}">
                                    Categorías
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'brands')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.brands.index')}}">
                                    Marcas
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'persons' && $path[1] === 'customers')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.persons.index', ['type' => 'customers'])}}">
                                    Clientes
                                </a>
                            </li>
                            <li class="nav-parent
                                {{ ($path[0] === 'summaries')?'nav-active nav-expanded':'' }}
                                {{ ($path[0] === 'voided')?'nav-active nav-expanded':'' }}
                                ">
                                <a class="nav-link" href="#">
                                    Resúmenes y Anulaciones
                                </a>
                                <ul class="nav nav-children">
                                    <li class="{{ ($path[0] === 'summaries')?'nav-active':'' }}">
                                        <a class="nav-link" href="{{route('tenant.summaries.index')}}">
                                            Resúmenes
                                        </a>
                                    </li>
                                    <li class="{{ ($path[0] === 'voided')?'nav-active':'' }}">
                                        <a class="nav-link" href="{{route('tenant.voided.index')}}">
                                            Anulaciones
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="{{ ($path[0] === 'quotations')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.quotations.index')}}">
                                    Cotizaciones
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'sale-notes')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.sale_notes.index')}}">
                                    Notas de Venta
                                </a>
                            </li>
                            {{-- <li class="#">
                                <a class="nav-link" href="#">
                                    Ventas sin facturar (Pronto)
                                </a>
                            </li> --}}
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if(auth()->user()->type != 'integrator')
                        @if(in_array('pos', $vc_modules))
                        <li class="
                        nav-parent
                        {{ ($path[0] === 'pos')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'cash')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'item-sets')?'nav-active nav-expanded':'' }}
                        ">
                            <a class="nav-link" href="#">
                                <span class="float-right badge badge-red badge-danger mr-3">Nuevo</span>
                                <i class="fas fa-cash-register" aria-hidden="true"></i>
                                <span>POS</span>
                            </a>
                            <ul class="nav nav-children">
                                <li class="{{ ($path[0] === 'pos'  )?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.pos.index')}}">
                                        Punto de venta
                                    </a>
                                </li>
                                <li class="{{ ($path[0] === 'cash'  )?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.cash.index')}}">
                                        Caja chica
                                    </a>
                                </li>
                                <li class="{{ ($path[0] === 'item-sets'  )?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.item_sets.index')}}">
                                        Conjuntos/Packs/Promociones
                                    </a>
                                </li>

                            </ul>
                        </li>
                        @endif
                    @endif


                    @if(in_array('ecommerce', $vc_modules))
                    <li class="nav-parent {{ in_array($path[0], ['items_ecommerce', 'tags', 'promotions', 'orders'])?'nav-active nav-expanded':'' }}">
                        <a class="nav-link" href="#">
                            <span class="float-right badge badge-red badge-danger mr-3">Nuevo</span>
                            <i class="fas fa-store" aria-hidden="true"></i>
                            <span>Tienda Virtual</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="">
                                <a class="nav-link" onclick="window.open( '{{ route("tenant.ecommerce.index") }} ')">
                                    Ir a Tienda
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'orders')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant_orders_index')}}">
                                    Pedidos
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'items_ecommerce')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.items_ecommerce.index')}}">
                                    Productos Tienda Virtual
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'tags')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.tags.index')}}">
                                    Tags - Categorias
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'promotions')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.promotion.index')}}">
                                    Promociones
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'configuration')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant_ecommerce_configuration')}}">
                                    Configuracion
                                </a>
                            </li>

                        </ul>
                    </li>
                    @endif

                    @if(auth()->user()->type != 'integrator')

                        @if(in_array('purchases', $vc_modules))
                        <li class="
                            nav-parent
                            {{ ($path[0] === 'purchases')?'nav-active nav-expanded':'' }}
                            {{ ($path[0] === 'persons' && $path[1] === 'suppliers')?'nav-active nav-expanded':'' }}
                            {{ ($path[0] === 'expenses')?'nav-active nav-expanded':'' }}
                            ">
                            <a class="nav-link" href="#">
                                <i class="fas fa-cart-plus" aria-hidden="true"></i>
                                <span>Compras</span>
                            </a>
                            <ul class="nav nav-children" style="">
                                <li class="{{ ($path[0] === 'persons' && $path[1] === 'suppliers')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.persons.index', ['type' => 'suppliers'])}}">
                                        Proveedores
                                    </a>
                                </li>
                                <li class="{{ ($path[0] === 'purchases' && $path[1] === 'create')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.purchases.create')}}">
                                        Nuevo
                                    </a>
                                </li>

                                <li class="{{ ($path[0] === 'purchases' && $path[1] != 'create')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.purchases.index')}}">
                                        Listado
                                    </a>
                                </li>

                                <li class="{{ ($path[0] === 'expenses' )?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.expenses.index')}}">
                                        Gastos diversos
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        @if(in_array('inventory', $vc_modules))
                        <li class="nav-parent {{ (in_array($path[0], ['inventory', 'warehouses']) ||
                                                ($path[0] === 'reports' && in_array($path[1], ['kardex', 'inventory'])))?'nav-active nav-expanded':'' }}">
                            <a class="nav-link" href="#">
                                <i class="fas fa-warehouse" aria-hidden="true"></i>
                                <span>Inventario</span>
                            </a>
                            <ul class="nav nav-children" style="">
                                <li class="{{ ($path[0] === 'warehouses')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('warehouses.index')}}">Almacenes</a>
                                </li>
                                <li class="{{ ($path[0] === 'inventory')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('inventory.index')}}">Movimientos</a>
                                </li>
                                <li class="{{(($path[0] === 'reports') && ($path[1] === 'kardex')) ? 'nav-active' : ''}}">
                                    <a class="nav-link" href="{{route('reports.kardex.index')}}">
                                        Reporte Kardex
                                    </a>
                                </li>
                                <li class="{{(($path[0] === 'reports') && ($path[1] == 'inventory')) ? 'nav-active' : ''}}">
                                    <a class="nav-link" href="{{route('reports.inventory.index')}}">
                                        Reporte Inventario
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                    @endif

                    @if(in_array('configuration', $vc_modules))
                    <li class="nav-parent {{ in_array($path[0], ['users', 'establishments'])?'nav-active nav-expanded':'' }}">
                        <a class="nav-link" href="#">
                            <i class="fas fa-users" aria-hidden="true"></i>
                            <span>Usuarios/Locales & Series</span>
                        </a>
                        <ul class="nav nav-children" style="">
                            <li class="{{ ($path[0] === 'users')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.users.index')}}">
                                    Usuarios
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'establishments')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.establishments.index')}}">
                                    Establecimientos
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    @if(in_array('advanced', $vc_modules))
                    <li class="
                        nav-parent
                        {{ ($path[0] === 'retentions')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'dispatches')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'perceptions')?'nav-active nav-expanded':'' }}
                        ">
                        <a class="nav-link" href="#">
                            <i class="fas fa-receipt" aria-hidden="true"></i>
                            <span>Comprobantes avanzados</span>
                        </a>
                        <ul class="nav nav-children" style="">
                            <li class="{{ ($path[0] === 'retentions')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.retentions.index')}}">
                                    Retenciones
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'dispatches')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.dispatches.index')}}">
                                    Guías de remisión
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'perceptions')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.perceptions.index')}}">
                                Percepciones
                                </a>
                            </li>

                        </ul>
                    </li>
                    @endif
                    @if(in_array('reports', $vc_modules))
                    <li class="nav-parent {{  ($path[0] === 'reports' && in_array($path[1], ['purchases', 'search','sales','consistency-documents', 'quotations', 'sale-notes','cash','document-hotels', 'validate-documents'])) ? 'nav-active nav-expanded' : ''}}">
                        <a class="nav-link" href="#">
                            <i class="fas fa-chart-area" aria-hidden="true"></i>
                            <span>Reportes</span>
                        </a>
                        <ul class="nav nav-children" style="">
                            <li class="{{(($path[0] === 'reports') && ($path[1] === 'purchases')) ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.reports.purchases.index')}}">
                                    Compras
                                </a>
                            </li>
                            <li class="{{(($path[0] === 'reports') && ($path[1] === 'sales')) ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.reports.sales.index')}}">
                                    Ventas
                                </a>
                            </li>

                            <li class="{{(($path[0] === 'reports') && ($path[1] == 'consistency-documents')) ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.consistency-documents.index')}}">Consistencia documentos</a>
                            </li>

                            <li class="{{(($path[0] === 'reports') && ($path[1] == 'quotations')) ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.reports.quotations.index')}}">
                                    Cotizaciones
                                </a>
                            </li>
                             <li class="{{(($path[0] === 'reports') && ($path[1] == 'sale-notes')) ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.reports.sale_notes.index')}}">
                                    Notas de Venta
                                </a>
                            </li>
                             <li class="{{(($path[0] === 'reports') && ($path[1] == 'validate-documents')) ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.validate_documents.index')}}">
                                    Validador documentos
                                </a>
                            </li>
                            @if(in_array('hotel', $vc_business_turns))
                            <li class="{{(($path[0] === 'reports') && ($path[1] == 'document-hotels')) ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.reports.document_hotels.index')}}">
                                    Giro negocio hoteles
                                </a>
                            </li>
                            @endif
                             <!-- <li class="{{(($path[0] === 'reports') && ($path[1] == 'cash')) ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.reports.cash.index')}}">
                                    Caja - POS
                                </a>
                            </li> -->






                        </ul>
                    </li>
                    @endif

                    @if(in_array('accounting', $vc_modules))
                    <li class="
                        nav-parent
                        {{ ($path[0] === 'account')?'nav-active nav-expanded':'' }}
                        ">
                        <a class="nav-link" href="#">
                            <span class="float-right badge badge-red badge-danger mr-3">Nuevo</span>
                            <i class="fas fa-chart-bar" aria-hidden="true"></i>
                            <span>Contabilidad</span>
                        </a>
                        <ul class="nav nav-children" style="">
                            <li class="{{(($path[0] === 'account') && ($path[1] === 'format')) ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{ route('tenant.account_format.index') }}">
                                    Exportar formatos
                                </a>
                            </li>
                            <li class="{{(($path[0] === 'account') && ($path[1] !== 'format'))   ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{ route('tenant.account.index') }}">
                                    Exportar SISCONT/CONCAR
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    @if(in_array('configuration', $vc_modules))
                    <li class="nav-parent {{in_array($path[0], ['companies', 'catalogs', 'advanced', 'tasks', 'inventories','company_accounts','bussiness_turns','offline-configurations','series-configurations']) ? 'nav-active nav-expanded' : ''}}">
                        <a class="nav-link" href="#">
                            <i class="fas fa-cogs" aria-hidden="true"></i>
                            <span>Configuración</span>
                        </a>
                        <ul class="nav nav-children" style="">
                            <li class="{{($path[0] === 'companies') ? 'nav-active': ''}}">
                                <a class="nav-link" href="{{route('tenant.companies.create')}}">
                                    Empresa
                                </a>
                            </li>
                            <li class="{{($path[0] === 'company_accounts') ? 'nav-active': ''}}">
                                <a class="nav-link" href="{{route('tenant.company_accounts.create')}}">
                                    Cuentas contables
                                </a>
                            </li>
                            <li class="{{($path[0] === 'bussiness_turns') ? 'nav-active': ''}}">
                                <a class="nav-link" href="{{route('tenant.bussiness_turns.index')}}">
                                    Giro de negocio
                                </a>
                            </li>
                            @if(auth()->user()->type != 'integrator')
                            <li class="{{($path[0] === 'catalogs') ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.catalogs.index')}}">
                                    Catálogos
                                </a>
                            </li>
                            @endif

                            <li class="{{($path[0] === 'advanced') ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.advanced.index')}}">
                                    Avanzado
                                </a>
                            </li>
                            <li class="{{($path[0] === 'offline-configurations') ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.offline_configurations.index')}}">
                                    Modo offline
                                </a>
                            </li>
                            <li class="{{($path[0] === 'series-configurations') ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.series_configurations.index')}}">
                                    Numeración de facturación
                                </a>
                            </li>
                            @if(auth()->user()->type != 'integrator')
                            <li class="{{($path[0] === 'tasks') ? 'nav-active': ''}}">
                                <a class="nav-link" href="{{route('tenant.tasks.index')}}">Tareas programadas</a>
                            </li>
                            <li class="{{($path[0] === 'inventories' && $path[1] === 'configuration') ? 'nav-active': ''}}">
                                <a class="nav-link" href="{{route('tenant.inventories.configuration.index')}}">Inventarios</a>
                            </li>
                            @endif

                        </ul>
                    </li>
                    @endif

                    @if(in_array('cuenta', $vc_modules))
                    <li class=" nav-parent
                        {{ ($path[0] === 'cuenta')?'nav-active nav-expanded':'' }}">
                        <a class="nav-link" href="#">
                            <span class="float-right badge badge-red badge-danger mr-3">Nuevo</span>
                            <i class="fas fa-dollar-sign" aria-hidden="true"></i>
                            <span>Mis Pagos</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="{{ (($path[0] === 'cuenta') && ($path[1] === 'configuration')) ?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.configuration.index')}}">
                                    Configuracion
                                </a>
                            </li>
                            <li class="{{ (($path[0] === 'cuenta') && ($path[1] === 'payment_index')) ?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.payment.index')}}">
                                    Lista de Pagos
                                </a>
                            </li>

                        </ul>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>
        <script>
            // Maintain Scroll Position
            if (typeof localStorage !== 'undefined') {
                if (localStorage.getItem('sidebar-left-position') !== null) {
                    var initialPosition = localStorage.getItem('sidebar-left-position'),
                        sidebarLeft = document.querySelector('#sidebar-left .nano-content');
                    sidebarLeft.scrollTop = initialPosition;
                }
            }
        </script>
    </div>
</aside>
