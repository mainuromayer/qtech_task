@extends('backend.app')

@section('title', 'Edit Job Category')

@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/cdn/css/dropify.min.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Edit Job Category</h4>
        </div>
        <div class="text-end">
            <a href="{{ route('job_category.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Assuming $category is passed from controller -->
                    <form action="{{ route('job_category.update', $category->id ?? 0) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $category->title ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="icon" class="form-label">Icon</label>
                            <input type="file" class="form-control dropify" id="icon" name="icon" data-default-file="{{ old('icon', isset($category->icon) ? asset($category->icon) : '') }}">
                        </div>

                        <div class="mb-3">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}">
                        </div>

                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="{{ route('job_category.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('backend/cdn/js/dropify.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        });
    </script>
@endpush
