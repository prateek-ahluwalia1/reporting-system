<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Locations;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $Location = Locations::all();

        return response()->json([
            'status' => true,
            'data'   => $Location,
        ]);
    }

    public function show($id)
    {
        $location = Locations::find($id);
        
        if (!$location) {
            return response()->json([
                'status' => false,
                'message' => 'Sales Representative not found'
            ], 404);
        }
        
        return response()->json([
            'status' => true,
            'data' => $location
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'costCentre'                      => 'required|string|max:255',
            'country'                         => 'required|string|max:255',
            'department'                      => 'required|string|max:255',
            'region'                          => 'required|string|max:255',
            'state'                           => 'required|string|max:255',
        ]);

        $location = Locations::create([
            'country'   => $request->country,
            'region'    => $request->region,
            'state'   => $request->state,
            'department'  => $request->department,
            'cost_centre'       => $request->costCentre,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Location created successfully',
            'data'    => $location,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $location = Locations::findOrFail($id);

        $request->validate([
            'costCentre'                      => 'required|string|max:255',
            'country'                         => 'required|string|max:255',
            'department'                      => 'required|string|max:255',
            'region'                          => 'required|string|max:255',
            'state'                           => 'required|string|max:255',
        ]);

        $location->update([
            'country'   => $request->country,
            'region'    => $request->region,
            'state'   => $request->state,
            'department'  => $request->department,
            'cost_centre'       => $request->costCentre,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Location updated successfully',
            'data'    => $location
        ]);
    }

    public function destroy($id)
    {
        $location = Locations::findOrFail($id);
        $location->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Location deleted successfully'
        ]);
    }
}