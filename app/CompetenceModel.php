<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompetenceModel extends Model
{
    use softDeletes;

    protected $table = 'kompetensi_keahlian';
    protected $primaryKey = 'id_kompetensi_keahlian';
    protected $fillable = [
        'kompetensi_keahlian'
    ];

    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'id_kompetensi_keahlian', 'id_kompetensi_keahlian');
    }
}
