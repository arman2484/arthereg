@extends('layouts/layoutMaster')

@section('title', 'Store Request List')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('/assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection
@section('page-script')
    <script src="{{ asset('/assets/js/app-ecommerce-store-request-list.js') }}"></script>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Store /</span> Store Request List
    </h4>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="datatables-store-request table border-top">
                <thead>
                    <tr>
                        <th></th>
                        <th>store logo</th>
                        <th>store details</th>
                        <th>owner</th>
                        <th>request</th>
                        <th>actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

@endsection
