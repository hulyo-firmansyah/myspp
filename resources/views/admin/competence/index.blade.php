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
    <h1>Jurusan / Kompetensi Keahlian</h1>
    <div class="section-header-button">
        <button class="btn btn-primary" data-target="#competenceAdd" data-toggle="modal"><i class="fa fa-plus" aria-hidden="true"></i> Tambah</button>
        <a href="{{route('a.competence.trash')}}" class="btn btn-danger ml-2" title="Recycle Bin"><i class="fa fa-trash" aria-hidden="true"></i></a>
    </div>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="#">Modules</a></div>
        <div class="breadcrumb-item">DataTables</div>
    </div>
</div>
<div class="section-body">
    <h2 class="section-title">Jurusan / Kompetensi Keahlian</h2>
    <p class="section-lead">
        Anda dapat melakuakan tambah, edit, ubah, atau hapus pada data Jurusan / Kompetensi Keahlian.
    </p>
</div>

<div class="card">
    <div class="card-header">
        <h4>Data Jurusan</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="competences">
                <thead>
                </thead>
                <tbody>
                </tbody>
            </table>
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

<div class="modal fade" id="competenceAdd" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Tambah Kelas</h5>
            </div>
            <div class="modal-body">
                <form method="post" action="/admin/competence/add" id="compNewForm">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="compNewCompName">Kompetensi Keahlian</label>
                                <input type="text" class="form-control" id="compNewCompName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary ml-2"><i class="fa fa-paper-plane" aria-hidden="true"></i> Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="competenceDetails" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="display:none">
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        /*
            Element Initialization
        */
        const table = $('#competences')
            , competenceNewForm = $('#compNewForm')
            , newCompetenceModal = $('#competenceAdd')
            , competenceDetailModal = $('#competenceDetails')

        /*
            Variable Initialization
        */

        let selectedCompetenceId = null
            , selectedCompetenceData

        const toastSuccessDelete = () => {
            return iziToast.success({
                title: 'Berhasil!'
                , message: 'Data kompetensi keahlian berhasil dihapus.'
                , position: 'topRight'
            })
        }
        const toastSuccessEdit = () => {
            return iziToast.success({
                title: 'Berhasil!'
                , message: 'Data kompetensi keahlian berhasil dirubah.'
                , position: 'topRight'
            })
        }
        const toastSuccessAdd = () => {
            return iziToast.success({
                title: 'Berhasil!'
                , message: 'Tambah data kompetensi keahlian berhasil dilakukan.'
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

        const SwalTemplate = (warning = false) => {
            return {
                title: 'Apakah Anda Yakin?'
                , text: `${(warning
                    ?
                    'Kompetensi keahlian ini terdapat beberapa data kelas dan siswa, jika Anda menghapus data kompetensi keahlian maka data kelas dan siswa akan terhapus. Tetapi Anda tetap dapat mengembalikannya dari recycle bin.'
                    :
                    'Saat data dihapus maka Anda tetap dapat mengembalikannya dari recycle bin.')}`
                , icon: 'warning'
                , buttons: true
                , dangerMode: true
            }
        }

        /*
            CRUD
        */

        /* Create */
        competenceNewForm.on('submit', function(e) {
            e.preventDefault()
            const data = {
                'competence_name': ['#compNewCompName', e.target[0].value]
            }

            $.each(data, function(i, v) {
                $(data[i][0]).removeClass('is-invalid').next().remove()
            })

            $.ajax({
                url: `{{route('a.competence.api.store')}}`
                , method: 'post'
                , data: {
                    'competence_name': data.competence_name[1]
                }
                , beforeSend: () => {
                    loadingOverlay.css("display", "flex").fadeIn('fast')
                }
                , success: function(res) {
                    loadingOverlay.fadeOut('fast')
                    let {
                        status
                    } = JSON.parse(res)
                    if (status) {
                        newCompetenceModal.modal('hide')
                        competencesDataTable.ajax.reload()
                        $.each(data, function(i, v) {
                            $(data[i][0]).val('')
                        })
                        return toastSuccessAdd()
                    }
                }
                , error: function(err, status, msg) {
                    loadingOverlay.fadeOut('fast')
                    if (err.status === 422) {
                        $.each(err.responseJSON.errors, function(i, v) {
                            $(data[i][0]).addClass('is-invalid')
                            $(`<div class="invalid-feedback">${v}</div>`).insertAfter($(data[i][0]))
                        })
                        return true
                    }
                    newCompetenceModal.modal('hide')
                    return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                }
            })
        })
        /* End Create */

        /* Read */
        const competencesDataTable = table.DataTable({
            ajax: {
                "url": "{{route('a.competence.api.get')}}"
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
                    title: "Kompetensi Keahlian"
                    , "data": "competence"
                }
                , {
                    title: "Total Kelas"
                    , "data": "class_total"
                }
                , {
                    title: "Total murid"
                    , "data": "student_total"
                }
                , {
                    'data': null
                    , title: 'Action'
                    , wrap: true
                    , orderable: false
                    , "render": function(item) {
                        return `<button type="button" class="btn btn-primary btn-sm competence-details-trigger" data-id=${item.id} data-toggle="modal" data-target="#competenceDetails"><i class="fa fa-info-circle" aria-hidden="true"></i> Details</button>`
                    }
                }
            ]
        })

        competenceDetailModal.on('show.bs.modal', function(e) {
            $.ajax({
                url: `/admin/competences/api/get-details/${selectedCompetenceId}`
                , beforeSend: () => {
                    loadingOverlay.css("display", "flex").fadeIn('fast')
                }
                , success: function(res) {
                    loadingOverlay.fadeOut('fast')
                    const {
                        data
                        , status
                        , length
                    } = JSON.parse(res)
                    selectedCompetenceData = data[0]
                    if (status) {
                        competenceDetailModal.find('.modal-content').html(`
                            <div class="modal-header">
                                <input class="btn btn-primary" type="button" value="Detail Kompetensi Keahlian">
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
                                        <i class="fas fa-school"></i>
                                    </div>
                                    <div class="desc">
                                        <div class="font-weight-bold">Kompetensi Keahlian</div>
                                        <div>${selectedCompetenceData.competence}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="d-flex py-2">
                                            <div class="icon mr-3">
                                                <i class="fas fa-chalkboard-teacher"></i>
                                            </div>
                                            <div class="desc">
                                                <div class="font-weight-bold">Jumlah Kelas</div>
                                                <div>${selectedCompetenceData.class_total}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex py-2">
                                            <div class="icon mr-3">
                                                <i class="fas fa-graduation-cap"></i>
                                            </div>
                                            <div class="desc">
                                                <div class="font-weight-bold">Jumlah Siswa</div>
                                                <div>${selectedCompetenceData.student_total}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex py-2">
                                    <div class="icon mr-3">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="desc">
                                        <div class="font-weight-bold">Dibuat</div>
                                        <div>${selectedCompetenceData.created_at}</div>
                                    </div>
                                </div>
                                <div class="d-flex py-2">
                                    <div class="icon mr-3">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                    <div class="desc">
                                        <div class="font-weight-bold">Diubah</div>
                                        <div>${selectedCompetenceData.updated_at}</div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                </div>
                            </div>
                        `)
                        competenceDetailModal.find('.modal-content').slideDown('slow')
                    }
                }
                , error: function(err, status, msg) {
                    loadingOverlay.fadeOut('fast')
                    competenceDetailModal.modal('hide')
                    return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                }
            })
        })
        /* End Read */

        /* Update */
        competenceDetailModal.on('click', '#modalEdit', function(e) {
            competenceDetailModal.find('.modal-content').html(`
            <div class="modal-header">
                    <h5>Ubah Data Kompetensi Keahlian</h5>
                </div>
                <div class="modal-body">
                    <form method="post" id="compDetailsForm">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="compDetCompName">Kompetensi Keahlian</label>
                                    <input type="text" class="form-control" id="compDetCompName" placeholder="" value="${selectedCompetenceData.competence}">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary ml-2"><i class="fa fa-paper-plane"
                                    aria-hidden="true"></i> Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
            `)
        })

        competenceDetailModal.on('submit', '#compDetailsForm', function(e) {
            e.preventDefault()

            const data = {
                'competence_name': ['#compDetCompName', e.target[0].value]
            }

            $.each(data, function(i, v) {
                $(data[i][0]).removeClass('is-invalid').next().remove()
            })

            $.ajax({
                url: `/admin/competences/api/update/${selectedCompetenceId}`
                , type: 'PUT'
                , data: {
                    'competence_name': data.competence_name[1]
                }
                , beforeSend: () => {
                    loadingOverlay.css("display", "flex").fadeIn('fast')
                }
                , success: function(res) {
                    loadingOverlay.fadeOut('fast')
                    const {
                        data
                        , status
                        , length
                    } = JSON.parse(res)
                    if (status) {
                        competencesDataTable.ajax.reload()
                        competenceDetailModal.modal('hide')
                        return toastSuccessEdit()
                    }
                }
                , error: function(err, status, msg) {
                    loadingOverlay.fadeOut('fast')
                    if (err.status === 422) {
                        $.each(err.responseJSON.errors, function(i, v) {
                            $(data[i][0]).addClass('is-invalid')
                            $(`<div class="invalid-feedback">${v}</div>`).insertAfter($(data[i][0]))
                        })
                        return true
                    }
                    competenceDetailModal.modal('hide')
                    return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                }
            })
        })
        /* End Update */

        /* Delete */
        competenceDetailModal.on('click', '#modalDelete', function(e) {
            swal(SwalTemplate((selectedCompetenceData.class_total || selectedCompetenceData.student_total > 0 ? true : false)))
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "{{route('a.competence.api.delete')}}"
                            , type: 'delete'
                            , dataType: "JSON"
                            , data: {
                                id: selectedCompetenceId
                            }
                            , beforeSend: () => {
                                loadingOverlay.css("display", "flex").fadeIn('fast')
                            }
                            , success: function(res) {
                                loadingOverlay.fadeOut('fast')
                                let {
                                    data
                                    , status
                                    , length
                                } = res
                                if (status) {
                                    competenceDetailModal.modal('hide')
                                    competencesDataTable.ajax.reload()
                                    return toastSuccessDelete()
                                }
                            }
                            , error: function(err, status, msg) {
                                loadingOverlay.fadeOut('fast')
                                competenceDetailModal.modal('hide')
                                return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                            }
                        })
                    }
                    return false
                })
        })
        /* End Delete */

        table.on('click', '.competence-details-trigger', function() {
            selectedCompetenceId = $(this).data('id')
        })

        competenceDetailModal.on('hide.bs.modal', function(e) {
            const sppDetailsModalContent = competenceDetailModal.find('.modal-content')
            sppDetailsModalContent.slideUp('slow')
            setTimeout(() => {
                sppDetailsModalContent.html('')
            }, 1000)
        })
    })

</script>
@endsection
