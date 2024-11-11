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
            @if (Auth::user()->role == 'superadmin')
            <li class="menu-header">User Management</li>
            <li class="{{ Request::is('user_management') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('user_management') }}"><i class="fas fa-user-shield"></i> <span>User Management</span></a>
            </li>
            @endif
            
            <li class="menu-header">Vendor</li>
            <li class="{{ Request::is('distributor') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('distributor') }}"><i class="fas fa-warehouse"></i> <span>Vendor</span></a>
            </li>
            <li class="{{ Request::is('purchase_orders') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('purchase_orders') }}"><i class="fas fa-store"></i> <span>Purchase Order</span></a>
            </li>
            <li class="{{ Request::is('purchase_orders_details') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('purchase_orders_details') }}"><i class="fas fa-clipboard"></i> <span>Purchase Order Details</span></a>
            </li>

            <li class="menu-header">Stock</li>
            <li class="{{ Request::is('stock') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('stock') }}"><i class="fas fa-dolly"></i> <span>Stock</span></a>
            </li>

            <li class="menu-header">Data Master</li>
            <li class="{{ Request::is('master_motor') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('master_motor') }}"><i class="fas fa-motorcycle"></i> <span>Master Motor</span></a>
            </li>
            <li class="{{ Request::is('master_spare_parts') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('master_spare_parts') }}"><i class="fas fa-toolbox"></i> <span>Master Spare Parts</span></a>
            </li>
            <li class="{{ Request::is('master_warna') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('master_warna') }}"><i class="fas fa-palette"></i> <span>Master Warna</span></a>
            </li>

             <!-- profile ganti password -->
             <li class="menu-header">Profile</li>
             <li class="{{ Request::is('profile/edit') ? 'active' : '' }}">
                 <a class="nav-link" href="{{ url('profile/edit') }}"><i class="far fa-user"></i> <span>Profile</span></a>
             </li>
             <li class="{{ Request::is('profile/change-password') ? 'active' : '' }}">
                 <a class="nav-link" href="{{ url('profile/change-password') }}"><i class="fas fa-key"></i> <span>Ganti Password</span></a>
             </li>
        </ul>
    </aside>
</div>
@endauth
