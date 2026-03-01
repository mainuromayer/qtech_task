<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.layouts.login');
    }

    /**
     * Handle an incoming authentication request.
     */

//     public function store(LoginRequest $request): RedirectResponse
//     {
//         $request->authenticate();
//
//         // Check the role of the authenticated user
//         $user = Auth::user();
//         if ($user->role !== 'admin') {
//
////             return redirect()->route('home')->with('t-success','User logged in successfully!');
//             return redirect()->route('login')->with('t-error','Only admin can access this page!');
//         }
//
//         // Regenerate session if user is admin
//         $request->session()->regenerate();
// //        if($user->role !== 'admin')
// //        {
// //            return redirect()->intended(route('dashboard', absolute: false));
// //        }
//
//         return redirect()->intended(route('dashboard', absolute: false))->with('t-success','Admin logged in successfully!');
//     }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Check the role of the authenticated user
        $user = Auth::user();
        if ($user->role !== 'admin') {
            Auth::logout(); // Logout the user if they are not an admin
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirect back to the login page with an error message
            return redirect()->route('login')->withErrors([
                'email' => 'Only admins can access the dashboard.',
            ]);
        }

        // Regenerate session if user is admin
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     return redirect()->intended(route('dashboard', absolute: false));
    // }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
