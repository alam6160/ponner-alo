<div class="sidebar-menu">
    <ul>
        <li>
            <a href="{{ route('agent.dashboard') }}">
                <i class="fa fa-dashcube"></i>
                <span>Dashboard</span>
            </a>
        </li>

        @if (Auth::user()->vendor_type == '1')
            <li>
                <a href="{{ route('agent.profile.bank') }}">
                    <i class="fa fa-dashcube"></i>
                    <span>Bank Info</span>
                </a>
            </li>
            <li>
                <a href="{{ route('agent.payout.request') }}">
                    <i class="fa fa-dashcube"></i>
                    <span>Withdrawl</span>
                </a>
            </li>
            <li>
                <a href="{{ route('agent.payout.creaditlog') }}">
                    <i class="fa fa-dashcube"></i>
                    <span>Wallet Creadit Log</span>
                </a>
            </li>
            <li class="sidebar-dropdown">
                <a href="javscript:void(0)">
                    <i class="fa fa-heart fa-lg"></i>
                    <span>Manage Products</span>
                </a>
                <div class="sidebar-submenu">
                    <ul>
                        {{-- <li><a href="{{ route('agent.product.addon.create') }}">Product Addon</a></li> --}}
                        <li><a href="{{ route('agent.product.create') }}">Add New Product</a></li>
                        <li><a href="{{ route('agent.product.index') }}">All Products</a></li>
                        <li><a href="{{ url('agent/product/type') }}/pending">Pending Products</a></li>
                        <li><a href="{{ url('agent/product/type') }}/rejected">Rejected Products</a></li>
                    </ul>
                </div>
            </li>

            <li class="sidebar-dropdown">
                <a href="#">
                    <i class="fa fa-address-card-o fa-lg"></i>
                    <span>My Orders</span>
                </a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="{{ route('agent.manageorder.recieved') }}">Recieved Orders</a></li>
                      	<li><a href="{{ route('agent.manageorder.processed') }}">Process Orders</a></li>
                      	<li><a href="{{ route('agent.manageorder.shipped') }}">Shipped Orders</a></li>
                      	<li><a href="{{ route('agent.manageorder.delivered') }}">Delivered Orders</a></li>
                      	<li><a href="{{ route('agent.manageorder.cancel') }}">Cancel Orders</a></li>
                      	<li><a href="{{ route('agent.manageorder.return') }}">Return Request</a></li>
                    </ul>
                </div>
            </li>
        @else
            <li>
                <a href="{{ route('agent.subscriptionlog') }}">
                    <i class="fa fa-dashcube"></i>
                    <span>Subscription History</span>
                </a>
            </li>
            @php
                $subscriptionLog = \App\Models\SubscriptionLog::where([
                    ['expaire_date', '>=', date('Y-m-d')],
                    ['agent_id','=', Auth::id()]
                ])->orderBy('id', 'desc')->first();
            @endphp
            @if (!blank($subscriptionLog))
                <li>
                    <a href="{{ route('agent.profile.bank') }}">
                        <i class="fa fa-dashcube"></i>
                        <span>Bank Info</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('agent.payout.request') }}">
                        <i class="fa fa-dashcube"></i>
                        <span>Withdrawl</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('agent.payout.creaditlog') }}">
                        <i class="fa fa-dashcube"></i>
                        <span>Wallet Creadit Log</span>
                    </a>
                </li>

                <li class="sidebar-dropdown">
                    <a href="javscript:void(0)">
                        <i class="fa fa-heart fa-lg"></i>
                        <span>Manage Products</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            {{-- <li><a href="{{ route('agent.product.addon.create') }}">Product Addon</a></li> --}}
                            <li><a href="{{ route('agent.product.create') }}">Add New Product</a></li>
                            <li><a href="{{ route('agent.product.index') }}">All Products</a></li>
                            <li><a href="{{ url('agent/product/type') }}/pending">Pending Products</a></li>
                            <li><a href="{{ url('agent/product/type') }}/rejected">Rejected Products</a></li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-dropdown">
                    <a href="#">
                        <i class="fa fa-address-card-o fa-lg"></i>
                        <span>My Orders</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li><a href="{{ route('agent.manageorder.recieved') }}">Recieved Orders</a></li>
                            <li><a href="{{ route('agent.manageorder.processed') }}">Process Orders</a></li>
                            <li><a href="{{ route('agent.manageorder.shipped') }}">Shipped Orders</a></li>
                            <li><a href="{{ route('agent.manageorder.delivered') }}">Delivered Orders</a></li>
                            <li><a href="{{ route('agent.manageorder.cancel') }}">Cancel Orders</a></li>
                            <li><a href="{{ route('agent.manageorder.return') }}">Return Request</a></li>
                        </ul>
                    </div>
                </li>
            @endif
        @endif

    
        @if ( auth()->user()->status == '2' )
        {{-- <li class="sidebar-dropdown">
            <a href="javscript:void(0)">
                <i class="fa fa-motorcycle fa-lg"></i>
                <span>Manage Delivery Boys</span>
            </a>
            <div class="sidebar-submenu">
                <ul>
                    <li><a href="{{ route('agent.manage-user.deliveryboy.create') }}">Add New Delivery Boy</a></li>
                    <li><a href="{{ route('agent.manage-user.deliveryboy.index') }}">All Delivery Boys</a></li>
                </ul>
            </div>
        </li>
        <li class="sidebar-dropdown">
            <a href="#">
                <i class="fa fa-stethoscope fa-lg"></i>
                <span>Manage Retailers</span>
            </a>
            <div class="sidebar-submenu">
                <ul>
                    <li><a href="{{ route('agent.manage-user.retailer.create') }}">Add New Retailer</a></li>
                    <li><a href="{{ route('agent.manage-user.retailer.index') }}">All Retailers</a></li>
                </ul>
            </div>
        </li> --}}
        @endif

        {{-- <li class="sidebar-dropdown">
            <a href="#">
                <i class="fa fa-address-card-o fa-lg"></i>
                <span>Manage HSP</span>
            </a>
            <div class="sidebar-submenu">
                <ul>
                    <li><a href="#">Add New</a></li>
                    <li><a href="#">All</a></li>
                </ul>
            </div>
        </li> --}}
        
        <li>
            <a href="{{ route('agent.logout') }}">
                <i class="fa fa-sign-out"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>