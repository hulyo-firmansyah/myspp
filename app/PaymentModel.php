<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentModel extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';
    protected $fillable = [
        'kode_pembayaran',
        'id_petugas',
        'id_siswa',
        'tgl_bayar',
        'bulan_dibayar',
        'tahun_dibayar',
        'id_spp',
        'jumlah_bayar'
    ];
}
