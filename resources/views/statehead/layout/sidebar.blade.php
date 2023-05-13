<div class="sidebar-menu">
    <ul>
        <li>
            <a href="{{ route('statehead.dashboard') }}">
                <i class="fa fa-dashcube"></i>
                <span>Dashboard</span>
            </a>
        </li>

        @if ( auth()->user()->status == '2' )
            
        <li class="sidebar-dropdown">
            <a href="javascript:void(0)">
                <i class="fa fa-address-card-o fa-lg"></i>
                <span>Manage HSP</span>
            </a>
            <div class="sidebar-submenu">
                <ul>
                    <li><a href="{{ route('statehead.manage-user.agent.create') }}">Add New HSP</a></li>
                    <li><a href="{{ route('statehead.manage-user.agent.index') }}">All HSP</a></li>
                </ul>
            </div>
        </li>

        <li class="sidebar-dropdown">
            <a href="javscript:void(0)">
                <i class="fa fa-motorcycle fa-lg"></i>
                <span>Manage Delivery Boys</span>
            </a>
            <div class="sidebar-submenu">
                <ul>
                    <li><a href="{{ route('statehead.manage-user.deliveryboy.create') }}">Add New Delivery Boy</a></li>
                    <li><a href="{{ route('statehead.manage-user.deliveryboy.index') }}">All Delivery Boys</a></li>
                </ul>
            </div>
        </li>

        <li class="sidebar-dropdown">
            <a href="javascript:void(0)">
                <i class="fa fa-stethoscope fa-lg"></i>
                <span>Manage Retailers</span>
            </a>
            <div class="sidebar-submenu">
                <ul>
                    <li><a href="{{ route('statehead.manage-user.retailer.create') }}">Add New Retailer</a></li>
                    <li><a href="{{ route('statehead.manage-user.retailer.index') }}">All Retailers</a></li>
                </ul>
            </div>
        </li>
        
        @endif
        
        
        
        <li>
            <a href="{{ route('statehead.logout') }}">
                <i class="fa fa-sign-out"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>