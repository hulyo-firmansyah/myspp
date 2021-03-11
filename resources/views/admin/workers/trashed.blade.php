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
        <a href="{{route('a.workers.index')}}" class="btn btn-primary mr-3"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
        <h1>Keranjang Sampah(Petugas)</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="#">Modules</a></div>
            <div class="breadcrumb-item">DataTables</div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">Keranjang Sampah(Petugas)</h2>
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
                            <table class="table table-striped" id="workerList">
                                <thead>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary btn-sm" id="workersRestore"><i class="fa fa-history" aria-hidden="true"></i> Restore</button>
                        <button class="btn btn-danger btn-sm" id="workersDelete"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
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
@section('js_custom')
<script>
    $(document).ready(function(){
        const wCheckBox = $('#workersCheckbox'),
              wTable = $('#workerList'),
              wsDelete = $('#workersDelete'),
              wsRestore = $('#workersRestore')

        const workertable = wTable.DataTable({
            ajax: {
                "url" : "{{route('a.workers.api.get.trash')}}",
                "dataSrc" : "data"
            },
            "columns": [
                {
                    title : "<input type='checkbox' id='workersCheckbox'>",
                    "data": null,
                    orderable: false,
                    "render" : function(itemdata){
                        return `<input type='checkbox' class="workers-checkbox" data-id=${itemdata.id}>`
                    }
                },
                { 
                   title : "Nama Petugas",
                    "data": "name" 
                },
                { 
                    title : "Username",
                    "data": "username"
                },
                { 
                    title : "Email",
                    "data": "email" 
                },
                { 
                    title : "Dihapus",
                    "data": "deleted_at"
                },
                { 
                    'data': null, 
                    title: 'Action',
                    wrap: true,
                    orderable: false,
                    "render": function (item) 
                    {
                        return `
                            <button type="button" class="btn btn-primary btn-sm workerRestore" data-id=${item.id}><i class="fa fa-history" aria-hidden="true" title="Restore"></i></button>
                            <button type="button" class="btn btn-danger btn-sm workerPermDelete" data-id=${item.id}><i class="fa fa-trash" aria-hidden="true" title="Delete Permanent"></i></button>
                        `
                    }
                },
            ],
        })

        wCheckBox.change(function(){
            const check = $(this).is(':checked')
            if(check){
                $('.workers-checkbox').prop('checked', true);
            }else{
                $('.workers-checkbox').prop('checked', false);
            }
        })

        //Worker Restore
        wTable.on('click', '.workerRestore', function(e){
            const id = $(this).data('id')
            $.ajax({
                url: "{{route('a.workers.api.restore')}}",
                type: 'PUT',
                data: {
                    'id': id
                },
                success : function(res){
                    let {data,status,lenght} = JSON.parse(res)
                    if(status){
                        workertable.ajax.reload()
                    }
                }
            })
        })
        //Worker Permanent Delete
        wTable.on('click', '.workerPermDelete', function(e){
            const id = $(this).data('id')
            $.ajax({
                url: "{{route('a.workers.api.force-delete')}}",
                type: 'delete',
                data: {
                    'id': id
                },
                success : function(res){
                    let {data,status,lenght} = JSON.parse(res)
                    if(status){
                        workertable.ajax.reload()
                    }
                }
            })
        })

        //Mass action
        //Restore
        wsDelete.on('click', function(){
            const id = $(this).data('id')
            $.ajax({
                url: "{{route('a.workers.api.restore')}}",
                type: 'PUT',
                data: {
                    'id': id
                },
                success : function(res){
                    let {data,status,lenght} = JSON.parse(res)
                    if(status){
                        workertable.ajax.reload()
                    }
                }
            })
        })
        //Delete
        wsRestore.on('click', function(){
            const id = $(this).data('id')
            $.ajax({
                url: "{{route('a.workers.api.force-delete')}}",
                type: 'delete',
                data: {
                    'id': id
                },
                success : function(res){
                    let {data,status,lenght} = JSON.parse(res)
                    if(status){
                        workertable.ajax.reload()
                    }
                }
            })
        })
    })
</script>
@endsection