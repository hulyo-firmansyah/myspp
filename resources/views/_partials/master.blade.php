<!DOCTYPE html>
<html lang="en">
<head>
    @include('_partials.head')
</head>

<body>
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
</body>
</html>
