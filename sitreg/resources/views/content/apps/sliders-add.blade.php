@extends('layouts/layoutMaster')

@section('title', 'Add Slider/Banner')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/dropzone/dropzone.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/categories-add.js') }}"></script>
@endsection


@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Slider/Banner /</span><span> Add Slider/Banner</span>
    </h4>

    <div class="app-ecommerce">

        <!-- Add Slider -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3">Add Slider/Banner</h4>
                <p class="text-muted">Explore and add slider/banner</p>
            </div>

            <div class="text-end">
                <button type="button" id="backButton" class="btn btn-outline-secondary">Back</button>
            </div>
        </div>

        <form class="add-category pt-0" id="addCategoryForm" method="post" action="{{ route('sliders-save') }}"
            enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Slider Information -->
                <div class="col-12 col-lg-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Slider/Banner information</h5>
                        </div>
                        <div class="card-body">

                            <!-- Slider Name -->
                            <div class="row">
                                <div class="row" style="padding-bottom: 8px;">
                                    <div class="col-md-6" style="margin-top: 7px; padding-bottom: 24px">
                                        <label class="form-label mb-1 d-flex justify-content-between align-items-center"
                                            for="module_id">
                                            <span>Module<span style="color: red;">*</span></span>
                                        </label>
                                        <select id="module_id" name="module_id" class="select2 form-select"
                                            data-placeholder="Select Module">
                                            <option value="">Select Module</option>
                                            @foreach ($services as $service)
                                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 col ecommerce-select2-dropdown" style="padding-top: 4px;">
                                        <label class="form-label mb-1 d-flex justify-content-between align-items-center"
                                            for="type">
                                            <span>Type<span style="color: red;">*</span></span><a
                                                href="javascript:void(0);" class="fw-medium"></a>
                                        </label>
                                        <select id="type" class="select2 form-select" name="type"
                                            data-placeholder="Choose Type">
                                            <option value="">Choose Type</option>
                                            <option value="1">Banner</option>
                                            <option value="0">Slider</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Image Upload --}}
                                <div class="form-group" style="padding-bottom: 20px;">
                                    <label class="form-label" for="image">Image<span style="color: red;">
                                            *</span></label>
                                    <div class="custom-file">
                                        <input type="file" name="image" class="form-control form-control-lg"
                                            id="exampleInputFile">
                                    </div>
                                </div>
                            </div>

                            <input type="submit" class="btn btn-primary me-sm-3 me-1 data-submit" value="Submit"
                                onclick="submitForm(event)">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection


<script>
    document.addEventListener("DOMContentLoaded", function() {
        var backButton = document.getElementById("backButton");
        backButton.addEventListener("click", function() {
            window.location.href = "{{ route('banner-list') }}";
        });
    });
</script>
