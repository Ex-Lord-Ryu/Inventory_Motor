@auth
    <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
            <div class="sidebar-brand">
                <a href="">Tunas Jaya</a>
            </div>
            <div class="sidebar-brand sidebar-brand-sm">
                <a href="">TJ</a>
            </div>
            <ul class="sidebar-menu">
                <li class="menu-header">Dashboard</li>
                <li class="{{ Request::is('home') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('home') }}"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                </li>

                @switch(Auth::user()->role)
                    @case('superadmin')
                        <li class="{{ Request::is('user_management') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ url('user_management') }}">
                                <i class="fas fa-user-shield"></i><span>User Management</span>
                            </a>
                        </li>
                    @endswitch

                @if(in_array(Auth::user()->role, ['superadmin', 'admin', 'operasional', 'finance']))
                    <li class="dropdown {{ Request::is('distributor*', 'purchase_orders*', 'purchase_orders_details*') ? 'active' : '' }}">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                            <i class="fas fa-warehouse"></i><span>Vendor Management</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="{{ Request::is('distributor') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ url('distributor') }}">Vendor</a>
                            </li>
                            <li class="{{ Request::is('purchase_orders') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ url('purchase_orders') }}">Purchase Order</a>
                            </li>
                            @if(in_array(Auth::user()->role, ['superadmin', 'admin']))
                                <li class="{{ Request::is('purchase_orders_details') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ url('purchase_orders_details') }}">Purchase Order Details</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(in_array(Auth::user()->role, ['superadmin', 'admin', 'operasional']))
                    <li class="dropdown {{ Request::is('stock*') ? 'active' : '' }}">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                            <i class="fas fa-dolly"></i><span>Stock Management</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="{{ Request::is('stock') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ url('stock') }}">Stock</a>
                            </li>
                            <li class="{{ Request::is('stock/sold-items') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ url('stock/sold-items') }}">Sold Items</a>
                            </li>
                            <li class="{{ Request::is('stock/all') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ url('stock/all') }}">All Stock</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if(in_array(Auth::user()->role, ['superadmin', 'admin', 'operasional', 'sales']))
                    <li class="dropdown {{ Request::is('order_motor*', 'order_spare_parts*') ? 'active' : '' }}">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                            <i class="fas fa-shopping-cart"></i><span>Orders</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="{{ Request::is('order_motor') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ url('order_motor') }}">Order Motor</a>
                            </li>
                            <li class="{{ Request::is('order_spare_parts') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ url('order_spare_parts') }}">Order Spare Parts</a>
                            </li>
                        </ul>
                    </li>
                @endif

                <li class="{{ Request::is('sales_report') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('sales_report') }}">
                        <i class="fas fa-chart-bar"></i><span>Laporan Penjualan</span>
                    </a>
                </li>

                @if(in_array(Auth::user()->role, ['superadmin', 'admin', 'operasional',]))
                    <li class="dropdown {{ Request::is('master_motor*', 'master_spare_parts*', 'master_warna*') ? 'active' : '' }}">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                            <i class="fas fa-database"></i><span>Master Data</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="{{ Request::is('master_motor') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ url('master_motor') }}">Master Motor</a>
                            </li>
                            <li class="{{ Request::is('master_spare_parts') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ url('master_spare_parts') }}">Master Spare Parts</a>
                            </li>
                            <li class="{{ Request::is('master_warna') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ url('master_warna') }}">Master Warna</a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </aside>
    </div>
@endauth
