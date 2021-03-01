<!-- General JS Scripts -->
<script src="/modules/jquery.min.js"></script>
<script src="/modules/popper.js"></script>
<script src="/modules/tooltip.js"></script>
<script src="/modules/bootstrap/js/bootstrap.min.js"></script>
<script src="/modules/nicescroll/jquery.nicescroll.min.js"></script>
<script src="/modules/moment.min.js"></script>
<script src="/js/stisla.js"></script>

<!-- JS Libraies -->
@yield('js_lib')

<!-- Page Specific JS File -->
@yield('js_page')

<!-- Template JS File -->
<script src="/js/scripts.js"></script>
<script src="/js/custom.js"></script>
@yield('js_custom')