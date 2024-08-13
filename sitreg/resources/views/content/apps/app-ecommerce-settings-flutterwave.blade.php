@extends('layouts/layoutMaster')

@section('title', 'eCommerce Settings Details - Apps')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('/assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('/assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('/assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('/assets/js/app-ecommerce-settings.js') }}"></script>
@endsection

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">eCommerce /</span> Payment
    </h4>

    <div class="row g-4">

        <!-- Navigation -->
        <div class="col-12 col-lg-4">
            <div class="d-flex justify-content-between flex-column mb-3 mb-md-0">
                <ul class="nav nav-align-left nav-pills flex-column">
                    <li class="nav-item mb-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <a class="nav-link " href="{{ url('/app/ecommerce/setting/stripe') }}">
                                <i class="bx bxs-credit-card me-2"></i>
                                <span class="align-middle">Stripe Payment</span>
                                @if (App\Models\Settings::where('id', 1)->first()->status == 1)
                                    <i title="Active" class="fa fa-circle text-success ms-auto" aria-hidden="true"></i>
                                @else
                                    <i title="Active" class="fa fa-circle text-danger ms-auto" aria-hidden="true"></i>
                                @endif
                            </a>
                        </div>
                    </li>
                    <li class="nav-item mb-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <a class="nav-link" href="{{ url('/app/ecommerce/settings/razer') }}">
                                <i class="bx bxs-credit-card me-2"></i>
                                <span class="align-middle">Razer Payment</span>
                                @if (App\Models\Settings::where('id', 2)->first()->status == 1)
                                    <i title="Active" class="fa fa-circle text-success ms-auto" aria-hidden="true"></i>
                                @else
                                    <i title="Active" class="fa fa-circle text-danger ms-auto" aria-hidden="true"></i>
                                @endif
                            </a>
                        </div>
                    </li>
                    <li class="nav-item mb-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <a class="nav-link active" href="{{ url('/app/ecommerce/settings/flutterwave') }}">
                                <i class="bx bxs-credit-card me-2"></i>
                                <span class="align-middle">Flutterwave Payment</span>
                                @if ($data->status == 1)
                                    <i title="Active" class="fa fa-circle text-success ms-auto" aria-hidden="true"></i>
                                @else
                                    <i title="Active" class="fa fa-circle text-danger ms-auto" aria-hidden="true"></i>
                                @endif
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /Navigation -->

        <!-- Options -->
        <div class="col-12 col-lg-8 pt-4 pt-lg-0">

            <div class="tab-content p-0">
                <!-- Store Details Tab -->
                <div class="tab-pane fade show active" id="store_details" role="tabpanel">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">Flutterwave Payment</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3 g-3">
                                <form action="{{ url('app/ecommerce/setting/stripe') }}" method="POST" class="pt-0">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    <div class="mb-3">
                                        <label class="form-label" for="ecommerce-category-title"><strong>Payment Flutterwave
                                                Key</strong></label>
                                        <input type="password" class="form-control" id="ecommerce-category-title"
                                            placeholder="Enter stripe key" name="stripe_key" aria-label="category title"
                                            value="{{ !empty($data->stripe_publish_key) ? $data->stripe_publish_key : '' }}">
                                        <small class="text-muted"><i class="fa fa-question-circle"></i>
                                            Enter your RAZER FLUTTERWAVE ID
                                        </small>
                                    </div>
                                    <br>
                                    <div class="mb-3">
                                        <label class="form-label" for="ecommerce-category-title"><strong>Flutterwave
                                                Secret</strong></label>
                                        <input type="password" class="form-control" id="ecommerce-category-title"
                                            placeholder="Enter stripe secret" name="stripe_secret"
                                            aria-label="category title"
                                            value="{{ !empty($data->stripe_secret) ? $data->stripe_secret : '' }}">
                                        <small class="text-muted"><i class="fa fa-question-circle"></i>
                                            Enter your FLUTTERWAVE SECRET ID
                                        </small>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                        <span class="mb-0 h6">Status</span>
                                        <div class="w-25 d-flex justify-content-end">
                                            <label class="switch switch-primary switch-sm me-4 pe-2">
                                                <input type="checkbox" name="status" class="switch-input"
                                                    {{ $data->status == 1 ? 'checked' : '' }}>
                                                <span class="switch-toggle-slider">
                                                    <span class="switch-on">
                                                        <span class="switch-off"></span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <span class="text-muted fw-light">(Please Enable For Payment Gateway)</span>
                                    <br>
                                    <br>
                                    <div class="mb-3">
                                        <button type="submit"
                                            class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <!-- /Options-->
    </div>

@endsection
