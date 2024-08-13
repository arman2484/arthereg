@extends('layouts/layoutMaster')

@section('title', 'eCommerce Product Category - Apps')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/dropzone/dropzone.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endsection
@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>

@endsection

@section('page-script')
    {{-- <script src="{{ asset('/assets/js/children-list.js') }}"></script> --}}
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Zone /</span> Add Zone
    </h4>

    <form action="{{ url('app/ecommerce/zone/add') }}" method="POST" class="pt-0"enctype="multipart/form-data">
        @csrf
        <div class="app-ecommerce-category">
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
                                <label class="form-label" for="ecommerce-category-title">Title<span class="text-danger">
                                        *</span></label>
                                <input type="text" class="form-control" id="title" name="name"
                                    aria-label="category title" placeholder="Enter title">
                                <span id="title-error" class="error">{{ $errors->first('name') }}</span>
                            </div>
                            <div class="mb-3 col ecommerce-select2-dropdown">
                                <div class="mb-3 ecommerce-select2-dropdown">
                                    <label class="form-label" for="ecommerce-country">Country</label>
                                    <select id="country_id" name="country_id" class="select2 form-select"
                                        data-placeholder="Select Country">
                                        <option value="">Select Country</option>
                                        @foreach ($data as $value)
                                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                    <span id="country-error" class="error">{{ $errors->first('country_id') }}</span>
                                </div>
                                <div class="mb-3 ecommerce-select2-dropdown">
                                    <label class="form-label" for="ecommerce-country">Select Zone</label>
                                    <select id="zone_id" name="zone_id[]" multiple class="select2 form-select"
                                        data-placeholder="Select Country for Zone">
                                        <option value="">Select Zone</option>
                                    </select>
                                    <span id="zone_id-error" class="error">{{ $errors->first('zone_id') }}</span>
                                </div>
                                <button type="button" class="btn btn-success btn-sm rounded-pill" id="choose-all"
                                    value="">Choose
                                    All &nbsp; <i class="fa-solid fa-square-check"></i></i></button>
                                <button type="button" class="btn btn-danger btn-sm rounded-pill" id="clear-all">Clear All
                                    &nbsp; <i class="fa-solid fa-trash"></i></i></button>

                            </div>
                            <div class="d-flex just-ify-content-between align-items-center pt-3">
                                <span class="mb-0 h6">Status<span class="text-danger">*</span></span>
                                <div class="w-100 d-flex justify-content-end">
                                    <label class="switch switch-primary switch-sm me-4 pe-2">
                                        <input type="checkbox" name="status" class="switch-input">
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
        var select2 = $('.select2');
        if (select2.length) {
            select2.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    dropdownParent: $this.parent(),
                    placeholder: $this.data('placeholder') // for dynamic placeholder
                });
            });
        }
        $('#country_id').on('change', function(e) {
            var country_id = e.target.value;
            $.ajax({
                url: "{{ route('get-state-data') }}",
                type: "get",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    country_id: country_id
                },
                success: function(data) {
                    var selectElement = $('.select2[name="zone_id[]"]');
                    selectElement.empty(); // Clear existing options
                    $.each(data.data, function(index, value) {
                        var option = '<option value="' + value.id +
                            '">' + value.name + ' (' + value.iso2 + ')' +
                            '</option>';
                        selectElement.append(option);
                    });
                    selectElement.select2();
                }
            });
        });

        $('#choose-all').on('click', function(e) {
            e.preventDefault();

            var country_id = $('#country_id').val();

            $.ajax({
                url: "{{ route('get-state-data') }}",
                type: "get",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    country_id: country_id
                },
                success: function(data) {
                    var selectElement = $('.select2[name="zone_id[]"]');
                    selectElement.empty();

                    $.each(data.data, function(index, value) {
                        var option = new Option(value.name + ' (' + value.iso2 +
                            ')', value.id, true, true);
                        selectElement.append(option).trigger('change');
                    });
                }
            });
        });
        $('#clear-all').on('click', function(e) {
            e.preventDefault();
            var selectElement = $('.select2[name="zone_id[]"]');
            selectElement.empty();
            var country_id = $('#country_id').val();

            $.ajax({
                url: "{{ route('get-state-data') }}",
                type: "get",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    country_id: country_id
                },
                success: function(data) {
                    var selectElement = $('.select2[name="zone_id[]"]');
                    selectElement.empty();
                    $.each(data.data, function(index, value) {
                        var option = '<option value="' + value.id +
                            '">' + value.name + ' (' + value.iso2 + ')' +
                            '</option>';
                        selectElement.append(option);
                    });
                    selectElement.select2();
                }
            });
        });


    });
</script>
