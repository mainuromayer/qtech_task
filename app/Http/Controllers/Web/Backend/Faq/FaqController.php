<?php

namespace App\Http\Controllers\Web\Backend\Faq;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class FaqController extends Controller
{
    /**
     * Display a listing of FAQs (AJAX DataTable).
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Faq::orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('status', function ($row) {
                    $checked = $row->status == 'active' ? 'checked' : '';
                    return '
                        <div class="form-check form-switch d-flex justify-content-center align-items-center">
                            <input type="checkbox" class="form-check-input status-toggle" data-id="' . $row->id . '" ' . $checked . '>
                        </div>
                    ';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <a href="' . route('faq.edit', $row->id) . '" class="btn btn-sm btn-primary me-1">Edit</a>
                        <button onclick="deleteFaq(' . $row->id . ')" class="btn btn-sm btn-danger">Delete</button>
                    ';
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('backend.layouts.faq.index');
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('backend.layouts.faq.create');
    }

    /**
     * Store a new FAQ.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
        ]);

        try {
            Faq::create([
                'question' => $request->question,
                'answer'   => $request->answer,
                'status'   => 'active',
            ]);

            ToastMagic::success('FAQ created successfully!');
            return redirect()->route('faq.index');
        } catch (\Exception $e) {
            ToastMagic::error('Something went wrong: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $faq = Faq::findOrFail($id);
        return view('backend.layouts.faq.edit', compact('faq'));
    }

    /**
     * Update FAQ.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $faq = Faq::findOrFail($id);
            $faq->update([
                'question' => $request->question,
                'answer'   => $request->answer,
            ]);

            DB::commit();
            ToastMagic::success('FAQ updated successfully!');
            return redirect()->route('faq.index');
        } catch (\Exception $e) {
            DB::rollBack();
            ToastMagic::error('Update failed: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Delete FAQ.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Faq::findOrFail($id)->delete();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'FAQ deleted successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Delete failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle status (active/inactive).
     */
    public function status($id)
    {
        $faq = Faq::findOrFail($id);

        if ($faq->status === 'active') {
            $faq->status = 'inactive';
            $message = 'FAQ unpublished successfully.';
            $success = false;
        } else {
            $faq->status = 'active';
            $message = 'FAQ published successfully.';
            $success = true;
        }

        $faq->save();

        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $faq,
        ]);
    }
}
