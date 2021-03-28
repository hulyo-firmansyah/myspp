@extends('_partials.master')
@section('css_lib')
<link rel="stylesheet" href="/modules/datatables/datatables.min.css">
<link rel="stylesheet" href="/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css">
<link rel="stylesheet" href="/modules/izitoast/css/iziToast.min.css">
@endsection
@section('css_custom')
<style>

</style>
@endsection
@section('content')
<div class="section-header">
    <h1>Transaksi</h1>
    {{-- <div class="section-header-button">
        <button class="btn btn-primary" data-target="#classAdd" data-toggle="modal"><i class="fa fa-plus"
                aria-hidden="true"></i> Tambah</button>
        <a href="{{route('a.class.trash')}}" class="btn btn-danger ml-2" title="Recycle Bin"><i class="fa fa-trash"
                aria-hidden="true"></i></a>
    </div> --}}
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="#">Modules</a></div>
        <div class="breadcrumb-item">DataTables</div>
    </div>
</div>
<div class="section-body">
    <h2 class="section-title">Transaksi</h2>
    <p class="section-lead">
        Anda dapat melakuakan transaksi pembayaran pada halaman ini.
    </p>

    <div class="card">
        <div class="card-body pt-4">
            <div class="row">
                <div class="col-12 col-sm-4 col-md-4 col-lg-3">
                    <div class="form-group">
                        <form id="studentSearchForm" onsubmit=" event.preventDefault() ">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Cari siswa"
                                    id="studentSearchField">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                            <small id="helpId" class="text-muted">*cari siswa berdasar nama / nisn</small>
                        </form>
                    </div>
                </div>
            </div>

            <div class="table-responsive" style="display: none;">
                <table class="table table-striped table-hover" id="studentsSearchTable">
                    <thead class="thead-inverse">
                        <tr>
                            <th>NISN</th>
                            <th>Kelas</th>
                            <th>Nama</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div id="payment-details" style="display: none">
                <h5>Detail Pembayaran</h5>
                <div class="row mt-3">
                    <div class="col-12 col-md-6 col-lg-5">
                        <label>Kode Pembayaran</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="" id="paymentCode" placeholder="Loading..."
                                disabled>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 offset-lg-2">
                        <label>Tanggal Pembayaran</label>
                        <div class="form-group">
                            <input type="date" class="form-control" name="" id="paymentDate" disabled>
                        </div>
                    </div>
                    <div class="col-12 col-md-5 col-lg-4">
                        <label>NISN</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="" id="paymentSNISN" placeholder="Loading..."
                                disabled>
                        </div>
                    </div>
                    <div class="col-12 col-md-7 col-lg-5 offset-lg-1">
                        <label>Nama</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="" id="paymentSName" placeholder="Loading..."
                                disabled>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-10">
                        <label>Kelas Saat Ini</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="" id="paymentSClass" placeholder="Loading..."
                                disabled>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-4">
                        <label for="">Pilih Pembayaran</label>
                        <div class="form-group">
                            <select class="form-control" name="" id="paymentType">
                                <option>- Loading -</option>
                            </select>
                            <small>* Kelas | Tahun Ajaran</small>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-3">
                        <label for="">Bulan Dibayar</label>
                        <div class="form-group">
                            <select class="form-control" name="" id="paymentMonth">
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-3">
                        <label for="">Tahun Dibayar</label>
                        <input type="text" class="form-control" name="" value="" id="paymentYear" disabled>
                    </div>
                    <div class="col-12 col-md-12 col-lg-10 mt-3 mt-md-1">
                        <div class="invoice">
                            <div class="d-flex">
                                <div class="invoice-detail-item mr-5">
                                    <div class="invoice-detail-name">Periode Pembayaran</div>
                                    <div class="invoice-detail-value" id="paymentPeriode">-</div>
                                </div>
                                <div class="invoice-detail-item ml-5">
                                    <div class="invoice-detail-name">Biaya Per Periode(Per Bulan)</div>
                                    <div class="invoice-detail-value" id="paymentPerPeriode">-</div>
                                </div>
                            </div>
                            <div class="invoice-detail-item">
                                <div class="invoice-detail-name">Total Biaya</div>
                                <div class="invoice-detail-value" id="paymentTotal">-</div>
                                <small>* Total biaya semua periode(12 Bulan)</small>
                            </div>
                            <div class="invoice-detail-item">
                                <div class="invoice-detail-name">Kekurangan Bulan Ini</div>
                                <div class="invoice-detail-value" id="paymentShortage">-</div>
                            </div>
                            <div class="invoice-detail-item">
                                <div class="invoice-detail-name">Total Yang Harus Dibayar</div>
                                <div class="invoice-detail-value invoice-detail-value-lg" id="paymentFinal">-
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-10">
                        <div class="form-group row align-items-center">
                            <h5 class="form-control-label col-sm-2 m-0">Jumlah Bayar</h5>
                            <div class="col-sm-10">
                                <input type="text" name="site_title" id="paymentForm"
                                    class="form-control bg-dark text-white" id="totalPayment" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-8 offset-md-2">
                        <button class="btn btn-primary" id="addToTransaction" disabled><i class="fa fa-plus"
                                aria-hidden="true"></i> Tambah
                            Transaksi</button>
                    </div>

                    <div class="col-12 col-md-12 col-lg-10 mt-4">
                        <div id="transactionCart" style="display: none;">
                            <form action="{{route('transaction.process')}}" method="post">
                                @csrf
                                <input type="hidden" name="t_code" id="transactionCartCode" value="">
                                <input type="hidden" name="s_id" id="transactionCartSId" value="">
                                <h5>Keranjang Transaksi</h5>
                                <div class="table-responsive mt-3">
                                    <table class="table table-striped table-hover" id="transactionCartTable">
                                        <thead class="thead-inverse">
                                            <tr>
                                                <th>#</th>
                                                <th>SPP</th>
                                                <th>Petugas</th>
                                                <th>Jumlah Bayar</th>
                                                <th><i class="fa fa-anchor" aria-hidden="true"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-end mt-4">
                                        <button class="btn btn-success" type="submit"><i class="fas fa-dollar-sign"></i>
                                            Cash
                                            Checkout</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@section('js_lib')
