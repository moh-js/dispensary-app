<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>{{ getAppShortName() }} {{ Request::segment(2)? '| '. Request::segment(2) : 'dashboard' }}</title>
    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 11]>
    	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    	<![endif]-->
    <!-- Meta -->
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Phoenixcoded" />
    <!-- Favicon icon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    @stack('css')


    <!-- prism css -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/prism-coy.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <!-- vendor css -->

    <link rel="stylesheet" href="{{ asset('assets/css/plugins/print.min.css') }}">

    <link href="{{ asset('assets/css/plugins/googlefonts.css') }}" rel="stylesheet">

    <style>
        body {
            font-family: 'Quicksand', sans-serif;
        }

        .list {
          border-top: 1px solid rgb(218, 218, 218);
        }
    </style>

    @livewireStyles


</head>

<body class="">
    @auth

    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    @php
        $inventoryNav = collect([]);
        foreach (App\Models\InventoryCategory::all() as $category) {
            $inventoryNav->push([
                'title' => title_case($category->name), 'url' => route('items.index', $category->slug), 'permission' => request()->user()->hasAnyPermission('item-view')
            ]);
        }

        $navigations = collect([
            [
                'title' => 'Dashboard', 'url' => route('dashboard'), 'permission' => request()->user()->hasAnyPermission('dashboard'), 'icon' => 'feather icon-home', 'childrens' => collect(),
            ], [
                'title' => 'Users Mgt', 'url' => 'javascript:void(0)', 'permission' => request()->user()->hasAnyPermission('user-view', 'user-add', 'user-update', 'user-delete', 'user-activate', 'user-deactivate'), 'icon' => 'feather icon-users', 'childrens' => collect([
                    [
                        'title' => 'List', 'url' => route('users.index'), 'permission' => request()->user()->hasAnyPermission('user-view')

                    ], [
                        'title' => 'Add', 'url' => route('users.add'), 'permission' => request()->user()->hasAnyPermission('user-add')
                    ], [
                        'title' => 'Role', 'url' => route('roles.index'), 'permission' => request()->user()->hasAnyPermission('user-add')
                    ]
                ]),
            ], [
                'title' => 'Inventory', 'url' => 'javascript:void(0)', 'permission' => request()->user()->hasAnyPermission('item-view'), 'icon' => 'feather icon-layers', 'childrens' => collect($inventoryNav),
            ], [
                'title' => 'Services', 'url' => route('services.index'), 'permission' => request()->user()->hasAnyPermission('service-view'), 'icon' => 'feather icon-shopping-cart', 'childrens' => collect(),
            ], [
                'title' => 'Audits', 'url' => 'javascript:void(0)', 'permission' => request()->user()->hasAnyPermission(['service-audits-view', 'inventory-audits-view']), 'icon' => 'feather icon-alert-octagon', 'childrens' => collect([
                    [
                        'title' => 'Service', 'url' => route('service-audits'), 'permission' => request()->user()->hasAnyPermission('service-audits-view')
                    ], [
                        'title' => 'Inventory', 'url' => route('inventory-audits'), 'permission' => request()->user()->hasAnyPermission('inventory-audits-view')
                    ]
                ]),
            ], [
                'title' => 'Configuration', 'url' => 'javascript:void(0)', 'permission' => request()->user()->hasAnyPermission(['configuration-general', 'configuration-data-import']), 'icon' => 'feather icon-settings', 'childrens' => collect([
                    [
                        'title' => 'General', 'url' => route('general.index'), 'permission' => request()->user()->hasAnyPermission('configuration-general')
                    ], [
                        'title' => 'Data Import', 'url' => route('data.index'), 'permission' => request()->user()->hasAnyPermission('configuration-data-import')
                    ]
                ]),
            ], [
                'title' => 'Reports', 'url' => 'javascript:void(0)', 'permission' => request()->user()->hasAnyPermission(['configuration-general', 'configuration-data-import', 'report-cash-view']), 'icon' => 'feather icon-clipboard', 'childrens' => collect([
                    [
                        'title' => 'Inventory Ledgers', 'url' => route('inventory-ledger.index'), 'permission' => request()->user()->hasAnyPermission('report-inventory-ledger-view')
                    ], [
                        'title' => 'Dispensing', 'url' => route('dispensing.index'), 'permission' => request()->user()->hasAnyPermission('report-dispensing-view')
                    ], [
                        'title' => 'Cash Book', 'url' => route('cash.index'), 'permission' => request()->user()->hasAnyPermission('report-cash-view')
                    ]
                ]),
            ]
        ]);
    @endphp

    <!-- [ navigation menu ] start -->
    @php
        $currentPath = request()->url();
        $userRole = request()->user()->getRoleNames()->first();
    @endphp

    <nav class="pcoded-navbar theme-horizontal menu-light brand-blue">
        <div class="navbar-wrapper container">
            <div class="navbar-content sidenav-horizontal" id="layout-sidenav">
                <ul class="nav pcoded-inner-navbar sidenav-inner">
                    @foreach ($navigations as $navigation)
                        @php
                            $HasChildrens = ($navigation['childrens']->count());
                        @endphp

                        @if ($navigation['permission'] || $userRole == 'super-admin')
                            <li class="nav-item  {{ ($HasChildrens)? 'pcoded-hasmenu':'' }} {{ $HasChildrens? (($navigation['childrens']->contains('url', $currentPath))?'active':''):(($navigation['url'] == $currentPath)? 'active':'') }}">
                                <a href="{{ $navigation['url'] }}" class="nav-link"><span class="pcoded-micon"><i class="{{ $navigation['icon'] }}"></i></span><span class="pcoded-mtext">{{ $navigation['title'] }}</span></a>
                                @if ($HasChildrens)
                                    <ul class="pcoded-submenu">
                                        @foreach ($navigation['childrens'] as $nav)
                                        @if ($nav['permission'] || $userRole == 'super-admin')
                                            <li class="{{ $nav['url'] == $currentPath? 'active':'' }}"><a href="{{ $nav['url'] }}">{{ $nav['title'] }}</a></li>
                                        @endif
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </nav>
    <!-- [ navigation menu ] end -->
    <!-- [ Header ] start -->
    <header class="navbar pcoded-header navbar-expand-lg navbar-light header-blue">
        <div class="container">
            <div class="m-header">
                <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
                <a href="{{ url('/') }}" class="b-brand">
                    <!-- ========   change your logo hear   ============ -->
                    {{-- <img src="{{ asset('image/must_logo.png') }}" alt="" width="50" class="logo"> --}}
                    <strong>{{ strtoupper(getAppName()) }}</strong>
                    {{-- <img src="assets/images/logo-icon.png" alt="" class="logo-thumb"> --}}
                </a>
                <a href="#!" class="mob-toggler">
                    <i class="feather icon-more-vertical"></i>
                </a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        @livewire('search')
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    {{-- <li>
                        <div class="dropdown">
                            <a class="dropdown-toggle" href="#" data-toggle="dropdown"><i class="icon feather icon-bell"></i></a>
                            <div class="dropdown-menu dropdown-menu-right notification">
                                <div class="noti-head">
                                    <h6 class="d-inline-block m-b-0">Notifications</h6>
                                    <div class="float-right">
                                        <a href="#!" class="m-r-10">mark as read</a>
                                        <a href="#!">clear all</a>
                                    </div>
                                </div>
                                <ul class="noti-body">
                                    <li class="n-title">
                                        <p class="m-b-0">NEW</p>
                                    </li>
                                    <li class="notification">
                                        <div class="media">
                                            <img class="img-radius" src="assets/images/user/avatar-1.jpg" alt="Generic placeholder image">
                                            <div class="media-body">
                                                <p><strong>John Doe</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>5 min</span></p>
                                                <p>New ticket Added</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="n-title">
                                        <p class="m-b-0">EARLIER</p>
                                    </li>
                                    <li class="notification">
                                        <div class="media">
                                            <img class="img-radius" src="assets/images/user/avatar-2.jpg" alt="Generic placeholder image">
                                            <div class="media-body">
                                                <p><strong>Joseph William</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>10 min</span></p>
                                                <p>Prchace New Theme and make payment</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="notification">
                                        <div class="media">
                                            <img class="img-radius" src="assets/images/user/avatar-1.jpg" alt="Generic placeholder image">
                                            <div class="media-body">
                                                <p><strong>Sara Soudein</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>12 min</span></p>
                                                <p>currently login</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="notification">
                                        <div class="media">
                                            <img class="img-radius" src="assets/images/user/avatar-2.jpg" alt="Generic placeholder image">
                                            <div class="media-body">
                                                <p><strong>Joseph William</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>30 min</span></p>
                                                <p>Prchace New Theme and make payment</p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <div class="noti-footer">
                                    <a href="#!">show all</a>
                                </div>
                            </div>
                        </div>
                    </li> --}}
                    <li>
                        <div class="dropdown drp-user">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="feather icon-user"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-notification">
                                <div class="pro-head">
                                    <img src="{{ asset('image/patient-male.png') }}" class="img-radius" alt="User-Profile-Image">
                                    <span>{{ auth()->user()->name }}</span>
                                    <a href="javascript:void(0)" onclick="$('#logout').submit()" class="dud-logout" title="Logout">
                                        <i class="feather icon-log-out"></i>
                                    </a>
                                </div>
                                <ul class="pro-body">
                                    <li><a href="{{ route('my.profile') }}" class="dropdown-item"><i class="feather icon-user"></i> Profile</a></li>
                                    {{-- <li><a href="email_inbox.html" class="dropdown-item"><i class="feather icon-mail"></i> My Messages</a></li>
                                    <li><a href="auth-signin.html" class="dropdown-item"><i class="feather icon-lock"></i> Lock Screen</a></li> --}}
                                </ul>
                            </div>
                            <form action="{{ route('logout') }}" id="logout" method="post">@csrf</form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <!-- [ Header ] end -->

    @php

        $segment_1 = Request::segment(1);
        $segment_2 = Request::segment(2);
        $segment_3 = Request::segment(3);

    @endphp

    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper container-fluid">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <div class="main-body">
                        <div class="page-wrapper">
                            <div class="page-header">
                                <div class="page-block">
                                    <div class="row align-items-center">
                                        <div class="col-md-12">
                                            <div class="page-header-title">
                                                <h5 class="m-b-10 text-capitalize">{{ strtoupper($segment_3??$segment_2??$segment_1) }}</h5>
                                            </div>
                                            <ul class="breadcrumb">
                                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                                                @if ($segment_2)
                                                <li class="breadcrumb-item"><a href="{{ Route::has($segment_2.'.index')?(route($segment_2.'.index')):'#' }}">{{ $segment_2 }}</a></li>
                                                @endif
                                                @if ($segment_3)
                                                <li class="breadcrumb-item"><a href="#">{{ $segment_3 }}</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div wire:ignore>
                                @include('flash::message')
                            </div>

                            @yield('content')

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->

    <!-- Warning Section start -->
    <!-- Older IE warning message -->
    <!--[if lt IE 11]>
        <div class="ie-warning">
            <h1>Warning!!</h1>
            <p>You are using an outdated version of Internet Explorer, please upgrade
                <br/>to any of the following web browsers to access this website.
            </p>
            <div class="iew-container">
                <ul class="iew-download">
                    <li>
                        <a href="http://www.google.com/chrome/">
                            <img src="assets/images/browser/chrome.png" alt="Chrome">
                            <div>Chrome</div>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.mozilla.org/en-US/firefox/new/">
                            <img src="assets/images/browser/firefox.png" alt="Firefox">
                            <div>Firefox</div>
                        </a>
                    </li>
                    <li>
                        <a href="http://www.opera.com">
                            <img src="assets/images/browser/opera.png" alt="Opera">
                            <div>Opera</div>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.apple.com/safari/">
                            <img src="assets/images/browser/safari.png" alt="Safari">
                            <div>Safari</div>
                        </a>
                    </li>
                    <li>
                        <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                            <img src="assets/images/browser/ie.png" alt="">
                            <div>IE (11 & above)</div>
                        </a>
                    </li>
                </ul>
            </div>
            <p>Sorry for the inconvenience!</p>
        </div>
    <![endif]-->
    <!-- Warning Section Ends -->

    <!-- Required Js -->
    <script src="{{ asset('assets/js/vendor-all.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/ripple.js') }}"></script>
    <script src="{{ asset('assets/js/pcoded.min.js') }}"></script>

    @stack('js')

    {{-- <script src="{{ asset('assets/js/pages/dashboard-main.js') }}"></script> --}}


    <script>
        $('#flash-overlay-modal').modal();
    </script>

    <!-- prism Js -->
    <script src="{{ asset('assets/js/plugins/prism.js') }}"></script>

    <script src="{{ asset('assets/js/analytics.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/print.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-select-custom.js') }}"></script>

    @livewireScripts

    <script src="{{ asset('assets/js/horizontal-menu.js') }}"></script>
    <script>
        (function() {
            if ($('#layout-sidenav').hasClass('sidenav-horizontal') || window.layoutHelpers.isSmallScreen()) {
                return;
            }
            try {
                window.layoutHelpers._getSetting("Rtl")
                window.layoutHelpers.setCollapsed(
                    localStorage.getItem('layoutCollapsed') === 'true',
                    false
                );
            } catch (e) {}
        })();
        $(function() {
            $('#layout-sidenav').each(function() {
                new SideNav(this, {
                    orientation: $(this).hasClass('sidenav-horizontal') ? 'horizontal' : 'vertical'
                });
            });
            $('body').on('click', '.layout-sidenav-toggle', function(e) {
                e.preventDefault();
                window.layoutHelpers.toggleCollapsed();
                if (!window.layoutHelpers.isSmallScreen()) {
                    try {
                        localStorage.setItem('layoutCollapsed', String(window.layoutHelpers.isCollapsed()));
                    } catch (e) {}
                }
            });
        });
        $(document).ready(function() {
            $("#pcoded").pcodedmenu({
                themelayout: 'horizontal',
                MenuTrigger: 'hover',
                SubMenuTrigger: 'hover',
            });
        });
    </script>

    <script src="{{ asset('assets/js/alphine.min.js') }}" defer></script>

    @endauth

    @guest
        <!-- [ auth-signin ] start -->
        @yield('content')
        <!-- [ auth-signin ] end -->

        <!-- Required Js -->
        <script src="{{ asset('assets/js/vendor-all.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/ripple.js') }}"></script>
        <script src="{{ asset('assets/js/pcoded.min.js') }}"></script>
    @endguest

    <script type="text/javascript">
        (function(){
            $('form').on('submit', function(){
                $("button[type='submit']").attr('disabled','true');
            })
        })();
    </script>

</body>

</html>

