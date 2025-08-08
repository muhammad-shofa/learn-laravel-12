<?php

namespace App\Http\Controllers;

use App\Models\SalarySettingModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalarySettingController extends Controller
{
    // get all salary settings data
    public function getSalarySettings()
    {
        $salarySettingsRaw = SalarySettingModel::with('employee', 'position')->get();

        $salarySettings = $salarySettingsRaw->map(function ($item) {
            return [
                'id' => $item->id,
                'employee_id' => $item->employee_id,
                'position_id' => $item->position_id,
                'default_salary' => $item->default_salary,
                'effective_date' => Carbon::parse($item->effective_date)->format('d-m-Y'),
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'employee' => $item->employee,
                'position' => $item->position
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Salary settings retrieved successfully',
            'data' => $salarySettings,
        ], 200);
    }

    // public function getSalarySettings()
    // {

    //     $salarySettings = SalarySettingModel::with('employee', 'position')->get();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Salary settings retrieved successfully',
    //         'data' => $salarySettings,
    //     ], 200);
    // }

    // get salary setting by id
    public function getSalarySetting($salary_setting_id)
    {
        $salarySetting = SalarySettingModel::with('employee', 'position')->findOrFail($salary_setting_id);

        return response()->json([
            'success' => true,
            'message' => 'Salary setting retrieved successfully',
            'data' => $salarySetting,
        ], 200);
    }

    // add new salary setting
    public function addSalarySetting(Request $request)
    {
        SalarySettingModel::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Salary setting added successfully',
            // 'data' => $salarySetting,
        ], 201);
    }

    // update salary setting
    public function updateSalarySetting(Request $request, $salary_setting_id)
    {
        $salarySetting = SalarySettingModel::findOrFail($salary_setting_id);
        $salarySetting->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Salary setting updated successfully',
        ], 200);
    }

    // delete salary setting
    public function deleteSalarySetting($salary_setting_id)
    {
        $salarySetting = SalarySettingModel::findOrFail($salary_setting_id);
        $salarySetting->delete();

        return response()->json([
            'success' => true,
            'message' => 'Salary setting deleted successfully',
        ], 200);
    }
}
