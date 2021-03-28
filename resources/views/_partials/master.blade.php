<!DOCTYPE html>
<html lang="en">

<head>
    @include('_partials.head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php

    $role = $userData->role;
    $theme = null;
    if($role === 'admin') $theme = 'theme-1';
    else if($role === 'worker') $theme = 'theme-2';
    else $theme = 'theme-3';

    @endphp
    @if($role === Config::get('site_vars.role.FIRST'))
    <style>
        body:not(.sidebar-mini) .sidebar-style-2 .sidebar-menu>li.active>a:before {
            background-color: #dc3545 !important;
        }

        .main-sidebar .sidebar-menu li.active a,
        .main-sidebar .sidebar-menu li ul.dropdown-menu li.active>a,
        .main-sidebar .sidebar-menu li ul.dropdown-menu li a:hover {
            color: #dc3545;
        }

        body.sidebar-mini .main-sidebar .sidebar-menu>li.active>a {
            background-color: #dc3545 !important;
        }
    </style>
    @elseif($role === Config::get('site_vars.role.SECOND'))
    <style>
        /*body:not(.sidebar-mini) .sidebar-style-2 .sidebar-menu>li.active>a:before {
            background-color: var(--primary);
        }*/
    </style>
    @elseif($role === Config::get('site_vars.role.THIRD'))
    <style>
        body:not(.sidebar-mini) .sidebar-style-2 .sidebar-menu>li.active>a:before {
            background-color: #63ed7a !important;
        }

        .main-sidebar .sidebar-menu li.active a,
        .main-sidebar .sidebar-menu li ul.dropdown-menu li.active>a,
        .main-sidebar .sidebar-menu li ul.dropdown-menu li a:hover {
            color: #63ed7a;
        }

        body.sidebar-mini .main-sidebar .sidebar-menu>li.active>a {
            background-color: #63ed7a !important;
        }
    </style>
    @endif
</head>

<body>
    <div class="loading-overlay" id="loadingOverlay">
        <span class="fas fa-spinner fa-3x fa-spin"></span>
    </div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            @include('_partials.navbar')

            @include('_partials.sidebar')

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    @yield('content')
                </section>
            </div>

            @include('_partials.footer')
        </div>
    </div>

    @include('_partials.script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

    </script>
</body>

</html>