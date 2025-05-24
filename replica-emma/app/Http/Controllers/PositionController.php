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

    // add new position
    public function addPosition(Request $request)
    {
        $request->validate([
            'position_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $position = PositionModel::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Position created successfully',
        ]);
    }
}
