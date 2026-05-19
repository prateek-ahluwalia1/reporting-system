<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReportManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parentMenu' => 'nullable|string|max:255',
            'childMenu' => 'nullable|string|max:255',
            'grandchildMenu' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'published' => 'required',
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

          // Handle file upload
         $reportFilePath = null;
        if ($request->hasFile('reportFile')) {
            $file = $request->file('reportFile');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $file->getClientOriginalName());
            
            $filePath = $file->storeAs('reports', $fileName, 'public');
            
            $reportFilePath = asset('public/storage/' . $filePath);
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
            'report_file' => $reportFilePath,
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parentMenu' => 'nullable|string|max:255',
            'childMenu' => 'nullable|string|max:255',
            'grandchildMenu' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'published' => 'required',
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

    // Handle file upload
    if ($request->hasFile('reportFile')) {
        // Delete old file if exists
        if ($report->report_file) {
            // Extract path from old URL to delete the file
            $oldPath = str_replace(asset('storage/'), '', $report->report_file);
            $oldPath = str_replace('storage/app/public/', '', $oldPath);
            $oldPath = ltrim($oldPath, '/');
            
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }
        
        $file = $request->file('reportFile');
        $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $file->getClientOriginalName());
        
        // Store file and get path (returns: reports/filename.docx)
        $filePath = $file->storeAs('reports', $fileName, 'public');
        
        // Generate FULL URL
        $fullUrl = asset('public/storage/' . $filePath);
        
        // Store the full URL in database
        $report->report_file = $fullUrl;
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