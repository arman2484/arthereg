@extends('layouts/layoutMaster')

@section('title', 'Preview - Invoice')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-invoice.css') }}" />
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/offcanvas-add-payment.js') }}"></script>
    <script src="{{ asset('assets/js/offcanvas-send-invoice.js') }}"></script>
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
@endsection

@section('content')

    <div class="row invoice-preview">
        <!-- Invoice -->
        <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-body">
                    <div
                        class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column p-sm-3 p-0">
                        <div class="mb-xl-0 mb-4">
                            <div class="d-flex svg-illustration mb-3 gap-2">
                                <span class="app-brand-logo demo">
                                    <img src="{{ asset('assets/images/sitreg.png') }}"
                                        style="height: 6rem;width:7rem;object-fit: contain;margin-left:2rem"
                                        alt=""></span>
                            </div>
                        </div>
                        <div>
                            <h4>Invoice {{ $data->order_id }}</h4>
                            <div class="mb-2">
                                <span class="me-1">Date Issues:</span>
                                <span class="fw-medium">{{ date('d-m-Y', strtotime($data->created_at)) }} </span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-0" />
                <div class="card-body">
                    <div class="row p-sm-3 p-0">
                        <div class="col-xl-6 col-md-12 col-sm-5 col-12 mb-xl-0 mb-md-4 mb-sm-0 mb-4">
                            <h6 class="pb-2">Invoice To:</h6>
                            @php
                                $fname = $userAddress->first_name ? $userAddress->first_name : '';
                                $lname = $userAddress->last_name ? $userAddress->last_name : '';
                                $full_name = $fname . ' ' . $lname;
                            @endphp
                            <p class="mb-1">{{ $full_name }}</p>
                            <p class="mb-1">{{ $userAddress->address . ' ' . $userAddress->locality }}</p>
                            <p class="mb-1">{{ $userAddress->city . ' ' . $userAddress->state }}</p>
                            <p class="mb-1">{{ $userAddress->mobile }}</p>
                        </div>
                        <div class="col-xl-6 col-md-12 col-sm-7 col-12">
                            <h6 class="pb-2">Bill To:</h6>
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="pe-3">Payment Mode:</td>
                                        <td>{{ $data->payment_mode }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table border-top m-0">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Cost</th>
                                <th>Qty</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $value)
                                <tr>
                                    <td class="text-nowrap">{{ $value->product_name }}</td>
                                    <td class="text-nowrap">{{ $value->product_about }}</td>
                                    <td>${{ $value->product_sale_price ? $value->product_sale_price : $value->product_price }}
                                    </td>
                                    <td>{{ $value->quantity }}</td>
                                    <td>{{ $value->quantity * ($value->product_sale_price ? $value->product_sale_price : $value->product_price) }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="align-top px-4 py-3">
                                    <p class="mb-2">
                                    </p>
                                </td>
                                <td class="text-end px-4 py-3">
                                    <p class="mb-2">Subtotal:</p>
                                    <p class="mb-2">Discount:</p>
                                    <p class="mb-0">Total:</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="fw-medium mb-2">${{ $subTotal }}</p>
                                    <p class="fw-medium mb-2">${{ $discount_amount }}</p>
                                    <p class="fw-medium mb-0">${{ $total }}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <!-- /Invoice -->

        <!-- Invoice Actions -->
        <div class="col-xl-3 col-md-4 col-12">
            <div class="card">
                <div class="card-body">
                    <a class="btn btn w-100 mb-3"
                        style="background: #E0BFB8; outline:none; outline-color:#E0BFB8; color:white" target="_blank"
                        href="{{ url('app/invoice/print/' . $id) }}">
                        Print
                    </a>
                </div>
            </div>
        </div>
        <!-- /Invoice Actions -->
    </div>

    <!-- Offcanvas -->
    @include('_partials/_offcanvas/offcanvas-send-invoice')
    @include('_partials/_offcanvas/offcanvas-add-payment')
    <!-- /Offcanvas -->
@endsection
