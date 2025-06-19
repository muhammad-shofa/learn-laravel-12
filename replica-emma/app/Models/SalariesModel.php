<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalariesModel extends Model
{
    protected $table = 'salaries';

    protected $fillable = [
        'employee_id',
        'salary_setting_id',
        'year',
        'month',
        'hour_deduction',
        'absent_deduction',
        'deduction',
        'bonus',
        'total_salary',
        'payment_date',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeModel::class, 'employee_id');
    }

    public function salarySetting()
    {
        return $this->belongsTo(SalarySettingModel::class, 'salary_setting_id');
    }

    public function scopeFilterByEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }
}
