@extends('backend.app')

@section('title', 'System Settings')

@push('styles')
    <style>
        .dropify-wrapper .dropify-message p {
            font-size: 26px;
            margin: 5px 0 0;
        }

        .ck-editor__editable[role="textbox"] {
            min-height: 150px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">System Settings</h4>
            </div>

            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="">System Settings</a></li>
                </ol>
            </div>
        </div>

        <div class="row">


            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">System Settings</h5>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <form class="row" method="POST" action="{{ route('settings.system.update') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="system_title">System Title :</label>
                                <input type="text" class="form-control @error('system_title') is-invalid @enderror"
                                    placeholder="System Title" id="system_title" name="system_title"
                                    value="{{ $setting->system_title ?? '' }}">
                                @error('system_title')
                                    <div style="color: red">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="system_short_title" class="form-label">System Short Title :</label>
                                <input type="text" class="form-control @error('system_short_title') is-invalid @enderror"
                                    placeholder="System Short Title" name="system_short_title" id="system_short_title"
                                    value="{{ $setting->system_short_title ?? '' }}">
                                @error('system_short_title')
                                    <div style="color: red">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label f-w-500" for="company_name">Company Name :</label>
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                    placeholder="Company Name" id="company_name" name="company_name"
                                    value="{{ $setting->company_name ?? '' }}">
                                @error('company_name')
                                    <div style="color: red">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label f-w-500" for="email">Email :</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Email" name="email" id="email" value="{{ $setting->email ?? '' }}">
                                @error('email')
                                    <div style="color: red">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label f-w-500" for="phone_number">Phone Number :</label>
                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                                    placeholder="Phone Number" name="phone_number" id="phone_number"
                                    value="{{ $setting->phone_number ?? '' }}">
                                @error('phone_number')
                                    <div style="color: red">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label f-w-500" for="whatsapp">WhatsApp :</label>
                                <input type="text" class="form-control @error('whatsapp') is-invalid @enderror"
                                    placeholder="WhatsApp" name="whatsapp" id="whatsapp"
                                    value="{{ $setting->whatsapp ?? '' }}">
                                @error('whatsapp')
                                    <div style="color: red">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label f-w-500" for="copyright_text">Copyright Text :</label>
                                <input type="text" class="form-control @error('copyright_text') is-invalid @enderror"
                                    placeholder="Copyright Text" name="copyright_text" id="copyright_text"
                                    value="{{ $setting->copyright_text ?? '' }}">
                                @error('copyright_text')
                                    <div style="color: red">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="logo">Logo :</label>
                                <input type="file" class="form-control dropify @error('logo') is-invalid @enderror"
                                    id="logo" name="logo"
                                    data-default-file="{{ $setting->logo ?? 'https://thumbs.dreamstime.com/b/demo-demo-icon-139882881.jpg' }}">
                                @error('logo')
                                    <div style="color: red">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="favicon">Favicon :</label>
                                <input type="file" class="form-control dropify @error('favicon') is-invalid @enderror"
                                    name="favicon" id="favicon"
                                    data-default-file="{{ $setting->favicon ?? 'https://thumbs.dreamstime.com/b/demo-demo-icon-139882881.jpg' }}">
                                @error('favicon')
                                    <div style="color: red">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="tag_line" class="form-label">Tag Line :</label>
                                <textarea placeholder="Type Here..." id="tag_line" class="form-control"
                                    name="tag_line">{{ $setting->tag_line ?? '' }}</textarea>
                                @error('tag_line')
                                    <div style="color: red">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="card-footer text-end">
                                <button class="btn btn-primary" type="submit">Update</button>
                                <a href="{{route('settings.system.index')}}" class="btn btn-warning">Cancel</a>
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
            .create(document.querySelector('#description'), {
                height: '500px'
            })
            .catch(error => {
                console.error(error);
            });
        ClassicEditor
            .create(document.querySelector('#description1'), {
                height: '500px'
            })
            .catch(error => {
                console.error(error);
            });


        $('.dropify').dropify();



        $(document).ready(function () {
            $('.select2').select2();
        });
    </script>
@endpush