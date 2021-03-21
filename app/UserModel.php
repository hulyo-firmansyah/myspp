<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserModel extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    protected $fillable = [
        'username', 
        'password',
        'email',
        'role',
        'data_of',
        'email_verified_at'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    public function workers()
    {
        return $this->hasOne(AdminModel::class, 'data_of', 'id_user');
    }

    public function officer()
    {
        return $this->hasOne(AdminModel::class, 'data_of', 'id_user');
    }

    public function student()
    {
        return $this->hasOne(StudentModel::class, 'data_of', 'id_user');
    }

    public function admin()
    {
        // return $this
    }
}
