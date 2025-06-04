<?php

namespace App\Http\Controllers;

use App\Models\PositionModel;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    // get all positions
    public function getPositions()
    {
        $positionsData = PositionModel::all();

        return response()->json([
            'success' => true,
            'message' => 'Positions retrieved successfully',
            'data' => $positionsData
        ]);
    }

    // get position by id
    public function getPosition($id)
    {
        $positionData = PositionModel::find($id);

        if (!$positionData) {
            return response()->json([
                'success' => false,
                'message' => 'Position not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Position retrieved successfully',
            'data' => $positionData
        ]);
    }

    // search position

    // search employee
    public function searchPositions(Request $request)
    {
        $search = $request->query('q');

        $employees = PositionModel::when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('position_name', 'like', "%{$search}%");
            });
        })
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $employees
        ]);
    }


    // add new position
    public function addPosition(Request $request)
    {
        $request->validate([
            'position_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        PositionModel::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Position created successfully',
        ]);
    }

    // update position by id
    public function updatePosition(Request $request, $id)
    {
        // $request->validate([
        //     'position_name' => 'required|string|max:255',
        //     'description' => 'nullable|string|max:255',
        // ]);

        $position = PositionModel::find($id);

        if (!$position) {
            return response()->json([
                'success' => false,
                'message' => 'Position not found',
            ], 404);
        }

        $position->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Position updated successfully',
        ]);
    }

    // delete position by id
    public function deletePosition($id)
    {
        $position = PositionModel::find($id);

        if (!$position) {
            return response()->json([
                'success' => false,
                'message' => 'Position not found',
            ], 404);
        }

        $position->delete();

        return response()->json([
            'success' => true,
            'message' => 'Position deleted successfully',
        ]);
    }
}
