<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionCartModel extends Model
{
    protected $table = 'keranjang_transaksi';
    protected $primaryKey = 'id_keranjang';
    protected $fillable = [
        'id_spp',
        'id_siswa',
        'jumlah_bayar',
        'bulan_dibayar'
    ];

    public function student()
    {
        return $this->belongsTo(StudentModel::class, 'id_siswa', 'id_siswa');
    }

    public function officer()
    {
        return $this->belongsTo(AdminModel::class, 'id_petugas', 'id_petugas');
    }

    public function spp()
    {
        return $this->belongsTo(SppModel::class, 'id_spp', 'id_spp');
    }
}
