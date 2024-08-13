@extends('layouts/layoutMaster')

@section('title', 'Order Detail')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/app-ecommerce-order-details.js') }}"></script>
    <script src="{{ asset('assets/js/modal-add-new-address.js') }}"></script>
    <script src="{{ asset('assets/js/modal-edit-user.js') }}"></script>
@endsection

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Sitreg /</span> Order Details
    </h4>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        {{-- {{ dd($data->order_id) }} --}}
        <div class="d-flex flex-column justify-content-center">
            <h5 class="mb-1 mt-3">Order {{ $data->OrderID }} <span class="badge bg-label-success me-2 ms-2">Paid</span>
                {{-- {{dd($data->is_status)}} --}}
                @if ($data->is_status == 1)
                    <span class="badge bg-label-info">Confirmed</span>
                @elseif ($data->is_status == 2)
                    <span class="badge bg-label-success">Delivered</span>
                @elseif ($data->is_status == 3)
                    <span class="badge bg-label-danger">Cancel</span>
                @elseif ($data->is_status == 0)
                    <span class="badge bg-label-primary">Pending</span>
                @endif
            </h5>
            {{-- <p class="text-body">{{ $data->OrderID }}, <span id="orderYear"></span>, 5:48 (ET)</p> --}}
        </div>
        <div class="d-flex align-content-center flex-wrap gap-2">
            <button class="btn btn-label-danger" onclick="deleteOrder({{ $data->order_id }})">Delete Order</button>
        </div>
    </div>

    <!-- Order Details Table -->

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0">Order details</h5>
                </div>
                <div class="card-datatable table-responsive">
                    {{-- @dd($records) --}}
                    <table class="datatables-order-details table">
                        <input type="hidden" name="" id="order_details" value="{{ $data->order_id }}">
                        <thead>
                            <tr>
                                <th class="w-25">products</th>
                                <th class="w-25">price</th>
                                <th class="w-25">qty</th>
                                <th class="w-25">size</th>
                                <th class="w-25">color</th>
                                <th>total</th>
                            </tr>
                            @foreach ($records as $value)
                                <tr>
                                    <td class="text-nowrap">{{ $value->product_name }}</td>
                                    <td>${{ $value->product_sale_price ? $value->product_sale_price : $value->product_price }}
                                    </td>
                                    <td>{{ $value->quantity }}</td>
                                    <td>{{ $value->product_size }}</td>
                                    <td>{{ $value->product_color }}</td>
                                    <td>${{ $value->quantity * ($value->product_sale_price ? $value->product_sale_price : $value->product_price) }}
                                    </td>
                                </tr>
                            @endforeach

                        </thead>
                    </table>
                    <div class="d-flex justify-content-end align-items-center m-3 mb-2 p-1">
                        <div class="order-calculations">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="w-px-100">Subtotal:</span>
                                <span id="" class="subtotal text-heading">${{ $subTotal }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="w-px-100">Discount:</span>
                                <span class="text-heading mb-0">${{ $discount_amount }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <h6 class="w-px-100 mb-0">Total:</h6>
                                <h6 class="mb-0">${{ $total }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title m-0">Customer details</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-start align-items-center mb-4">
                        <div class="avatar me-2">
                            <img src="{{ $data->image ? asset('assets/images/users_images/' . $data->image) : asset('assets/images/user-default.png') }}"
                                alt="Avatar" class="rounded-circle">
                        </div>
                        <div class="d-flex flex-column">
                            <a href="{{ url('app/user/view/account') }}" class="text-body text-nowrap">
                                @php
                                    $first_name = $data->first_name ? $data->first_name : '';
                                    $last_name = $data->last_name ? $data->last_name : '';
                                @endphp
                                <h6 class="mb-0"> {{ $first_name . ' ' . $last_name }} </h6>
                            </a>
                            <small class="text-muted">Customer ID: {{ $data->id }}</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h6>Contact info</h6>
                    </div>
                    @if ($data->email)
                        <p class=" mb-1">Email: {{ $data->email }}</p>
                    @else
                        <p class=" mb-0">Mobile: {{ $data->mobile }}</p>
                    @endif
                </div>
            </div>

            <div class="card mb-4">

                <div class="card-header d-flex justify-content-between">
                    <h6 class="card-title m-0">Shipping address</h6>
                </div>
                <div class="card-body">
                    @php
                        $firstName = $address->first_name ? $address->first_name : '';
                        $lastName = $address->last_name ? $address->last_name : '';
                    @endphp
                    <p class="mb-0"> {{ $firstName . ' ' . $lastName }}<br>
                        {{ $address->address . ' ' . $address->locality }} <br>{{ $data->city }}
                        {{ $address->mobile }}
                        <br>{{ $address->state }} <br>
                    </p>
                </div>

            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card mb-4">

            <div class="card-header d-flex justify-content-between">
                <h6 class="card-title m-0">Order status change</h6>
            </div>
            <div class="card-body">
                <select name="is_status" class="form-control" id="is_status">
                    <option value="">Select Status</option>
                    <option value="1" {{ $data->is_status == 1 ? 'selected' : '' }}>Confirmed</option>
                    <option value="2"{{ $data->is_status == 2 ? 'selected' : '' }}>Delivered </option>
                    <option value="3"{{ $data->is_status == 3 ? 'selected' : '' }}>Cancel </option>
                    <option value="0"{{ $data->is_status == 0 ? 'selected' : '' }}>Pending</option>
                </select><br>
                <input type="hidden" id="order_id" value="{{ $data->order_id }}">
                <button class="btn btn-primary" id="submit" type="button">Submit</button>
            </div>

        </div>
    </div>

    <!-- Modals -->
    @include('_partials/_modals/modal-edit-user')
    @include('_partials/_modals/modal-add-new-address')
@endsection
