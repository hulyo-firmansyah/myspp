<!DOCTYPE html>
<html lang="en">
@include('auth._partials.head')

<body>
    <div id="app">
        @yield('content')
    </div>

    @include('auth._partials.script')
</body>

</html>