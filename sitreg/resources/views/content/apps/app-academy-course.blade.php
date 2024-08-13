@extends('layouts/layoutMaster')
@php
    $configData = Helper::appClasses();
@endphp

@section('title', 'POS')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/plyr/plyr.css') }}" />
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-academy.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/plyr/plyr.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/app-academy-course.js') }}"></script>
    <style>
        .product-description {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .price-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .price-container .original-price {
            font-size: 1.2rem;
            color: #999;
            text-decoration: line-through;
        }

        .price-container .sale-price {
            font-size: 1.5rem;
            color: #000;
            font-weight: bold;
        }

        .price-container .cart-icon {
            font-size: 1.5rem;
            color: #333;
        }
    </style>

    <style>
        .cart-icon-box {
            display: inline-block;
            padding: 5px 10px;
            border: 1px solid #fff;
            border-radius: 5px;
            background-color: #e7e7ff !important;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .cart-icon-box .bx-cart {
            font-size: 24px;
            color: #00438F;
            transition: color 0.3s;
        }

        .cart-icon-box:hover {
            background-color: #333;
            border-color: #00438F;
        }

        .cart-icon-box:hover .bx-cart {
            color: #00438F;
        }

        .added-to-cart-label {
            font-size: 1rem;
            color: #28a745;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div class="d-flex flex-column justify-content-center">
            <h4 class="mb-1 mt-3"><span class="text-muted fw-light">POS/</span> Products</h4>
            <p class="text-muted">Orders placed across your store</p>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-3">
            <a href="/wizard/ex-checkout" class="btn btn-primary">Cart</a>
        </div>
    </div>
    <div class="app-academy">
        <div class="card mb-4">
            <div class="card-header d-flex flex-wrap justify-content-between gap-3">
                <div class="card-title mb-0 me-1">
                    <h5 class="mb-1">Products</h5>
                    <p class="text-muted mb-0">Filter & Get your products </p>
                </div>
                <div class="d-flex justify-content-md-end align-items-center gap-3 flex-wrap" style="width: 200px;">
                    <select id="select2_course_select" class="select2 form-select" data-placeholder="Stores">
                        <option value="">Stores</option>
                        @foreach ($stores as $value)
                            <option value="{{ $value->id }}"
                                {{ isset($storeId) && $storeId == $value->id ? 'selected' : '' }}>
                                {{ $value->store_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body">
                @if ($products->isEmpty())
                    <p>No products found.</p>
                @else
                    <div class="row gy-4 mb-4">
                        @foreach ($products as $product)
                            <div class="col-sm-6 col-lg-4">
                                <div class="card p-2 h-100 shadow-none border">
                                    <div class="rounded-2 text-center mb-3">
                                        <div>
                                            <img class="img-fluid"
                                                src="{{ asset('assets/images/product_images/' . ($product->productImages->first()->product_image ?? 'default-image.png')) }}"
                                                alt="{{ $product->product_name }}"
                                                style="height: 224px;object-fit: contain;" />
                                        </div>
                                    </div>
                                    <div class="card-body p-3 pt-2">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span
                                                class="badge bg-label-primary">{{ $product->category->category_name ?? 'Category' }}</span>
                                            <h6 class="d-flex align-items-center justify-content-center gap-1 mb-0">
                                                {{ $product->product_review }} <span class="text-warning">
                                                    {{-- <i class="bx bxs-star me-1"></i></span><span
                                                    class="text-muted">(1.23k)</span> --}}
                                            </h6>
                                        </div>
                                        <div class="h5">{{ $product->product_name }}</div>
                                        <p class="mt-2 product-description">{{ $product->product_about }}</p>
                                        <div class="d-flex align-items-center mb-2 price-container">
                                            <div>
                                                @if ($product->product_sale_price)
                                                    <span class="original-price">${{ $product->product_price }}</span>
                                                    <span
                                                        class="ms-2 sale-price">${{ $product->product_sale_price }}</span>
                                                @else
                                                    <span class="sale-price">${{ $product->product_price }}</span>
                                                @endif
                                            </div>
                                            @if (!in_array($product->id, $cartItems))
                                                <a href="javascript:void(0);" class="cart-icon cart-icon-box add-to-cart"
                                                    data-product-id="{{ $product->id }}"
                                                    data-product-price="{{ $product->product_sale_price ?? $product->product_price }}">
                                                    <i class="bx bx-cart"></i>
                                                </a>
                                            @else
                                                <span class="added-to-cart-label">Added to cart</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of
                                    {{ $products->total() }}
                                    entries
                                </div>
                                <div>
                                    {{ $products->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                @endif
            </div>
        </div>
    </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.add-to-cart').forEach(function(element) {
            element.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const productPrice = this.dataset.productPrice;

                fetch('{{ route('cart.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: 1,
                            product_price: productPrice
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        window.location.href = '{{ route('wizard-ex-checkout') }}';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });
    });
</script>
