@php
$routeList = Route::current()->uri;
$route = explode('/', $routeList);
@endphp
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">Stisla</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">St</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="dropdown {{Request::is('admin') ? 'active' : null}}">
                <a href="#" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-header">Manajemen Data</li>
            <li class="dropdown {{ in_array('workers', $route) || in_array('students', $route) || in_array('class', $route) || in_array('spps', $route) ? 'active' : null}}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-database"></i><span>Manajemen Data</span></a>
                <ul class="dropdown-menu has-dropdown">
                    <li class="{{ in_array('workers', $route) ? 'active' : null}}"><a href="{{route('a.workers.index')}}" class="nav-link"><i class="fas fa-users"></i><span>Petugas</span></a></li>
                    <li class="{{ in_array('students', $route) ? 'active' : null}}"><a href="{{route('a.students.index')}}" class="nav-link"><i class="fas fa-graduation-cap"></i><span>Siswa</span></a></li>
                    <li class="{{ in_array('class', $route) ? 'active' : null}}"><a href="{{route('a.class.index')}}" class="nav-link"><i class="fas fa-chalkboard-teacher"></i><span>Kelas</span></a></li>
                    <li class="{{ in_array('spps', $route) ? 'active' : null}}"><a href="{{route('a.spps.index')}}" class="nav-link"><i class="fas fa-clipboard-list"></i><span>SPP</span></a></li>
                </ul>
            </li>
            <li class="menu-header">Transaksi</li>
            <li class="dropdown {{ in_array('transaction', $route) ? 'active' : null}}">
                <a href="{{ route('transaction.index') }}" class="nav-link"><i class="fas fa-book"></i><span>Transaksi</span></a>
            </li>
            <li class="menu-header">Laporan</li>
            <li class="dropdown">
                <a href="#" class="nav-link"><i class="fas fa-chart-bar"></i><span>Laporan</span></a>
            </li>
    </aside>
</div>
