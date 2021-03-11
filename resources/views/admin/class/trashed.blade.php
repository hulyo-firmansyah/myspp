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
        <a href="{{route('a.class.index')}}" class="btn btn-primary mr-3"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
        <h1>Keranjang Sampah(Kelas)</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="#">Modules</a></div>
            <div class="breadcrumb-item">DataTables</div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">Keranjang Sampah(Kelas)</h2>
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
                            <table class="table table-striped" id="classList">
                                <thead>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary btn-sm" id="classesRestore"><i class="fa fa-history" aria-hidden="true"></i> Restore</button>
                        <button class="btn btn-danger btn-sm" id="classesDelete"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
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
        const 
            newClassModal = $('#classAdd'),
            newClass = $('#clssNewForm'),
            clsTable = $('#classList'),
            clsRestore = $('#classesRestore'),
            clsDelete = $('#classesDelete')
        
        let selectedClassId = null,
            selectedClassData

        const classDataTable = $('#classList').DataTable({
            ajax: {
                "url" : "{{route('a.class.api.get.trash')}}",
                "dataSrc" : "data"
            },
            "columns": [
                { title : "<input type='checkbox' id='classesCheckbox'>", "data": null, orderable: false, "render" : 
                function(itemdata){
                    return `<input type='checkbox' class="class-checkbox" data-id=${itemdata.id}>`
                }},
                { title: "Nama Kelas", "data" : "class_name" },
                { title: "Kelas", "data" : "steps" },
                { title: "Kompetensi Keahlian", "data" : "competence" },
                { title: "Murid", "data" : "student_count" },
                { 'data': null, title: 'Action', wrap: true, orderable: false, "render": 
                function (item) { 
                    return `
                        <button type="button" class="btn btn-primary btn-sm classRestore" title="Restore" data-id=${item.id}><i class="fa fa-history" aria-hidden="true" title="Restore"></i></button>
                        <button type="button" class="btn btn-danger btn-sm classPermDelete" title="Permanent Delete" data-id=${item.id}><i class="fa fa-trash" aria-hidden="true" title="Delete Permanent"></i></button>
                    `
                }}
            ]
        })

        $('#classesCheckbox').change(function(e){
            const check = $(this).is(':checked')
            if(check){
                $('.class-checkbox').prop('checked', true);
            }else{
                $('.class-checkbox').prop('checked', false);
            }
        })

        //Worker Restore
        $('#classList').on('click', '.classRestore', function(e){
            const id = $(this).data('id')
            $.ajax({
                url: "{{route('a.class.api.restore')}}",
                type: 'PUT',
                data: {
                    'id': id
                },
                success : function(res){
                    let {data,status,lenght} = JSON.parse(res)
                    if(status){
                        classDataTable.ajax.reload()
                    }
                }
            })
        })
        //Worker Permanent Delete
        $('#classList').on('click', '.classPermDelete', function(e){
            const id = $(this).data('id')
            $.ajax({
                url: "{{route('a.class.api.force-delete')}}",
                type: 'delete',
                data: {
                    'id': id
                },
                success : function(res){
                    let {data,status,lenght} = JSON.parse(res)
                    if(status){
                        classDataTable.ajax.reload()
                    }
                }
            })
        })

        //Mass action
            //Restore
            clsRestore.on('click', function(){
                let selectedClasses = []

                $('.class-checkbox:checked').each(function(key, value){
                    selectedClasses.push(value.dataset.id)
                })

                if(selectedClasses.length > 0){
                    $.ajax({
                        url: "{{route('a.class.api.restore')}}",
                        type: 'PUT',
                        data: {
                            'id': selectedClasses
                        },
                        success : function(res){
                            let {data,status,lenght} = JSON.parse(res)
                            if(status){
                                classDataTable.ajax.reload()
                            }
                        }
                    })
                }
            })
            //Delete
            clsDelete.on('click', function(){
                let selectedClasses = []

                $('.class-checkbox:checked').each(function(key, value){
                    selectedClasses.push(value.dataset.id)
                })

                if(selectedClasses.length > 0){
                    $.ajax({
                        url: "{{route('a.class.api.force-delete')}}",
                        type: 'DELETE',
                        data: {
                            'id': selectedClasses
                        },
                        success : function(res){
                            let {data,status,lenght} = JSON.parse(res)
                            if(status){
                                classDataTable.ajax.reload()
                            }
                        }
                    })
                }
            })
    })
</script>
@endsection