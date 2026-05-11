<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SalesRepresentative;
use Illuminate\Http\Request;

class SalesRepresentativeController extends Controller
{
    public function index(Request $request)
    {
        $salesRepresentatives = SalesRepresentative::all();

        return response()->json([
            'status' => true,
            'data'   => $salesRepresentatives,
        ]);
    }

    public function show($id)
    {
        $salesRepresentative = SalesRepresentative::find($id);
        
        if (!$salesRepresentative) {
            return response()->json([
                'status' => false,
                'message' => 'Sales Representative not found'
            ], 404);
        }
        
        return response()->json([
            'status' => true,
            'data' => $salesRepresentative
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'repId'                            => 'required|string|max:255|unique:sales_representatives,rep_id',
            'lastName'                         => 'required|string|max:255',
            'firstName'                        => 'required|string|max:255',
            'department'                       => 'required|string|max:255',
            'costCentre'                       => 'required|string|max:255',
        ]);

        $salesRepresentative = SalesRepresentative::create([
            'first_name'   => $request->firstName,
            'last_name'    => $request->lastName,
            'department'   => $request->department,
            'cost_centre'  => $request->costCentre,
            'rep_id'       => $request->repId,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Sales Representative created successfully',
            'data'    => $salesRepresentative,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $salesRepresentative = SalesRepresentative::findOrFail($id);

        $request->validate([
            'repId'                            => 'required|string|max:255|unique:sales_representatives,rep_id,' . $id,
            'lastName'                         => 'required|string|max:255',
            'firstName'                        => 'required|string|max:255',
            'department'                       => 'required|string|max:255',
            'costCentre'                       => 'required|string|max:255',
        ]);

        $salesRepresentative->update([
            'first_name'   => $request->firstName,
            'last_name'    => $request->lastName,
            'department'   => $request->department,
            'rep_id'       => $request->repId,
            'cost_centre'  => $request->costCentre,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Sales Representative updated successfully',
            'data'    => $salesRepresentative
        ]);
    }

    public function destroy($id)
    {
        $salesRepresentative = SalesRepresentative::findOrFail($id);
        $salesRepresentative->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Sales Representative deleted successfully'
        ]);
    }
}