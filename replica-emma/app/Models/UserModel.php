<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserModel extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'employee_id',
        'username',
        'password',
        'role',
        'try_login',
        'status_login',
        'is_login',
        'created_at',
        'updated_at',
        'cooldown_until'
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeModel::class, 'employee_id', 'id');
    }
}
