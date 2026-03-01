<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Backend\V1\Application\StoreApplicationRequest;
use App\Models\Application;
use App\Traits\ApiResponse;
use Exception;

class ApplicationController extends Controller
{
    use ApiResponse;
    public function store(StoreApplicationRequest $request)
    {
        try {
            $application = new Application();
            $application->job_id = $request->job_id;
            $application->name = $request->name;
            $application->email = $request->email;
            $application->phone = $request->phone;
            $application->cover_letter = $request->cover_letter;

            if ($request->hasFile('resume')) {
                $file = $request->file('resume');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('resumes', $filename, 'public');
                $application->resume = $path;
            }

            $application->save();

            return $this->success($application, 'Application submitted successfully');

        } catch (Exception $e) {
            return $this->error($e->getMessage(), 'Something went wrong', 500);
        }
    }
}
