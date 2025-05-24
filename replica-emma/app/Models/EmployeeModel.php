<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeModel extends Model
{
    protected $table = 'employees';
    protected $primaryKey = 'id';
    protected $fillable = [
        'position_id',
        'employee_code',
        'full_name',
        'email',
        'phone',
        'position',
        'gender',
        'join_date',
        'status',
        'has_account',
        'created_at',
        'updated_at',
    ];

    public function position()
    {
        return $this->belongsTo(PositionModel::class, 'position_id', 'id');
    }
}
