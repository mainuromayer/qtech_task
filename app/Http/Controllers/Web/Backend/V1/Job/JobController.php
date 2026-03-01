<?php

namespace App\Http\Controllers\Web\Backend\V1\Job;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Backend\V1\Job\StoreJobRequest;
use App\Http\Requests\Web\Backend\V1\Job\UpdateJobRequest;
use App\Models\Job;
use App\Models\JobCategory;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JobController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = Job::with('category')->latest();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('category', function ($data) {
                        return $data->category->title ?? '-';
                    })
                    ->addColumn('status', function ($data) {
                        $status = '';
                        $status .= '<div class="form-check form-switch">';
                        $status .= '<input class="form-check-input" type="checkbox" role="switch" id="flexSwitch' . $data->id . '" onclick="showStatusChangeAlert(' . $data->id . ')" getAreaid="' . $data->id . '" name="status"';
                        if ($data->status == "active") {
                            $status .= ' checked';
                        }
                        $status .= '></div>';
                        return $status;
                    })
                    ->addColumn('action', function ($data) {
                        return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                  <a href="' . route('job.edit', $data->id) . '" type="button" class="btn btn-icon btn-sm bg-success-subtle me-1" title="Edit">
                                  <i class="mdi mdi-pencil-outline fs-14 text-success"></i>
                                  </a>&nbsp;
                                  <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="btn btn-icon btn-sm bg-danger-subtle delete-button" title="Delete">
                                  <i class="mdi mdi-delete fs-14 text-danger"></i>
                                </a>
                                </div>';
                    })
                    ->rawColumns(['status', 'action'])
                    ->make(true);
            }

            return view('backend.layouts.V1.job.index');
        } catch (Exception $e) {
            ToastMagic::error('Job Index Failed' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function create()
    {
        try {
            $categories = JobCategory::where('status', 'active')->get();
            return view('backend.layouts.V1.job.create', compact('categories'));
        } catch (Exception $e) {
            ToastMagic::error('Job Create Failed' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function store(StoreJobRequest $request)
    {
        try {
            $data = new Job();
            $data->title = $request->title;
            $data->company = $request->company;
            $data->description = $request->description;
            $data->salary = $request->salary;
            $data->location = $request->location;
            $data->department = $request->department;
            $data->job_type = $request->job_type;
            $data->category_id = $request->category_id;
            $data->status = 'active';

            $data->save();

            ToastMagic::success('Job Created Successfully');
            return redirect()->route('job.index');
        } catch (Exception $e) {
            ToastMagic::error('Job Create Failed' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $job = Job::findOrFail($id);
            $categories = JobCategory::where('status', 'active')->get();
            return view('backend.layouts.V1.job.edit', compact('job', 'categories'));
        } catch (Exception $e) {
            ToastMagic::error('Job Edit Failed' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function update(UpdateJobRequest $request, $id)
    {
        try {
            $data = Job::findOrFail($id);
            $data->title = $request->title;
            $data->company = $request->company;
            $data->description = $request->description;
            $data->salary = $request->salary;
            $data->location = $request->location;
            $data->department = $request->department;
            $data->job_type = $request->job_type;
            $data->category_id = $request->category_id;

            $data->save();

            ToastMagic::success('Job Updated Successfully');
            return redirect()->route('job.index');
        } catch (Exception $e) {
            ToastMagic::error('Job Update Failed' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function status($id)
    {
        try {
            $data = Job::findOrFail($id);

            if ($data->status == 'active') {
                $data->status = 'inactive';
                $data->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Status changed to Inactive.',
                ]);
            } else {
                $data->status = 'active';
                $data->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Status changed to Active.',
                ]);
            }
        } catch (Exception $e) {
            ToastMagic::error('Job Status Failed' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $data = Job::findOrFail($id);
            $data->delete();

            return response()->json([
                'success' => true,
                'message' => 'Deleted successfully.',
            ]);
        } catch (Exception $e) {
            ToastMagic::error('Job Delete Failed' . $e->getMessage());
            return redirect()->back();
        }
    }
}
