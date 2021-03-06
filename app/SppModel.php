<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SppModel extends Model
{
    // use softDeletes;

    protected $table = 'spp';
    protected $primaryKey = 'id_spp';
    protected $fillable = [
        'tahun',
        'nominal',
        'periode',
        'tingkat'
    ];

    public function step()
    {
        return $this->belongsTo(StepsModel::class, 'id_tingkatan', 'id_tingkatan');
    }

    public function payments()
    {
        return $this->hasMany(PaymentModel::class, 'id_spp', 'id_spp');
    }
}
