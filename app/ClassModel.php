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
        'tingkatan',
        'kompetensi_keahlian'
    ];

    public function students()
    {
        return $this->hasMany(StudentModel::class, 'id_kelas', 'id_kelas');
    }

    public function studentUser()
    {
        //kelas //mechanic
        //student //car // id_kelas
        //user //owner
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
