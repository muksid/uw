<header class="main-header">
    <!-- Logo -->
    <a href="{{ url('home') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>T</b>B</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Андеррайтинг </b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ url('/admin-lte/dist/img/user.png') }}" class="user-image">
                        <span class="hidden-xs">{{ mb_substr(Auth::user()->personal->f_name ??'', 0,1).'.'.Auth::user()->personal->l_name??'' }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="{{ url('/admin-lte/dist/img/user.png') }}" class="img-circle">

                            <p>
                                {{Auth::user()->personal->f_name??''}} {{Auth::user()->personal->l_name??''}}
                                <small>Login: {{Auth::user()->username}}</small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ url('/madmin/user/profile', \Illuminate\Support\Facades\Auth::id()) }}" class="btn btn-default btn-flat">@lang('blade.profile')</a>
                            </div>
                            <div class="pull-right">
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">@lang('blade.exit')</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
