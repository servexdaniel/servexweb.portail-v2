<!DOCTYPE html>
<html lang="en">

<head>
    <title>:: HexaBit :: Login</title>
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
</head>

<body class="theme-orange">
    <!-- WRAPPER -->
    <div id="wrapper" class="auth-main">
        <div class="container">
            <div class="row clearfix">
                @include('partials.messages')
                <div class="col-12">
                    <nav class="navbar navbar-expand-lg">
                        <a class="navbar-brand" href="javascript:void(0);"><img
                                src="{{ asset('v1/images/Logo_servex.png') }}" width="auto" height="30"
                                class="d-inline-block align-top mr-2" alt=""></a>
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link" href="javascript:void(0);">Documentation</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="page-register.html">Sign Up</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-8">
                    <div class="auth_detail">
                        <h2 class="text-monospace">
                            Everything<br> you need for
                            <div id="carouselExampleControls" class="carousel vert slide" data-ride="carousel"
                                data-interval="1500">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">your Admin</div>
                                    <div class="carousel-item">your Project</div>
                                    <div class="carousel-item">your Dashboard</div>
                                    <div class="carousel-item">your Application</div>
                                    <div class="carousel-item">your Client</div>
                                </div>
                            </div>
                        </h2>
                        <p>It is a long established fact that a reader will be distracted by the readable content of a
                            page when looking at its layout.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <!-- END WRAPPER -->

    <script src="{{ asset('v1/light/bundles/libscripts.bundle.js') }}"></script>
    <script src="{{ asset('v1/light/bundles/vendorscripts.bundle.js') }}"></script>

    <script src="{{ asset('v1/light/bundles/mainscripts.bundle.js') }}"></script>
</body>

</html>
