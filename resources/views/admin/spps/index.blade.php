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
    <h1>SPP</h1>
    <div class="section-header-button">
        <button class="btn btn-primary" data-target="#sppAdd" data-toggle="modal"><i class="fa fa-plus" aria-hidden="true"></i> Tambah</button>
        {{-- <a href="{{route('a.spps.trash')}}" class="btn btn-danger ml-2" title="Recycle Bin"><i class="fa fa-trash" aria-hidden="true"></i></a> --}}
    </div>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="#">Modules</a></div>
        <div class="breadcrumb-item">DataTables</div>
    </div>
</div>
<div class="section-body">
    <h2 class="section-title">SPP</h2>
    <p class="section-lead">
        Anda dapat melakuakan tambah, edit, ubah, atau hapus pada data SPP.
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

<script src="/modules/input-mask/jquery.inputmask.bundle.min.js"></script>
<script src="/modules/sweetalert/sweetalert.min.js"></script>
<script src="/modules/izitoast/js/iziToast.min.js"></script>
@endsection
@section('js_page')

@endsection
@section('js_custom')

<div class="modal fade" id="sppAdd" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Tambah Pembayaran SPP</h5>
            </div>
            <div class="modal-body">
                <form method="post" action="/admin/spp/add" id="sppNewForm">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="sppNewPeriode">Periode</label>
                                <input type="number" class="form-control" id="sppNewPeriode" placeholder="" value="">
                                <small>*Periode pembayaran per tahun.</small>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="form-group">
                                <label for="sppNewSteps">Tingkat</label>
                                <select name="" id="sppNewSteps" class="form-control">
                                    <option value="10">X</option>
                                    <option value="11">XI</option>
                                    <option value="12">XII</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="sppNewYear">Tahun</label>
                                <input type="text" class="form-control" id="sppNewYear" placeholder="" value="">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="sppNewNominal">Nominal</label>
                                <input type="text" class="form-control" id="sppNewNominal" placeholder="" value="">
                                <small>Nominal akan dikalikan sejumlah periode.</small>
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

