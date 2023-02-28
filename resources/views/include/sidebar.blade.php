<div class="app-sidebar colored">
    <div class="sidebar-header">
        <a class="header-brand" href="{{route('admin')}}">
            <div class="logo-img">
               @if(config('setting.logo'))
               <img src="{{ asset(config('setting.logo','img/logo_white.png'))}}" class="header-brand-img" title="{{ucwords(config('app.name'))}}" style="height:60px;"> 
               @else 
               {!! config('app.name') !!}
               @endif
            </div>
        </a>
        <div class="sidebar-action"><i class="ik ik-arrow-left-circle"></i></div>
        <button id="sidebarClose" class="nav-close"><i class="ik ik-x"></i></button>
    </div>

    @php
        $segment1 = request()->segment(2);
        $segment2 = request()->segment(3);
    @endphp
    
    <div class="sidebar-content">
        <div class="nav-container">
            <nav id="main-menu-navigation" class="navigation-main">
                <div class="nav-item {{ ($segment1 == 'admin') ? 'active' : '' }}">
                    <a href="{{route('admin')}}"><i class="ik ik-bar-chart-2"></i><span>{{ __('Dashboard')}}</span></a>
                </div>
                {{-- <div class="nav-lavel">{{ __('Layouts')}} </div>
                <div class="nav-item {{ ($segment1 == 'pos') ? 'active' : '' }}">
                    <a href="{{url('inventory')}}"><i class="ik ik-shopping-cart"></i><span>{{ __('Inventory')}}</span> <span class=" badge badge-success badge-right">{{ __('New')}}</span></a>
                </div>
                <div class="nav-item {{ ($segment1 == 'pos') ? 'active' : '' }}">
                    <a href="{{url('pos')}}"><i class="ik ik-printer"></i><span>{{ __('POS')}}</span> <span class=" badge badge-success badge-right">{{ __('New')}}</span></a>
                </div> --}}
                <div class="nav-lavel">{{ __('Request/ Lead')}} </div>
                <div class="nav-item {{ ($segment1 == 'request') ? 'active open' : '' }} has-sub">
                    <a href="#"><i class="ik ik-shopping-cart"></i><span>{{ __('Request')}}</span></a>
                    <div class="submenu-content">
                        <a href="{{url('admin/request')}}" class="menu-item {{ ($segment1 == 'request') ? 'active' : '' }}">{{ __('Request')}}</a>

                    </div>
                </div>  

                
                <div class="nav-item {{ ($segment1 == 'payment') ? 'active open' : '' }} has-sub">
                    <a href="#"><i class="ik ik-shopping-cart"></i><span>{{ __('Payment')}}</span></a>
                    <div class="submenu-content">
                        <a href="{{url('admin/payment')}}" class="menu-item {{ ($segment1 == 'payment') ? 'active' : '' }}">{{ __('Payment')}}</a>

                    </div>
                </div>  


                <div class="nav-item {{ ($segment1 == 'quotation') ? 'active open' : '' }} has-sub">
                    <a href="#"><i class="ik ik-clipboard"></i><span>{{ __('Quotation')}}</span></a>
                    <div class="submenu-content">
                        <a href="{{url('admin/quotation')}}" class="menu-item {{ ($segment1 == 'quotation') ? 'active' : '' }}">{{ __('Quotation')}}</a>
                    </div>
                </div>  
                @if(auth()->user()->hasRole('Super Admin'))  
                <div class="nav-item {{ ($segment1 == 'partner-request') ? 'active' : '' }}">
                    <a href="{{route('admin.partner.request.index')}}"><i class="fa fa-hands-helping"></i><span>{{ __('Partner Request')}}</span></a>
                </div>
                @endif
                @if(auth()->user()->hasRole('Super Admin'))  
                <div class="nav-item {{ ($segment1 == 'service') ? 'active' : '' }}">
                    <a href="{{route('admin.service.index')}}"><i class="fa fa-wrench"></i><span>{{ __('Service Request')}}</span></a>
                </div>
                @endif
                @if(auth()->user()->hasRole('Super Admin'))  
                <div class="nav-item {{ ($segment1 == 'category') ? 'active open' : '' }} has-sub">
                    <a href="#"><i class="ik ik-list"></i><span>{{ __('Categories')}}</span></a>
                    <div class="submenu-content">
                        <a href="{{url('admin/category')}}" class="menu-item {{ ($segment1 == 'category') ? 'active' : '' }}">{{ __('Category')}}</a>
                        <a href="{{url('admin/category/item')}}" class="menu-item {{ ($segment2 == 'item') ? 'active' : '' }}">{{ __('Items')}}</a>
                    </div>
                </div>  
                @endif
                @if(auth()->user()->hasRole('Super Admin'))
                <div class="nav-item {{ ($segment1 == 'users' || $segment1 == 'roles'||$segment1 == 'permission' ||$segment1 == 'user') ? 'active open' : '' }} has-sub">
                    <a href="#"><i class="ik ik-user"></i><span>{{ __('Adminstrator')}}</span></a>
                    <div class="submenu-content">
                        <!-- only those have manage_user permission will get access -->
                        @can('manage_user')
                        <a href="{{url('admin/users')}}" class="menu-item {{ ($segment1 == 'users') ? 'active' : '' }}">{{ __('Users')}}</a>
                        <a href="{{url('admin/user/create')}}" class="menu-item {{ ($segment1 == 'user' && $segment2 == 'create') ? 'active' : '' }}">{{ __('Add User')}}</a>
                         @endcan
                         <!-- only those have manage_role permission will get access -->
                        @can('manage_role') 
                        <a href="{{url('admin/roles')}}" class="menu-item {{ ($segment1 == 'roles') ? 'active' : '' }}">{{ __('Roles')}}</a>
                        @endcan 
                        <!-- only those have manage_permission permission will get access -->
                        @can('manage_permission') 
                        <a href="{{url('admin/permission')}}" class="menu-item {{ ($segment1 == 'permission') ? 'active' : '' }}">{{ __('Permission')}}</a>
                        @endcan
                    </div>
                </div>
                @endif
                @if(auth()->user()->hasRole('Super Admin'))
                <div class="d-none nav-item {{ ($segment1 == 'page' || $segment1 == 'page'||$segment1 == 'page' ||$segment1 == 'page') ? 'active open' : '' }} has-sub">
                    <a href="#"><i class="ik ik-file-text"></i><span>Pages</span></a>
                    <div class="submenu-content">
                        <a href="{{url('admin/page')}}" class="menu-item {{ ($segment1 == 'page') ? 'active' : '' }}">All Pages</a>
                        <a href="{{url('admin/page/create')}}" class="menu-item ">Add New</a>
                    </div>
                </div>
                <div class="nav-item {{ ($segment1 == 'banner') ? 'active' : '' }}">
                    <a href="{{route('admin.banner.index')}}"><i class="ik ik-image"></i><span>{{ __('Banner')}}</span></a>
                </div>
                @endif
                @can('manage_setting')    
                <div class="nav-lavel">{{ __('Setting')}} </div>
                <div class="nav-item {{ ($segment1 == 'setting') ? 'active open' : '' }} has-sub">
                    <a href="#"><i class="ik ik-settings"></i><span>{{ __('Setting')}}</span></a>
                    <div class="submenu-content">
                        <a href="{{url('admin/setting/country')}}" class="menu-item {{ ($segment2 == 'country') ? 'active' : '' }}">{{ __('Country')}}</a>
                        <a href="{{url('admin/setting/state')}}" class="menu-item {{ ($segment2 == 'state') ? 'active' : '' }}">{{ __('State')}}</a>
                        <!-- <a href="{{url('admin/setting/mail')}}" class="menu-item {{ ($segment1 == 'form-advance') ? 'active' : '' }}">{{ __('Mail')}}</a> -->
                        <a href="{{url('admin/setting/request-status')}}" class="menu-item {{ ($segment2 == 'request-status') ? 'active' : '' }}">{{ __('Request Status')}}</a>
                        <a href="{{url('admin/setting/shifting-type')}}" class="menu-item {{ ($segment2 == 'shifting-type') ? 'active' : '' }}">{{ __('Shifting Type')}}</a>
                    </div>
                </div>
                @endcan    
        </div>
    </div>
</div>