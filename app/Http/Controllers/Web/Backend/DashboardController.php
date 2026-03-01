<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Job;
use App\Models\Application;

class DashboardController extends Controller
{
    public function index()
    {
        $total_user = User::count();
        $total_job = Job::count();
        $total_application = Application::count();
        return view('backend.layouts.dashboard.dashboard', compact('total_user', 'total_job', 'total_application'));
    }
}
