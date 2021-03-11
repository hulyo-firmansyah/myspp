<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminModel extends Model
{
    use softDeletes;

    protected $table = 'petugas';
    protected $primaryKey = 'id_petugas';
    protected $fillable = [
        'nama_petugas'
    ];

    public function userWorker()
    {
        return $this->belongsTo(UserModel::class, 'data_of', 'id_user')
            ->where('role', 'worker');
    }

    public function userAdmin()
    {
        return $this->belongsTo(UserModel::class, 'data_of', 'id_user')
            ->where('role', 'admin');
    }
}
