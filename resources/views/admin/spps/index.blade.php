@extends('_partials.master')
@section('css_lib')
<link rel="stylesheet" href="/modules/datatables/datatables.min.css">
<link rel="stylesheet" href="/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css">
@endsection
@section('css_custom')
@endsection
@section('content')
<div class="section-header">
    <h1>SPP</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="#">Modules</a></div>
        <div class="breadcrumb-item">DataTables</div>
    </div>
</div>
<div class="section-body">
    <h2 class="section-title">SPP</h2>
    <p class="section-lead">
        Anda hanya dapat melakuakan edit atau ubah pada data SPP.
    </p>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Data SPP</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="sppsList">
                            <thead>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
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
@endsection
@section('js_page')

@endsection
@section('js_custom')

<div class="modal fade" id="workerDetails" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<script>
    $(document).ready(function() {

        const tableworker = $("#sppsList").DataTable({
            ajax: {
                "url": "{{route('a.spps.api.get')}}"
                , "dataSrc": "data"
            }
            , "columns": [{
                    title: "#"
                    , "data": null
                    , orderable: false
                    , "render": function(itemdata) {
                        return `#`
                    }
                }
                , {
                    title: "Nama Siswa"
                    , "data": null
                }
                , {
                    title: "NISN"
                    , "data": null
                }
                , {
                    title: "Kelas"
                    , "data": null
                }
                , {
                    title: "Tahun"
                    , "data": "year"
                }
                , {
                    title: "Nominal"
                    , "data": "nominal"
                }
                , {
                    'data': null
                    , title: 'Action'
                    , wrap: true
                    , orderable: false
                    , "render": function(item) {
                        return `<button type="button" class="btn btn-primary btn-sm worker-details-trigger" data-id=${item.id} data-toggle="modal" data-target="#workerDetails"><i class="fa fa-info-circle" aria-hidden="true"></i> Details</button>`
                    }
                }
            , ]
            , "sDom": '<"ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix"lfr>t<"ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix"<"testbutton">ip>'
        , })
        let selectedWorkers = []
            , selectedWorkersId = null
            , selectedWorkersData

        $('table').on('click', '.worker-details-trigger', function(e) {
            selectedWorkersId = $(this).data('id')
        })

        $('#workersDelete').click(function() {
            selectedWorkers = []
            $('.workers-checkbox:checked').each(function(key, value) {
                selectedWorkers.push(value.dataset.id)
            })

            if (selectedWorkers.length > 0) {
                $.ajax({
                    url: "{{route('a.workers.api.delete')}}"
                    , type: 'delete'
                    , dataType: "JSON"
                    , data: {
                        id: selectedWorkers
                    }
                    , success: function(data) {
                        $('.workers-checkbox:checked').each(function(key, value) {
                            tableworker.row($(value).parents('tr')).remove().draw()
                        })
                    }
                })
            }
        })

        $('#workersCheckbox').change(function() {
            const check = $(this).is(':checked')
            if (check) {
                $('.workers-checkbox').prop('checked', true);
            } else {
                $('.workers-checkbox').prop('checked', false);
            }
        })


        //modal

        //Detail
        $('#workerDetails').on('show.bs.modal', function(e) {
            $.ajax({
                url: `/admin/workers/api/get-details/${selectedWorkersId}`
                , success: function(result) {
                    let {
                        data
                        , status
                        , length
                    } = JSON.parse(result)
                    selectedWorkersData = data[0]
                    $('#workerDetails .modal-dialog .modal-content').html(`
                        <div class="modal-header">
                            ${data[0].role == 'admin' ? '<div class="btn btn-sm btn-danger">Administrator</div>' : ''}
                            ${data[0].role == 'worker' ? '<div class="btn btn-sm btn-primary">Petugas</div>' : ''}
                            ${data[0].role == 'student' ? '<div class="btn btn-sm btn-success">Siswa</div>' : ''}
                            <div class="d-flex justify-content-center align-items-center">
                                <button type="button" class="close" id="modalEdit">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button type="button" class="close" id="modalDelete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                        <div class="modal-body">
                            <hr class="my-2">
                            <div class="d-flex py-2">
                                <div class="icon mr-3">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="desc">
                                    <div class="font-weight-bold">Nama</div>
                                    <div>${data[0].name}</div>
                                </div>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex py-2">
                                <div class="icon mr-3">
                                    <i class="fas fa-user-alt"></i>
                                </div>
                                <div class="desc">
                                    <div class="font-weight-bold">Username</div>
                                    <div>${data[0].username}</div>
                                </div>
                            </div>
                            <div class="d-flex py-2">
                                <div class="icon mr-3">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="desc">
                                    <div class="font-weight-bold">Email</div>
                                    <div>${data[0].email}</div>
                                </div>
                            </div>
                            <div class="d-flex py-2">
                                <div class="icon mr-3">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="desc">
                                    <div class="font-weight-bold">Dibuat</div>
                                    <div>${data[0].created_at}</div>
                                </div>
                            </div>
                            <div class="d-flex py-2">
                                <div class="icon mr-3">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div class="desc">
                                    <div class="font-weight-bold">Diubah</div>
                                    <div>${data[0].updated_at}</div>
                                </div>
                            </div>
                            <hr class="mt-2">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        </div>
                    `)
                }
            })
        })

        $('#workerDetails').on('hide.bs.modal', function(e) {
            $('#workerDetails .modal-dialog .modal-content').html('')
        })

        //action
        //update
        $('#workerDetails').on('click', '#modalEdit', function(e) {
            $('#workerDetails .modal-dialog .modal-content').html(` 
                    <div class="modal-header">
                        <h5>Edit Data</h5>
                        <div class="d-flex justify-content-center align-items-center">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    <form method="post" id="workerDetailsForm">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="wDetName">Nama</label>
                                <input type="text" class="form-control" id="wDetName" placeholder="" value="${selectedWorkersData.name}">
                            </div>
                            <div class="form-group">
                                <label for="wDetUsername">Username</label>
                                <input type="text" class="form-control" id="wDetUsername" placeholder="" value="${selectedWorkersData.username}">
                            </div>
                            <div class="form-group">
                                <label for="wDetEmail">Email</label>
                                <input type="email" class="form-control" id="wDetEmail" placeholder="" value="${selectedWorkersData.email}">
                            </div>
                            <div class="form-group">
                                <label for="wDetPassword">Password</label>
                                <input type="password" class="form-control" id="wDetPassword" placeholder="">
                            </div>
                            <div class="form-group">
                                <label for="wDetPasswordC">Confirm Password</label>
                                <input type="password" class="form-control" id="wDetPasswordC" placeholder="">
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary ml-2"><i class="fa fa-paper-plane" aria-hidden="true"></i> Simpan</button>
                            </div>
                        </div>
                    </form>
                `)
        })

        $('#workerDetails').on('submit', '#workerDetailsForm', function(e) {
            e.preventDefault()
            const data = {
                'name': ['#wDetName', e.target[0].value]
                , 'username': ['#wDetUsername', e.target[1].value]
                , 'email': ['#wDetEmail', e.target[2].value]
                , 'password': ['#wDetPassword', e.target[3].value]
                , 'password_conf': ['#wDetPasswordC', e.target[4].value]
            , }

            $.each(data, function(i, v) {
                $(data[i][0]).removeClass('is-invalid').next().remove()
            })

            $.ajax({
                url: `/admin/workers/api/update/${selectedWorkersId}`
                , type: 'PUT'
                , data: {
                    'name': data.name[1]
                    , 'username': data.username[1]
                    , 'email': data.email[1]
                    , 'password': data.password[1]
                    , 'password_conf': data.password_conf[1]
                , }
                , success: function(res) {
                    const {
                        data
                        , status
                        , length
                    } = JSON.parse(res)
                    if (status) {
                        tableworker.ajax.reload()
                    }
                    $('#workerDetails').modal('hide')
                }
                , error: function(err, status, msg) {
                    $.each(err.responseJSON.errors, function(i, v) {
                        $(data[i][0]).addClass('is-invalid')
                        $(`<div class="invalid-feedback">${v}</div>`).insertAfter($(data[i][0]))
                    })
                }
            })
        })
    })

</script>
@endsection
