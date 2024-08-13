@extends('layouts/layoutMaster')

@section('title', 'eCommerce Product Category - Apps')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-ecommerce.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
@endsection

@section('page-script')
    {{-- <script src="{{ asset('assets/js/app-ecommerce-category-list.js') }}"></script> --}}
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Settings /</span> Mobile Settings
    </h4>

    <div class="app-ecommerce-category">
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="offcanvas-body border-top">
            <form action="{{ url('app/ecommerce/setting') }}" method="POST" enctype="multipart/form-data" class="pt-0">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="ecommerce-category-image">Logo</label>
                    <input class="form-control" type="file" id="imageUpload" name="app_logo" value="" />
                    <div id="imagePreview" class="mb-4 border">
                        @if (!empty($data->app_logo))
                            <img src="{{ asset('assets/images/app_images/' . $data->app_logo) }}" id="imagePreview"
                                width="100px" class="me-4 border">
                        @endif

                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="ecommerce-category-title">App Name</label>
                    <input type="text" class="form-control" id="ecommerce-category-title" placeholder="Enter app name"
                        name="app_name" aria-label="category title"
                        value="{{ isset($data->app_name) ? $data->app_name : '' }}">
                </div>

                <!-- Status -->
                <div class="mb-4 ecommerce-select2-dropdown">
                    <label class="form-label">App Color</label>
                    <select id="ecommerce-category-status" name="app_color" class="form-select"
                        data-placeholder="Select category status">
                        <option value="">Select app color</option>
                        <option value="white" @if ($data->app_color == 'white') selected @endif>White</option>
                        <option value="blue" @if ($data->app_color == 'blue') selected @endif>Blue</option>
                        <option value="black" @if ($data->app_color == 'black') selected @endif>Black</option>
                        <option value="red" @if ($data->app_color == 'red') selected @endif>Red</option>
                        <option value="yellow" @if ($data->app_color == 'yellow') selected @endif>Yellow</option>
                        <option value="green" @if ($data->app_color == 'green') selected @endif>Green</option>

                    </select>
                    <span class="error">{{ $errors->first('status') }}</span>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
<script>
    $(document).ready(function() {
        $('#imageUpload').on('change', function() {
            $('#imagePreview').html('');
            var files = $(this)[0].files;
            for (var i = 0; i < files.length; i++) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').append('<img src="' + e.target.result +
                        '" width="100" class="me-4 border">');
                };
                reader.readAsDataURL(files[i]);
            }
        });
    });
</script>
