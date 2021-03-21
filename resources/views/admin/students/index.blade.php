@extends('_partials.master')
@section('css_lib')
<link rel="stylesheet" href="/modules/datatables/datatables.min.css">
<link rel="stylesheet" href="/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css">

<link rel="stylesheet" href="/modules/izitoast/css/iziToast.min.css">
@endsection
@section('css_custom')
<style>
    .loading-overlay {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        top: 0;
        z-index: 9998;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.3);
    }

    .loading-overlay>span {
        font-size: 70px;
        color: var(--white);
    }

    /* .loading-overlay.is-active {
        display: flex;
    } */

    .code {
        font-family: monospace;
        /*   font-size: .9em; */
        color: #dd4a68;
        background-color: rgb(238, 238, 238);
        padding: 0 3px;
    }
</style>
@endsection
@section('content')
<div class="section-header">
    <h1>Siswa</h1>
    <div class="section-header-button">
        <button class="btn btn-primary" data-target="#studentAdd" data-toggle="modal"><i class="fa fa-plus"
                aria-hidden="true"></i> Tambah</button>
        <a href="{{route('a.students.trash')}}" class="btn btn-danger ml-2" title="Recycle Bin"><i class="fa fa-trash"
                aria-hidden="true"></i></a>
    </div>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="#">Modules</a></div>
        <div class="breadcrumb-item">DataTables</div>
    </div>
</div>
<div class="section-body">
    <h2 class="section-title">Siswa</h2>
    <p class="section-lead">
        Anda dapat melakuakan tambah, edit, ubah, atau hapus pada data siswa.
    </p>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Data Siswa</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="studentsList">
                            <thead>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-danger btn-sm" id="studentsDelete"><i class="fa fa-trash"
                            aria-hidden="true"></i> Delete</button>
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

<script src="/modules/input-mask/jquery.inputmask.bundle.min.js"></script>
<script src="/modules/sweetalert/sweetalert.min.js"></script>
<script src="/modules/izitoast/js/iziToast.min.js"></script>
@endsection
@section('js_page')
@endsection
@section('js_custom')

