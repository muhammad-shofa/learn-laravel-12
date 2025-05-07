<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
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
        'created_at',
        'updated_at',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeModel::class, 'employee_id', 'id');
    }
}
