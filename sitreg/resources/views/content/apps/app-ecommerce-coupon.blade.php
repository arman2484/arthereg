@extends('layouts/layoutMaster')

@section('title', 'Coupons')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/rateyo/rateyo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/rateyo/rateyo.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-ecommerce.css') }}" />
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/app-ecommerce-coupon.js') }}"></script>
@endsection

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Coupon / </span>Coupon List
    </h4>
    <div class="app-ecommerce-category">
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <!-- review List Table -->
        <div class="card">
            <div class="card-datatable table-responsive">
                <table class="datatables-coupon table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Coupon Code</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th class="text-lg-center">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEcommerceCouponList"
            aria-labelledby="offcanvasEcommerceCategoryListLabel">
            <!-- Offcanvas Header -->
            <div class="offcanvas-header py-4">
                <h5 id="offcanvasEcommerceCategoryListLabel" class="offcanvas-title">Add Coupon</h5>
                <button type="button" class="btn-close bg-label-secondary text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <!-- Offcanvas Body -->
            <div class="offcanvas-body border-top">
                <form action="{{ url('app/ecommerce/coupon/add') }}" method="POST" class="pt-0"
                    onsubmit="return couponValidation()">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="ecommerce-category-title">Coupon Code</label>
                        <input type="text" class="form-control" id="coupon-code" placeholder="Enter coupon code"
                            name="coupon_code" aria-label="coupon code">
                        <span id="coupon-code-error" class="error">{{ $errors->first('coupon_code') }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="ecommerce-category-title">Coupon Amount</label>
                        <input type="text" class="form-control" id="coupon-amount" placeholder="Enter coupon amount"
                            name="discount_amount" aria-label="coupon amount">
                        <span id="coupon-amount-error" class="error">{{ $errors->first('discount_amount') }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="ecommerce-category-title">Description</label>
                        <input type="text" class="form-control" id="coupon-description"
                            placeholder="Enter coupon description" name="description" aria-label="coupon description">
                        <span id="coupon-description-error" class="error">{{ $errors->first('description') }}</span>
                    </div>
                    <div class="mb-4 ecommerce-select2-dropdown">
                        <label class="form-label">Select coupon status</label>
                        <select id="coupon-status" name="status" class="select2 form-select"
                            data-placeholder="Select category status">
                            <option value="">Select coupon status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <span id="coupon-status-error" class="error">{{ $errors->first('status') }}</span>
                    </div>
                    <!-- Submit and reset -->
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Add</button>
                        <button type="reset" class="btn bg-label-danger" data-bs-dismiss="offcanvas">Discard</button>
                    </div>
                </form>
            </div>


        </div>

        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEcommerceCouponEdit"
            aria-labelledby="offcanvasEcommerceCouponEditLabel">
            <!-- Offcanvas Header -->
            <div class="offcanvas-header py-4">
                <h5 id="offcanvasEcommerceCouponEditLabel" class="offcanvas-title">Edit Coupon</h5>
                <button type="button" class="btn-close bg-label-secondary text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <!-- Offcanvas Body -->
            <div class="offcanvas-body border-top">
                <form action="{{ url('app/ecommerce/coupon/update') }}" method="POST" class="pt-0"
                    onsubmit="return couponEditValidation()">
                    @csrf
                    <div class="mb-3">
                        <input type="hidden" name="coupon_id" id="coupon_id">
                        <label class="form-label" for="ecommerce-category-title">Coupon Code</label>
                        <input type="text" class="form-control" id="coupon-code-edit" placeholder="Enter coupon code"
                            name="coupon_code" aria-label="coupon code">
                        <span id="coupon-code-edit-error" class="error">{{ $errors->first('coupon_code') }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="ecommerce-category-title">Coupon Amount</label>
                        <input type="text" class="form-control" id="discount-amount-edit"
                            placeholder="Enter coupon amount" name="discount_amount" aria-label="coupon amount">
                        <span id="coupon-amount-edit-error" class="error">{{ $errors->first('discount_amount') }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="ecommerce-category-title">Description</label>
                        <input type="text" class="form-control" id="coupon-description-edit"
                            placeholder="Enter coupon description" name="description" aria-label="coupon description">
                        <span id="coupon-description-edit-error"
                            class="error">{{ $errors->first('description') }}</span>
                    </div>
                    <div class="mb-4 ecommerce-select2-dropdown">
                        <label class="form-label">Select coupon status</label>
                        <select id="coupon-status-edit" name="status" class="select2 form-select"
                            data-placeholder="Select category status">
                            <option value="">Select coupon status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <span id="coupon-status-edit-error" class="error">{{ $errors->first('status') }}</span>
                    </div>
                    <!-- Submit and reset -->
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Update</button>
                        <button type="reset" class="btn bg-label-danger" data-bs-dismiss="offcanvas">Discard</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection
