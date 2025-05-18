<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeOffModel extends Model
{
    protected $table = 'time_off_requests';
    protected $primaryKey = 'id';
    protected $fillable = [
        'employee_id',
        'request_date',
        'start_date',
        'end_date',
        'reason',
        'status',
        'created_at',
        'updated_at',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeModel::class, 'employee_id', 'id');
    }
}
