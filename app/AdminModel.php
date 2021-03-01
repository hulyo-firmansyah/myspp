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
}
