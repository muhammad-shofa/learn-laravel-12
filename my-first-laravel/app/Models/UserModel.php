<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'remember_token',
    ];

    // public function getAuthPassword()
    // {
    //     return $this->password;
    // }
}
