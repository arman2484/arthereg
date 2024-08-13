@extends('layouts/layoutMaster')

@section('title', 'Edit Categories')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
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
        <span class="text-muted fw-light">Sitreg /</span> Category Edit
    </h4>



    <div class="app-ecommerce-category">
        <!-- Add Product -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3">Edit your category</h4>
                <p class="text-muted">Discover the range of available categories</p>
            </div>

            <div class="text-end">
                <button type="button" id="backButton" class="btn btn-outline-secondary">Back</button>
            </div>
        </div>
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="offcanvas-body border-top">
            <form action="{{ url('app/ecommerce/product/category/update', $data->id) }}" method="POST"
                enctype="multipart/form-data" class="pt-0">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="ecommerce-category-title">Title</label>
                    <input type="text" class="form-control" id="ecommerce-category-title"
                        placeholder="Enter category title" name="category_name" aria-label="category title"
                        value="{{ $data->category_name }}">
                    <span class="error">{{ $errors->first('category_name') }}</span>
                </div>
                <div class="col-md-12" style="margin-top: 4px; padding-bottom: 24px">
                    <label class="form-label mb-1 d-flex justify-content-between align-items-center" for="module_id">
                        <span>Module</span>
                    </label>
                    <select id="module_id" name="module_id" class="select2 form-select" data-placeholder="Select Module">
                        <option value="">Select Module</option>
                        @foreach ($module as $module)
                            <option value="{{ $module->id }}" {{ $module->id == $data->module_id ? 'selected' : '' }}>
                                {{ $module->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="ecommerce-category-image">Attachment</label>
                    <input class="form-control" type="file" id="imageUpload" name="category_image"
                        value="{{ $data->category_image }}" />
                    <span class="error">{{ $errors->first('category_image') }}</span>
                    <div id="imagePreview" class="mb-4 border">
                        <img src="{{ asset('assets/images/category_images/' . $data->category_image) }}" id="imagePreview"
                            width="100px" class="me-4 border">

                    </div>

                </div>

                <!-- Status -->
                <div class="mb-4 ecommerce-select2-dropdown">
                    <label class="form-label">Select category status</label>
                    <select id="ecommerce-category-status" name="status" class="form-select"
                        data-placeholder="Select category status">
                        <option value="">Select category status</option>
                        <option value="1"{{ $data->status == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0"{{ $data->status == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <span class="error">{{ $errors->first('status') }}</span>
                </div>
                <!-- Submit and reset -->
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var backButton = document.getElementById("backButton");
        backButton.addEventListener("click", function() {
                   window.location.href = '/app/ecommerce/product/category';
        });
    });
</script>
