<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SecurityManagement;
use Illuminate\Http\Request;

class SecurityManagementController extends Controller
{
    public function index(Request $request)
    {
        $securityManagements = SecurityManagement::all();

        return response()->json([
            'status' => true,
            'data'   => $securityManagements,
        ]);
    }

    public function show($id)
    {
        $securityManagement = SecurityManagement::find($id);
        
        if (!$securityManagement) {
            return response()->json([
                'status' => false,
                'message' => 'Security Management record not found'
            ], 404);
        }
        
        return response()->json([
            'status' => true,
            'data' => $securityManagement
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'costCentres'              => 'nullable|array',
            'countries'                => 'nullable|array',
            'customerGroups'           => 'nullable|array',
            'mode'                     => 'nullable|string|max:255',
            'name'                     => 'nullable|string|max:255',
            'departments'              => 'nullable|array',
            'email'                    => 'nullable|email|max:255',
            'menuAccess'               => 'nullable|array',
            'menuSecurityEnabled'      => 'nullable|boolean',
            'productGroups'            => 'nullable|array',
            'regions'                  => 'nullable|array',
            'reportAccess'             => 'nullable|array',
            'reportSecurityEnabled'    => 'nullable|boolean',
            'salesReps'                => 'nullable|array',
            'states'                   => 'nullable|array',
            'overrides'                => 'nullable|array',
        ]);

        $securityManagement = SecurityManagement::create([
            'costCentres'            => $request->costCentres,
            'countries'              => $request->countries,
            'customerGroups'         => $request->customerGroups,
            'mode'                   => $request->mode,
            'name'                   => $request->name,
            'departments'            => $request->departments,
            'email'                  => $request->email,
            'menuAccess'             => $request->menuAccess,
            'menuSecurityEnabled'    => $request->menuSecurityEnabled,
            'productGroups'          => $request->productGroups,
            'regions'                => $request->regions,
            'reportAccess'           => $request->reportAccess,
            'reportSecurityEnabled'  => $request->reportSecurityEnabled,
            'salesReps'              => $request->salesReps,
            'states'                 => $request->states,
            'overrides'              => $request->overrides,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Security Management record created successfully',
            'data'    => $securityManagement,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $securityManagement = SecurityManagement::findOrFail($id);

        $request->validate([
            'costCentres'              => 'nullable|array',
            'countries'                => 'nullable|array',
            'customerGroups'           => 'nullable|array',
            'mode'                     => 'nullable|string|max:255',
            'name'                     => 'nullable|string|max:255',
            'departments'              => 'nullable|array',
            'email'                    => 'nullable|email|max:255',
            'menuAccess'               => 'nullable|array',
            'menuSecurityEnabled'      => 'nullable|boolean',
            'productGroups'            => 'nullable|array',
            'regions'                  => 'nullable|array',
            'reportAccess'             => 'nullable|array',
            'reportSecurityEnabled'    => 'nullable|boolean',
            'salesReps'                => 'nullable|array',
            'states'                   => 'nullable|array',
            'overrides'                => 'nullable|array',
        ]);

        $securityManagement->update([
            'costCentres'            => $request->costCentres,
            'countries'              => $request->countries,
            'customerGroups'         => $request->customerGroups,
            'mode'                   => $request->mode,
            'name'                   => $request->name,
            'departments'            => $request->departments,
            'email'                  => $request->email,
            'menuAccess'             => $request->menuAccess,
            'menuSecurityEnabled'    => $request->menuSecurityEnabled,
            'productGroups'          => $request->productGroups,
            'regions'                => $request->regions,
            'reportAccess'           => $request->reportAccess,
            'reportSecurityEnabled'  => $request->reportSecurityEnabled,
            'salesReps'              => $request->salesReps,
            'states'                 => $request->states,
            'overrides'              => $request->overrides,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Security Management record updated successfully',
            'data'    => $securityManagement
        ]);
    }

    public function destroy($id)
    {
        $securityManagement = SecurityManagement::findOrFail($id);
        $securityManagement->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Security Management record deleted successfully'
        ]);
    }
}