@php
$routeList = Route::current()->uri;
$route = explode('/', $routeList);
@endphp
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">{{ Config::get('site_vars.app_name') }}</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">SPP</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="dropdown {{ (in_array(Config::get('site_vars.role.FIRST'), $route) && count($route) < 2 ) || in_array(Config::get('site_vars.role.SECOND'), $route) || (in_array(Config::get('site_vars.role.THIRD'), $route) && count($route) < 2) ? 'active' : null}}">

                <!-- Variable Role Is On Partials/Master -->
                @if($role === Config::get('site_vars.role.FIRST'))
                <a href="{{route('a.index')}}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                @elseif($role === Config::get('site_vars.role.SECOND'))
                <a href="{{route('w.index')}}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                @else
                <a href="{{route('s.index')}}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                @endif
            </li>
            @if($role === Config::get('site_vars.role.FIRST'))
            <li class="menu-header">Manajemen Data</li>
            <li class="dropdown {{ in_array('workers', $route) || in_array('students', $route) || in_array('class', $route) || in_array('spps', $route) ? 'active' : null}}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-database"></i><span>Manajemen Data</span></a>
                <ul class="dropdown-menu has-dropdown">
                    <li class="{{ in_array('workers', $route) ? 'active' : null}}"><a href="{{route('a.workers.index')}}" class="nav-link"><i class="fas fa-users"></i><span>Petugas</span></a></li>
                    <li class="{{ in_array('students', $route) ? 'active' : null}}"><a href="{{route('a.students.index')}}" class="nav-link"><i class="fas fa-graduation-cap"></i><span>Siswa</span></a></li>
                    <li class="{{ in_array('competences', $route) ? 'active' : null}}"><a href="{{route('a.competence.index')}}" class="nav-link"><i class="fas fa-school"></i><span>Jurusan</span></a></li>
                    <li class="{{ in_array('class', $route) ? 'active' : null}}"><a href="{{route('a.class.index')}}" class="nav-link"><i class="fas fa-chalkboard-teacher"></i><span>Kelas</span></a></li>
                    <li class="{{ in_array('spps', $route) ? 'active' : null}}"><a href="{{route('a.spps.index')}}" class="nav-link"><i class="fas fa-clipboard-list"></i><span>SPP</span></a></li>
                </ul>
            </li>
            @endif
            @if($role !== Config::get('site_vars.role.THIRD'))
            <li class="menu-header">Transaksi</li>
            <li class="dropdown {{ in_array('transaction', $route) ? 'active' : null}}">
                <a href="{{ route('transaction.index') }}" class="nav-link"><i class="fas fa-credit-card"></i><span>Transaksi</span></a>
            </li>
            <li class="menu-header">Laporan</li>
            <li class="dropdown {{ in_array('payment-history', $route) ? 'active' : null}}">
                <a href="{{ route('history.index') }}" class="nav-link"><i class="fas fa-chart-bar"></i><span>Histori Pembayaran</span></a>
            </li>
            @else
            <li class="menu-header">Laporan</li>
            <li class="dropdown {{ in_array('payment-history', $route) ? 'active' : null}}">
                <a href="{{ route('s.history') }}" class="nav-link"><i class="fas fa-chart-bar"></i><span>Histori Pembayaran</span></a>
            </li>
            @endif
            <li class="menu-header">Setting</li>
            <li class="dropdown {{ in_array('settings', $route) ? 'active' : null}}">
                <a href="{{ route('history.index') }}" class="nav-link"><i class="fas fa-wrench"></i><span>Pengaturan Umum</span></a>
            </li>
    </aside>
</div>
