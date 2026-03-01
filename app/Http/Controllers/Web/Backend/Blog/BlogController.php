<?php

namespace App\Http\Controllers\Web\Backend\Blog;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Traits\ApiResponse;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\DataTables;

class BlogController extends Controller
{
    use ApiResponse;

    /**
     * Displays the list of blog.
     *
     * This method handles AJAX requests to fetch and return product data
     * in a format suitable for DataTables, including columns for publish
     * products. If not an AJAX request, it returns the main view for products.
     *
     * @param Request $request The incoming HTTP request.
     */

    public function index(Request $request)
    {
        $data = Blog::orderBy('id', 'desc')->latest();


        $blogs = $data->get();

        // Check if it's an API request
        if ($request->wantsJson() && !$request->ajax()) {
            // Clean HTML from each blog description
            $cleanedBlogs = $blogs->map(function ($blog) {
                return [
                    'id' => $blog->id,
                    'status' => $blog->status,
                ];
            });

            return $this->success($cleanedBlogs, 'Book Categories Retrieved Successfully.', 200);
        }

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    $url = asset($data->image);
                    return '<img src="' . $url . '" alt="image" width="50px" height="50px" style="margin-left:20px;">';
                })
                ->addColumn('description', function ($data) {
                    $description       = $data->description;
                    $short_description = strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description;
                    return '<p>' . $short_description . '</p>';
                })
                ->addColumn('status', function ($data) {
                    $status = '';
                    $status .= '<div class="form-check form-switch">'; // Bootstrap 5 switch container
                    $status .= '<input
                    class="form-check-input"
                    type="checkbox"
                    role="switch"
                    id="flexSwitch' . $data->id . '"
                    onclick="showStatusChangeAlert(' . $data->id . ')"
                    getAreaid="' . $data->id . '"
                    name="status"';

                    // Check if the status is active
                    if ($data->status == "active") {
                        $status .= ' checked';
                    }

                    $status .= '>';
                    $status .= '</div>';

                    return $status;
                })

                ->addColumn('action', function ($data) {

                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                  <a href="' . route('blog.edit',  $data->id) . '" type="button" class="btn btn-icon btn-sm bg-success-subtle me-1" title="Edit">
                                  <i class="mdi mdi-pencil-outline fs-14 text-success"></i>
                                  </a>&nbsp;
                                  <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="btn btn-icon btn-sm bg-danger-subtle delete-button" title="Delete">
                                  <i class="mdi mdi-delete fs-14 text-danger"></i>
                                </a>
                                </div>';
                })
                ->rawColumns(['status', 'action','image','description'])
                ->make(true);
        }

        return view('backend.layouts.blog.index');
    }

    /**
     * Show the form for creating a new blog dynamic page.
     */

    public function create()
    {
        return view('backend.layouts.blog.create');
    }


    /**
     * Store a newly created dynamic page in the database.
     *
     * @param Request $request
     */

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4000',
            'published_at' => 'required|date',
        ]);

        $data = new Blog();
        $data->title = $request->title;
        $data->description = $request->description;
        $data->published_at = $request->published_at;

        $blogImage = Helper::fileUpload($request->file('image'), 'blog-image', $request->image);

        $data->image = $blogImage;

        $data->save();



        ToastMagic::success('Blog Created Successfully');

        return redirect()->route('blog.index');
    }

    /**
     * Display the specified blog to edit and update.
     *
     * @param  string  $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */

    public function edit($id)
    {
        $data = Blog::find($id);
        return view('backend.layouts.blog.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4000',
            'published_at' => 'nullable|date',
        ]);

        $data = Blog::find($id);
        $data->title = $request->title;
        $data->description = $request->description;
        $data->published_at = $request->published_at;

        if ($request->hasFile('image')) {
            // Remove old image if a new image is uploaded
            if ($data->image && File::exists($data->image)) {
                File::delete($data->image);
            }
            // Store the new image
            $data->image = Helper::fileUpload($request->file('image'), 'blog-image', $request->image);
        }

        $data->save();


        ToastMagic::success('Blog Updated Successfully');

        return redirect()->route('blog.index');
    }

    /**
     * Delete the specified dynamic page from the blog database.
     *
     * @param int $id
     */
    public function destroy($id)
    {
        $data = Blog::findOrFail($id);

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully.',
        ]);
    }


    /**
     * Update the status of a blog.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function status($id)
    {
        $data = Blog::findOrFail($id);

        if ($data->status == 'active') {
            $data->status = 'inactive';
            $data->save();
            return response()->json([
                'success' => false,
                'message' => 'Unpublished Successfully.',
                'data'    => $data,
            ]);
        } else {
            $data->status = 'active';
            $data->save();
            return response()->json([
                'success' => true,
                'message' => 'Published Successfully.',
                'data'    => $data,
            ]);
        }
    }
}
