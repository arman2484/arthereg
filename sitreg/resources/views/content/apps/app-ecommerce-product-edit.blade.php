@extends('layouts/layoutMaster')

@section('title', 'Edit Product')

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
        <span class="text-muted fw-light">Sitreg /</span><span> Edit Product</span>
    </h4>

    <form action="{{ url('app/ecommerce/product/update', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="app-ecommerce">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1 mt-3">Edit your Product</h4>
                    <p class="text-muted">Orders placed across your store</p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-3">
                    <button type="submit" class="btn btn-primary">Publish product</button>
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
                                    value="{{ $data->product_name }}">
                                <span class="error">{{ $errors->first('product_name') }}</span>
                            </div>
                            <div>
                                <label class="form-label">Product Description <span style="color: red;">
                                        *</span></label>
                                <textarea name="product_about" class="form-control" id="product_about" placeholder="Enter Product Description"
                                    cols="30" rows="5">{{ $data->product_about }}</textarea>
                                <span class="error">{{ $errors->first('product_about') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Vendor --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Vendor</h5>
                        </div>
                        <div class="card-body">
                            <!-- Container for the dropdowns -->
                            <div class="row">
                                <!-- Vendor Dropdown -->
                                <div class="col-md-12" style="padding-bottom: 20px;">
                                    <label class="form-label mb-1" for="vendor_id">
                                        <span>Vendor<span style="color: red;">*</span></span>
                                    </label>
                                    <select id="vendor_id" name="vendor_id" class="select2 form-select"
                                        data-placeholder="Select Vendor">
                                        <option value="">Select Vendor</option>
                                        @foreach ($vendor as $value)
                                            <option value="{{ $value->id }}"
                                                {{ $value->id == $data->vendor_id ? 'selected' : '' }}>
                                                {{ $value->first_name }} {{ $value->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error">{{ $errors->first('vendor_id') }}</span>
                                </div>
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
                                        data-placeholder="Select Module">
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
                                        data-placeholder="Select Store">
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
                                        data-placeholder="Select Category">
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
                                        data-placeholder="Select Sub Category">
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
                                        data-placeholder="Select Unit">
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
                                        value="{{ $data->product_price }}">
                                    <span class="error">{{ $errors->first('product_price') }}</span>
                                </div>
                                <div style="flex: 1; margin-right: 10px;">
                                    <label class="form-label" for="ecommerce-product-discount-price">Sale Price<span
                                            style="color: red;">*</span></label>
                                    <input type="number" class="form-control" id="ecommerce-product-discount-price"
                                        placeholder="Enter Product Sale Price" name="product_sale_price"
                                        aria-label="Product sale price" value="{{ $data->product_sale_price }}">
                                    <span class="error">{{ $errors->first('product_sale_price') }}</span>
                                </div>
                                <div style="flex: 1;">
                                    <label class="form-label" for="ecommerce-product-total-stock">Total Stock<span
                                            style="color: red;">*</span></label>
                                    <input type="number" class="form-control" id="ecommerce-product-total-stock"
                                        placeholder="Enter Total Stock" name="product_quantity"
                                        aria-label="Product total stock" value="{{ $data->product_quantity }}">
                                    <span class="error">{{ $errors->first('product_quantity') }}</span>
                                </div>
                            </div>
                            <div class="d-flex border-top pt-3">
                                <span class="mb-0 h6">In stock<span style="color: red;">*</span></span>
                                <div>
                                    <label class="switch switch-primary switch-sm me-4 pe-2" style="padding-left: 12px;">
                                        <input type="checkbox" class="switch-input" id="in_stock" name="in_stock"
                                            {{ $data->in_stock ? 'checked' : '' }}>
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
                            <h5 class="card-title mb-0">Product Images <small class="text-muted">(Multiple images can
                                    be uploaded
                                    )</small><span style="color: red;">*</span></h5>

                            <input type="file" name="product_images[]" class="form-control" multiple id="imageUpload"
                                accept="image/*" style="margin-top: 15px;" value="{{ $data->product_name }}">
                            <div id="imagePreview" class="mb-4 border">
                                @if ($data->productImages)
                                    <div class="row" style="gap: 0px;">
                                        @foreach ($data->productImages as $value)
                                            <div class="col-md-2" id="image-preview_{{ $value->id }}">
                                                <img src="{{ asset('assets/images/product_images/' . $value->product_image) }}"
                                                    style="width: 150px; height:150px;" class="me-4 border">
                                                <a href="javascript:void(0)" class="delete_product_image"
                                                    onclick="return deleteImage({{ $value->id }}, this);">Remove</a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <span class="error">{{ $errors->first('product_image') }}</span>
                            {{-- <div id="imagePreview" class="mb-4 border"></div> --}}
                        </div>
                    </div>


                    {{-- Variants --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4" id="hidevariants">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Variants <small class="text-muted">(Add or edit variants
                                            if you have any)</small></h5>
                                </div>

                                <div class="card-body">
                                    <div class="form-repeater">
                                        <div class="row align-items-end" style="gap: 0px;">
                                            <div class="mb-3 col-2">
                                                <label class="form-label" for="color">Color</label>
                                                <input type="text" id="color" class="form-control"
                                                    placeholder="Enter color" name="color" />
                                            </div>
                                            <div class="mb-3 col-2">
                                                <label class="form-label" for="size">Size</label>
                                                <input type="text" id="size" class="form-control"
                                                    placeholder="Enter size" name="size" />
                                            </div>
                                            <div class="mb-3 col-2">
                                                <label class="form-label" for="type">Type</label>
                                                <input type="text" id="type" class="form-control"
                                                    placeholder="Enter type" name="type" />
                                            </div>
                                            <div class="mb-3 col-2">
                                                <label class="form-label" for="price">Price</label>
                                                <input type="number" id="price" class="form-control"
                                                    placeholder="Enter price" name="price" />
                                            </div>
                                            <div class="mb-3 col-2">
                                                <label class="form-label" for="total_stock">Total Stock</label>
                                                <input type="number" id="total_stock" class="form-control"
                                                    placeholder="Enter total stock" name="total_stock" />
                                            </div>
                                            <div class="d-flex justify-content-end"
                                                style="width: fit-content; margin-bottom: 1rem !important;">
                                                <button type="button" id="add-variant" class="btn btn-success"
                                                    style="width: 5rem;">Add</button>
                                                <button type="button" id="update-variant" class="btn btn-primary"
                                                    style="width: 8rem; display: none;">Update</button>
                                            </div>
                                        </div>
                                    </div>
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
                                                <div class="col-2">
                                                    <button type="button" class="btn btn-warning edit-variant"
                                                        style="width: 5rem;">Edit</button>
                                                    <button type="button" class="btn btn-danger delete-variant"
                                                        style="width: 5rem;"
                                                        onclick="return Deletevariant({{ $variant->id }}, this)">Delete</button>
                                                </div>
                                                <input type="hidden" name="variants[color][]"
                                                    value="{{ $variant->color }}">
                                                <input type="hidden" name="variants[size][]"
                                                    value="{{ $variant->size }}">
                                                <input type="hidden" name="variants[type][]"
                                                    value="{{ $variant->type }}">
                                                <input type="hidden" name="variants[price][]"
                                                    value="{{ $variant->price }}">
                                                <input type="hidden" name="variants[total_stock][]"
                                                    value="{{ $variant->total_stock }}" class="variant-total-stock">
                                                <input type="hidden" class="variant-id" name="variants[id][]"
                                                    value="{{ $variant->id }}">
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
                                        value="{{ $data->tag }}">
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
<script>
    $(document).ready(function() {
        $('#imageUpload').on('change', function() {
            $('#imagePreview').html('');
            var files = $(this)[0].files;
            for (var i = 0; i < files.length; i++) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').append('<img src="' + e.target.result +
                        '" width="80px" height="80px" class="me-4 border">');
                };
                reader.readAsDataURL(files[i]);
            }
        });
        $('#category_id').ready(function() {
            // $(document).ready('#category_id', function(e) {
            var category_id = $('#category_id').val();
            $.ajax({
                url: "{{ route('app-ecommerce-product-get-subcategory') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    category_id: category_id
                },
                success: function(data) {
                    console.log(data.data);
                    $('#sub_category_id').empty();
                    $.each(data.data, function(index, subcategory) {
                        console.log(subcategory.id);
                        $('#sub_category_id').append('<option value="' + subcategory
                            .id + '">' + subcategory.sub_category_name +
                            '</option>');
                    })
                }
            })
        });
        $('#category_id').on('change', function() {
            // $(document).ready('#category_id', function(e) {
            var category_id = $('#category_id').val();
            $.ajax({
                url: "{{ route('app-ecommerce-product-get-subcategory') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    category_id: category_id
                },
                success: function(data) {
                    console.log(data.data);
                    $('#sub_category_id').empty();
                    $.each(data.data, function(index, subcategory) {
                        console.log(subcategory.id);
                        $('#sub_category_id').append('<option value="' + subcategory
                            .id + '">' + subcategory.sub_category_name +
                            '</option>');
                    })
                }
            })
        });
    });

    function deleteImage(id, element) {
        $.ajax({
            url: "{{ url('app/ecommerce/product/image/delete/') }}" + "/" + id,
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                // Remove only the specific image preview element
                $(element).closest('div').remove();
            }
        });
    }



    function Deletevariant(id, element) {
        $.ajax({
            url: "{{ url('app/ecommerce/product/variant/delete/') }}/" + id,
            type: "DELETE", // Use DELETE method for deletion
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                // On success, remove the entire variant row
                $(element).closest('.variant-row').remove();
                // Optionally, show a success message or perform additional actions
            },
            error: function(xhr, status, error) {
                console.error('Error deleting variant:', error);
                // Optionally, handle errors or show error messages
            }
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let editingVariantIndex = null;

        function updateTotalStock() {
            var totalStockInputs = document.querySelectorAll('.variant-total-stock');
            var totalStock = 0;

            totalStockInputs.forEach(function(input) {
                if (input.value.trim() !== '') {
                    totalStock += parseInt(input.value);
                }
            });

            var totalStockElement = document.getElementById('ecommerce-product-total-stock');
            var productQuantityElement = document.getElementById('product_quantity');

            if (totalStockElement) {
                totalStockElement.value = totalStock;
            }
            if (productQuantityElement) {
                productQuantityElement.value = totalStock;
            }
        }

        function setupDeleteButton(button) {
            button.addEventListener('click', function() {
                var variantDiv = button.closest('.variant-row');
                var variantIdInput = variantDiv.querySelector('.variant-id');

                // Mark for deletion in the interface
                variantDiv.remove();

                // Update total stock
                updateTotalStock();

                // Mark for deletion in backend
                if (variantIdInput.value) {
                    var deletedVariantsInput = document.getElementById('deleted_variants');
                    var deletedVariantIds = deletedVariantsInput.value ? JSON.parse(deletedVariantsInput
                        .value) : [];
                    deletedVariantIds.push(variantIdInput.value);
                    deletedVariantsInput.value = JSON.stringify(deletedVariantIds);
                }
            });
        }

        function setupEditButton(button) {
            button.addEventListener('click', function() {
                var variantDiv = button.closest('.row');
                editingVariantIndex = Array.from(document.querySelectorAll('.variant-row')).indexOf(
                    variantDiv);

                var color = variantDiv.querySelector('input[name="variants[color][]"]').value;
                var size = variantDiv.querySelector('input[name="variants[size][]"]').value;
                var type = variantDiv.querySelector('input[name="variants[type][]"]').value;
                var price = variantDiv.querySelector('input[name="variants[price][]"]').value;
                var totalStock = variantDiv.querySelector('input[name="variants[total_stock][]"]')
                    .value;

                document.getElementById('color').value = color;
                document.getElementById('size').value = size;
                document.getElementById('type').value = type;
                document.getElementById('price').value = price;
                document.getElementById('total_stock').value = totalStock;

                document.getElementById('add-variant').style.display = 'none';
                document.getElementById('update-variant').style.display = 'inline-block';
            });
        }

        document.querySelectorAll('.delete-variant').forEach(function(button) {
            setupDeleteButton(button);
        });

        document.querySelectorAll('.edit-variant').forEach(function(button) {
            setupEditButton(button);
        });

        document.getElementById('add-variant').addEventListener('click', function() {
            var color = document.getElementById('color').value;
            var size = document.getElementById('size').value;
            var type = document.getElementById('type').value;
            var price = document.getElementById('price').value;
            var totalStock = document.getElementById('total_stock').value;

            if (color || size || type || price || totalStock) {
                var variantDiv = document.createElement('div');
                variantDiv.className = 'row mt-6 variant-row';

                var attributes = [];
                if (color) attributes.push(`Color: ${color}`);
                if (size) attributes.push(`Size: ${size}`);
                if (type) attributes.push(`Type: ${type}`);
                if (price) attributes.push(`Price: ${price}`);
                if (totalStock) attributes.push(`Total Stock: ${totalStock}`);

                var variantHTML = `
                    <div class="col-4">
                        <p class="form-control">${attributes.join(', ')}</p>
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-warning edit-variant" style="width: 5rem;">Edit</button>
                        <button type="button" class="btn btn-danger delete-variant" style="width: 5rem;">Delete</button>
                    </div>
                    <input type="hidden" name="variants[color][]" value="${color}">
                    <input type="hidden" name="variants[size][]" value="${size}">
                    <input type="hidden" name="variants[type][]" value="${type}">
                    <input type="hidden" name="variants[price][]" value="${price}">
                    <input type="hidden" name="variants[total_stock][]" value="${totalStock}" class="variant-total-stock">
                    <input type="hidden" class="variant-id" name="variants[id][]" value="">
                `;

                variantDiv.innerHTML = variantHTML;

                document.getElementById('variants-list').appendChild(variantDiv);

                document.getElementById('color').value = '';
                document.getElementById('size').value = '';
                document.getElementById('type').value = '';
                document.getElementById('price').value = '';
                document.getElementById('total_stock').value = '';

                document.getElementById('ecommerce-product-total-stock').disabled = true;
                updateTotalStock();

                setupDeleteButton(variantDiv.querySelector('.delete-variant'));
                setupEditButton(variantDiv.querySelector('.edit-variant'));
            }
        });

        document.getElementById('update-variant').addEventListener('click', function() {
            var color = document.getElementById('color').value;
            var size = document.getElementById('size').value;
            var type = document.getElementById('type').value;
            var price = document.getElementById('price').value;
            var totalStock = document.getElementById('total_stock').value;

            if (editingVariantIndex !== null) {
                var variantDiv = document.querySelectorAll('.variant-row')[editingVariantIndex];

                var attributes = [];
                if (color) attributes.push(`Color: ${color}`);
                if (size) attributes.push(`Size: ${size}`);
                if (type) attributes.push(`Type: ${type}`);
                if (price) attributes.push(`Price: ${price}`);
                if (totalStock) attributes.push(`Total Stock: ${totalStock}`);

                variantDiv.querySelector('p').textContent = attributes.join(', ');

                variantDiv.querySelector('input[name="variants[color][]"]').value = color;
                variantDiv.querySelector('input[name="variants[size][]"]').value = size;
                variantDiv.querySelector('input[name="variants[type][]"]').value = type;
                variantDiv.querySelector('input[name="variants[price][]"]').value = price;
                variantDiv.querySelector('input[name="variants[total_stock][]"]').value = totalStock;

                document.getElementById('color').value = '';
                document.getElementById('size').value = '';
                document.getElementById('type').value = '';
                document.getElementById('price').value = '';
                document.getElementById('total_stock').value = '';

                document.getElementById('add-variant').style.display = 'inline-block';
                document.getElementById('update-variant').style.display = 'none';

                updateTotalStock();
            }
        });
    });
</script>
