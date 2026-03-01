<?php

namespace App\Http\Controllers\Web\Backend\Settings;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Exception;
use function App\Helpers\deleteFile;
use function App\Helpers\uploadFile;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class ProfileController extends Controller
{
    /**
     * Display the profile settings page.
     *
     * @return View
     */
    public function showProfile() {
        // Get the currently authenticated user's ID
        $userId = Auth::id();
        // Query the users table to get the user with the given ID
        $user = User::where('id', $userId)->first();
        return view('backend.layouts.settings.profile_settings', ['users' => $user]);
    }

    /**
     * Update the user's profile information.
     *
     * @param Request $request
     * @return RedirectResponse
     */

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'nullable|max:100|min:2',
            'email'   => 'nullable|email|unique:users,email,' . auth()->user()->id,
            'avatar'  => 'nullable|image|mimes:jpeg,png,jpg|max:4048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $user = User::find(auth()->user()->id);
            $user->name  = $request->name;
            $user->email = $request->email;

            // Define protected files
            $protectedFiles = ['user-1.jpg', 'user-2.jpg', 'user-8.jpg', 'user-9.jpg', 'user-10.jpg', 'user-11.jpg', 'user-13.jpg','user-16.jpg', 'user-17.jpg'];

            // Handle avatar deletion (from public folder)
            if ($request->has('delete_avatar') && $request->delete_avatar == 'on') {
                // Check if the current avatar is protected
                if ($user->avatar && !in_array(basename($user->avatar), $protectedFiles) && File::exists(public_path($user->avatar))) {
                    File::delete(public_path($user->avatar)); // Delete only if it's not a protected file
                }
                $user->avatar = null;
            }

            // Check if an image is being uploaded and update the avatar
            if ($request->hasFile('avatar')) {
                // If an avatar already exists and is not protected, delete it
                if ($user->avatar && !in_array(basename($user->avatar), $protectedFiles) && File::exists(public_path($user->avatar))) {
                    File::delete(public_path($user->avatar)); // Delete the old avatar from the public folder
                }

                // Store the new image in the public folder and get the public path
                $avatarFileName = 'user-avatar/' . $user->id . '_' . $request->file('avatar')->getClientOriginalName();
                $request->file('avatar')->move(public_path('user-avatar'), $avatarFileName); // Move to public folder

                // Update the user's avatar field
                $user->avatar = $avatarFileName;
            }

            // Save user updates
            $user->save();

            ToastMagic::success('Profile Updated Successfully');
            return redirect()->back();
        } catch (Exception $e) {
            ToastMagic::warning('Something went wrong');
            return redirect()->back();
        }
    }


    /**
     * Update the user's password.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function updatePassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password'     => 'required|confirmed|min:8',
            'password_confirmation'     => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $user = Auth::user();

            if (Hash::check($request->old_password, $user->password)) {
                $user->password = Hash::make($request->password);
                $user->save();

                ToastMagic::success('Password Updated Successfully');
                return redirect()->back();
            } else {
                ToastMagic::warning('Current password is incorrect');
                return redirect()->back();
            }

        } catch (Exception $e) {
            ToastMagic::error('Something went wrong');
            return redirect()->back();
        }
    }
}