<div class="modal fade" id="studentDetails" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="display:none">
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="studentAdd" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Tambah Siswa</h5>
            </div>
            <div class="modal-body">
                <form method="post" action="/admin/students/add" id="sNewForm">
                    <div class="row">
                        <div class="col-6 col-md-7">
                            <div class="form-group">
                                <label for="sNewName">Nama</label>
                                <input type="text" class="form-control" id="sNewName" placeholder="">
                            </div>
                        </div>
                        <div class="col-6 col-md-5">
                            <div class="form-group">
                                <label for="sNewUsername">Username</label>
                                <input type="text" class="form-control" id="sNewUsername" placeholder="">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="sNewEmail">Email</label>
                                <input type="email" class="form-control" id="sNewEmail" placeholder="">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="sNewPhone">Telepon</label>
                                <input type="tel" class="form-control" id="sNewPhone" placeholder="">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="sNewPassword">Password</label>
                                <input type="password" class="form-control" id="sNewPassword" placeholder="">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="sNewPasswordC">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="sNewPasswordC" placeholder="">
                            </div>
                        </div>
                        <div class="col-12 font-weight-bold mb-2">
                            Identitas Siswa
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="form-group">
                                <label for="sNewNISN">NISN</label>
                                <input type="text" class="form-control" id="sNewNISN" placeholder="">
                            </div>
                        </div>
                        <div class="col-12 col-md-5">
                            <div class="form-group">
                                <label for="sNewNIS">NIS</label>
                                <input type="numeric" class="form-control" id="sNewNIS" placeholder="">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="sNewClass">Kelas</label>
                                <select class="form-control" name="" id="sNewClass">
                                    @foreach($data as $cls)
                                    <option value="{{$cls['class_id']}}">{{ $cls['class']
                                        }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="sNewAddress">Alamat</label>
                                <textarea name="" id="sNewAddress" class="form-control"></textarea>
                            </div>
                        </div>
                        {{-- <div class="col-12 font-weight-bold mb-2">
                            SPP
                        </div>
                        <div class="col-12 col-6">
                            <div class="form-group">
                                <label for="sNewYear">Tahun</label>
                                <input type="text" class="form-control" name="" id="sNewYear" aria-describedby="helpId"
                                    placeholder="">
                            </div>
                        </div>
                        <div class="col-12 col-6">
                            <div class="form-group">
                                <label for="sNewNominal">Nominal</label>
                                <input type="text" class="form-control" name="" id="sNewNominal"
                                    aria-describedby="helpId" placeholder="">
                            </div>
                        </div> --}}
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary ml-2"><i class="fa fa-paper-plane"
                                aria-hidden="true"></i> Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {

        //Student new mask
        $('#sNewPhone').inputmask("(+62) 999-9999-9999")
        $('#sNewNominal').inputmask({
            'alias': 'decimal'
            , 'groupSeparator': ','
            , 'autoGroup': true,
            // 'digits': 1,
            // 'digitsOptional': false,
            // 'placeholder': '-',
            'prefix': 'Rp. '
            , 'rightAlign': false
            ,
        })

        const studentsDataTable = $("#studentsList").DataTable({
            ajax: {
                "url": "{{route('a.students.api.get')}}"
                , "dataSrc": "data"
            }
            , "columns": [{
                title: "<input type='checkbox' id='studentsCheckbox'>"
                , "data": null
                , orderable: false
                , "render": function (itemdata) {
                    return `<input type='checkbox' class="students-checkbox" data-id=${itemdata.id}>`
                }
            }
                , {
                title: "Nama Siswa"
                , "data": "name"
            }
                , {
                title: "Username"
                , "data": "username"
            }
                , {
                title: "Kelas"
                , "data": "class"
            }
                , {
                title: "Dibuat"
                , "data": "created_at"
            }
                , {
                'data': null
                , title: 'Action'
                , wrap: true
                , orderable: false
                , "render": function (item) {
                    return `<button type="button" class="btn btn-primary btn-sm student-details-trigger" data-id=${item.id} data-toggle="modal" data-target="#studentDetails"><i class="fa fa-info-circle" aria-hidden="true"></i> Details</button>`
                }
            }
                ,]
        })
        const loadingOverlay = $('#loadingOverlay')

        let selectedStudents = []
            , selectedStudentsId = null
            , selectedStudentsData

        $('table').on('click', '.student-details-trigger', function (e) {
            selectedStudentsId = $(this).data('id')
        })

        $('#studentsDelete').click(function () {
            selectedStudents = []
            $('.students-checkbox:checked').each(function (key, value) {
                selectedStudents.push(value.dataset.id)
            })
            if (selectedStudents.length > 0) {
                swal({
                    title: 'Apakah Anda yakin?'
                    , text: 'Saat data dihapus Anda masih bisa melihatnya pada recycle bin.'
                    , icon: 'warning'
                    , buttons: true
                    , dangerMode: true
                    , showCancelButton: true
                    , reverseButtons: true
                    ,
                })
                    .then((willDelete) => {
                        if (willDelete) {

                            $.ajax({
                                url: "{{route('a.students.api.delete')}}"
                                , type: 'delete'
                                , dataType: "JSON"
                                , data: {
                                    id: selectedStudents
                                }
                                , success: function (res) {
                                    let {
                                        status
                                    } = res
                                    if (status) {
                                        studentsDataTable.ajax.reload()
                                        iziToast.success({
                                            title: 'Berhasil!'
                                            , message: 'Data siswa berhasil dihapus.'
                                            , position: 'topRight'
                                        })
                                    }
                                }
                            })
                        }
                        return false
                    })
                return
            }
            return iziToast.error({
                title: 'Gagal'
                , message: 'Tidak ada data yang dipilih!.'
                , position: 'topRight'
            })

        })

        $('#studentsCheckbox').change(function () {
            const check = $(this).is(':checked')
            if (check) {
                $('.students-checkbox').prop('checked', true);
            } else {
                $('.students-checkbox').prop('checked', false);
            }
        })


        //modal

        //New
        $('#sNewForm').on('submit', function (e) {
            e.preventDefault()

            const data = {
                'student_name': ['#sNewName', e.target[0].value]
                , 'student_username': ['#sNewUsername', e.target[1].value]
                , 'student_email': ['#sNewEmail', e.target[2].value]
                , 'student_phone': ['#sNewPhone', () => {
                    return (e.target[3].inputmask.unmaskedvalue() != "" ? ("0" + e.target[3].inputmask.unmaskedvalue()) : null)
                }]
                , 'student_password': ['#sNewPassword', e.target[4].value]
                , 'student_password_conf': ['#sNewPasswordC', e.target[5].value]
                , 'student_nisn': ['#sNewNISN', e.target[6].value]
                , 'student_nis': ['#sNewNIS', e.target[7].value]
                , 'student_class': ['#sNewClass', e.target[8].value]
                , 'student_address': ['#sNewAddress', e.target[9].value]
                //, 'student_year': ['#sNewYear', e.target[10].value]
                //, 'student_nominal': ['#sNewNominal', () => {
                //return e.target[11].inputmask.unmaskedvalue()
                //}]
            }

            $.each(data, function (i, v) {
                $(v[0]).removeClass('is-invalid').next().remove()
            })

            $.ajax({
                url: `{{route('a.students.api.store')}}`
                , type: 'post'
                , data: {
                    'student_name': data.student_name[1]
                    , 'student_username': data.student_username[1]
                    , 'student_email': data.student_email[1]
                    , 'student_phone': data.student_phone[1]
                    , 'student_password': data.student_password[1]
                    , 'student_password_conf': data.student_password_conf[1]
                    , 'student_nisn': data.student_nisn[1]
                    , 'student_nis': data.student_nis[1]
                    , 'student_class': data.student_class[1]
                    , 'student_address': data.student_address[1]
                    //, 'student_year': data.student_year[1]
                    //, 'student_nominal': data.student_nominal[1]
                }
                , beforeSend: () => {
                    loadingOverlay.css("display", "flex").fadeIn('fast')
                }
                , success: function (res) {
                    loadingOverlay.fadeUut('fast')
                    const {
                        status
                        , length
                    } = JSON.parse(res)
                    if (status) {
                        studentsDataTable.ajax.reload()
                        $.each(data, function (i, v) {
                            $(v[0]).val('')
                        })
                        $('#studentAdd').modal('hide')
                        iziToast.success({
                            title: 'Berhasil!'
                            , message: 'Tambah data siswa berhasil dilakukan.'
                            , position: 'topRight'
                        })
                    }
                }
                , error: function (err, status, msg) {
                    $.each(err.responseJSON.errors, function (i, v) {
                        $(data[i][0]).addClass('is-invalid')
                        $(`<div class="invalid-feedback">${v}</div>`).insertAfter($(data[i][0]))
                    })
                }
            })
        })

        //Detail
        $('#studentDetails').on('show.bs.modal', function (e) {
            $.ajax({
                url: `/admin/students/api/get-details/${selectedStudentsId}`
                , beforeSend: () => {
                    loadingOverlay.css("display", "flex").fadeIn('fast')
                }
                , success: function (result) {
                    loadingOverlay.fadeOut('fast')
                    let {
                        data
                        , status
                        , length
                    } = JSON.parse(result)
                    selectedStudentsData = data[0]
                    if (status) {
                        $('#studentDetails .modal-dialog .modal-content').html(`
                        <div class="modal-header">
                            ${selectedStudentsData.role == 'admin' ? '<div class="btn btn-sm btn-danger">Administrator</div>' : ''}
                            ${selectedStudentsData.role == 'worker' ? '<div class="btn btn-sm btn-primary">Petugas</div>' : ''}
                            ${selectedStudentsData.role == 'student' ? '<div class="btn btn-sm btn-success">Siswa</div>' : ''}
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
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex py-2">
                                        <div class="icon mr-3">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="desc">
                                            <div class="font-weight-bold">Nama</div>
                                            <div>${selectedStudentsData.name}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex py-2">
                                        <div class="icon mr-3">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="desc">
                                            <div class="font-weight-bold">Email</div>
                                            <div>${selectedStudentsData.email}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex py-2">
                                        <div class="icon mr-3">
                                            <i class="fas fa-user-alt"></i>
                                        </div>
                                        <div class="desc">
                                            <div class="font-weight-bold">Username</div>
                                            <div>${selectedStudentsData.username}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex py-2">
                                        <div class="icon mr-3">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                        <div class="desc">
                                            <div class="font-weight-bold">Telfon</div>
                                            <div>${selectedStudentsData.phone}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-2">
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex py-2">
                                        <div class="icon mr-3">
                                            <i class="fas fa-address-card"></i>
                                        </div>
                                        <div class="desc">
                                            <div class="font-weight-bold">NISN</div>
                                            <div>${selectedStudentsData.nisn}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex py-2">
                                        <div class="icon mr-3">
                                            <i class="fas fa-address-card"></i>
                                        </div>
                                        <div class="desc">
                                            <div class="font-weight-bold">NIS</div>
                                            <div>${selectedStudentsData.nis}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex py-2">
                                <div class="icon mr-3">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div class="desc">
                                    <div class="font-weight-bold">Alamat</div>
                                    <div>${selectedStudentsData.address}</div>
                                </div>
                            </div>
                            <div class="d-flex py-2">
                                <div class="icon mr-3">
                                    <i class="fas fa-school"></i>
                                </div>
                                <div class="desc">
                                    <div class="font-weight-bold">Kelas</div>
                                    <div>${selectedStudentsData.class} - ${selectedStudentsData.class_name}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex py-2">
                                        <div class="icon mr-3">
                                            <i class="fas fa-credit-card"></i>
                                        </div>
                                        <div class="desc">
                                            <div class="font-weight-bold">Tahun SPP</div>
                                            <div>${selectedStudentsData.spp_year}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex py-2">
                                        <div class="icon mr-3">
                                            <i class="fas fa-dollar-sign"></i>
                                        </div>
                                        <div class="desc">
                                            <div class="font-weight-bold">Nominal SPP</div>
                                            <div>${selectedStudentsData.spp_nominal}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="#">Lihat detail transaksi.</a>
                            <hr class="mt-2">
                            <div class="d-flex py-2">
                                <div class="icon mr-3">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="desc">
                                    <div class="font-weight-bold">Dibuat</div>
                                    <div>${selectedStudentsData.created_at}</div>
                                </div>
                            </div>
                            <div class="d-flex py-2">
                                <div class="icon mr-3">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div class="desc">
                                    <div class="font-weight-bold">Diubah</div>
                                    <div>${selectedStudentsData.updated_at}</div>
                                </div>
                            </div>
                            <hr class="mt-2">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            </div>
                        </div>
                        `)
                        $('#studentDetails .modal-dialog .modal-content').slideDown('slow')
                    }
                }
            })
        })

        $('#studentDetails').on('hide.bs.modal', function (e) {
            $('#studentDetails .modal-dialog .modal-content').slideUp('slow')
            setTimeout(() => {
                $('#studentDetails .modal-dialog .modal-content').html('')
            }, 1000)
        })

        //action
        //update
        $('#studentDetails').on('click', '#modalEdit', function (e) {
            $('#studentDetails .modal-dialog .modal-content').html(`
                <div class="modal-header">
                    <h5>Edit Data</h5>
                    <div class="d-flex justify-content-center align-items-center">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <form method="post" id="studentDetailsForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="sDetName">Nama</label>
                                    <input type="text" class="form-control" id="sDetName" placeholder="" value="${selectedStudentsData.name}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="sDetUsername">Username</label>
                                    <input type="text" class="form-control" id="sDetUsername" placeholder="" value="${selectedStudentsData.username}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sDetEmail">Email</label>
                            <input type="email" class="form-control" id="sDetEmail" placeholder="" value="${selectedStudentsData.email}">
                        </div>
                        <div class="form-group">
                            <label for="sDetPhone">Telfon</label>
                            <input type="tel" class="form-control" id="sDetPhone" placeholder="" value="${selectedStudentsData.phone.replace(/^0/, '')}">
                        </div>
                        <div class="form-group">
                            <label for="sDetPassword">Password</label>
                            <input type="password" class="form-control" id="sDetPassword" placeholder="">
                            <small>Kosongkan password jika tidak dirubah.</small>
                        </div>
                        <div class="form-group">
                            <label for="sDetPasswordC">Confirm Password</label>
                            <input type="password" class="form-control" id="sDetPasswordC" placeholder="">
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="sDetNISN">NISN</label>
                                    <input type="text" class="form-control" value="${selectedStudentsData.nisn}" id="sDetNISN" placeholder="">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="sDetNIS">NIS</label>
                                    <input type="text" class="form-control" id="sDetNIS" value="${selectedStudentsData.nis}" placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sDetClass">Kelas</label>
                            <select class="form-control" name="" id="sDetClass">
                                @foreach($data as $cls)
                                <option value="{{$cls['class_id']}}">{{ $cls['class']
                                    }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sDetAddress">Alamat</label>
                            <textarea class="form-control" id="sDetAddress">${selectedStudentsData.address}</textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary ml-2"><i class="fa fa-paper-plane" aria-hidden="true"></i> Simpan</button>
                        </div>
                    </div>
                </form>
            `)

            $('#sDetPhone').inputmask("(+62) 999-9999-9999")
        })

        $('#studentDetails').on('submit', '#studentDetailsForm', function (e) {
            e.preventDefault()

            const data = {
                'student_name': ['#sDetName', e.target[0].value]
                , 'student_username': ['#sDetUsername', e.target[1].value]
                , 'student_email': ['#sDetEmail', e.target[2].value]
                , 'student_phone': ['#sDetPhone', () => {
                    return (e.target[3].inputmask.unmaskedvalue() != "" ? ("0" + e.target[3].inputmask.unmaskedvalue()) : null)
                }]
                , 'student_password': ['#sDetPassword', e.target[4].value]
                , 'student_password_conf': ['#sDetPasswordC', e.target[5].value]
                , 'student_nisn': ['#sDetNISN', e.target[6].value]
                , 'student_nis': ['#sDetNIS', e.target[7].value]
                , 'student_class': ['#sDetClass', e.target[8].value]
                , 'student_address': ['#sDetAddress', e.target[9].value]
            }

            $.each(data, function (i, v) {
                $(v[0]).removeClass('is-invalid').next().remove()
            })

            $.ajax({
                url: `/admin/students/api/update/${selectedStudentsId}`
                , type: 'PUT'
                , data: {
                    'student_name': data.student_name[1]
                    , 'student_username': data.student_username[1]
                    , 'student_email': data.student_email[1]
                    , 'student_phone': data.student_phone[1]
                    , 'student_password': data.student_password[1]
                    , 'student_password_conf': data.student_password_conf[1]
                    , 'student_nisn': data.student_nisn[1]
                    , 'student_nis': data.student_nis[1]
                    , 'student_class': data.student_class[1]
                    , 'student_address': data.student_address[1]
                    //, 'student_year': 0
                    //, 'student_nominal': 0
                }
                , beforeSend: () => {
                    loadingOverlay.css("display", "flex").fadeIn('fast')
                }
                , success: function (res) {
                    loadingOverlay.fadeOut('fast')
                    const {
                        data
                        , status
                        , length
                    } = JSON.parse(res)
                    if (status) {
                        studentsDataTable.ajax.reload()
                        iziToast.success({
                            title: 'Berhasil!'
                            , message: 'Data siswa berhasil dirubah.'
                            , position: 'topRight'
                        })
                    }
                    $('#studentDetails').modal('hide')
                }
                , error: function (err, status, msg) {
                    $.each(err.responseJSON.errors, function (i, v) {
                        $(data[i][0]).addClass('is-invalid')
                        $(`<div class="invalid-feedback">${v}</div>`).insertAfter($(data[i][0]))
                    })
                }
            })
        })

        //delete
        $('#studentDetails').on('click', '#modalDelete', function (e) {
            swal({
                title: 'Apakah Anda yakin?'
                , text: 'Saat data dihapus Anda masih bisa melihatnya pada recycle bin.'
                , icon: 'warning'
                , buttons: true
                , dangerMode: true
                , showCancelButton: true
                , reverseButtons: true
                ,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "{{route('a.students.api.delete')}}"
                            , type: 'delete'
                            , dataType: "JSON"
                            , data: {
                                id: selectedStudentsId
                            }
                            , success: function (result) {
                                let {
                                    data
                                    , status
                                    , length
                                } = result
                                if (status) {
                                    $('#studentDetails').modal('hide')
                                    studentsDataTable.ajax.reload()
                                    iziToast.success({
                                        title: 'Berhasil!'
                                        , message: 'Data siswa berhasil dihapus.'
                                        , position: 'topRight'
                                    })
                                }
                            }
                        })
                    }
                    return false
                })
        })
    })

</script>
@endsection