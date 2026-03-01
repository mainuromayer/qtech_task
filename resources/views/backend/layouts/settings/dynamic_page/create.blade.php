@extends('backend.app')

@section('title', 'Create Dynamic Page')

@push('styles')
    <style>
        .ck-editor__editable[role="textbox"] {
            min-height: 150px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Dynamic Page Form</h4>
            </div>

            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="{{route('settings.dynamic-page.index')}}">Dynamic Page</a></li>
                </ol>
            </div>
        </div>

        <div class="row">


            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">Dynamic Page</h5>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <form class="" method="POST" action="{{ route('settings.dynamic-page.store') }}"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="title" class="form-label">Title :</label>
                                <input type="text"
                                       class="form-control @error('page_title') is-invalid @enderror"
                                       placeholder="Title" name="page_title" id="page_title"
                                       value="{{old('page_title')}}">
                                @error('page_title')
                                <div style="color: red">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label f-w-500">Content :</label>
                                <textarea class="form-control @error('page_content') is-invalid @enderror" id="content" name="page_content" rows="5">{{old('page_content')}}</textarea>
                                @error('page_content')
                                <div style="color: red;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="card-footer text-end">
                                <button class="btn btn-primary" type="submit">Create</button>
                                <a href="{{route('settings.dynamic-page.create')}}" class="btn btn-warning">Cancel</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>


        </div>

    </div> <!-- container-fluid -->
@endsection

@push('scripts')
    <script>
        ClassicEditor
            .create(document.querySelector('#content'), {
                height: '500px'
            })
            .catch(error => {
                console.error(error);
            });
        ClassicEditor
            .create(document.querySelector('#content1'), {
                height: '500px'
            })
            .catch(error => {
                console.error(error);
            });


        $('.dropify').dropify();



        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