<script src="/modules/datatables/datatables.min.js"></script>
<script src="/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script src="/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>
<script src="/modules/jquery-ui/jquery-ui.min.js"></script>
<script src="/modules/sweetalert/sweetalert.min.js"></script>
<script src="/modules/izitoast/js/iziToast.min.js"></script>
<script src="/modules/input-mask/jquery.inputmask.bundle.min.js"></script>
<script src="/modules/blockui/jquery.blockUI.min.js"></script>
@endsection
@section('js_custom')
<script>
    $(document).ready(function () {
        const studentSForm = $("#studentSearchForm")
            , studentSField = $('#studentSearchField')
            , studentTable = $('#studentsSearchTable')
            , paymentDetails = $('#payment-details')
            , addTransactionBtn = $('#addToTransaction')
            , transactionCart = $('#transactionCart')
            , transactionTable = $('#transactionCartTable')

        const paymentField = {
            'payment_code': '#paymentCode'
            , 'payment_date': '#paymentDate'
            , 'student_nisn': '#paymentSNISN'
            , 'student_name': '#paymentSName'
            , 'student_class': '#paymentSClass'
            , 'payment_type': '#paymentType'
            , 'payment_month': '#paymentMonth'
            , 'payment_year': '#paymentYear'
            , 'payment_periode': '#paymentPeriode'
            , 'payment_per_periode': '#paymentPerPeriode'
            , 'payment_total': '#paymentTotal'
            , 'payment_shortage': '#paymentShortage'
            , 'payment_final': '#paymentFinal'
            , 'payment_form': '#paymentForm'
        }

        const paymentContentResest = () => {
            studentTable.parent().show('slow')
            paymentDetails.slideUp().hide('slow')
        }
        const transactionCartContentReset = () => {
            transactionCart.slideUp('slow').hide('slow')
            transactionTable.children('tbody').html('')
        }
        const paymentInvoiceReset = () => {
            // $(paymentField.payment_month).val('1').change()
            $(paymentField.payment_month).val("1")
            $(paymentField.payment_year).val('')
            $(paymentField.payment_periode).text('-')
            $(paymentField.payment_per_periode).text('-')
            $(paymentField.payment_total).text('-')
            $(paymentField.payment_shortage).text('-')
            $(paymentField.payment_final).text('-')
            $(paymentField.payment_form).val('').attr('disabled', true)
            addTransactionBtn.attr('disabled', true)
        }

        const toastSuccessDelete = () => {
            return iziToast.success({
                title: 'Berhasil!'
                , message: 'Data petugas berhasil dihapus.'
                , position: 'topRight'
            })
        }

        /*Beta*/
        const getTransaction = () => {
            $.ajax({
                url: `{{route('transaction.api.get.transaction')}}?id=${id}`
                , success: (res) => {

                }
                , error: ({
                    responseJSON: {
                        errors: err
                    }
                }, status, msg) => {

                }
            })
        }
        /*() ☜(ﾟヮﾟ☜) */

        const blockDataWhite = {
            message: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg>'
            , fadeIn: 800
            , fadeOut: 800
            , overlayCSS: {
                backgroundColor: '#fafafa'
                , opacity: 0.6
                , zIndex: 1200
                , cursor: 'wait'
            }
            , css: {
                border: 0
                , color: '#000'
                , zIndex: 1201
                , padding: 0
                , backgroundColor: 'transparent'
            }
        }

        /* 
            Variable Declaration


            For payment data
        */

        let transactionData = {
            't_code': null
            , 's_id': null
        }
            , paymentType = {}
            , selectedPaymendIndex
            , transactionCartData = {}

        /* End Variable Declaration */


        /*
            CRUD
        */

        /*Create*/

        /*
            Perform action to search student data then show on the student's table
        */
        studentSForm.on('submit', function (e) {
            e.preventDefault()

            //reset payment content
            paymentContentResest()
            //reset payment invoice
            paymentInvoiceReset()
            //reset transaction cart content
            transactionCartContentReset()

            $.ajax({
                url: `{{route('transaction.api.students.search')}}?q=${studentSField.val()}`
                , beforeSend: function () {
                    studentTable.children('tbody').html('<td colspan="4" class="text-center">Loading ...</td>')
                }
                , success: function (res) {
                    const {
                        data
                        , length
                        , status
                    } = JSON.parse(res)
                    if (status) {
                        if (length > 0) {
                            let temp = ''
                            $.each(data, function (i, v) {
                                temp += `
                                    <tr>
                                        <td>${v.nisn}</td>
                                        <td>${v.class.map((v, i) => v.selected ? `${v.steps} ${v.competence}` : null).join('')}</td>
                                        <td>${v.name}</td>
                                        <td><button class="btn btn-sm btn-primary add-to-transaction" data-id="${v.id}"><i class="fa fa-plus" aria-hidden="true"></i></button></td>
                                    </tr>
                                `
                            })
                            studentTable.children('tbody').html(temp)
                            return true;
                        }
                        swal(`Not Found`, 'Siswa tidak ditemukan!', 'warning')
                        return studentTable.children('tbody').html('<td colspan="4" class="text-center">Siswa tidak ditemukan. :(</td>')
                    }
                }
                , error: (err, status, msg) => {
                    return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                }
            })
        })

        /* 
            Add to transaction info(on top)
         */
        studentTable.on('click', '.add-to-transaction', function (e) {
            const id = $(this).data('id')
            studentTable.parent().slideUp('slow').hide('slow')
            setTimeout(function () {
                studentTable.children('tbody').html('')
            }, 1500)
            paymentDetails.slideDown().show('slow')
            const block = paymentDetails.block(blockDataWhite)
            $.ajax({
                url: `{{route('transaction.api.get.transaction')}}?id=${id}`
                , success: function (res) {
                    const {
                        status
                        , length
                        , data
                    } = JSON.parse(res)
                    if (status) {
                        block.unblock()
                        //transactionCartData is variable for transaction table in the bottom
                        transactionCartData = data.transaction_cart
                        console.table(transactionCartData, 'transaction_cart_data')

                        $(paymentField.payment_code).val(data.payment_code.toUpperCase())
                        $(paymentField.payment_date).val(data.payment_date)
                        $(paymentField.student_nisn).val(data.student_nisn)
                        $(paymentField.student_name).val(data.student_name)
                        $(paymentField.student_class).val(data.student_class)
                        let temp = `<option selected disabled>Pilih pembayaran</option>`
                        transactionData.t_code = data.enc.t_code ?? null
                        transactionData.s_id = data.enc.s_id ?? null
                        $('#transactionCartCode').val(transactionData.t_code)
                        $('#transactionCartSId').val(transactionData.s_id)
                        paymentType = data.payment_type
                        if (paymentType.length > 0) {
                            $.each(data.payment_type, function (i, v) {
                                temp += `<option value="${i}"> SPP Kelas ${v.steps} | ${v.year} </option>`
                            })
                        }
                        $(paymentField.payment_type).html(temp)

                        /*Process for transaction cart
                        First check if "transactionCartData" variable is not null/undefined
                        Then render element to transaction cart table
                        */
                        if (transactionCartData?.length) {
                            let trstemp = ''
                            $.each(transactionCartData, (i, v) => {
                                trstemp += `
                                <tr>
                                    <td>${++i}</td>
                                    <td>${v.trs_month} ${v.trs_spp_steps} | ${v.trs_spp_year}</td>
                                    <td>${v.trs_officer_name}</td>
                                    <td>${v.trs_nominal_formatted}</td>
                                    <td><button class="btn btn-danger remove-from-transaction-cart" data-id="${v.trs_id}"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
                                </tr>`
                            })
                            //Append to table
                            transactionTable.children('tbody').html(trstemp)
                            //Visible the table's parent
                            transactionCart.slideDown('slow').show('slow')
                        }
                    }
                }
                , error: (err, status, msg) => {
                    block.unblock()
                    return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                }
            })

            /*
                Add to transaction cart(On bottom)
            */
            addTransactionBtn.on('click', function (e) {
                transactionData.spp_id = paymentType[selectedPaymendIndex]?.id
                transactionData.nominal = $(paymentField.payment_form)[0].inputmask.unmaskedvalue()
                transactionData.month = $(paymentField.payment_month).val()

                $(paymentField.payment_form).removeClass('is-invalid').next().remove()
                const block = paymentDetails.block(blockDataWhite)
                $.ajax({
                    url: `{{route('transaction.api.add-to-cart-transaction')}}`
                    , data: transactionData
                    , method: 'post'
                    , success: function (res) {
                        const {
                            status
                            , length
                            , data
                        } = JSON.parse(res)
                        block.unblock()
                        if (status && length > 0 && data.length > 0) {
                            let trstemp = ''
                            $.each(data, (i, v) => {
                                trstemp += `
                                <tr>
                                    <td>${++i}</td>
                                    <td>${v.trs_month} ${v.trs_spp_steps} | ${v.trs_spp_year}</td>
                                    <td>${v.trs_officer_name}</td>
                                    <td>${v.trs_nominal_formatted}</td>
                                    <td><button class="btn btn-danger remove-from-transaction-cart" title="Hapus" data-id="${v.trs_id}"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
                                </tr>`
                            })
                            //Append to table
                            transactionTable.children('tbody').html(trstemp)
                            //Visible the table's parent
                            transactionCart.slideDown('slow').show('slow')
                            return false
                        }
                        //Print alert 'Keranjang pembayaran SPP pada bulan ini telah terisi, Apakah anda ingin mengubah nominal ? Ya : TIdak'
                        return swal(`Warning`, 'Pembayaran pada bulan ini sudah terdapat dalam keranjang!\n Hapus data pembayaran dari keranjang transaksi yang dimaksud jika terjadi kesalahan input.', 'warning')
                    }
                    , error: function (err, status, msg) {
                        block.unblock()
                        const {
                            errorStatus
                            , responseJSON: {
                                errors: errors
                            }
                        } = err
                        if (errorStatus === 422) {
                            if (errors.t_code || errors.s_id) {
                                //print alert payment error
                            }
                            if (errors.spp_id) {
                                $(paymentField.payment_type).addClass('is-invalid')
                                $(`<div class="invalid-feedback">${errors.nominal[0]}</div>`).insertAfter($(paymentField.payment_type))
                            }
                            if (errors.nominal) {
                                $(paymentField.payment_form).addClass('is-invalid')
                                $(`<div class="invalid-feedback">${errors.nominal[0]}</div>`).insertAfter($(paymentField.payment_form))
                            }
                            return
                        }
                        return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                    }
                })
            })
        })

        /*End Create*/

        /*Read*/
        /*End Read*/

        /*Update*/
        /*End Update*/

        /*Delete*/

        /*
            Delete transaction cart from transaction cart table(on bottom)
        */
        transactionTable.on('click', '.remove-from-transaction-cart', function (e) {
            const thisTableRow = $(this).closest('tr')
            const transactionCartId = $(this).data('id')
            e.preventDefault()
            swal({
                title: 'Apakah Anda yakin?'
                , text: 'Data transaksi akan dihapus dari keranjang transaksi.'
                , icon: 'warning'
                , buttons: true
                , dangerMode: true
                , reverseButtons: true
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: `{{route('transaction.api.remove-from-cart-transaction')}}?id=${transactionCartId}`
                            , method: 'delete'
                            , success: function (res) {
                                const {
                                    status
                                    , length
                                    , data
                                } = JSON.parse(res)
                                if (status && length > 0) {
                                    let trstemp = ''
                                    $.each(data, (i, v) => {
                                        trstemp += `
                                        <tr>
                                            <td>${++i}</td>
                                            <td>${v.trs_month} ${v.trs_spp_steps} | ${v.trs_spp_year}</td>
                                            <td>${v.trs_officer_name}</td>
                                            <td>${v.trs_nominal_formatted}</td>
                                            <td><button class="btn btn-danger remove-from-transaction-cart" title="Hapus" data-id="${v.trs_id}"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
                                        </tr>`
                                    })
                                    //Append to table
                                    transactionTable.children('tbody').html(trstemp)
                                    return toastSuccessDelete()
                                }
                                transactionCart.slideUp('slow').hide('slow')
                                setTimeout(function () {
                                    transactionTable.children('tbody').html('')
                                }, 1500)
                                return toastSuccessDelete()
                            }
                            , error: function (err, status, msg) {
                                block.unblock()
                                return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                            }
                        })
                    }
                })
        })
        /*End Delete*/

        /*
            Perform when change the transaction type
        */
        $(paymentField.payment_type).on('change', function (e) {
            const index = e.target.value
            selectedPaymendIndex = index

            $(paymentField.payment_year).val(paymentType[index]?.year)
            $(paymentField.payment_periode).text(paymentType[index]?.periode)
            $(paymentField.payment_per_periode).text(paymentType[index]?.nominal_per_periode_formatted)
            $(paymentField.payment_total).text(paymentType[index]?.nominal_formatted)
            $(paymentField.payment_shortage).text(paymentType[index]?.nominal_per_periode_formatted)
            $(paymentField.payment_final).text(paymentType[index]?.nominal_per_periode_formatted)
            $(paymentField.payment_form).attr('disabled', false)
            $(paymentField.payment_form).inputmask({
                'alias': 'decimal'
                , 'groupSeparator': ','
                , 'autoGroup': true
                , 'prefix': 'Rp. '
                , 'rightAlign': false
                , min: 0
                , max: paymentType[index]?.nominal_per_periode
            })
            addTransactionBtn.attr('disabled', false)
        })




    })

</script>
@if (session('success'))
<script>
    swal("Pembayaran Berhasil", 'Selamat pembayaran SPP atas nama {{ session("success")["student_name"] }} telah berhasil diproses pada tanggal {{ session("success")["date"] }}', 'success');

</script>
@endif
@endsection