<div id="left-sidebar" class="sidebar">
    <div class="navbar-brand">
        <a href="#"><img src="{{ asset('v1/images/Logo_servex.png') }}" width="150" height="30"
                alt="HexaBit Logo" class="img-fluid logo"><span>HexaBit</span></a>
        <button type="button" class="btn-toggle-offcanvas btn btn-sm btn-default float-right"><i
                class="lnr lnr-menu fa fa-chevron-circle-left"></i></button>
    </div>
    <div class="sidebar-scroll">
        <div class="user-account">
            <div class="dropdown">
                <span>Welcome,</span>
                <a href="javascript:void(0);" class="dropdown-toggle user-name" data-toggle="dropdown">
                    <strong>
                        <span x-data="{{ json_encode(['name' => auth('contact')->user()->CcName]) }}" x-text="name"
                            x-on:contact-profile-updated.window="name = $event.detail.name;">
                        </span>
                    </strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-right account">
                    <li><a href="{{ route('admin.profile') }}"><i class="icon-user"></i>My Profile</a></li>
                    <li><a href="app-inbox.html"><i class="icon-envelope-open"></i>Messages</a></li>
                    <li><a href="javascript:void(0);"><i class="icon-settings"></i>Settings</a></li>
                    <li class="divider"></li>
                    <li><a href="page-login.html"><i class="icon-power"></i>Logout</a></li>
                </ul>
            </div>
        </div>
        <nav id="left-sidebar-nav" class="sidebar-nav">
            <ul id="main-menu" class="metismenu">
                <li><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i><span>Dashboard</span></a></li>
                <li>
                    <a href="#uiElements" class="has-arrow"><i class="icon-diamond"></i><span>Appels</span></a>
                    <ul>
                        <li><a href="ui-card.html">Affichage</a></li>
                        <li><a href="ui-helper-class.html">Historique des appels</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#forms" class="has-arrow"><i class="icon-pencil"></i><span>Numéros de séries</span></a>
                    <ul>
                        <li><a href="forms-basic.html">Affichage</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#Tables" class="has-arrow"><i class="icon-tag"></i><span>Autres</span></a>
                    <ul>
                        <li><a href="table-basic.html">Affichage</a></li>
                        <li><a href="table-normal.html">Logo & couleurs</a></li>
                        <li><a href="table-jquery-datatable.html">Nombre de connexions</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#Authentication" class="has-arrow"><i class="icon-lock"></i><span>Authentication</span></a>
                    <ul>
                        <li><a href="page-login.html">Profil</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>
