@extends('layouts/layoutMaster')

@section('title', 'Invoice (Print version) - Pages')

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-invoice-print.css') }}" />
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/app-invoice-print.js') }}"></script>
@endsection

@section('content')
    <div class="invoice-print p-5">

        <div class="d-flex justify-content-between flex-row">
            <div class="mb-4">
                <div class="d-flex svg-illustration mb-3 gap-2">
                    <span class="app-brand-logo demo">
                        <img src="{{ asset('assets/images/sitreg.png') }}"
                            style="height: 6rem;width:7rem;object-fit: contain;margin-left:2rem" alt=""></span>
                </div>
                {{-- <p class="mb-1">Office 149, 450 South Brand Brooklyn</p>
      <p class="mb-1">San Diego County, CA 91905, USA</p>
      <p class="mb-0">+1 (123) 456 7891, +44 (876) 543 2198</p> --}}
            </div>
            <div>
                <h4>Invoice {{ $data->order_id }}</h4>
                <div class="mb-2">
                    <span>Date Issues:</span>
                    <span class="fw-medium">{{ date('d-m-Y', strtotime($data->created_at)) }} </span>
                </div>
            </div>
        </div>

        <hr />

        <div class="row d-flex justify-content-between mb-4">
            <div class="col-sm-6 w-50">
                <h6>Invoice To:</h6>
                <p class="mb-1">{{ $userAddress->first_name . ' ' . $userAddress->last_name }}</p>
                <p class="mb-1">{{ $userAddress->address . ' ' . $userAddress->locality }}</p>
                <p class="mb-1">{{ $userAddress->city . ' ' . $userAddress->state }}</p>
                <p class="mb-1">{{ $userAddress->mobile }}</p>
            </div>
            <div class="col-sm-6 w-50">
                <h6>Bill To:</h6>
                <table>
                    <tbody>
                        <tr>
                            <td class="pe-3">Payment Mode:</td>
                            <td>{{ $data->payment_mode }}</td>
                    </tbody>
                </table>
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
@endsection
