<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>500 &mdash; Internal Server Error</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="/modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/modules/fontawesome/css/all.min.css">

    <!-- CSS Libraries -->

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
</head>

<body>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="page-error">
                    <div class="page-inner">
                        <h1>500</h1>
                        <div class="page-description">
                            Woops! Sepertinya ada kesalahan.
                        </div>
                        <div class="page-search">
                            <div class="mt-3">
                                <a href="/">Kembali ke dashboard</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="simple-footer mt-5">
                    Copyright &copy; by {{Config::get('site_vars.app_name')}}
                    <script>
                        document.write(new Date().getFullYear())

                    </script>
                </div>
            </div>
        </section>
    </div>

    <!-- General JS Scripts -->
    <script src="/modules/jquery.min.js"></script>
    <script src="/modules/popper.js"></script>
    <script src="/modules/tooltip.js"></script>
    <script src="/modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="/modules/nicescroll/jquery.nicescroll.min.js"></script>
    <script src="/modules/moment.min.js"></script>
    <script src="/js/stisla.js"></script>

    <!-- JS Libraies -->

    <!-- Page Specific JS File -->

    <!-- Template JS File -->
    <script src="/js/scripts.js"></script>
    <script src="/js/custom.js"></script>
</body>
</html>
