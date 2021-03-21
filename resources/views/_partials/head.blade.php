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
<style>
    .loading-overlay {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        top: 0;
        z-index: 9998;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.3);
    }

    .loading-overlay>span {
        font-size: 70px;
        color: var(--white);
    }

</style>
<!-- /END GA -->
@yield('css_custom')
