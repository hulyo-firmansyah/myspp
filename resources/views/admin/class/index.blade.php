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
    <h1>Kelas</h1>
    <div class="section-header-button">
        <button class="btn btn-primary" data-target="#classAdd" data-toggle="modal"><i class="fa fa-plus" aria-hidden="true"></i> Tambah</button>
        <a href="{{route('a.class.trash')}}" class="btn btn-danger ml-2" title="Recycle Bin"><i class="fa fa-trash" aria-hidden="true"></i></a>
    </div>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="#">Modules</a></div>
        <div class="breadcrumb-item">DataTables</div>
    </div>
</div>
<div class="section-body">
    <h2 class="section-title">Kelas</h2>
    <p class="section-lead">
        Anda dapat melakuakan tambah, edit, ubah, atau hapus pada data kelas.
    </p>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Data Kelas</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="classList">
                            <thead>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- <div class="card-footer">
                    <button class="btn btn-danger btn-sm" id="classesDelete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
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

<div class="modal fade" id="classDetails" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="display:none">
        </div>
    </div>
</div>

<div class="modal fade" id="classAdd" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Tambah Kelas</h5>
            </div>
            <div class="modal-body">
                <form method="post" action="/admin/class/add" id="clssNewForm">
                    <div class="form-group">
                        <label for="clssNewClassName">Nama Kelas</label>
                        <input type="text" class="form-control" id="clssNewClassName" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="classNewSteps">Tingkatan</label>
                        <select class="form-control" name="" id="classNewSteps">
                            <option value="10">X</option>
                            <option value="11">XI</option>
                            <option value="12">XII</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="clssNewKompetensi">Kompetensi Keahlian</label>
                        <input type="text" class="form-control" id="clssNewKompetensi" placeholder="">
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

