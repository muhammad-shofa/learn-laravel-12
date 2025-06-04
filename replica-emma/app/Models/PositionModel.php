<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PositionModel extends Model
{
    protected $table = 'positions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'position_name',
        'description',
        'hourly_rate',
        'overtime_multiplier',
        'standard_monthly_hours',
        'annual_salary_increase',
        'base_salary',
        'status',
        'created_at',
        'updated_at',
    ];

    
}
