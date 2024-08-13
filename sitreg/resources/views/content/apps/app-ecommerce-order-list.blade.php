@extends('layouts/layoutMaster')

@section('title', 'Order List')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection
@section('page-script')
    <script src="{{ asset('assets/js/app-ecommerce-order-list.js') }}"></script>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Order /</span> Order List
    </h4>

    <div class="card mb-4">
        <div class="card-widget-separator-wrapper">
            <div class="card-body card-widget-separator">
                <div class="row gy-4 gy-sm-1">
                    <div class="col-sm-6 col-lg-4 cursor-pointer" onclick="pendingOrder()">
                        <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                            <div>
                                <h3 class="mb-2">{{ $pendingOrder }}</h3>
                                <p class="mb-0">Order Pending</p>
                            </div>
                            <div class="avatar me-sm-4">
                                <span class="avatar-initial rounded bg-label-secondary">
                                    <i class="bx bx-calendar bx-sm"></i>
                                </span>
                            </div>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none me-4">
                    </div>
                    <div class="col-sm-6 col-lg-4 cursor-pointer" onclick="completedOrder()">
                        <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
                            <div>
                                <h3 class="mb-2">{{ $completedOrder }}</h3>
                                <p class="mb-0">Order Completed</p>
                            </div>
                            <div class="avatar me-lg-4">
                                <span class="avatar-initial rounded bg-label-secondary">
                                    <i class="bx bx-check-double bx-sm"></i>
                                </span>
                            </div>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none">
                    </div>
                    {{-- <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start border-end pb-3 pb-sm-0 card-widget-3">
                            <div>
                                <h3 class="mb-2">124</h3>
                                <p class="mb-0">Order Pending</p>
                            </div>
                            <div class="avatar me-sm-4">
                                <span class="avatar-initial rounded bg-label-secondary">
                                    <i class="bx bx-wallet bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-sm-6 col-lg-4 cursor-pointer" onclick="cancelOrder()">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h3 class="mb-2">{{ $cancleOrder }}</h3>
                                <p class="mb-0">Order Cancelled</p>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-secondary">
                                    <i class="bx bx-error-alt bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order List Table -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="datatables-order table border-top">
                <thead>
                    <tr>
                        <th></th>
                        <th>order</th>
                        <th>date</th>
                        <th>customers</th>
                        {{-- <th>payment</th> --}}
                        <th>payment method</th>
                        <th>status</th>
                        <th>actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

@endsection
