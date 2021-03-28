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

    public function worker()
    {
        return $this->belongsTo(AdminModel::class, 'id_petugas', 'id_petugas');
    }

    public function student()
    {
        return $this->belongsTo(StudentModel::class, 'id_siswa', 'id_siswa');
    }

    public function spp()
    {
        return $this->belongsTo(SppModel::class, 'id_spp', 'id_spp');
    }
}
