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
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="m-t-30"><img src="../assets/images/icon-light.svg" width="48" height="48" alt="HexaBit">
            </div>
            <p>Please wait...</p>
        </div>
    </div>
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
                    <ul class="nav navbar-nav">
                        <li class="dropdown dropdown-animated scale-right">
                            <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown"><i
                                    class="icon-grid"></i></a>
                            <ul class="dropdown-menu menu-icon app_menu">
                                <li>
                                    <a class="#">
                                        <i class="icon-envelope"></i>
                                        <span>Inbox</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="#">
                                        <i class="icon-bubbles"></i>
                                        <span>Chat</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="#">
                                        <i class="icon-list"></i>
                                        <span>Task</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="#">
                                        <i class="icon-globe"></i>
                                        <span>Blog</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li><a href="app-calendar.html" class="icon-menu d-none d-sm-block d-md-none d-lg-block"><i
                                    class="icon-calendar"></i></a></li>
                        <li><a href="app-chat.html" class="icon-menu d-none d-sm-block"><i class="icon-bubbles"></i></a>
                        </li>
                    </ul>
                </div>

                <div class="navbar-right">
                    <form id="navbar-search" class="navbar-form search-form">
                        <input value="" class="form-control" placeholder="Search here..." type="text">
                        <button type="button" class="btn btn-default"><i class="icon-magnifier"></i></button>
                    </form>

                    <div id="navbar-menu">
                        <ul class="nav navbar-nav">
                            <li class="dropdown dropdown-animated scale-left">
                                <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown">
                                    <i class="icon-envelope"></i>
                                    <span class="notification-dot"></span>
                                </a>
                                <ul class="dropdown-menu right_chat email">
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="media">
                                                <img class="media-object " src="../assets/images/xs/avatar4.jpg"
                                                    alt="">
                                                <div class="media-body">
                                                    <span class="name">James Wert <small class="float-right">Just
                                                            now</small></span>
                                                    <span class="message">Lorem ipsum Veniam aliquip culpa laboris minim
                                                        tempor</span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="media">
                                                <img class="media-object " src="../assets/images/xs/avatar1.jpg"
                                                    alt="">
                                                <div class="media-body">
                                                    <span class="name">Folisise Chosielie <small
                                                            class="float-right">12min ago</small></span>
                                                    <span class="message">There are many variations of Lorem Ipsum
                                                        available, but the majority</span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="media">
                                                <img class="media-object " src="../assets/images/xs/avatar5.jpg"
                                                    alt="">
                                                <div class="media-body">
                                                    <span class="name">Ava Alexander <small
                                                            class="float-right">38min ago</small></span>
                                                    <span class="message">Many desktop publishing packages and web page
                                                        editors</span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="media mb-0">
                                                <img class="media-object " src="../assets/images/xs/avatar2.jpg"
                                                    alt="">
                                                <div class="media-body">
                                                    <span class="name">Debra Stewart <small class="float-right">2hr
                                                            ago</small></span>
                                                    <span class="message">Contrary to popular belief, Lorem Ipsum is
                                                        not simply random text</span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown dropdown-animated scale-left">
                                <a href="javascript:void(0);" class="dropdown-toggle icon-menu"
                                    data-toggle="dropdown">
                                    <i class="icon-bell"></i>
                                    <span class="notification-dot"></span>
                                </a>
                                <ul class="dropdown-menu feeds_widget">
                                    <li class="header">You have 5 new Notifications</li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="feeds-left"><i class="fa fa-thumbs-o-up text-success"></i>
                                            </div>
                                            <div class="feeds-body">
                                                <h4 class="title text-success">7 New Feedback <small
                                                        class="float-right text-muted">Today</small></h4>
                                                <small>It will give a smart finishing to your site</small>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="feeds-left"><i class="fa fa-user"></i></div>
                                            <div class="feeds-body">
                                                <h4 class="title">New User <small
                                                        class="float-right text-muted">10:45</small></h4>
                                                <small>I feel great! Thanks team</small>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="feeds-left"><i class="fa fa-question-circle text-warning"></i>
                                            </div>
                                            <div class="feeds-body">
                                                <h4 class="title text-warning">Server Warning <small
                                                        class="float-right text-muted">10:50</small></h4>
                                                <small>Your connection is not private</small>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="feeds-left"><i class="fa fa-check text-danger"></i></div>
                                            <div class="feeds-body">
                                                <h4 class="title text-danger">Issue Fixed <small
                                                        class="float-right text-muted">11:05</small></h4>
                                                <small>WE have fix all Design bug with Responsive</small>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="feeds-left"><i class="fa fa-shopping-basket"></i></div>
                                            <div class="feeds-body">
                                                <h4 class="title">7 New Orders <small
                                                        class="float-right text-muted">11:35</small></h4>
                                                <small>You received a new oder from Tina.</small>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="javascript:void(0);" class="right_toggle icon-menu" title="Right Menu"><i
                                        class="icon-settings"></i></a></li>
                            <li><a href="page-login.html" class="icon-menu"><i class="icon-power"></i></a></li>
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
                        <div class="card planned_task">
                            <div class="header">
                                <h2>Stater Page</h2>
                                <ul class="header-dropdown dropdown dropdown-animated scale-left">
                                    <li> <a href="javascript:void(0);" data-toggle="cardloading"
                                            data-loading-effect="pulse"><i class="icon-refresh"></i></a></li>
                                    <li><a href="javascript:void(0);" class="full-screen"><i
                                                class="icon-size-fullscreen"></i></a></li>
                                    <li class="dropdown">
                                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"
                                            role="button" aria-haspopup="true" aria-expanded="false"></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="javascript:void(0);">Action</a></li>
                                            <li><a href="javascript:void(0);">Another Action</a></li>
                                            <li><a href="javascript:void(0);">Something else</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <div class="body">
                                <h4>Stater Page</h4>
                            </div>
                        </div>

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
