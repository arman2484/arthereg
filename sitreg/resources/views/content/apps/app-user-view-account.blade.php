@extends('layouts/layoutMaster')

@section('title', 'User View - Pages')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-user-view.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/modal-edit-user.js') }}"></script>
    <script src="{{ asset('assets/js/app-user-view.js') }}"></script>
    <script src="{{ asset('assets/js/app-user-view-account.js') }}"></script>
@endsection

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">User / View /</span> Account
    </h4>
    <div class="row">
        <!-- User Sidebar -->
        <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
            <!-- User Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="user-avatar-section">
                        <div class=" d-flex align-items-center flex-column">
                            @if ($data->image)
                                <img class="img-fluid rounded my-4"
                                    src="{{ asset('assets/images/users_images/' . $data->image) }}" height="110"
                                    width="110" alt="User avatar" />
                            @else
                                <img class="img-fluid rounded my-4"
                                    src="{{ asset('assets/images/user-default.png') }}" height="110"
                                    width="110" alt="User avatar" />
                            @endif
                            <div class="user-info text-center">
                                <h4 class="mb-2">{{ $data->first_name . ' ' . $data->last_name }}</h4>
                                {{-- <span class="badge bg-label-secondary">Author</span> --}}
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-around flex-wrap my-4 py-3">

                        <div class="d-flex align-items-start mt-3 gap-3">
                            <span class="badge bg-label-primary p-2 rounded"><i
                                    class='menu-icon tf-icons bx bx-cart-alt'></i></span>
                            <div>
                                <h5 class="mb-0">{{ $orderCount }}</h5>
                                <span>Orders</span>
                            </div>
                        </div>
                    </div>
                    <h5 class="pb-2 border-bottom mb-4">Details</h5>
                    <div class="info-container">
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <span class="fw-medium me-2">First Name:</span>
                                <span>{{ $data->first_name }}</span>
                            </li>
                            <li class="mb-3">
                                <span class="fw-medium me-2">Last Name:</span>
                                <span>{{ $data->last_name }}</span>
                            </li>
                            <li class="mb-3">
                                <span class="fw-medium me-2">DOB:</span>
                                <span>{{ $data->dob }}</span>
                            </li>
                            <li class="mb-3">
                                <span class="fw-medium me-2">Gender:</span>
                                <span>{{ $data->gender }}</span>
                            </li>
                            <li class="mb-3">
                                @if ($data->email)
                                    <span class="fw-medium me-2">Email:</span>
                                    <span>{{ $data->email }}</span>
                                @else
                                    <span class="fw-medium me-2">Mobile:</span>
                                    <span>{{ $data->mobile }}</span>
                                @endif
                            </li>
                            <li class="mb-3">
                                <span class="fw-medium me-2">Status:</span>
                                @if ($data->status == 1)
                                    <span class="badge bg-label-success">Active</span>
                                @else
                                    <span class="badge bg-label-danger">Inactive</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Plan Card -->
        </div>
        <!--/ User Sidebar -->


        <!-- User Content -->
        <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
            <div class="card mb-4">
                <h5 class="card-header">User Order's List</h5>
                <div class="table-responsive mb-3">
                    <table class="table datatable-project border-top">
                        <input type="hidden" value="{{ $id }}" id="user_id">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Order</th>
                                <th class="text-nowrap">Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!--/ User Content -->
    </div>

    <!-- Modal -->
    @include('_partials/_modals/modal-edit-user')
    @include('_partials/_modals/modal-upgrade-plan')
    <!-- /Modal -->
@endsection
