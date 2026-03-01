@php
    $systemSetting = App\Models\SystemSetting::first();
@endphp

<!-- App favicon -->
<link rel="shortcut icon"
    href="{{ $systemSetting->favicon ?? 'https://thumbs.dreamstime.com/b/demo-demo-icon-139882881.jpg' }}" />

<!-- App css -->
<link href="{{asset('backend/css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-style" />

<!-- Icons -->
<link href="{{asset('backend/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

@stack('styles')

<style>
    body {
        background: #f4f7fe;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05) !important;
    }

    .btn-primary {
        border-radius: 8px;
        padding: 10px;
        background-color: #4e73df;
        border-color: #4e73df;
    }

    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #264dc0;
    }

    .form-control {
        border-radius: 8px;
        padding: 10px 15px;
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1);
    }
</style>