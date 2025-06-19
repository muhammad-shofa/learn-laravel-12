<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyHolidayModel extends Model
{
    protected $table = 'weekly_holidays';
    protected $primaryKey = 'id';
    protected $fillable = [
        'max_holidays_per_week',
        'days',
        'created_at',
        'updated_at',
    ];
}
