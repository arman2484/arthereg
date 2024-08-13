@extends('layouts/layoutMaster')

@section('title', 'Sub categories')

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
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-ecommerce.css') }}" />
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
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
    <script src="{{ asset('assets/js/app-ecommerce-subcategory-list.js') }}"></script>
@endsection


@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Sub Category /</span> Sub Category List
    </h4>

    <div class="app-ecommerce-category">
        <!-- Category List Table -->
        <div class="card">
            <div class="card-datatable table-responsive">
                <table class="datatables-subcategory-list table border-top">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Sub Categories</th>
                            <th>Parent category</th>
                            <th>Status</th>
                            <th class="text-lg-center">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- Offcanvas to add new customer -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEcommerceCategoryList"
            aria-labelledby="offcanvasEcommerceCategoryListLabel">
            <!-- Offcanvas Header -->
            <div class="offcanvas-header py-4">
                <h5 id="offcanvasEcommerceCategoryListLabel" class="offcanvas-title">Add Sub Category</h5>
                <button type="button" class="btn-close bg-label-secondary text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <!-- Offcanvas Body -->
            <div class="offcanvas-body border-top">
                <form action="{{ url('app/ecommerce/product/subcategory/add') }}" enctype="multipart/form-data"
                    method="post" class="pt-0" onsubmit="return subCategoryValidaton()">
                    <!-- Title -->
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="ecommerce-category-title">Title</label>
                        <input type="text" class="form-control" id="subcategory-name"
                            placeholder="Enter sub category title" name="sub_category_name" aria-label="category title">
                        <span id="subcategory-name-error" class="error">{{ $errors->first('sub_category_name') }}</span>
                    </div>
                    <!-- Parent category -->
                    <div class="mb-3 ecommerce-select2-dropdown">
                        <label class="form-label" for="ecommerce-category-parent-category">Parent category</label>
                        <select id="category-parent" name="category" class="select2 form-select"
                            data-placeholder="Select parent category">
                            <option value="">Select parent Category</option>
                            @foreach ($category as $value)
                                <option value="{{ $value->id }}">{{ $value->category_name }}</option>
                            @endforeach
                        </select>
                        <span id="category-parent-error" class="error">{{ $errors->first('category') }}</span>
                    </div>

                      <!-- Module -->
                    <div class="mb-3 ecommerce-select2-dropdown">
                        <label class="form-label" for="ecommerce-category-parent-category">Module</label>
                        <select id="module_id" name="module_id" class="select2 form-select"
                            data-placeholder="Select module">
                            <option value="">Select module module</option>
                            @foreach ($module as $value)
                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                            @endforeach
                        </select>
                        <span id="module_id-error" class="error">{{ $errors->first('module') }}</span>
                    </div>
                    <!-- Status -->
                    <div class="mb-4 ecommerce-select2-dropdown">
                        <label class="form-label">Select sub category status</label>
                        <select id="subcategory-status" name="sub_category_status" class="select2 form-select"
                            data-placeholder="Select category status">
                            <option value="">Select category status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <span id="subcategory-status-error" class="error">{{ $errors->first('status') }}</span>
                    </div>
                    <!-- Submit and reset -->
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Add</button>
                        <button type="reset" class="btn bg-label-danger" data-bs-dismiss="offcanvas">Discard</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
