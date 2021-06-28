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
    <a href="{{route('a.competence.index')}}" class="btn btn-primary mr-3"><i class="fa fa-arrow-left"
            aria-hidden="true"></i> Back</a>
    <h1>Keranjang Sampah(Jurusan)</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="#">Modules</a></div>
        <div class="breadcrumb-item">DataTables</div>
    </div>
</div>
<div class="section-body">
    <h2 class="section-title">Keranjang Sampah(Jurusan)</h2>
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
                        <table class="table table-striped" id="competenceList">
                            <thead>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- <div class="card-footer">
                    <button class="btn btn-primary btn-sm" id="competencesRestore"><i class="fa fa-history"
                            aria-hidden="true"></i> Restore</button>
                    <button class="btn btn-danger btn-sm" id="competencesDelete"> <i class="fa fa-trash"
                            aria-hidden="true"></i> Delete</button>
                </div> --}}
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
    $(document).ready(function () {
        const wCheckBox = $('#competencesCheckbox')
            , wTable = $('#competenceList')
            , wsDelete = $('#competencesDelete')
            , wsRestore = $('#competencesRestore')

        const toastSuccessPermanentDelete = () => {
            return iziToast.success({
                title: 'Berhasil!'
                , message: 'Data petugas berhasil dihapus permanent!'
                , position: 'topRight'
            })
        }
        const toastSuccessRestore = () => {
            return iziToast.success({
                title: 'Berhasil!'
                , message: 'Data petugas berhasil di restore.'
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

        const competencetable = wTable.DataTable({
            ajax: {
                "url": "{{route('a.competence.api.get.trash')}}"
                , "dataSrc": "data"
            }
            , "columns": [{
                title: "#"
                //, title: "<input type='checkbox' id='competencesCheckbox'>"
                , "data": null
                , orderable: false
                , "render": function (itemdata) {
                    //return `<input type='checkbox' class="competences-checkbox" data-id=${itemdata.id}>`
                    return `#`
                }
            }
                , {
                title: "Nama Petugas"
                , "data": "name"
            }
                , {
                title: "Username"
                , "data": "username"
            }
                , {
                title: "Email"
                , "data": "email"
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
                , "render": function (item) {
                    return `
                            <button type="button" class="btn btn-primary btn-sm competenceRestore" data-id=${item.id}><i class="fa fa-history" aria-hidden="true" title="Restore"></i></button>
                            <button type="button" class="btn btn-danger btn-sm competencePermDelete" data-id=${item.id}><i class="fa fa-trash" aria-hidden="true" title="Delete Permanent"></i></button>
                        `
                }
            }
                ,]
            ,
        })

        wCheckBox.change(function () {
            const check = $(this).is(':checked')
            if (check) {
                $('.competences-checkbox').prop('checked', true);
            } else {
                $('.competences-checkbox').prop('checked', false);
            }
        })

        //Worker Restore
        wTable.on('click', '.competenceRestore', function (e) {
            const id = $(this).data('id')
            $.ajax({
                url: "{{route('a.competence.api.restore')}}"
                , type: 'PUT'
                , data: {
                    'id': id
                }
                , beforeSend: () => {
                    loadingOverlay.css("display", "flex").fadeIn('fast')
                }
                , success: function (res) {
                    loadingOverlay.fadeOut('fast')
                    let {
                        data
                        , status
                        , lenght
                    } = JSON.parse(res)
                    if (status) {
                        competencetable.ajax.reload()
                        return toastSuccessRestore()
                    }
                }
                , error: function (err, status, msg) {
                    loadingOverlay.fadeOut('fast')
                    return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                }
            })
        })
        //Worker Permanent Delete
        wTable.on('click', '.competencePermDelete', function (e) {
            const id = $(this).data('id')
            swal({
                title: 'Apakah Anda yakin?'
                , text: 'Saat data dihapus maka data tidak akan bisa dikembalikan.'
                , icon: 'warning'
                , buttons: true
                , dangerMode: true
                , showCancelButton: true
                ,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "{{route('a.competence.api.force-delete')}}"
                            , type: 'delete'
                            , data: {
                                'id': id
                            }
                            , beforeSend: () => {
                                loadingOverlay.css("display", "flex").fadeIn('fast')
                            }
                            , success: function (res) {
                                loadingOverlay.fadeOut('fast')
                                let {
                                    data
                                    , status
                                    , lenght
                                } = JSON.parse(res)
                                if (status) {
                                    competencetable.ajax.reload()
                                    return toastSuccessPermanentDelete()
                                }
                            }
                            , error: function (err, status, msg) {
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
        wsRestore.on('click', function () {
            let selectedWorkers = []

            $('.competences-checkbox:checked').each(function (key, value) {
                selectedWorkers.push(value.dataset.id)
            })

            if (selectedWorkers.length > 0) {
                $.ajax({
                    url: "{{route('a.competence.api.restore')}}"
                    , type: 'PUT'
                    , data: {
                        'id': selectedWorkers
                    }
                    , beforeSend: () => {
                        loadingOverlay.css("display", "flex").fadeIn('fast')
                    }
                    , success: function (res) {
                        loadingOverlay.fadeOut('fast')
                        let {
                            data
                            , status
                            , lenght
                        } = JSON.parse(res)
                        if (status) {
                            competencetable.ajax.reload()
                            return toastSuccessRestore()
                        }
                    }
                    , error: function (err, status, msg) {
                        loadingOverlay.fadeOut('fast')
                        return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                    }
                })
                return
            }
            return toastErrorDataNull()
        })
        //Delete
        wsDelete.on('click', function () {
            let selectedWorkers = []

            $('.competences-checkbox:checked').each(function (key, value) {
                selectedWorkers.push(value.dataset.id)
            })

            if (selectedWorkers.length > 0) {
                swal({
                    title: 'Apakah Anda yakin?'
                    , text: 'Saat data dihapus maka data tidak akan bisa dikembalikan.'
                    , icon: 'warning'
                    , buttons: true
                    , dangerMode: true
                    , showCancelButton: true
                    ,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: "{{route('a.competence.api.force-delete')}}"
                                , type: 'delete'
                                , data: {
                                    'id': selectedWorkers
                                }
                                , beforeSend: () => {
                                    loadingOverlay.css("display", "flex").fadeIn('fast')
                                }
                                , success: function (res) {
                                    loadingOverlay.fadeOut('fast')
                                    let {
                                        data
                                        , status
                                        , lenght
                                    } = JSON.parse(res)
                                    if (status) {
                                        competencetable.ajax.reload()
                                        return toastSuccessPermanentDelete()
                                    }
                                }
                                , error: function (err, status, msg) {
                                    loadingOverlay.fadeOut('fast')
                                    return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                                }
                            })
                        }
                    })
                return true
            }
            return toastErrorDataNull()
        })
    })

</script>
@endsection