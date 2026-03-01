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
            $application->resume_link = $request->resume_link;
            $application->cover_note = $request->cover_note;

            $application->save();

            return $this->success($application, 'Application submitted successfully');

        } catch (Exception $e) {
            return $this->error($e->getMessage(), 'Something went wrong', 500);
        }
    }
}
