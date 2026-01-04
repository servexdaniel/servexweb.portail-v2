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
                    <li><a href="{{ route('contact.profile') }}"><i class="icon-user"></i>My Profile</a></li>
                    <li><a href="app-inbox.html"><i class="icon-envelope-open"></i>Messages</a></li>
                    <li><a href="javascript:void(0);"><i class="icon-settings"></i>Settings</a></li>
                    <li class="divider"></li>
                    <li><a href="page-login.html"><i class="icon-power"></i>Logout</a></li>
                </ul>
            </div>
        </div>
        <nav id="left-sidebar-nav" class="sidebar-nav">
            <ul id="main-menu" class="metismenu">
                <li><a href="{{ route('contact.dashboard') }}"><i class="icon-home"></i><span>Dashboard</span></a></li>
                <li><a href="{{ route('contact.calls') }}"><i class="icon-envelope"></i><span>Calls</span></a></li>
                <li><a href="app-chat.html"><i class="icon-bubbles"></i><span>Chat</span></a></li>
                <li>
                    <a href="#uiElements" class="has-arrow"><i class="icon-diamond"></i><span>UI
                            Elements</span></a>
                    <ul>
                        <li><a href="ui-card.html">Card Layout</a></li>
                        <li><a href="ui-helper-class.html">Helper Classes</a></li>
                        <li><a href="ui-bootstrap.html">Bootstrap UI</a></li>
                        <li><a href="ui-typography.html">Typography</a></li>
                        <li><a href="ui-tabs.html">Tabs</a></li>
                        <li><a href="ui-buttons.html">Buttons</a></li>
                        <li><a href="ui-icons.html">Icons</a></li>
                        <li><a href="ui-notifications.html">Notifications</a></li>
                        <li><a href="ui-colors.html">Colors</a></li>
                        <li><a href="ui-dialogs.html">Dialogs</a></li>
                        <li><a href="ui-list-group.html">List Group</a></li>
                        <li><a href="ui-media-object.html">Media Object</a></li>
                        <li><a href="ui-modals.html">Modals</a></li>
                        <li><a href="ui-nestable.html">Nestable</a></li>
                        <li><a href="ui-progressbars.html">Progress Bars</a></li>
                        <li><a href="ui-range-sliders.html">Range Sliders</a></li>
                        <li><a href="ui-treeview.html">Treeview</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#forms" class="has-arrow"><i class="icon-pencil"></i><span>Forms</span></a>
                    <ul>
                        <li><a href="forms-basic.html">Basic Elements</a></li>
                        <li><a href="forms-advanced.html">Advanced Elements</a></li>
                        <li><a href="forms-validation.html">Form Validation</a></li>
                        <li><a href="forms-wizard.html">Form Wizard</a></li>
                        <li><a href="forms-dragdropupload.html">Drag &amp; Drop Upload</a></li>
                        <li><a href="forms-cropping.html">Image Cropping</a></li>
                        <li><a href="forms-summernote.html">Summernote</a></li>
                        <li><a href="forms-editors.html">CKEditor</a></li>
                        <li><a href="forms-markdown.html">Markdown</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#Tables" class="has-arrow"><i class="icon-tag"></i><span>Tables</span></a>
                    <ul>
                        <li><a href="table-basic.html">Tables Example</a></li>
                        <li><a href="table-normal.html">Normal Tables</a></li>
                        <li><a href="table-jquery-datatable.html">Jquery Datatables</a></li>
                        <li><a href="table-editable.html">Editable Tables</a></li>
                        <li><a href="table-color.html">Tables Color</a></li>
                        <li><a href="table-filter.html">Table Filter</a></li>
                        <li><a href="table-dragger.html">Table dragger</a></li>
                    </ul>
                </li>
                <li><a href="app-taskboard.html"><i class="icon-list"></i><span>Taskboard</span></a></li>
                <li><a href="app-calendar.html"><i class="icon-calendar"></i><span>Calendar</span></a></li>
                <li><a href="app-contact.html"><i class="icon-book-open"></i><span>Contact</span></a></li>
                <li>
                    <a href="#Blog" class="has-arrow"><i class="icon-globe"></i><span>Blog</span></a>
                    <ul>
                        <li><a href="blog-dashboard.html">Dashboard</a></li>
                        <li><a href="blog-post.html">New Post</a></li>
                        <li><a href="blog-list.html">Blog List</a></li>
                        <li><a href="blog-details.html">Blog Detail</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#charts" class="has-arrow"><i class="icon-bar-chart"></i><span>Charts</span></a>
                    <ul>
                        <li><a href="chart-morris.html">Morris</a></li>
                        <li><a href="chart-flot.html">Flot</a></li>
                        <li><a href="chart-chartjs.html">ChartJS</a></li>
                        <li><a href="chart-c3.html">C3 Charts</a></li>
                        <li><a href="chart-jquery-knob.html">Jquery Knob</a></li>
                        <li><a href="chart-sparkline.html">Sparkline Chart</a></li>
                        <li><a href="chart-peity.html">Peity</a></li>
                        <li><a href="chart-gauges.html">Gauges</a></li>
                        <li><a href="chart-e.html">E Chart</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#Widgets" class="has-arrow"><i class="icon-puzzle"></i><span>Widgets</span></a>
                    <ul>
                        <li><a href="widgets-statistics.html">Statistics</a></li>
                        <li><a href="widgets-data.html">Data</a></li>
                        <li><a href="widgets-chart.html">Chart</a></li>
                        <li><a href="widgets-weather.html">Weather</a></li>
                        <li><a href="widgets-social.html">Social</a></li>
                        <li><a href="widgets-blog.html">Blog</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#Authentication" class="has-arrow"><i
                            class="icon-lock"></i><span>Authentication</span></a>
                    <ul>
                        <li><a href="page-login.html">Login</a></li>
                        <li><a href="page-register.html">Register</a></li>
                        <li><a href="page-lockscreen.html">Lockscreen</a></li>
                        <li><a href="page-forgot-password.html">Forgot Password</a></li>
                        <li><a href="page-404.html">Page 404</a></li>
                        <li><a href="page-403.html">Page 403</a></li>
                        <li><a href="page-500.html">Page 500</a></li>
                        <li><a href="page-503.html">Page 503</a></li>
                    </ul>
                </li>
                <li class="active">
                    <a href="#Pages" class="has-arrow"><i class="icon-docs"></i><span>Pages</span></a>
                    <ul>
                        <li class="active"><a href="page-blank.html">Blank Page</a></li>
                        <li><a href="page-search-results.html">Search Results</a></li>
                        <li><a href="page-profile.html">Profile </a></li>
                        <li><a href="page-invoices.html">Invoices </a></li>
                        <li><a href="page-gallery.html">Image Gallery</a></li>
                        <li><a href="page-gallery2.html">Image Gallery </a></li>
                        <li><a href="page-timeline.html">Timeline</a></li>
                        <li><a href="page-timeline-h.html">Horizontal Timeline</a></li>
                        <li><a href="page-pricing.html">Pricing</a></li>
                        <li><a href="page-maintenance.html">Maintenance</a></li>
                        <li><a href="page-testimonials.html">Testimonials</a></li>
                        <li><a href="page-faq.html">FAQ</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#Maps" class="has-arrow"><i class="icon-map"></i><span>Maps</span></a>
                    <ul>
                        <li><a href="map-google.html">Google Map</a></li>
                        <li><a href="map-jvectormap.html">jVector Map</a></li>
                        <li><a href="map-yandex.html">Yandex Map</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>
