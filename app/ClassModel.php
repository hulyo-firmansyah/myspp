<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassModel extends Model
{
    use softDeletes;

    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';
    protected $fillable = [
        'nama_kelas',
        'id_tingkatan',
        'id_kompetensi_keahlian'
    ];

    public function students()
    {
        return $this->hasMany(StudentModel::class, 'id_kelas', 'id_kelas');
    }

    public function competence()
    {
        return $this->belongsTo(CompetenceModel::class, 'id_kompetensi_keahlian', 'id_kompetensi_keahlian');
    }

    public function step()
    {
        return $this->belongsTo(StepsModel::class, 'id_tingkatan', 'id_tingkatan');
    }

    public function studentUser()
    {
        return $this->hasManyThrough(
            UserModel::class,
            StudentModel::class, 
            'id_kelas',
            'id_user',
            'id_kelas',
            'id_siswa',
        );
    }

}
