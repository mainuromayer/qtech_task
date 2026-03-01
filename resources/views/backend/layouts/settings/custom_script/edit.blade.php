@extends('backend.app')

@section('title', 'Custom Script Edit')

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
                <h4 class="fs-18 fw-semibold m-0">Custom Script Form</h4>
            </div>

            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="{{route('settings.custom-script.index')}}">Custom Script</a></li>
                </ol>
            </div>
        </div>

        <div class="row">


            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">Custom Script</h5>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <form class="" method="POST" action="{{ route('settings.custom-script.update',['id' => $custom_script->id]) }}"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="title" class="form-label">Type :</label>
                                <select name="type" class="form-select">
                                    <option value="">-- Select Type --</option>
                                    <option value="header" {{$custom_script->type == 'header' ? 'selected' : ''}}>Header</option>
                                    <option value="footer" {{$custom_script->type == 'footer' ? 'selected' : ''}}>Footer</option>
                                </select>
                                @error('type')
                                <div style="color: red;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="script_content" class="form-label">Content :</label>
                                <textarea name="script_content" class="form-control @error('script_content') is-invalid @enderror" id="script_content" cols="" rows="">{{$custom_script->script_content}}</textarea>
                                @error('script_content')
                                <div style="color: red;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="card-footer text-end">
                                <button class="btn btn-primary" type="submit">Update</button>
                                <a href="{{ route('settings.custom-script.edit',['id' => $custom_script->id]) }}" class="btn btn-warning">Cancel</a>
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

