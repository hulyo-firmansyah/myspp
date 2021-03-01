<meta charset="UTF-8">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
<title>@yield('title')</title>

<!-- General CSS Files -->
<link rel="stylesheet" href="/modules/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="/modules/fontawesome/css/all.min.css">

<!-- CSS Libraries -->
@yield('css_lib')

<!-- Template CSS -->
<link rel="stylesheet" href="/css/style.css">
<link rel="stylesheet" href="/css/components.css">
<!-- Start GA -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'UA-94034622-3');

</script>
<!-- /END GA -->
@yield('css_custom')