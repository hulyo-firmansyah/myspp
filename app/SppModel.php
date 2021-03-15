<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SppModel extends Model
{
    use softDeletes;

    protected $table = 'spp';
    protected $primaryKey = 'id_spp';
    protected $fillable = [
        'tahun',
        'nominal',
    ];

    public function students()
    {
        return $this->belongsTo(StudentModel::class, 'id_spp', 'id_spp');
    }
}
