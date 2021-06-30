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
<script>
    const loadingOverlay = $('#loadingOverlay')
    const rupiahCurrency = number => {
        let number_string = number.toString()
            , prefix = 'Rp '
            , split = number_string.split(',')
            , sisa = split[0].length % 3
            , rupiah = split[0].substr(0, sisa)
            , ribuan = split[0].substr(sisa).match(/\d{3}/gi)

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? '.' : ''
            rupiah += separator + ribuan.join('.')
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '')
    }

</script>
@yield('js_custom')
