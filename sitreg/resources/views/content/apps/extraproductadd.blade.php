@extends('layouts/layoutMaster')

@section('title', 'eCommerce Add Product - Apps')

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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">eCommerce /</span><span> Add Product</span>
    </h4>

    <form action="{{ url('app/ecommerce/product/store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="app-ecommerce">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1 mt-3">Add a new Product</h4>
                    <p class="text-muted">Orders placed across your store</p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-3">
                    <button type="submit" class="btn btn-primary">Publish product</button>
                </div>

            </div>

            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Product information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label" for="ecommerce-product-name">Name</label>
                                <input type="text" class="form-control" id="product_name" placeholder="Product title"
                                    name="product_name" aria-label="Product title" value="{{ old('product_name') }}">
                                <span class="error">{{ $errors->first('product_name') }}</span>
                            </div>
                            <div>
                                <label class="form-label">Description <span class="text-muted">(Optional)</span></label>
                                <textarea name="product_description" class="form-control" id="product_description" cols="30" rows="10">{{ old('product_description') }}</textarea>
                                <span class="error">{{ $errors->first('product_description') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-body">
                            @csrf
                            <input type="file" name="product_images[]" class="form-control" multiple id="imageUpload"
                                accept="image/*">
                            <span class="error">{{ $errors->first('product_image') }}</span>
                            <div id="imagePreview" class="mb-4 border"></div>
                            {{-- <button type="submit">Upload</button> --}}

                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Inventory</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                {{-- {{dd($data->product_color)}} --}}
                                <label class="form-label" for="ecommerce-product-price">Prodcut Colors</label>
                                <input type="text" class="form-control" id="ecommerce-product-color"
                                    placeholder="Product Color" name="product_color" aria-label="Product color"
                                    value="{{ old('product_color') }}">
                                <span class="error">{{ $errors->first('product_color') }}</span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="ecommerce-product-discount-price">Prodcut Size</label>
                                <input type="text" class="form-control" id="ecommerce-product-size"
                                    placeholder="Prodcut Size" name="product_size" aria-label="Product size"
                                    value="{{ old('product_size') }}">
                                <span class="error">{{ $errors->first('product_size') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Pricing</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label" for="ecommerce-product-price">Base Price</label>
                                <input type="number" class="form-control" id="ecommerce-product-price" placeholder="Price"
                                    name="product_price" aria-label="Product price" value="{{ old('product_price') }}">
                                <span class="error">{{ $errors->first('product_price') }}</span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="ecommerce-product-discount-price">Sale Price</label>
                                <input type="number" class="form-control" id="ecommerce-product-discount-price"
                                    placeholder="Sale Price" name="product_sale_price" aria-label="Product sale price"
                                    value="{{ old('product_sale_price') }}">
                                <span class="error">{{ $errors->first('product_sale_price') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Organize</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 col ecommerce-select2-dropdown">
                                <select id="category_id" class="select2 form-select" name="category_id"
                                    data-placeholder="Select Category">
                                    <option value="">Select Category</option>
                                    @foreach ($category as $value)
                                        <option value="{{ $value->id }}"
                                            {{ old('category_id') == $value->id ? 'selected' : '' }}>
                                            {{ $value->category_name }}</option>
                                    @endforeach
                                </select>
                                <span class="error">{{ $errors->first('category_id') }}</span>
                            </div>
                            <div class="mb-3 col ecommerce-select2-dropdown">
                                <select id="sub_category_id" class="select2 form-select" name="sub_category_id"
                                    data-placeholder="Select Sub Category">
                                    <option value="">Select Sub Category</option>
                                    @foreach ($subcategory as $value)
                                        <option value="{{ $value->id }}"
                                            {{ old('sub_category_id') == $value->id ? 'selected' : '' }}>
                                            {{ $value->sub_category_name }}</option>
                                    @endforeach
                                </select>
                                <span class="error">{{ $errors->first('category_id') }}</span>
                            </div>
                            <div class="mb-3 col ecommerce-select2-dropdown">
                                <label class="form-label mb-1" for="status-org">Status
                                </label>
                                <select id="status-org" class="select2 form-select" name="status"
                                    data-placeholder="Select Status">
                                    <option value="">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <span class="error">{{ $errors->first('status') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <img src="" class="" alt="">
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
                        '" width="80" height="80px" class="me-4 border">');
                };
                reader.readAsDataURL(files[i]);
            }
        });
        $('#category_id').on('change', function(e) {
            var category_id = e.target.value;
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
</script>
