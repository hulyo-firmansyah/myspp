@extends('_partials.master')
@section('css_lib')
<link rel="stylesheet" href="/modules/datatables/datatables.min.css">
<link rel="stylesheet" href="/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css">
<link rel="stylesheet" href="/modules/izitoast/css/iziToast.min.css">
@endsection
@section('css_custom')
<style>
    #dataTablesTopContainer {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
    }
</style>
@endsection
@section('content')
<div class="section-header">
    <h1>Dashboard Siswa</h1>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="hero bg-primary text-white">
            <div class="hero-inner">
                <h2>Selamat Datang, {{ $userData->name }}!</h2>
                <p class="lead">Ayooo bayar!!!. Masih banyak yang perlu dibayar.</p>
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
@endsection