<script>
    $(document).ready(function() {
        const
            newClassModal = $('#classAdd')
            , newClass = $('#clssNewForm')

        let selectedClassId = null
            , selectedClassData

        const toastSuccessDelete = () => {
            return iziToast.success({
                title: 'Berhasil!'
                , message: 'Data kelas berhasil dihapus.'
                , position: 'topRight'
            })
        }
        const toastSuccessEdit = () => {
            return iziToast.success({
                title: 'Berhasil!'
                , message: 'Data kelas berhasil dirubah.'
                , position: 'topRight'
            })
        }
        const toastSuccessAdd = () => {
            return iziToast.success({
                title: 'Berhasil!'
                , message: 'Tambah data kelas berhasil dilakukan.'
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

        let SwalTemplate = (warning = false) => {
            return {
                title: 'Apakah Anda Yakin?'
                , text: `${(warning 
                ? 
                    'Kelas ini terdapat beberapa data siswa, jika Anda menghapus data kelas maka data siswa akan terhapus. Tetapi Anda tetap dapat mengembalikannya dari recycle bin.'
                : 
                    'Saat data dihapus maka data tidak akan bisa dikembalikan.')}`
                , icon: 'warning'
                , buttons: true
                , dangerMode: true
            }
        }

        /*
            CRUD
        */

        //CREATE
        newClass.on('submit', function(e) {
            e.preventDefault()
            const data = {
                'class_name': ['#clssNewClassName', e.target[0].value]
                , 'class_steps': ['#clssNewSteps', e.target[1].value]
                , 'class_competence': ['#clssNewKompetensi', e.target[2].value]
            }

            $.each(data, function(i, v) {
                $(data[i][0]).removeClass('is-invalid').next().remove()
            })

            $.ajax({
                url: `{{route('a.class.api.store')}}`
                , method: 'post'
                , data: {
                    'class_name': data.class_name[1]
                    , 'class_steps': data.class_steps[1]
                    , 'class_competence': data.class_competence[1]
                , }
                , beforeSend: () => {
                    loadingOverlay.css("display", "flex").fadeIn('fast')
                }
                , success: function(res) {
                    loadingOverlay.fadeOut('fast')
                    let {
                        status
                    } = JSON.parse(res)
                    if (status) {
                        newClassModal.modal('hide')
                        classDataTable.ajax.reload()
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
                    newClassModal.modal('hide')
                    return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                }
            })
        })
        //ENDCREATE

        //READ
        const classDataTable = $('#classList').DataTable({
            ajax: {
                "url": "{{route('a.class.api.get')}}"
                , "dataSrc": "data"
            }
            , "columns": [{
                    //title: "<input type='checkbox' id='classesCheckbox'>",
                    title: "#"
                    , "data": null
                    , orderable: false
                    , "render": function(itemdata) {
                        //return `<input type='checkbox' class="class-checkbox" data-id=${itemdata.id}>`
                        return `#`
                    }
                }
                , {
                    title: "Nama Kelas"
                    , "data": "class_name"
                }
                , {
                    title: "Kelas"
                    , "data": "steps"
                }
                , {
                    title: "Kompetensi Keahlian"
                    , "data": "competence"
                }
                , {
                    title: "Murid"
                    , "data": "student_count"
                }
                , {
                    'data': null
                    , title: 'Action'
                    , wrap: true
                    , orderable: false
                    , "render": function(item) {
                        return `<button type="button" class="btn btn-primary btn-sm worker-details-trigger" data-id=${item.id} data-toggle="modal" data-target="#classDetails"><i class="fa fa-info-circle" aria-hidden="true"></i> Details</button>`
                    }
                }
            ]
        })
        /*Detail Modal*/
        $('#classDetails').on('show.bs.modal', function(e) {
            $.ajax({
                url: `/admin/class/api/get-details/${selectedClassId}`
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
                    selectedClassData = data[0]
                    if (status) {
                        $('#classDetails .modal-dialog .modal-content').html(`
                            <div class="modal-header">
                                <input class="btn btn-primary" type="button" value="Detail Kelas">
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
                                        <div class="font-weight-bold">Nama Kelas</div>
                                        <div>${data[0].class_name}</div>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex py-2">
                                    <div class="icon mr-3">
                                        <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                                    </div>
                                    <div class="desc">
                                        <div class="font-weight-bold">Kelas</div>
                                        <div>${data[0].steps}</div>
                                    </div>
                                </div>
                                <div class="d-flex py-2">
                                    <div class="icon mr-3">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="desc">
                                        <div class="font-weight-bold">Kompetensi Keahlian</div>
                                        <div>${data[0].competence}</div>
                                    </div>
                                </div>
                                <div class="d-flex py-2">
                                    <div class="icon mr-3">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="desc">
                                        <div class="font-weight-bold">Siswa</div>
                                        <div>${data[0].student_count} ${(data[0].student_count > 0 ? '<a href="' + data[0].students_detail_url + '" class="text-primary">See Details</a>' : '')}</div>
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
                            </div>
                        `)
                        $('#classDetails .modal-dialog .modal-content').slideDown('slow')
                    }
                }
                , error: function(err, status, msg) {
                    loadingOverlay.fadeOut('fast')
                    $('#classDetails').modal('hide')
                    return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                }
            })
        })
        //ENDREAD 

        //UPDATE
        $('#classDetails').on('click', '#modalEdit', function(e) {
            $('#classDetails .modal-dialog .modal-content').html(` 
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
                                <label for="wDetClassName">Nama Kelas</label>
                                <input type="text" class="form-control" id="wDetClassName" placeholder="" value="${selectedClassData.class_name}">
                            </div>
                            <div class="form-group">
                                <label for="wDetSteps">Kelas</label>
                                <select class="form-control" name="" id="wDetSteps">
                                    <option value="10" ${(selectedClassData.steps === 'X' ? 'selected="selected"' : '')}>X</option>
                                    <option value="11" ${(selectedClassData.steps === 'XI' ? 'selected="selected"' : '')}>XI</option>
                                    <option value="12" ${(selectedClassData.steps === 'XII' ? 'selected="selected"' : '')}>XII</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="wDetCompetence">Kompetensi Keahlian</label>
                                <input type="text" class="form-control" id="wDetCompetence" placeholder="" value="${selectedClassData.competence}">
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary ml-2"><i class="fa fa-paper-plane" aria-hidden="true"></i> Simpan</button>
                            </div>
                        </div>
                    </form>
                `)
        })
        /*Submit Update*/
        $('#classDetails').on('submit', '#workerDetailsForm', function(e) {
            e.preventDefault()
            const data = {
                'class_name': ['#wDetClassName', e.target[0].value]
                , 'class_steps': ['#wDetSteps', e.target[1].value]
                , 'class_competence': ['#wDetCompetence', e.target[2].value]
            }

            $.each(data, function(i, v) {
                $(data[i][0]).removeClass('is-invalid').next().remove()
            })

            $.ajax({
                url: `/admin/class/api/update/${selectedClassId}`
                , type: 'PUT'
                , data: {
                    'class_name': data.class_name[1]
                    , 'class_steps': data.class_steps[1]
                    , 'class_competence': data.class_competence[1]
                , }
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
                        classDataTable.ajax.reload()
                        $('#classDetails').modal('hide')
                        return toastSuccessEdit()
                    }
                }
                , error: function(err, status, msg) {
                    loadingOverlay.fadeOut('fast')
                    if (err.status === 422) {
                        $.each(err.responseJSON.errors, function(i, v) {
                            console.log(data[i])
                            $(data[i][0]).addClass('is-invalid')
                            $(`<div class="invalid-feedback">${v}</div>`).insertAfter($(data[i][0]))
                        })
                        return true
                    }
                    $('#classDetails').modal('hide')
                    return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                }
            })
        })
        //END UPDATE

        //DELETE
        $('#classDetails').on('click', '#modalDelete', function(e) {
            swal(SwalTemplate((selectedClassData.student_count > 0 ? true : false)))
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "{{route('a.class.api.delete')}}"
                            , type: 'delete'
                            , dataType: "JSON"
                            , data: {
                                id: selectedClassId
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
                                    $('#classDetails').modal('hide')
                                    classDataTable.ajax.reload()
                                    return toastSuccessDelete()
                                }
                            }
                            , error: function(err, status, msg) {
                                loadingOverlay.fadeOut('fast')
                                $('#classDetails').modal('hide')
                                return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                            }
                        })
                    }
                    return false
                })
        })

        /* Mass Action */
        /*
            $('#classesDelete').click(function() {
                selectedClasses = []
                $('.class-checkbox:checked').each(function(key, value) {
                    selectedClasses.push(value.dataset.id)
                })

                if (selectedClasses.length > 0) {
                    swal(SwalTemplate(false))
                        .then((willDelete) => {
                            if (willDelete) {
                                $.ajax({
                                    url: "{{route('a.class.api.delete')}}"
                                    , type: 'delete'
                                    , dataType: "JSON"
                                    , data: {
                                        id: selectedClasses
                                    }
                                    , beforeSend: () => {
                                        loadingOverlay.css("display", "flex").fadeIn('fast')
                                    }
                                    , success: function(res) {
                                        let {
                                            status
                                        } = res
                                        if (status) {
                                            loadingOverlay.fadeOut('fast')
                                            classDataTable.ajax.reload()
                                            return toastSuccessDelete()
                                        }
                                    }
                                    , error: function(err, status, msg) {
                                        loadingOverlay.fadeOut('fast')
                                        $('#workerDetails').modal('hide')
                                        return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                                    }
                                })
                            }
                        })
                    return true
                }
                return toastErrorDataNull()
            })
        */
        //END DELETE

        $('table').on('click', '.worker-details-trigger', function() {
            selectedClassId = $(this).data('id')
        })

        $('#classDetails').on('hide.bs.modal', function(e) {
            $('#classDetails .modal-dialog .modal-content').slideUp('slow')
            setTimeout(() => {
                $('#classDetails .modal-dialog .modal-content').html(``)
            }, 1000)
        })

        $('#classesCheckbox').change(function() {
            const check = $(this).is(':checked')
            if (check) {
                $('.class-checkbox').prop('checked', true);
            } else {
                $('.class-checkbox').prop('checked', false);
            }
        })

    })

</script>
@endsection
