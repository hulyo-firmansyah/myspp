<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Print NotaFDNRL53V</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- General CSS Files -->
    <link rel="stylesheet" href="/modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/modules/fontawesome/css/all.min.css">

    <!-- Template CSS -->
    {{--
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/components.css"> --}}
    <style>
        .no-print {
            display: none !important;
        }

        .borderless td,
        .borderless th {
            border: none;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body onload="window.print();">
    <div class="container-fluid">
        <div class="card card-body border-0 px-0">
            <div class="row">
                <div class="col-12">
                    <div class="h3 text-center">My SPP</div>
                </div>
            </div>
            <hr class="mb-0">
            <div class="float-right text-right">{{$paymentData->now_date}}</div>
            <div class="mt-2">
                <div class="h5 text-center">BUKTI PEMBAYARAN</div>
            </div>
            <div class="row mt-1">
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <table class="table borderless">
                                <tbody>
                                    <tr>
                                        <td class="font-weight-bold p-0">Kode Pembayaran</td>
                                        <td class="p-0">: {{$paymentData->payment_code}}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold p-0">Tanggal</td>
                                        <td class="p-0">: {{$paymentData->payment_date}}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold p-0">Kelas</td>
                                        <td class="p-0">: {{$paymentData->class}}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold p-0">NISN</td>
                                        <td class="p-0">: {{$paymentData->nisn}}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold p-0">Nama Siswa</td>
                                        <td class="p-0">: {{$paymentData->student}}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold p-0">Petugas Pembayaran</td>
                                        <td class="p-0">: {{$paymentData->officer}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-5">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th>Kelas</th>
                                    <th>Tahun Pelajaran</th>
                                    <th>Jumlah Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$paymentData->payment_month}}</td>
                                    <td>{{$paymentData->class}}</td>
                                    <td>{{$paymentData->payment_teaching_year}}</td>
                                    <td>{{$paymentData->payment_nominal_formatted}}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-center">Total</td>
                                    <td class="font-weight-bold">{{$paymentData->payment_nominal_formatted}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div>
                        Keterangan:
                        <div class="row">
                            <div class="col-6">
                                <div class="card mt-4">
                                    <div class="card-body">
                                        Bukti pembayaran ini adalah bukti pembayaran
                                        yang sah. Dikeluarkan oleh Aplikasi MySPP.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>