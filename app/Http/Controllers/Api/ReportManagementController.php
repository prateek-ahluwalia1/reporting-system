<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReportManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportManagementController extends Controller
{
    /**
     * Display a listing of the reports.
     */
    public function index(Request $request)
    {
        $reports = ReportManagement::all();

        // Decode parameters for each report
        $reports->transform(function ($report) {
            $report->parameters = $report->parameters ? json_decode($report->parameters, true) : [];
            return $report;
        });

        return response()->json([
            'status' => true,
            'data' => $reports,
        ]);
    }

    /**
     * Display the specified report.
     */
    public function show($id)
    {
        $report = ReportManagement::find($id);
        
        if (!$report) {
            return response()->json([
                'status' => false,
                'message' => 'Report not found'
            ], 404);
        }
        
        // Decode parameters
        $report->parameters = $report->parameters ? json_decode($report->parameters, true) : [];
        
        return response()->json([
            'status' => true,
            'data' => $report
        ]);
    }

    /**
     * Store a newly created report.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:report_managements,name',
            'description' => 'nullable|string',
            'parentMenu' => 'nullable|string|max:255',
            'childMenu' => 'nullable|string|max:255',
            'grandchildMenu' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'published' => 'required|boolean',
            'storedProcedure' => 'nullable|string|max:255',
            'viewerType' => 'nullable|string|max:255',
            'parameters' => 'nullable|array',
            'parameters.*.id' => 'required|string',
            'parameters.*.label' => 'required|string',
            'parameters.*.name' => 'required|string',
            'parameters.*.type' => 'required|string|in:text,number,date,datetime,select,checkbox,radio',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $report = ReportManagement::create([
            'name' => $request->name,
            'description' => $request->description,
            'parent_menu' => $request->parentMenu,
            'child_menu' => $request->childMenu,
            'grandchild_menu' => $request->grandchildMenu,
            'category' => $request->category,
            'published' => $request->published,
            'stored_procedure' => $request->storedProcedure,
            'viewer_type' => $request->viewerType,
            'parameters' => $request->parameters ? json_encode($request->parameters) : null,
        ]);

        // Decode parameters for response
        $report->parameters = $report->parameters ? json_decode($report->parameters, true) : [];

        return response()->json([
            'status' => true,
            'message' => 'Report created successfully',
            'data' => $report,
        ], 201);
    }

    /**
     * Update the specified report.
     */
    public function update(Request $request, $id)
    {
        $report = ReportManagement::find($id);
        
        if (!$report) {
            return response()->json([
                'status' => false,
                'message' => 'Report not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:report_managements,name,' . $id,
            'description' => 'nullable|string',
            'parentMenu' => 'nullable|string|max:255',
            'childMenu' => 'nullable|string|max:255',
            'grandchildMenu' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'published' => 'required|boolean',
            'storedProcedure' => 'nullable|string|max:255',
            'viewerType' => 'nullable|string|max:255',
            'parameters' => 'nullable|array',
            'parameters.*.id' => 'required|string',
            'parameters.*.label' => 'required|string',
            'parameters.*.name' => 'required|string',
            'parameters.*.type' => 'required|string|in:text,number,date,datetime,select,checkbox,radio',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $report->update([
            'name' => $request->name,
            'description' => $request->description,
            'parent_menu' => $request->parentMenu,
            'child_menu' => $request->childMenu,
            'grandchild_menu' => $request->grandchildMenu,
            'category' => $request->category,
            'published' => $request->published,
            'stored_procedure' => $request->storedProcedure,
            'viewer_type' => $request->viewerType,
            'parameters' => $request->parameters ? json_encode($request->parameters) : null,
        ]);

        // Decode parameters for response
        $report->parameters = $report->parameters ? json_decode($report->parameters, true) : [];

        return response()->json([
            'status' => true,
            'message' => 'Report updated successfully',
            'data' => $report
        ]);
    }

    /**
     * Remove the specified report.
     */
    public function destroy($id)
    {
        $report = ReportManagement::find($id);
        
        if (!$report) {
            return response()->json([
                'status' => false,
                'message' => 'Report not found'
            ], 404);
        }
        
        $report->delete();

        return response()->json([
            'status' => true,
            'message' => 'Report deleted successfully'
        ]);
    }
}