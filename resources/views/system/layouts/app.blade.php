<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="sidebar-light sidebar-left-big-icons dashboard-system">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="googlebot" content="noindex">
    <meta name="robots" content="noindex">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Facturación Electrónica</title>

    <!-- Scripts -->

    <!-- Fonts -->
    {{--<link rel="dns-prefetch" href="https://fonts.gstatic.com">--}}
    {{--<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">--}}

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="{{ asset('porto-light/vendor/bootstrap/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('porto-light/vendor/animate/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('porto-light/vendor/font-awesome/css/fontawesome-all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('porto-light/vendor/select2/css/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('porto-light/vendor/select2-bootstrap-theme/select2-bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('porto-light/vendor/datatables/media/css/dataTables.bootstrap4.css') }}" />
    <link rel="stylesheet" href="{{ asset('porto-light/css/theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('porto-light/css/custom.css') }}" />

    @if (file_exists(public_path('theme/custom_styles.css')))
        <link rel="stylesheet" href="{{ asset('theme/custom_styles.css') }}" />
    @endif

    {{--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" />--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.26.29/sweetalert2.min.css" />
    <link rel="stylesheet" href="{{asset('porto-light/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css')}}" />

    <!-- Specific Page Vendor CSS -->
    <link rel="stylesheet" href="{{asset('porto-light/vendor/jquery-ui/jquery-ui.css')}}" />
    <link rel="stylesheet" href="{{asset('porto-light/vendor/jquery-ui/jquery-ui.theme.css')}}" />
    <link rel="stylesheet" href="{{asset('porto-light/vendor/select2/css/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('porto-light/vendor/select2-bootstrap-theme/select2-bootstrap.min.css')}}" />

    <!-- Daterange picker plugins css -->
    <link href="{{ asset('porto-light/vendor/bootstrap-timepicker/css/bootstrap-timepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('porto-light/vendor/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('porto-light/vendor/bootstrap-timepicker/css/bootstrap-timepicker.css')}}" />

    <link rel="stylesheet" href="{{asset('porto-light/vendor/jquery-loading/dist/jquery.loading.css')}}" />

    @stack('styles')


    <script src="{{ asset('porto-light/vendor/modernizr/modernizr.js') }}"></script>

    <style>
        .descarga {
            color:black;
            padding:5px;
        }
        html.sidebar-light:not(.dark) ul.nav-main > li.nav-active > a {
            color: #0088CC;
        }

        ul.nav-main > li.nav-active > a {
            box-shadow: 2px 0 0 #0088CC inset;
        }
    </style>
</head>
<body class="pr-0">
    <section class="body">
        <!-- start: header -->
        @include('system.layouts.partials.header')
        <!-- end: header -->
        <div class="inner-wrapper">
            <!-- start: sidebar -->
            @include('system.layouts.partials.sidebar')
            <!-- end: sidebar -->
            <section role="main" class="content-body" id="main-wrapper">
              @yield('content')
            </section>
        </div>
    </section>

    <!-- Vendor -->
    <script src="{{ asset('porto-light/vendor/jquery/jquery.js')}}"></script>
    <script src="{{ asset('porto-light/vendor/jquery-browser-mobile/jquery.browser.mobile.js')}}"></script>
    <script src="{{ asset('porto-light/vendor/jquery-cookie/jquery-cookie.js')}}"></script>
        {{--<script src="{{asset('master/style-switcher/style.switcher.js')}}"></script>--}}
    <script src="{{ asset('porto-light/vendor/popper/umd/popper.min.js')}}"></script>
    <!-- <script src="{{ asset('porto-light/vendor/bootstrap/js/bootstrap.js')}}"></script> -->
    {{-- <script src="{{ asset('porto-light/vendor/common/common.js')}}"></script> --}}
    <script src="{{ asset('porto-light/vendor/nanoscroller/nanoscroller.js')}}"></script>
    <script src="{{ asset('porto-light/vendor/magnific-popup/jquery.magnific-popup.js')}}"></script>
    <script src="{{ asset('porto-light/vendor/jquery-placeholder/jquery-placeholder.js')}}"></script>
    <script src="{{ asset('porto-light/vendor/select2/js/select2.js') }}"></script>
    <script src="{{ asset('porto-light/vendor/datatables/media/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('porto-light/vendor/datatables/media/js/dataTables.bootstrap4.min.js')}}"></script>

    {{-- Specific Page Vendor --}}
    <script src="{{asset('porto-light/vendor/jquery-ui/jquery-ui.js')}}"></script>
    <script src="{{asset('porto-light/vendor/jqueryui-touch-punch/jqueryui-touch-punch.js')}}"></script>
    <script src="{{asset('porto-light/vendor/select2/js/select2.js')}}"></script>

    <script src="{{asset('porto-light/vendor/jquery-loading/dist/jquery.loading.js')}}"></script>

    <!--<script src="assets/vendor/select2/js/select2.js"></script>-->
    <script src="{{asset('porto-light/vendor/bootstrap-multiselect/bootstrap-multiselect.js')}}"></script>

    <!-- Moment -->
    <script src="{{ asset('porto-light/vendor/moment/moment.js') }}"></script>

    <!-- DatePicker -->
    <script src="{{asset('porto-light/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>

    <!-- Date range Plugin JavaScript -->
    <script src="{{ asset('porto-light/vendor/bootstrap-timepicker/bootstrap-timepicker.js') }}"></script>
    <script src="{{ asset('porto-light/vendor/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

    <!-- Theme Custom -->
    <script src="{{asset('porto-light/js/custom.js')}}"></script>

    <!-- Theme Initialization Files -->
    {{-- <script src="{{asset('porto-light/js/theme.init.js')}}"></script> --}}

    {{--<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>--}}
    {{--<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>--}}

    @stack('scripts')

    <script src="{{ asset('js/manifest.js') }}"></script>
    <script src="{{ asset('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <!-- Theme Base, Components and Settings -->
    <script src="{{asset('porto-light/js/theme.js')}}"></script>
    <!-- <script src="//code.tidio.co/1vliqewz9v7tfosw5wxiktpkgblrws5w.js"></script> -->
</body>
</html>
