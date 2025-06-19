<?php

namespace App\Http\Controllers;

use App\Models\WeeklyHolidayModel;
use Illuminate\Http\Request;

class WeeklyHolidayController extends Controller
{
    // Ambil data terbaru dari weekly
    public function get()
    {
        $setting = WeeklyHolidayModel::latest()->first();
        return response()->json($setting);
    }

    public function save(Request $request)
    {
        $request->validate([
            'max_holidays_per_week' => 'required|integer|min:1|max:7',
            'days' => 'required|array|min:1|max:7',
            'days.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
        ]);

        WeeklyHolidayModel::create([
            'max_holidays_per_week' => $request->max_holidays_per_week,
            'days' => $request->days,
        ]);

        return response()->json(['success' => true]);
    }
}
