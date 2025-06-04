<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalarySettingModel extends Model
{
    protected $table = 'salary_settings';
    protected $fillable = [
        'employee_id',
        'position_id',
        'default_salary',
        'effective_date',
        'created_at',
        'updated_at'
    ];
    
    public function employee()
    {
        return $this->belongsTo(EmployeeModel::class, 'employee_id');
    }

    public function position()
    {
        return $this->belongsTo(PositionModel::class, 'position_id');
    }
}
