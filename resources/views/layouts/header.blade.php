<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 06.01.2020
 * Time: 17:08
 */
?>

<!-- Main Header -->
<header class="main-header">
    <!-- Logo -->
    <a href="{{ url('uw/home') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>T</b>B</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Andirayting </b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li><a><i class="fa fa-language"></i></a></li>
                <li><a href="{{ url('locale/ru') }}" >@lang('blade.lang_ru')</a></li>
                <li><a href="{{ url('locale/uz') }}" >@lang('blade.lang_uz')</a></li>
                <li><a href="{{ url('locale/uzl') }}" >@lang('blade.lang_uzl')</a></li>

                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success"></span>
                    </a>
                </li>
                <!-- Notifications: style can be found in dropdown.less -->
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-clock-o"></i>
                        <span class="label label-danger"></span>
                    </a>
                </li>
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="/admin-lte/dist/img/user.png" class="user-image" alt="User Image">
                        <span class="hidden-xs">{{ mb_substr(Auth::user()->fname ??'', 0,1).'.'.Auth::user()->lname }}</span>

                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="/admin-lte/dist/img/user.png" class="img-circle" alt="User Image">

                            <p>
                                {{Auth::user()->fname}} {{Auth::user()->lname}}
                                <small>Login: {{Auth::user()->username}}</small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ route('users.edit', Auth::id()) }}" class="btn btn-default btn-flat">@lang('blade.profile')</a>
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
