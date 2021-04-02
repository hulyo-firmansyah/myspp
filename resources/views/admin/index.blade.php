@extends('_partials.master')
@section('css_lib')
<link rel="stylesheet" href="/modules/jqvmap/dist/jqvmap.min.css">
<link rel="stylesheet" href="/modules/weather-icon/css/weather-icons.min.css">
<link rel="stylesheet" href="/modules/weather-icon/css/weather-icons-wind.min.css">
<link rel="stylesheet" href="/modules/summernote/summernote-bs4.css">
@endsection
@section('content')
<div class="section-header">
    <h1>Dashboard Admin</h1>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="hero text-white hero-bg-image hero-bg-parallax"
                    style="background-image: url('/img/unsplash/andre-benz-1214056-unsplash.jpg');">
                    <div class="hero-inner">
                        <h2>Selamat Datang, {{ $userData->name }}!</h2>
                        <p class="lead">Banyak sesuatu yang harus dilakukan hari ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
                <i class="far fa-user"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Petugas</h4>
                </div>
                <div class="card-body">
                    {{$pageData->workerTotal}}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-danger">
                <i class="far fa-graduation-cap"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Murid</h4>
                </div>
                <div class="card-body">
                    {{$pageData->studentTotal}}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
                <i class="far fa-chalkboard-teacher"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Kelas</h4>
                </div>
                <div class="card-body">
                    {{$pageData->classTotal}}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-success">
                <i class="far fa-credit-card"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Transaksi</h4>
                </div>
                <div class="card-body">
                    {{$pageData->transactionTotal}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js_lib')
<script src="/modules/simple-weather/jquery.simpleWeather.min.js"></script>
<script src="/modules/chart.min.js"></script>
<script src="/modules/jqvmap/dist/jquery.vmap.min.js"></script>
<script src="/modules/jqvmap/dist/maps/jquery.vmap.world.js"></script>
<script src="/modules/summernote/summernote-bs4.js"></script>
<script src="/modules/chocolat/dist/js/jquery.chocolat.min.js"></script>
@endsection
@section('js_page')
<script src="/js/page/index-0.js"></script>
@endsection