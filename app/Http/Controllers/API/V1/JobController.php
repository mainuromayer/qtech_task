<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Backend\V1\Job\StoreJobRequest;
use App\Http\Resources\API\V1\Job\JobCollection;
use App\Http\Resources\API\V1\Job\JobResource;
use App\Models\Job;
use App\Traits\ApiResponse;
use Exception;

class JobController extends Controller
{
   use ApiResponse;

   public function index()
   {
      try {
         $jobs = Job::latest()->get();
         return $this->success(new JobCollection($jobs), 'Jobs fetched successfully');
      } catch (Exception $e) {
         return $this->error($e->getMessage(), 'Failed to fetch jobs');
      }
   }


   public function show($id)
   {
      try {
         $job = Job::findOrFail($id);
         if (!$job) {
            return $this->error('Job not found', 'Job not found');
         }
         return $this->success(new JobResource($job), 'Job fetched successfully');
      } catch (Exception $e) {
         return $this->error($e->getMessage(), 'Failed to fetch job');
      }
   }


   // admin job store 
   public function store(StoreJobRequest $request)
   {
      try {
         $data = new Job();
         $data->title = $request->title;
         $data->description = $request->description;
         $data->salary = $request->salary;
         $data->location = $request->location;
         $data->department = $request->department;
         $data->job_type = $request->job_type;
         $data->category_id = $request->category_id;
         $data->status = 'active';

         $data->save();
         return $this->success(new JobResource($data), 'Job created successfully');
      } catch (Exception $e) {
         return $this->error($e->getMessage(), 'Failed to create job');
      }
   }

   // admin job destroy
   public function destroy($id)
   {
      try {
         $job = Job::findOrFail($id);
         if (!$job) {
            return $this->error('Job not found', 'Job not found');
         }
         $job->delete();
         return $this->success(null, 'Job deleted successfully');
      } catch (Exception $e) {
         return $this->error($e->getMessage(), 'Failed to delete job');
      }
   }
}
