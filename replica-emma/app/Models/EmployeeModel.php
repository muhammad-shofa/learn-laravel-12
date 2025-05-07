<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeModel extends Model
{
    protected $table = 'employees'; 
    protected $primaryKey = 'id';
    protected $fillable = [
        'employee_code',
        'full_name',
        'email',
        'phone',
        'position',
        'gender',
        'join_date',
        'status',
        'created_at',
        'updated_at',
    ];
}
