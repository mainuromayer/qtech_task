@extends('backend.app')

@section('title', 'Application Details')

@section('content')
    <div class="container-fluid">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Application Details</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('job.application.index') }}">Job Applications</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Applicant Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 200px;">Applicant Name</th>
                                <td>{{ $application->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $application->email }}</td>
                            </tr>
                            <tr>
                                <th>Applied For</th>
                                <td>{{ $application->job->title ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Applied At</th>
                                <td>{{ $application->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Resume Link</th>
                                <td>
                                    <a href="{{ $application->resume_link }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-link"></i> View Resume
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span
                                        class="badge bg-{{ $application->status == 'pending' ? 'warning' : ($application->status == 'rejected' ? 'danger' : 'success') }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>
                            </tr>
                        </table>

                        <div class="mt-4">
                            <h6>Cover Note:</h6>
                            <div class="p-3 bg-light border rounded">
                                {!! nl2br(e($application->cover_note)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Job Details</h5>
                    </div>
                    <div class="card-body">
                        @if($application->job)
                            <h6>{{ $application->job->title }}</h6>
                            <p class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ $application->job->location }}</p>
                            <p><span class="badge bg-primary">{{ $application->job->job_type }}</span></p>
                            <hr>
                            <a href="{{ route('job.edit', $application->job->id) }}" class="btn btn-sm btn-outline-primary">View
                                Job Post</a>
                        @else
                            <p>Job information not available.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection