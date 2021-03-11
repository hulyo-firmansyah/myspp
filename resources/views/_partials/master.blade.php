<!DOCTYPE html>
<html lang="en">
<head>
    @include('_partials.head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
    </script>
</body>
</html>
