@extends('layouts/layoutMaster')

@section('title', 'Edit Variant')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/quill/editor.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('/assets/vendor/css/pages/app-ecommerce.css') }}" />

@endsection

@section('vendor-script')
    <script src="{{ asset('/assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('/assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('/assets/vendor/libs/quill/quill.js') }}"></script>

    <script src="{{ asset('/assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('page-script')
    {{-- <script src="{{ asset('/assets/js/children-list.js') }}"></script> --}}
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Variant / </span> Edit Variant
    </h4>

    <form action="{{ url('app/ecommerce/variant/update/' . $data->id) }}" method="POST"class="pt-0"
        enctype="multipart/form-data">
        @csrf
        <div class="app-ecommerce-category">
                    <!-- Add Product -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3">Edit your variants</h4>
                <p class="text-muted">Know your variants</p>
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
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label" for="ecommerce-category-title">Delivery Title<span
                                        class="text-danger">
                                        *</span></label>
                                <input type="text" class="form-control" id="title" name="name"
                                    aria-label="category title" placeholder="Enter title" value="{{ $data->name }}">
                                <span id="title-error" class="error">{{ $errors->first('name') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-3">
                                <span class="mb-0 h6">Status<span class="text-danger">
                                        *</span></span>
                                <div class="w-25 d-flex justify-content-end">
                                    <label class="switch switch-primary switch-sm me-4 pe-2">
                                        <input type="checkbox" name="status" class="switch-input"
                                            {{ $data->status == 1 ? 'checked' : '' }}>
                                        <span class="switch-toggle-slider">
                                            <span class="switch-on">
                                                <span class="switch-off"></span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <span class="text-muted fw-light">(Please Select Status)</span><br>
                            <span id="title-error" class="error">{{ $errors->first('status') }}</span><br>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
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
                   window.location.href = '/app/ecommerce/variant/list';
        });
    });
</script>
