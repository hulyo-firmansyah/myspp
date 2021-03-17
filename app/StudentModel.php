<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentModel extends Model
{
    use SoftDeletes;
    
    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';
    protected $fillable = [
        'nisn',
        'nis',
        'nama',
        'id_kelas',
        'alamat',
        'no_telp',
        // 'id_spp',
        'data_of',
    ];

    public function users()
    {
        return $this->belongsTo(UserModel::class, 'data_of', 'id_user');
    }

    public function spps()
    {
        return $this->belongsTo(SppModel::class, 'id_spp', 'id_spp');
    }

    public function classes()
    {
        return $this->belongsTo(ClassModel::class, 'id_kelas', 'id_kelas');
    }
}
