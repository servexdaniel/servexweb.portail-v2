<!doctype html>
<html lang="en">

<head>
    <title>:: HexaBit :: Page Blank</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="description" content="HexaBit Bootstrap 4x Admin Template">
    <meta name="author" content="WrapTheme, www.thememakker.com">

    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="{{ asset('v1/light/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('v1/light/vendor/font-awesome/css/font-awesome.min.css') }}">


    <!-- MAIN CSS -->
    <link rel="stylesheet" href="{{ asset('v1/light/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('v1/light/css/color_skins.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="theme-orange">

    <!-- Page Loader -->
    {{-- <div class="page-loader-wrapper">
        <div class="loader">
            <div class="m-t-30"><img src="../assets/images/icon-light.svg" width="48" height="48" alt="HexaBit">
            </div>
            <p>Please wait...</p>
        </div>
    </div> --}}
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>

    <div id="wrapper">

        <nav class="navbar navbar-fixed-top">
            <div class="container-fluid">

                <div class="navbar-left">
                    <div class="navbar-btn">
                        <a href="index.html"><img src="../assets/images/icon-light.svg" alt="HexaBit Logo"
                                class="img-fluid logo"></a>
                        <button type="button" class="btn-toggle-offcanvas"><i
                                class="lnr lnr-menu fa fa-bars"></i></button>
                    </div>
                    <a href="javascript:void(0);" class="icon-menu btn-toggle-fullwidth"><i
                            class="fa fa-arrow-left"></i></a>
                </div>

                <div class="navbar-right">

                    <div id="navbar-menu">
                        <ul class="nav navbar-nav">
                            <li><a href="javascript:void(0);" class="right_toggle icon-menu" title="Right Menu"><i
                                        class="icon-settings"></i></a></li>
                            <li><a href="page-login.html" class="icon-menu"><i class="icon-power"></i></a></li>
                            <li>
                                <div>
                                    <div class="dropdown">
                                        <span>Welcome,</span>
                                        <a href="javascript:void(0);" class="dropdown-toggle user-name"
                                            data-toggle="dropdown">
                                            <strong>
                                                <span x-data="{{ json_encode(['name' => auth('contact')->user()->CcName]) }}" x-text="name"
                                                    x-on:contact-profile-updated.window="name = $event.detail.name;">
                                                </span>
                                            </strong>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right account">
                                            <li><a href={{ route('contact.profile') }}>My Profile</a></li>
                                            <li><a href="javascript:void(0);" class="right_toggle">Settings</a></li>
                                            <li>
                                                <form method="POST" action="{{ route('contact.logout') }}">
                                                    @csrf

                                                    <a
                                                        onclick="event.preventDefault();
                                                                        this.closest('form').submit();">Logout</a>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        @include('layouts.v1.partials.rightbar')

        @include('layouts.v1.partials.sidebar')

        <div id="main-content">
            <div class="block-header">
                <div class="row clearfix">
                    @isset($title)
                        <div class="col-md-6 col-sm-12">
                            <h2>{{ $title }}</h2>
                        </div>
                    @endisset
                    <div class="col-md-6 col-sm-12 text-right">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="icon-home"></i></a></li>
                            <li class="breadcrumb-item">Pages</li>
                            @isset($title)
                                <li class="breadcrumb-item active">
                                    {{ $title }}
                                </li>
                            @endisset
                        </ul>
                        <a href="javascript:void(0);" class="btn btn-sm btn-primary" title="">Create New</a>
                    </div>
                </div>
            </div>

            <div class="container-fluid">

                <div class="row clearfix">

                    <div class="col-lg-12 col-md-12">
                        @yield('content')
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- Javascript -->
    <script src="{{ asset('v1/light/bundles/libscripts.bundle.js') }}"></script>
    <script src="{{ asset('v1/light/bundles/vendorscripts.bundle.js') }}"></script>

    <script src="{{ asset('v1/light/bundles/mainscripts.bundle.js') }}"></script>
</body>

</html>
