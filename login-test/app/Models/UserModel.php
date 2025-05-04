<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = "users";
    protected $primaryKey = "id";
    protected $fillable = [
        'name',
        'username',
        'password',
        'email',
        'gender',
        'role',
        'created_at',
        'updated_at'
    ];
}
