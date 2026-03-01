<?php

namespace App\Http\Controllers\Web\Backend\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index()
    {
        return view('backend.layouts.user.index'); // The Blade template
    }

    // Return JSON for DataTables
    public function getData()
    {
        $users = User::select('id', 'name', 'email', 'status', 'role');

        return DataTables::of($users)
            ->addIndexColumn() // DT_RowIndex for serial number
            ->addColumn('status', function ($user) {
                $checked = $user->status === 'active' ? 'checked' : '';
                return '
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="status' . $user->id . '" ' . $checked . ' 
                            onchange="showStatusChangeAlert(' . $user->id . ')">
                        <label class="form-check-label" for="status' . $user->id . '"></label>
                    </div>
                ';
            })

            ->addColumn('action', function ($user) {
                $edit = '<a href="' . route('user.edit', $user->id) . '" class="btn btn-sm btn-primary">Edit</a>';
                $delete = '<button onclick="showDeleteConfirm(' . $user->id . ')" class="btn btn-sm btn-danger">Delete</button>';
                return $edit . ' ' . $delete;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function status($id)
    {
        $user = User::findOrFail($id);

        // Toggle ENUM value
        if ($user->status === 'active') {
            $user->status = 'inactive';
        } else {
            $user->status = 'active';
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => $user->status === 'active' ? 'Activated Successfully' : 'Deactivated Successfully'
        ]);
    }

    // Show create form
    public function create()
    {
        return view('backend.layouts.user.create');
    }

    // Store new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:user,admin,instructor,student',
            'status' => 'required|in:active,inactive',
            'password' => 'required|min:6',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'is_admin' => $request->role === 'admin' ? 1 : 0,
                'status' => $request->status,
                'password' => bcrypt($request->password),
                'joining_date' => now(),
            ]);

            // Assign Spatie Role
            $user->assignRole($request->role);

            // Success toast
            ToastMagic::success('User created successfully!');

            return redirect()->route('user.index');
        } catch (\Exception $e) {
            // Error toast
            ToastMagic::error('Something went wrong: ' . $e->getMessage());

            return back()->withInput();
        }
    }


    // Show edit form
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('backend.layouts.user.edit', compact('user'));
    }

    // Update existing user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin,instructor,student',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|min:6',
        ]);

        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role;
            $user->is_admin = $request->role === 'admin' ? 1 : 0;
            $user->status = $request->status;

            if ($request->password) {
                $user->password = bcrypt($request->password);
            }

            $user->save();

            // Sync Spatie Role
            $user->syncRoles([$request->role]);

            // Success toast
            ToastMagic::success('User updated successfully!');

            return redirect()->route('user.index');
        } catch (\Exception $e) {
            // Error toast
            ToastMagic::error('Something went wrong: ' . $e->getMessage());

            return back()->withInput();
        }
    }

    // Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully!'
        ]);
    }


}
