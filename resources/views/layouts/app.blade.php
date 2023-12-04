<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Health & Life Center</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="{{asset('storage/plugins/bower_components/chartist/dist/chartist.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('storage/plugins/bower_components/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.css')}}">
    <!-- Custom CSS -->
    <link href="{{asset('storage/css/style.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
        <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" data-logobg="skin6">
                    <a class="navbar-brand" href="https://healthandlifecentre.com" target="_blank">
                        <span class="logo-text">
                            <b class="text-dark">CHALK</b>
                        </span>
                    </a>
                    <a class="nav-toggler waves-effect waves-light text-dark d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                </div>
                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                    <ul class="navbar-nav ms-auto d-flex align-items-center">
                        <li>
                            @guest
                            @else
                            <a class="profile-pic" href="#">
                                <i class="fa fa-user"></i>
                                <span class="text-white font-medium"> {{Auth()->user()->name}}</span>
                            </a>
                            @endguest
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        @guest
        @else
        <aside class="left-sidebar" data-sidebarbg="skin6">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="sidebar-item pt-2">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/" aria-expanded="false">
                                <i class="fa fa-home" aria-hidden="true"></i>
                                <span class="hide-menu">Home</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/fees" aria-expanded="false">
                                <i class="fa-solid fa-hand-holding-dollar" aria-hidden="true"></i>
                                <span class="hide-menu">Fees</span>
                            </a>
                        </li>
                        @if(Auth()->user()->role=='Admin')
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/enrolls" aria-expanded="false">
                                <i class="fa fa-table" aria-hidden="true"></i>
                                <span class="hide-menu">Enrolled Students</span>
                            </a>
                        </li>
                        @endif
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/mycourses" aria-expanded="false">
                                <i class="fa fa-list" aria-hidden="true"></i>
                                <span class="hide-menu">My Courses</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/profile" aria-expanded="false">
                                <i class="fa fa-user" aria-hidden="true"></i>
                                <span class="hide-menu">Profile</span>
                            </a>
                        </li>
                        <li class="text-center p-20 upgrade-btn">
                            <a href="/logout" class="btn d-grid btn-danger text-white">
                                Logout
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        @endguest
        <div class="page-wrapper">
            <div class="mt-3 d-flex align-content-center" style="min-height: 500px;">
                @yield('content')
            </div>
            <footer class="footer text-center"> {{date('Y')}} © Health & Life Center <a href="https://apekinc.top/">APEK INC</a>
            </footer>
        </div>
    </div>
    <script src="{{asset('storage/plugins/bower_components/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('storage/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('storage/js/app-style-switcher.js')}}"></script>
    <script src="{{asset('storage/plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js')}}"></script>
    <script src="{{asset('storage/js/waves.js')}}"></script>
    <script src="{{asset('storage/js/sidebarmenu.js')}}"></script>
    <script src="{{asset('storage/js/custom.js')}}"></script>
    <script src="{{asset('storage/plugins/bower_components/chartist/dist/chartist.min.js')}}"></script>
    <script src="{{asset('storage/plugins/bower_components/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js')}}"></script>
    <script src="{{asset('storage/js/pages/dashboards/dashboard1.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>