<div class="modal fade" id="sppDetails" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        const toastSuccessDelete = () => {
            return iziToast.success({
                title: 'Berhasil!'
                , message: 'Data SPP berhasil dihapus.'
                , position: 'topRight'
            })
        }
        const toastSuccessEdit = () => {
            return iziToast.success({
                title: 'Berhasil!'
                , message: 'Data SPP berhasil dirubah.'
                , position: 'topRight'
            })
        }
        const toastSuccessAdd = () => {
            return iziToast.success({
                title: 'Berhasil!'
                , message: 'Tambah data SPP berhasil dilakukan.'
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

        const sppsDataTable = $("#sppsList").DataTable({
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
                    title: "Tahun"
                    , "data": "year"
                }
                , {
                    title: "Nominal"
                    , "data": "nominal_formatted"
                }
                , {
                    title: "Tingkat"
                    , "data": "step"
                }
                , {
                    title: "Periode"
                    , "data": "periode"
                }
                , {
                    'data': null
                    , title: 'Action'
                    , wrap: true
                    , orderable: false
                    , "render": function(item) {
                        return `<button type="button" class="btn btn-primary btn-sm spp-details-trigger" data-id=${item.id} data-toggle="modal" data-target="#sppDetails"><i class="fa fa-info-circle" aria-hidden="true"></i> Details</button>`
                    }
                }
            , ]
        , })

        let selectedSpps = []
            , selectedSppsId = null
            , selectedSppsData

        $('#sppNewNominal').inputmask({
            'alias': 'decimal'
            , 'groupSeparator': ','
            , 'autoGroup': true
            , 'prefix': 'Rp. '
            , 'rightAlign': false
        })

        $('#sppAdd').on('submit', '#sppNewForm', function(e) {
            e.preventDefault()

            const data = {
                'periode': ['#sppNewPeriode', e.target[0].value]
                , 'steps': ['#sppNewSteps', e.target[1].value]
                , 'year': ['#sppNewYear', e.target[2].value]
                , 'nominal': ['#sppNewNominal', e.target[3].inputmask.unmaskedvalue()]
            }

            $.each(data, function(i, v) {
                $(v[0]).removeClass('is-invalid').next().remove()
            })

            $.ajax({
                url: `{{route('a.spps.api.store')}}`
                , type: 'post'
                , data: {
                    'periode': data.periode[1]
                    , 'steps': data.steps[1]
                    , 'year': data.year[1]
                    , 'nominal': data.nominal[1]
                }
                , beforeSend: () => {
                    loadingOverlay.css("display", "flex").fadeIn('fast')
                }
                , success: function(res) {
                    const {
                        status
                        , length
                    } = JSON.parse(res)
                    if (status) {
                        loadingOverlay.fadeOut('fast')
                        $('#sppAdd').modal('hide')
                        sppsDataTable.ajax.reload()
                        $.each(data, function(i, v) {
                            $(v[0]).val('')
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
                    $('#sppAdd').modal('hide')
                    return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                }
            })
        })

        $('table').on('click', '.spp-details-trigger', function(e) {
            selectedSppsId = $(this).data('id')
        })


        //modal
        //Detail
        $('#sppDetails').on('show.bs.modal', function(e) {
            $.ajax({
                url: `/admin/spps/api/get-details/${selectedSppsId}`
                , beforeSend: () => {
                    loadingOverlay.css("display", "flex").fadeIn('fast')
                }
                , success: function(res) {
                    loadingOverlay.fadeOut('fast')
                    let {
                        data
                        , status
                        , length
                    } = JSON.parse(res)
                    selectedSppsData = data[0]
                    $('#sppDetails .modal-dialog .modal-content').html(`
                        <div class="modal-header">
                            <h5>Detail SPP</h5>
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
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex py-2">
                                        <div class="icon mr-3">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                        <div class="desc">
                                            <div class="font-weight-bold">Tahun</div>
                                            <div>${selectedSppsData.year}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex py-2">
                                        <div class="icon mr-3">
                                            <i class="fas fa-angle-double-up"></i>
                                        </div>
                                        <div class="desc">
                                            <div class="font-weight-bold">Tingkat</div>
                                            <div>${selectedSppsData.step}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex py-2">
                                <div class="icon mr-3">
                                    <i class="fas fa-globe-asia"></i>
                                </div>
                                <div class="desc">
                                    <div class="font-weight-bold">Periode Pembayaran</div>
                                    <div>${selectedSppsData.periode} Kali</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex py-2">
                                        <div class="icon mr-3">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </div>
                                        <div class="desc">
                                            <div class="font-weight-bold">Biaya Per Periode</div>
                                            <div>${selectedSppsData.nominal_per_steps_formatted}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex py-2">
                                        <div class="icon mr-3">
                                            <i class="fas fa-dollar-sign"></i>
                                        </div>
                                        <div class="desc">
                                            <div class="font-weight-bold">Total Yang Harus Dibayar</div>
                                            <div>${selectedSppsData.nominal_formatted}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3"></div>
                            <div class="d-flex py-2">
                                <div class="icon mr-3">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div class="desc">
                                    <div class="font-weight-bold">Dibuat</div>
                                    <div>${selectedSppsData.updated_at}</div>
                                </div>
                            </div>
                            <div class="d-flex py-2">
                                <div class="icon mr-3">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div class="desc">
                                    <div class="font-weight-bold">Diubah</div>
                                    <div>${selectedSppsData.updated_at}</div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            </div>
                        </div>
                    `)
                }
                , error: function(err, status, msg) {
                    loadingOverlay.fadeOut('fast')
                    $('#sppDetails').modal('hide')
                    return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                }
            })
        })

        $('#sppDetails').on('hide.bs.modal', function(e) {
            $('#sppDetails .modal-dialog .modal-content').html('')
        })

        //action
        //update
        $('#sppDetails').on('click', '#modalEdit', function(e) {
            $('#sppDetails .modal-dialog .modal-content').html(` 
                <div class="modal-header">
                    <h5>Tambah Pembayaran SPP</h5>
                </div>
                <div class="modal-body">
                    <form method="post" id="sppDetailsForm">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="sppDetPeriode">Periode</label>
                                    <input type="number" class="form-control" id="sppDetPeriode" placeholder="" value="${selectedSppsData.periode}">
                                    <small>*Periode pembayaran per tahun.</small>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="sppDetSteps">Tingkat</label>
                                    <select name="" id="sppDetSteps" class="form-control">
                                        <option value="10" ${selectedSppsData.step == 'X' ? 'selected' : null}>X</option>
                                        <option value="11" ${selectedSppsData.step == 'XI' ? 'selected' : null}>XI</option>
                                        <option value="12" ${selectedSppsData.step == 'XII' ? 'selected' : null}>XII</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="sppDetYear">Tahun</label>
                                    <input type="text" class="form-control" id="sppDetYear" placeholder="" value="${selectedSppsData.year}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="sppDetNominal">Nominal</label>
                                    <input type="text" class="form-control" id="sppDetNominal" placeholder="" value="${selectedSppsData.nominal_per_steps}">
                                    <small>Nominal akan dikalikan sejumlah periode.</small>
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
            `)

            $('#sppDetNominal').inputmask({
                'alias': 'decimal'
                , 'groupSeparator': ','
                , 'autoGroup': true
                , 'prefix': 'Rp. '
                , 'rightAlign': false
            })
        })
        $('#sppDetails').on('submit', '#sppDetailsForm', function(e) {
            e.preventDefault()

            const data = {
                'periode': ['#sppDetPeriode', e.target[0].value]
                , 'steps': ['#sppDetSteps', e.target[1].value]
                , 'year': ['#sppDetYear', e.target[2].value]
                , 'nominal': ['#sppDetNominal', e.target[3].inputmask.unmaskedvalue()]
            }

            $.each(data, function(i, v) {
                $(v[0]).removeClass('is-invalid').next().remove()
            })

            $.ajax({
                url: `/admin/spps/api/update/${selectedSppsId}`
                , type: 'PUT'
                , data: {
                    'periode': data.periode[1]
                    , 'steps': data.steps[1]
                    , 'year': data.year[1]
                    , 'nominal': data.nominal[1]
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
                        sppsDataTable.ajax.reload()
                        $('#sppDetails').modal('hide')
                        return toastSuccessEdit()
                    }
                }
                , error: function(err, status, msg) {
                    if (err.status === 422) {
                        $.each(err.responseJSON.errors, function(i, v) {
                            $(data[i][0]).addClass('is-invalid')
                            $(`<div class="invalid-feedback">${v}</div>`).insertAfter($(data[i][0]))
                        })
                        return true
                    }
                    $('#sppDetails').modal('hide')
                    return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                }
            })
        })

        //delete
        $('#sppDetails').on('click', '#modalDelete', function(e) {
            swal({
                    title: 'Apakah Anda yakin?'
                    , text: 'Terdapat Transaksi pada SPP ini. Jika Anda menghapus data spp maka data transaksi juga akan terhapus dan tidak akan bisa dikembalikan.'
                    , icon: 'warning'
                    , buttons: true
                    , dangerMode: true
                    , showCancelButton: true
                    , reverseButtons: true
                , })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "{{route('a.spps.api.delete')}}"
                            , type: 'delete'
                            , dataType: "JSON"
                            , data: {
                                id: selectedSppsId
                            }
                            , beforeSend: () => {
                                loadingOverlay.css("display", "flex").fadeIn('fast')
                            }
                            , success: function(result) {
                                loadingOverlay.fadeOut('fast')
                                let {
                                    data
                                    , status
                                    , length
                                } = result
                                if (status) {
                                    $('#sppDetails').modal('hide')
                                    sppsDataTable.ajax.reload()
                                    return toastSuccessDelete()
                                }
                            }
                            , error: function(err, status, msg) {
                                loadingOverlay.fadeOut('fast')
                                $('#sppDetails').modal('hide')
                                return swal(`${status.toUpperCase()} ${err.status}`, msg, 'error')
                            }
                        })
                    }
                    return false
                })
        })
    })

</script>
@endsection
