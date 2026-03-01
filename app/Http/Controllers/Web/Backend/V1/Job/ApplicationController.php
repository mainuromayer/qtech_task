<?php

namespace App\Http\Controllers\Web\Backend\V1\Job;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ApplicationController extends Controller
{
    public function index()
    {
        try {
            return view('backend.layouts.V1.job_application.index');
        } catch (Exception $e) {
            ToastMagic::error('Database Error: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = Application::with('job')->latest();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('job_title', function ($row) {
                        return $row->job->title ?? 'N/A';
                    })
                    ->addColumn('resume_link', function ($row) {
                        return '<a href="' . asset('storage/' . $row->resume) . '" target="_blank" class="btn btn-sm btn-info">View Resume</a>';
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '<div class="btn-group btn-group-sm">
                                    <a href="' . route('job.application.show', $row->id) . '" class="btn btn-primary" title="Show">
                                        <i class="mdi mdi-eye"></i> Show
                                    </a>
                                    <button onclick="deleteApplication(' . $row->id . ')" class="btn btn-danger" title="Delete">
                                        <i class="mdi mdi-delete"></i> Delete
                                    </button>
                                </div>';
                        return $btn;
                    })
                    ->rawColumns(['resume_link', 'action'])
                    ->make(true);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $application = Application::with('job')->findOrFail($id);
            return view('backend.layouts.V1.job_application.show', compact('application'));
        } catch (Exception $e) {
            ToastMagic::error('Application not found');
            return redirect()->route('job.application.index');
        }
    }

    public function destroy($id)
    {
        try {
            $application = Application::findOrFail($id);
            $application->delete();

            return response()->json([
                'success' => true,
                'message' => 'Application deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete application'
            ], 500);
        }
    }
}
