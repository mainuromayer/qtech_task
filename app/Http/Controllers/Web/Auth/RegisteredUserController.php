<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.layouts.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

//     public function store(Request $request): RedirectResponse
//     {
//         // Validate the registration request
//         $request->validate([
//             'name' => ['required', 'string', 'max:255'],
//             'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
//             'password' => ['required', 'confirmed', Rules\Password::defaults()],
//         ]);
//
//         // Create a new user
//         $user = User::create([
//             'name' => $request->name,
//             'email' => $request->email,
//             'password' => Hash::make($request->password),
//         ]);
//
//         // Trigger the Registered event
//         event(new Registered($user));
//
//         // Log in the newly registered user
//         Auth::login($user);
//
//         // Check the role of the authenticated user
//         if ($user->role !== 'admin') {
//             // Redirect non-admin users to the home route
//             return redirect()->route('home')->with('t-success','User registered in successfully!');
//         }
//
//         // Regenerate session if user is admin
//         $request->session()->regenerate();
//
//         // Redirect admin users to the dashboard
//         return redirect()->intended(route('dashboard', absolute: false));
//     }

    public function store(Request $request): RedirectResponse
    {
        $data = User::where('role','user')->get();


        if ($data) {

            // Redirect back to the login page with an error message
            return redirect()->route('register')->withErrors([
                'email' => 'Only admins can access the dashboard.',
            ]);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);


        if (Auth::user()->role !== 'admin')
        {
            return redirect()->route('register')
                ->withErrors(['registration' => 'Only admins can register new users.']);
        }
        else
        {
            return redirect(route('dashboard', absolute: true));
        }

    }

    // public function store(Request $request): RedirectResponse
    // {
    //     $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    //         'password' => ['required', 'confirmed', Rules\Password::defaults()],
    //     ]);

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     event(new Registered($user));

    //     Auth::login($user);

    //     return redirect(route('dashboard', absolute: false));
    // }
}
