<?php

namespace App\Http\Controllers\Web\Backend\V1\Job;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Backend\V1\JobCategory\StoreJobCategoryRequest;
use App\Http\Requests\Web\Backend\V1\JobCategory\UpdateJobCategoryRequest;
use App\Models\JobCategory;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\DataTables;

class JobCategoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = JobCategory::orderBy('sort_order', 'asc')->latest();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('icon', function ($data) {
                    if ($data->icon) {
                        $url = asset($data->icon);
                        return '<img src="' . $url . '" alt="icon" width="50px" height="50px">';
                    }
                    return '-';
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
                                  <a href="' . route('job_category.edit', $data->id) . '" type="button" class="btn btn-icon btn-sm bg-success-subtle me-1" title="Edit">
                                  <i class="mdi mdi-pencil-outline fs-14 text-success"></i>
                                  </a>&nbsp;
                                  <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="btn btn-icon btn-sm bg-danger-subtle delete-button" title="Delete">
                                  <i class="mdi mdi-delete fs-14 text-danger"></i>
                                </a>
                                </div>';
                })
                    ->rawColumns(['status', 'action', 'icon'])
                    ->make(true);
            }

            return view('backend.layouts.V1.job_category.index');
        }
        catch (Exception $e) {
            ToastMagic::error('Job Category Index Failed' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function create()
    {
        try {
            return view('backend.layouts.V1.job_category.create');
        }
        catch (Exception $e) {
            ToastMagic::error('Job Category Create Failed' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function store(StoreJobCategoryRequest $request)
    {
        try {
            $data = new JobCategory();
            $data->title = $request->title;
            $data->sort_order = $request->sort_order;
            $data->status = 'active';
            if ($request->hasFile('icon')) {
                $data->icon = Helper::fileUpload($request->file('icon'), 'job-category-icon', $request->title);
            }

            $data->save();

            ToastMagic::success('Job Category Created Successfully');
            return redirect()->route('job_category.index');
        }
        catch (Exception $e) {
            ToastMagic::error('Job Category Create Failed' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $category = JobCategory::findOrFail($id);
            return view('backend.layouts.V1.job_category.edit', compact('category'));
        }
        catch (Exception $e) {
            ToastMagic::error('Job Category Edit Failed' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function update(UpdateJobCategoryRequest $request, $id)
    {
        try {
            $data = JobCategory::findOrFail($id);
            $data->title = $request->title;
            $data->sort_order = $request->sort_order;

            if ($request->hasFile('icon')) {
                if ($data->icon && File::exists(public_path($data->icon))) {
                    File::delete(public_path($data->icon));
                }
                $data->icon = Helper::fileUpload($request->file('icon'), 'job-category-icon', $request->title);
            }

            $data->save();

            ToastMagic::success('Job Category Updated Successfully');
            return redirect()->route('job_category.index');
        }
        catch (Exception $e) {
            ToastMagic::error('Job Category Update Failed' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function status($id)
    {
        try {
            $data = JobCategory::findOrFail($id);

            if ($data->status == 'active') {
                $data->status = 'inactive';
                $data->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Status changed to Inactive.',
                ]);
            }
            else {
                $data->status = 'active';
                $data->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Status changed to Active.',
                ]);
            }
        }
        catch (Exception $e) {
            ToastMagic::error('Job Category Status Failed' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $data = JobCategory::findOrFail($id);
            if ($data->icon && File::exists(public_path($data->icon))) {
                File::delete(public_path($data->icon));
            }
            $data->delete();

            return response()->json([
                'success' => true,
                'message' => 'Deleted successfully.',
            ]);
        }
        catch (Exception $e) {
            ToastMagic::error('Job Category Delete Failed' . $e->getMessage());
            return redirect()->back();
        }
    }
}
