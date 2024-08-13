@extends('layouts/layoutMaster')

@section('title', 'Show Product')

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
    <script src="{{ asset('assets/js/app-ecommerce-product-add.js') }}"></script>
@endsection

<style>
    .row {
        display: flex;
        flex-wrap: wrap;
        gap: 50px;
    }

    .col-md-2 {
        flex: 1 1 20%;
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Sitreg /</span><span> Show Product</span>
    </h4>

    <form action="{{ url('app/ecommerce/product/update', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="app-ecommerce">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1 mt-3">Show your Product</h4>
                    <p class="text-muted">Orders placed across your store</p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-3">
                    <a href="/app/ecommerce/product/list" class="btn btn-secondary">Back</a>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Product information</h5>
                        </div>
                        {{-- Product Name & Description --}}
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label" for="ecommerce-product-name">Product Name<span
                                        style="color: red;">
                                        *</span></label>
                                <input type="text" class="form-control" id="product_name"
                                    placeholder="Enter Product Name" name="product_name" aria-label="Product title"
                                    value="{{ $data->product_name }}" disabled>
                                <span class="error">{{ $errors->first('product_name') }}</span>
                            </div>
                            <div>
                                <label class="form-label">Product Description <span style="color: red;">
                                        *</span></label>
                                <textarea name="product_about" class="form-control" id="product_about" placeholder="Enter Product Description"
                                    cols="30" rows="5" disabled>{{ $data->product_about }}</textarea>
                                <span class="error">{{ $errors->first('product_about') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Store & Category Info --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Store & Category Info</h5>
                        </div>
                        <div class="card-body">
                            <!-- Container for the dropdowns -->
                            <div class="row">
                                <!-- Module Dropdown -->
                                <div class="col-md-2" style="padding-bottom: 20px;">
                                    <label class="form-label mb-1" for="module_id">
                                        <span>Module<span style="color: red;">*</span></span>
                                    </label>
                                    <select id="module_id" name="module_id" class="select2 form-select"
                                        data-placeholder="Select Module" disabled>
                                        <option value="">Select Module</option>
                                        @foreach ($module as $value)
                                            <option value="{{ $value->id }}"
                                                {{ $value->id == $data->module_id ? 'selected' : '' }}>
                                                {{ $value->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error">{{ $errors->first('module_id') }}</span>
                                </div>
                                <!-- Store Dropdown -->
                                <div class="col-md-2" style="padding-bottom: 20px;">
                                    <label class="form-label mb-1" for="store_id">
                                        <span>Store<span style="color: red;">*</span></span>
                                    </label>
                                    <select id="store_id" name="store_id" class="select2 form-select"
                                        data-placeholder="Select Store" disabled>
                                        <option value="">Select Store</option>
                                        @foreach ($store as $value)
                                            <option value="{{ $value->id }}"
                                                {{ $value->id == $data->store_id ? 'selected' : '' }}>
                                                {{ $value->store_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error">{{ $errors->first('store_id') }}</span>
                                </div>
                                <!-- Category Dropdown -->
                                <div class="col-md-2" style="padding-bottom: 20px;">
                                    <label class="form-label mb-1" for="category_id">
                                        <span>Category<span style="color: red;">*</span></span>
                                    </label>
                                    <select id="category_id" class="select2 form-select" name="category_id"
                                        data-placeholder="Select Category" disabled>
                                        <option value="">Select Category</option>
                                        @foreach ($category as $value)
                                            <option value="{{ $value->id }}"
                                                {{ $value->id == $data->category_id ? 'selected' : '' }}>
                                                {{ $value->category_name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="error">{{ $errors->first('category_id') }}</span>
                                </div>
                                <!-- Sub Category Dropdown -->
                                <div class="col-md-2" style="padding-bottom: 20px;">
                                    <label class="form-label mb-1" for="sub_category_id">
                                        <span>Sub Category</span>
                                    </label>
                                    <select id="sub_category_id" class="select2 form-select" name="sub_category_id"
                                        data-placeholder="Select Sub Category" disabled>
                                        <option value="">Select Sub Category</option>
                                        @foreach ($subcategory as $value)
                                            <option value="{{ $value->id }}"
                                                {{ $value->id == $data->sub_category_id ? 'selected' : '' }}>
                                                {{ $value->sub_category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error">{{ $errors->first('sub_category_id') }}</span>
                                </div>
                                <!-- Unit Dropdown -->
                                <div class="col-md-2" style="padding-bottom: 20px;">
                                    <label class="form-label mb-1" for="unit_id">
                                        <span>Unit<span style="color: red;">*</span></span>
                                    </label>
                                    <select id="unit_id" class="select2 form-select" name="unit_id"
                                        data-placeholder="Select Unit" disabled>
                                        <option value="">Select Unit</option>
                                        @foreach ($units as $value)
                                            <option value="{{ $value->id }}"
                                                {{ $value->id == $data->unit_id ? 'selected' : '' }}>
                                                {{ $value->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error">{{ $errors->first('unit_id') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Price Information --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Price Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <div style="flex: 1; margin-right: 10px;">
                                    <label class="form-label" for="ecommerce-product-price">Base Price<span
                                            style="color: red;">*</span></label>
                                    <input type="number" class="form-control" id="ecommerce-product-price"
                                        placeholder="Enter Product Price" name="product_price" aria-label="Product price"
                                        value="{{ $data->product_price }}" disabled>
                                    <span class="error">{{ $errors->first('product_price') }}</span>
                                </div>
                                <div style="flex: 1; margin-right: 10px;">
                                    <label class="form-label" for="ecommerce-product-discount-price">Sale Price<span
                                            style="color: red;">*</span></label>
                                    <input type="number" class="form-control" id="ecommerce-product-discount-price"
                                        placeholder="Enter Product Sale Price" name="product_sale_price"
                                        aria-label="Product sale price" value="{{ $data->product_sale_price }}" disabled>
                                    <span class="error">{{ $errors->first('product_sale_price') }}</span>
                                </div>
                                <div style="flex: 1;">
                                    <label class="form-label" for="ecommerce-product-total-stock">Total Stock<span
                                            style="color: red;">*</span></label>
                                    <input type="number" class="form-control" id="ecommerce-product-total-stock"
                                        placeholder="Enter Total Stock" name="product_quantity"
                                        aria-label="Product total stock" value="{{ $data->product_quantity }}" disabled>
                                    <span class="error">{{ $errors->first('product_quantity') }}</span>
                                </div>
                            </div>
                            <div class="d-flex border-top pt-3">
                                <span class="mb-0 h6">In stock<span style="color: red;">*</span></span>
                                <div>
                                    <label class="switch switch-primary switch-sm me-4 pe-2" style="padding-left: 12px;">
                                        <input type="checkbox" class="switch-input" id="in_stock" name="in_stock"
                                            {{ $data->in_stock ? 'checked' : '' }} disabled>
                                        <span class="switch-toggle-slider">
                                            <span class="switch-on">
                                                <span class="switch-off"></span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- Product Images --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            @csrf
                            <h5 class="card-title mb-0">Product Images<span style="color: red;">*</span></h5>

                            <input type="file" name="product_images[]" class="form-control" multiple id="imageUpload"
                                accept="image/*" style="margin-top: 15px;" value="{{ $data->product_name }}" disabled>
                            <div id="imagePreview" class="mb-4 border">
                                @if ($data->productImages)
                                    <div class="row">
                                        @foreach ($data->productImages as $value)
                                            <div class="col-md-2" id="image-preview_{{ $value->id }}">
                                                <img src="{{ asset('assets/images/product_images/' . $value->product_image) }}"
                                                    style="width: 150px; height:150px;" class="me-4 border">

                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <span class="error">{{ $errors->first('product_image') }}</span>
                        </div>
                    </div>


                    {{-- Variants --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4" id="hidevariants">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Variants</h5>
                                </div>
                                <div class="card-body">
                                    <div id="variants-list">
                                        @foreach ($variants as $variant)
                                            <div class="row mt-6 variant-row" data-id="{{ $variant->id }}">
                                                <div class="col-4">
                                                    <p class="form-control">
                                                        @if ($variant->color)
                                                            Color: {{ $variant->color }},
                                                        @endif
                                                        @if ($variant->size)
                                                            Size: {{ $variant->size }},
                                                        @endif
                                                        @if ($variant->type)
                                                            Type: {{ $variant->type }},
                                                        @endif
                                                        @if ($variant->price)
                                                            Price: {{ $variant->price }},
                                                        @endif
                                                        @if ($variant->total_stock)
                                                            Total Stock: {{ $variant->total_stock }},
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tags --}}
                    <div class="col-12 col-lg-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Tags</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <input type="text" class="form-control" id="ecommerce-product-color"
                                        placeholder="Enter Tags" name="tag" aria-label="Product color"
                                        value="{{ $data->tag }}" disabled>
                                    <span class="error">{{ $errors->first('tag') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection
