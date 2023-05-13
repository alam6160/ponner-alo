<div class="sidebar-menu">
    <ul>
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="fa fa-dashcube"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.subscriptionlog') }}">
                <i class="fa fa-dashcube"></i>
                <span>Subscription History</span>
            </a>
        </li>
        <li class="sidebar-dropdown">
            <a href="#">
                <i class="fa fa-american-sign-language-interpreting fa-lg"></i>
                <span>Site Setting</span>
            </a>
            <div class="sidebar-submenu">
                <ul>
                    <li><a href="{{ route('admin.home.sitesetting') }}">Setting</a></li>

                    <!-- <li><a href="{{ route('admin.home.blog.category.create') }}">Blog Category</a></li>
                    <li><a href="{{ route('admin.home.blog.create') }}">Add Blogs</a></li>
                    <li><a href="{{ route('admin.home.blog.index') }}">All Blogs</a></li>
                    <li><a href="{{ route('admin.home.page.create') }}">Add Pages</a></li> -->
                    <li><a href="{{ route('admin.home.page.index') }}">All Pages</a></li>
                    <li><a href="{{ route('admin.home.slider.create') }}">Manage Slider</a></li>
                </ul>
            </div>
        </li>
        <li>
            <a href="{{ route('admin.payout.index') }}">
                <i class="fa fa-dashcube"></i>
                <span>Withdrawl Request</span>
            </a>
        </li>
        {{-- <li class="sidebar-dropdown">
            <a href="#">
                <i class="fa fa-wpforms fa-lg"></i>
                <span>Application</span>
            </a>
            <div class="sidebar-submenu">
                <ul>
                    <li><a href="{{ route('admin.application.index') }}">All Applications</a></li>
                    <li><a href="{{ route('admin.application.payment.index') }}">Request Payments</a></li>
                </ul>
            </div>
        </li> --}}
        <!-- <li class="sidebar-dropdown">
            <a href="#">
                <i class="fa fa-star fa-lg"></i>
                <span>Manage Staffs</span>
            </a>
            <div class="sidebar-submenu">
                <ul>
                    <li><a href="{{ route('admin.manage-user.staff.create') }}">Add Staff</a></li>
                    <li><a href="{{ route('admin.manage-user.staff.index') }}">All Staffs</a></li>
                </ul>
            </div>
        </li> -->
        {{-- <li class="sidebar-dropdown">
            <a href="#">
                <i class="fa fa-certificate fa-lg"></i>
                <span>Manage State Head</span>
            </a>
            <div class="sidebar-submenu">
                <ul>
                    <li><a href="{{ route('admin.manage-user.statehead.create') }}">Add New State Head</a></li>
                    <li><a href="{{ route('admin.manage-user.statehead.index') }}">All State Head</a></li>
                </ul>
            </div>
        </li> --}}
        <li class="sidebar-dropdown">
            <a href="javascript:void(0)">
                <i class="fa fa-address-card-o fa-lg"></i>
                <span>Manage Vendor</span>
            </a>
            <div class="sidebar-submenu">
                <ul>
                    
                    <li><a href="{{ route('admin.manage-user.agent.create') }}">Add New Vendor</a></li>
                    <li><a href="{{ route('admin.manage-user.agent.index') }}">All Vendor</a></li>
                    {{-- <li><a href="#"> New Vendors</a></li> --}}
                    <li><a href="{{ route('admin.manage-user.agent.subscription') }}">Membership Vendors</a></li>
                    <li><a href="{{ route('admin.manage-user.agent.regular') }}">Regular Vendors</a></li>
                </ul>
            </div>
        </li>

        <!-- <li class="sidebar-dropdown">
            <a href="#">
                <i class="fa fa-motorcycle fa-lg"></i>
                <span>Manage Delivery Boys</span>
            </a>
            <div class="sidebar-submenu">
                <ul>
                    <li><a href="{{ route('admin.manage-user.deliveryboy.create') }}">Add New Delivery Boy</a></li>
                    <li><a href="{{ route('admin.manage-user.deliveryboy.index') }}">All Delivery Boys</a></li>
                </ul>
            </div>
        </li> -->

        {{-- <li class="sidebar-dropdown">
            <a href="javascript:void(0)">
                <i class="fa fa-stethoscope fa-lg"></i>
                <span>Manage Retailers</span>
            </a>
            <div class="sidebar-submenu">
                <ul>
                    <li><a href="{{ route('admin.manage-user.retailer.create') }}">Add New Retailer</a></li>
                    <li><a href="{{ route('admin.manage-user.retailer.index') }}">All Retailers</a></li>
                </ul>
            </div>
        </li> --}}

        <li class="sidebar-dropdown">
            <a href="#">
                <i class="fa fa-users fa-lg"></i>
                <span>Manage Customers</span>
            </a>
            <div class="sidebar-submenu">
                <ul>
                    <li><a href="{{ route('admin.manage-user.customer.create') }}">Add New Customer</a></li>
                    <li><a href="{{ route('admin.manage-user.customer.index') }}">All Customers</a></li>
                </ul>
            </div>
        </li>

        <li class="sidebar-dropdown">
            <a href="#">
                <i class="fa fa-heart fa-lg"></i>
                <span>Manage Products</span>
            </a>
            <div class="sidebar-submenu">
                <ul>
                    <!-- <li><a href="{{ route('admin.product.filter.create') }}">Product Filter</a></li> -->
                    <li><a href="{{ route('admin.product.category.create') }}">Product Categories</a></li>
                    <!-- <li><a href="{{ route('admin.product.addon.create') }}">Product Addon</a></li> -->
                    <!-- <li><a href="{{ route('admin.product.coupon.create') }}">Coupon</a></li> -->
                    <li><a href="{{ route('admin.product.create') }}">Add New Product</a></li>
                    {{-- <li><a href="{{ route('admin.product.index') }}">All Products</a></li> --}}
                    <li><a href="{{ url('admin/product/log') }}/all">All Products</a></li>
                    <li><a href="{{ url('admin/product/log') }}/pending">Pending Products</a></li>
                    <li><a href="{{ url('admin/product/log') }}/rejected">Rejected Products</a></li>
                </ul>
            </div>
        </li>

        <li class="sidebar-dropdown">
            <a href="#">
                <i class="fa fa-shopping-cart fa-lg"></i>
                <span>Manage Orders</span>
            </a>
            <div class="sidebar-submenu">
                <ul>
                    {{-- <li><a href="#">Manage RX Orders</a></li>
                    <li><a href="#">New Orders</a></li> --}}
                    <li><a href="{{ route('admin.manageorder.index') }}">All Orders</a></li>
                    <li><a href="{{ route('admin.manageorder.recieved') }}">Recieved Orders</a></li>
                    <li><a href="{{ route('admin.manageorder.processed') }}">Processed Orders</a></li>
                    <li><a href="{{ route('admin.manageorder.shipped') }}">Shipped Orders</a></li>
                    <li><a href="{{ route('admin.manageorder.delivered') }}">Delivered Orders</a></li>
                    <li><a href="{{ route('admin.manageorder.cancel') }}">Cancel Orders</a></li>
                    <li><a href="{{ route('admin.manageorder.return') }}">Returned Orders</a></li>
                </ul>
            </div>
        </li>

        <!-- <li class="sidebar-dropdown">
            <a href="#">
                <i class="fa fa-credit-card" aria-hidden="true"></i>
                <span>Invoice/Payment History</span>
            </a>
            <div class="sidebar-submenu">
                <ul>
                    {{-- <li><a href="#">Add New Invoice</a></li> --}}
                    <li><a href="#">Recent Payment</a></li>
                    <li><a href="#">Upcoming Payment</a></li>
                    <li><a href="#">Cancelled Subscriptions</a></li>
                </ul>
            </div>
        </li> -->

        <li class="sidebar-dropdown">
            <a href="#">
                <i class="fa fa-cog"></i>
                <span>Profile Settings</span>
            </a>
            <div class="sidebar-submenu">
                <ul>
                    <li><a href="{{ route('admin.profile.edit') }}">Profile</a></li>
                    <li><a href="{{ route('admin.profile.change-password') }}">Change Password</a></li>
                </ul>
            </div>
        </li>

        <li>
            <a href="{{ route('admin.logout') }}">
                <i class="fa fa-sign-out"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>