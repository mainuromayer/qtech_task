@extends('backend.app')

@section('title', 'Edit Job')

@section('content')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

<div class="container-fluid">
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Edit Job</h4>
        </div>
        <div class="text-end">
            <a href="{{ route('job.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Assuming $job and $categories are passed from controller -->
                    <form action="{{ route('job.update', $job->id ?? 0) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="" disabled>Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $job->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $job->title ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $job->description ?? '') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="salary" class="form-label">Salary</label>
                                <input type="text" class="form-control" id="salary" name="salary" value="{{ old('salary', $job->salary ?? '') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $job->location ?? '') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="department" class="form-label">Department</label>
                                @php
                                    $selectedDepts = old('department', $job->department ?? []);
                                    if(!is_array($selectedDepts)) {
                                        $selectedDepts = $selectedDepts ? [$selectedDepts] : [];
                                    }
                                @endphp
                                <select class="form-control select2" id="department" name="department[]" multiple="multiple">
                                    @foreach($selectedDepts as $dept)
                                        @if($dept)
                                        <option value="{{ $dept }}" selected>{{ $dept }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="job_type" class="form-label">Job Type <span class="text-danger">*</span></label>
                                <select name="job_type" id="job_type" class="form-select" required>
                                    <option value="full_time" {{ old('job_type', $job->job_type ?? '') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                    <option value="part_time" {{ old('job_type', $job->job_type ?? '') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                    <option value="contract" {{ old('job_type', $job->job_type ?? '') == 'contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="temporary" {{ old('job_type', $job->job_type ?? '') == 'temporary' ? 'selected' : '' }}>Temporary</option>
                                    <option value="other" {{ old('job_type', $job->job_type ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="{{ route('job.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                tags: true,
                tokenSeparators: [',', ' ']
            });
        });
    </script>
@endpush
