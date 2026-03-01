<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\JobCategory\JobCategoryCollection;
use App\Models\JobCategory;
use App\Traits\ApiResponse;
use Exception;

class JobCategoryController extends Controller
{

    use ApiResponse;
    public function index()
    {
        try {
            $categories = JobCategory::latest()->get();
            return $this->success(new JobCategoryCollection($categories), 'Job Categories fetched successfully');
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 'Failed to fetch job categories');
        }
    }
}
