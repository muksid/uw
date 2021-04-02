<aside class="main-sidebar">

    <section class="sidebar">

        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ url('/admin-lte/dist/img/user.png') }}" class="img-circle">
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->lname.' '.Auth::user()->fname }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> @lang('blade.online')</a>
            </div>
        </div>

        <ul class="sidebar-menu">
            @switch(Auth::user()->uwUsers())
                @case('super_admin')
                <li class="treeview active">
                    <a href="#"><i class="fa fa-wrench"></i> <span>Administrator</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">

                        <li>
                            <a href="{{ url('uw/filials') }}">
                                <i class="fa fa-bank"></i> <span> Bank Filiallari</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('uw/uw-users') }}">
                                <i class="fa fa-users"></i> <span>@lang('blade.sidebar_users')</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('uw-loan-types') }}">
                                <i class="fa fa-list"></i> <span>Kredit turlari</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('uw/clients') }}">
                                <i class="fa fa-plus-circle"></i> <span>Ariza yaratish</span>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="header">@lang('blade.main_nav')</li>
                <li>
                    <a href="{{ url('/uw/loan-app/2') }}">
                        <i class="fa fa-undo"></i> <span>Yangi arizalar</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/uw/loan-app/3') }}">
                        <i class="fa fa-check-circle-o"></i> <span>Tasdiqlangan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/uw/loan-app/0') }}">
                        <i class="fa fa-pencil-square-o"></i> <span>Taxrirlashda</span>
                    </a>
                </li>
                <li class="header">@lang('blade.statistics')</li>
                <li>
                    <a href="{{ url('/uw/all-clients') }}">
                        <i class="fa fa-list"></i> <span>Barcha Arizalar</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/uw/loan-app-statistics') }}">
                        <i class="fa fa-file-excel-o"></i> <span>Xisobotlar</span>
                    </a>
                </li>
                @break
                @case('risk_adminstrator')
                @case('risk_user')
                @if(Auth::user()->uwUsers() == 'risk_adminstrator')
                    <li class="treeview active">
                        <a href="#"><i class="fa fa-wrench"></i> <span>Administrator</span>
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">

                            <li>
                                <a href="{{ url('uw/filials') }}">
                                    <i class="fa fa-bank"></i> <span> Bank filialllari</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('uw/uw-users') }}">
                                    <i class="fa fa-users"></i> <span>@lang('blade.sidebar_users')</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('uw-loan-types') }}">
                                    <i class="fa fa-list"></i> <span>Kreturlari</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('uw/clients') }}">
                                    <i class="fa fa-plus-circle"></i> <span>Ariza yaratish</span>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif
                <li class="header">@lang('blade.main_nav')</li>
                <li>
                    <a href="{{ url('/uw/loan-app/2') }}">
                        <i class="fa fa-undo"></i> <span>Yangi arizalar</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/uw/loan-app/3') }}">
                        <i class="fa fa-check-circle-o"></i> <span>Tasdiqlangan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/uw/loan-app/0') }}">
                        <i class="fa fa-pencil-square-o"></i> <span>Taxrirlashda</span>
                    </a>
                </li>
                <li class="header">@lang('blade.statistics')</li>
                <li>
                    <a href="{{ url('/uw/all-clients') }}">
                        <i class="fa fa-list"></i> <span>Barcha Arizalar</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/uw/loan-app-statistics') }}">
                        <i class="fa fa-file-excel-o"></i> <span>Xisobotlar</span>
                    </a>
                </li>
                @break
                @case('credit_insp')
                <li>
                    <a href="{{ url('uw/clients') }}">
                        <i class="fa fa-plus-circle"></i> <span>Ariza yaratish</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/uw/clients/1') }}">
                        <i class="fa fa-undo"></i> <span>Yangi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/uw/clients/2') }}">
                        <i class="fa fa-send-o"></i> <span>Yuborilgan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/uw/clients/3') }}">
                        <i class="fa fa-check-circle-o"></i> <span>Tasdiqlangan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/uw/clients/0') }}">
                        <i class="fa fa-pencil-square-o"></i> <span>Taxrirlashda</span>
                    </a>
                </li>
                @break
            @endswitch
        </ul>

    </section>

</aside>
