<aside class="main-sidebar">

    <section class="sidebar">

        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ url('/admin-lte/dist/img/user.png') }}" class="img-circle" alt="{{Auth::user()->fname}}">
            </div>
            <div class="pull-left info">
                <p>{{Auth::user()->personal->l_name}} {{Auth::user()->personal->f_name}}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> @lang('blade.online')</a>
            </div>
        </div>



        <?php
            $menus = Auth::user()->getMenus();

            $categories = Auth::user()->getCategory();

        ?>
        <ul class="sidebar-menu">
            @foreach($categories as $key => $value)

                <li class="treeview active">

                    <a href="{{ url($value->menuRole->url_path??'')}}">

                        <i class="{{ $value->menuRole->icon_code??'' }}"></i>
                        <span>@lang('blade.'.$value->menuRole->lang_code)</span>
                        <span class="pull-right-container"></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>

                    </a>

                    <ul class="treeview-menu">
                        @foreach($menus as $k => $val)
                            @if($val->parent_id === $value->menu_id)
                            <li class="treeview">
                                <a href="{{$val->menuRole->url_path}}">
                                    <i class="{{$val->menuRole->icon_code}}"></i>
                                    <span>{{$val->menuRole->title}}</span>
                                    <span class="pull-right-container"></span>
                                </a>
                            </li>

                            @endif
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>

    </section>

</aside>
