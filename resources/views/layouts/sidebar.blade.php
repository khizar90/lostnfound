<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="" class="app-brand-link">
            
            <span class="app-brand-text demo menu-text fw-bold"><img src="/assets/img/App logo.png" alt="" height="50px" width="100%"></span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
      



        <!-- Apps & Pages -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">FEED</span>
        </li>
        <li class="menu-item {{ Request::url() == route('dashboard-users') ? 'active' : '' }} || {{ Request::url() == route('dashboard-home') ? 'active' : '' }}">
            <a href="{{ route('dashboard-users') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-user"></i>
                <div data-i18n="User">User</div>
            </a>
        </li>
        
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Pages</span>
        </li>

        
        <li class="menu-item {{ Request::url() == route('dashboard-posts','lost') ? 'open active' : '' }} || {{ Request::url() == route('dashboard-posts','found') ? 'open active' : '' }}  || {{ Request::url() == route('dashboard-repoted-posts') ? 'open active' : '' }}"> 
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-files"></i>
                <div data-i18n="Posts">Posts</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::url() == route('dashboard-posts','lost') ? ' active' : '' }}">
                    <a href="{{ route('dashboard-posts' ,'lost') }}" class="menu-link">
                        <div>Lost</div>
                    </a>
                </li> 
                
                <li class="menu-item {{ Request::url() == route('dashboard-posts','found') ? ' active' : '' }}">
                    <a href="{{ route('dashboard-posts' ,'found') }}" class="menu-link">
                        <div>Found</div>
                    </a>
                </li> 

                <li class="menu-item {{ Request::url() == route('dashboard-repoted-posts') ? ' active' : '' }}">
                    <a href="{{ route('dashboard-repoted-posts') }}" class="menu-link">
                        <div>Repoted Posts</div>
                    </a>
                </li> 

                {{-- <li class="menu-item {{ Request::url() == route('dashboard-hadith-add') ? 'active' : '' }}">
                    <a href="{{ route('dashboard-hadith-add') }}" class="menu-link">
                        <div>Add Hadith</div>
                    </a>
                </li> --}}
            

                {{-- <li class="menu-item {{ Request::url() == route('dashboard-hadith-subcategory') ? 'active' : '' }}">
                    <a href="{{ route('dashboard-hadith-subcategory') }}" class="menu-link">
                        <div>Books Category</div>
                    </a>
                </li>   --}}


                
                
            </ul>
        </li>

       


       
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">OTHERS</span>
        </li>


        <li class="menu-item {{ Request::url() == route('dashboard-send-notification') ? 'active' : '' }} ">
            <a href="{{ route('dashboard-send-notification') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-bell"></i>
                <div data-i18n="Send Notification">Send Notification</div>
            </a>
        </li>
      

       
    </ul>
</aside>
