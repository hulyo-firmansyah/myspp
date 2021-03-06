@extends('_partials.master')
@section('css_lib')
<link rel="stylesheet" href="/modules/datatables/datatables.min.css">
<link rel="stylesheet" href="/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css">
<link rel="stylesheet" href="/modules/izitoast/css/iziToast.min.css">
<meta id="s_id" content="{{$studentData->student_id}}" />
@endsection
@section('css_custom')

@endsection
@section('content')

<div class="section-header">
    <h1>Histori Pembayaran</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="#">Modules</a></div>
        <div class="breadcrumb-item">DataTables</div>
    </div>
</div>

<div class="section-body">
    <h2 class="section-title">Histori Pembayaran</h2>
    <p class="section-lead">
        Anda dapat melihat Histori Pembayaran pembayaran pada halaman ini.
    </p>

    <div class="card">
        <div class="card-body">
            <address>
                <strong>Pembayaran SPP atas nama :</strong><br>
                {{$studentData->student_name}}<br>
                <strong>{{$studentData->student_nisn}}</strong><br>
            </address>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Histori Pembayaran SPP</h4>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped" id="transactionHistoryTable">
                    <thead>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
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
        /* 
            Element Initialization
         */
        const table = $('#transactionHistoryTable'),
            studentId = $('#s_id').attr('content')

        console.log(studentId)
        /*
            CRUD
        */

        /* Create */
        /* End Create */
        /* Read */
        const historyDataTable = table.DataTable({
            ajax: {
                "url": `/student/api/get-history/${studentId}`
                , "dataSrc": "data"
            }
            , 'columns': [{
                title: "<input type='checkbox' id='historyCheckbox'>"
                , "data": null
                , orderable: false
                , "render": function (itemdata) {
                    return `<input type='checkbox' class="history-checkbox" data-id=${itemdata.id}>`
                }
            }
                , {
                title: 'Kode'
                , 'data': null
                , "render": (item) => {
                    return `${item.transaction_code.toUpperCase()}`
                }
            }
                , {
                title: 'Petugas'
                , 'data': 'worker.name'
            }
                , {
                title: 'Periode Pembayaran'
                , 'data': null
                , "render": item => {
                    return `${item.spp.step} / ${item.payment_month} ${item.spp.year}`
                }
            }
                , {
                title: 'Tanggal Pembayaran'
                , 'data': 'created_at'
            }
                , {
                title: 'Jumlah Bayar'
                , 'data': 'payment_nominal_formatted'
            }
                , {
                title: 'Jenis Pembayaran'
                , 'data': 'payment_type'
            }
                //     , {
                //     'data': null
                //     , title: 'Action'
                //     , wrap: true
                //     , orderable: false
                //     , "render": function (item) {
                //         // return `<button type="button" class="btn btn-primary btn-sm student-details-trigger" data-id=${item.id} data-toggle="modal" data-target="#studentDetails"><i class="fa fa-info-circle" aria-hidden="true"></i> Details</button>
                //         // <a href="/payment-history/print-report/${item.transaction_code}" target="_blank" rel="noopener noreferrer" class="btn btn-dark btn-sm student-details-trigger"><i class="fa fa-print" aria-hidden="true"></i> Print</a>`
                //         return `<a href="/payment-history/print-report/${item.transaction_code}" target="_blank" rel="noopener noreferrer" class="btn btn-dark btn-sm student-details-trigger"><i class="fa fa-print" aria-hidden="true"></i> Print</a>`
                //     }
                // }
            ]
        })
        /* End Read */
    })
</script>
@endsection