@extends('_partials.master')
@section('css_lib')
<link rel="stylesheet" href="/modules/datatables/datatables.min.css">
<link rel="stylesheet" href="/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css">

<link rel="stylesheet" href="/modules/izitoast/css/iziToast.min.css">
@endsection
@section('css_custom')
@endsection
@section('content')
<div class="section-header">
    <a href="{{route('a.students.index')}}" class="btn btn-primary mr-3"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    <h1>Keranjang Sampah(Siswa)</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="#">Modules</a></div>
        <div class="breadcrumb-item">DataTables</div>
    </div>
</div>
<div class="section-body">
    <h2 class="section-title">Keranjang Sampah(Siswa)</h2>
    <p class="section-lead">
        Anda dapat melakukan restore data yang telah terhapus atau menghapus data secara permanen.
    </p>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Terhapus</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="studentList">
                            <thead>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary btn-sm mr-2" id="studentsRestore"><i class="fa fa-history" aria-hidden="true"></i> Restore</button>
                    <button class="btn btn-danger btn-sm" id="studentsDelete"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
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
@endsection
@section('js_custom')
<script>
    $(document).ready(function() {
        const sCheckBox = $('#studentsCheckbox')
            , sTable = $('#studentList')
            , ssDelete = $('#studentsDelete')
            , ssRestore = $('#studentsRestore')

        const toastSuccessPermanentDelete = () => {
            return iziToast.success({
                title: 'Berhasil!'
                , message: 'Data siswa berhasil dihapus permanent!'
                , position: 'topRight'
            })
        }
        const toastSuccessRestore = () => {
            return iziToast.success({
                title: 'Berhasil!'
                , message: 'Data siswa berhasil di restore.'
                , position: 'topRight'
            })
        }
        const toastErrorDataNull = () => {
            return iziToast.error({
                title: 'Gagal!'
                , message: 'Tidak ada data yang dipilih!.'
                , position: 'topRight'
            })
        }


        const studentDataTable = sTable.DataTable({
            ajax: {
                "url": "{{route('a.students.api.get.trash')}}"
                , "dataSrc": "data"
            }
            , "columns": [{
                    title: "<input type='checkbox' id='studentsCheckbox'>"
                    , "data": null
                    , orderable: false
                    , "render": function(itemdata) {
                        return `<input type='checkbox' class="students-checkbox" data-id=${itemdata.id}>`
                    }
                }
                , {
                    title: "Nama Siswa"
                    , "data": "name"
                }
                , {
                    title: "NISN"
                    , "data": "username"
                }
                , {
                    title: "Kelas"
                    , "data": "class"
                }
                , {
                    title: "Dihapus"
                    , "data": "deleted_at"
                }
                , {
                    'data': null
                    , title: 'Action'
                    , wrap: true
                    , orderable: false
                    , "render": function(item) {
                        return `
                            <button type="button" class="btn btn-primary btn-sm studentRestore" data-id=${item.id}><i class="fa fa-history" aria-hidden="true" title="Restore"></i></button>
                            <button type="button" class="btn btn-danger btn-sm studentPermDelete" data-id=${item.id}><i class="fa fa-trash" aria-hidden="true" title="Delete Permanent"></i></button>
                        `
                    }
                }
            , ]
        , })

        $('#studentsCheckbox').change(function() {
            const check = $(this).is(':checked')
            if (check) {
                $('.students-checkbox').prop('checked', true);
            } else {
                $('.students-checkbox').prop('checked', false);
            }
        })

        //student Restore
        sTable.on('click', '.studentRestore', function(e) {
            const id = $(this).data('id')
            $.ajax({
                url: "{{route('a.students.api.restore')}}"
                , type: 'PUT'
                , data: {
                    'id': id
                }
                , beforeSend: () => {
                    loadingOverlay.css("display", "flex").fadeIn('fast')
                }
                , success: function(res) {
                    loadingOverlay.fadeOut('fast')
                    let {
                        data
                        , status
                        , lenght
                    } = JSON.parse(res)
                    if (status) {
                        studentDataTable.ajax.reload()
                        return toastSuccessRestore()
                    }
                }
                , error: function(err, status, msg) {
                    loadingOverlay.fadeOut('fast')
                    return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                }
            })
        })
        //student Permanent Delete
        sTable.on('click', '.studentPermDelete', function(e) {
            const id = $(this).data('id')
            swal({
                    title: 'Apakah Anda yakin?'
                    , text: 'Saat data dihapus maka data tidak akan bisa dikembalikan.'
                    , icon: 'warning'
                    , buttons: true
                    , dangerMode: true
                    , showCancelButton: true
                , })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "{{route('a.students.api.force-delete')}}"
                            , type: 'delete'
                            , data: {
                                'id': id
                            }
                            , beforeSend: () => {
                                loadingOverlay.css("display", "flex").fadeIn('fast')
                            }
                            , success: function(res) {
                                loadingOverlay.fadeOut('fast')
                                let {
                                    data
                                    , status
                                    , lenght
                                } = JSON.parse(res)
                                if (status) {
                                    studentDataTable.ajax.reload()
                                    return toastSuccessPermanentDelete()
                                }
                            }
                            , error: function(err, status, msg) {
                                loadingOverlay.fadeOut('fast')
                                return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                            }
                        })
                    }
                    return false
                })
        })

        //Mass action
        //Restore
        ssRestore.on('click', function() {
            let selectedStudents = []

            $('.students-checkbox:checked').each(function(key, value) {
                selectedStudents.push(value.dataset.id)
            })

            if (selectedStudents.length > 0) {
                $.ajax({
                    url: "{{route('a.students.api.restore')}}"
                    , type: 'PUT'
                    , data: {
                        'id': selectedStudents
                    }
                    , beforeSend: () => {
                        loadingOverlay.css("display", "flex").fadeIn('fast')
                    }
                    , success: function(res) {
                        loadingOverlay.fadeOut('fast')
                        let {
                            status
                            , lenght
                        } = JSON.parse(res)
                        if (status) {
                            studentDataTable.ajax.reload()
                            return toastSuccessRestore()
                        }
                    }
                    , error: function(err, status, msg) {
                        loadingOverlay.fadeOut('fast')
                        return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                    }
                })
                return
            }
            return toastErrorDataNull()

        })
        //Delete
        ssDelete.on('click', function() {
            let selectedStudents = []

            $('.students-checkbox:checked').each(function(key, value) {
                selectedStudents.push(value.dataset.id)
            })

            if (selectedStudents.length > 0) {
                swal({
                        title: 'Apakah Anda yakin?'
                        , text: 'Saat data dihapus maka data tidak akan bisa dikembalikan.'
                        , icon: 'warning'
                        , buttons: true
                        , dangerMode: true
                        , showCancelButton: true
                    , })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: "{{route('a.students.api.force-delete')}}"
                                , type: 'delete'
                                , data: {
                                    'id': selectedStudents
                                }
                                , beforeSend: () => {
                                    loadingOverlay.css("display", "flex").fadeIn('fast')
                                }
                                , success: function(res) {
                                    loadingOverlay.fadeOut('fast')
                                    let {
                                        status
                                        , lenght
                                    } = JSON.parse(res)
                                    if (status) {
                                        studentDataTable.ajax.reload()
                                        return toastSuccessPermanentDelete()
                                    }
                                }
                                , error: function(err, status, msg) {
                                    loadingOverlay.fadeOut('fast')
                                    return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                                }
                            })
                        }
                    })
                return
            }
            return toastErrorDataNull()
        })
    })

</script>
@endsection
