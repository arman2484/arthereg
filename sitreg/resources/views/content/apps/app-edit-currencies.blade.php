@extends('layouts/layoutMaster')

@section('title', 'eCommerce Product Category - Apps')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/quill/editor.css') }}" />
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('/assets/vendor/css/pages/app-ecommerce.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('/assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('/assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('/assets/vendor/libs/quill/quill.js') }}"></script>
@endsection

@section('page-script')
    {{-- <script src="{{ asset('assets/js/app-ecommerce-category-list.js') }}"></script> --}}
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Add /</span> Currency <a href="{{ url('app/ecommerce/currencies') }}"
            class="btn btn-secondary float-end">Back</a>
    </h4>

    <div class="app-ecommerce-category">
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="offcanvas-body border-top">
            <form action="{{ url('app/ecommerce/currencies/update/' . $currency->id) }}" method="POST"
                enctype="multipart/form-data" class="pt-0">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="ecommerce-category-title">Country</label>
                    <select class="form-control" name="country_id" id="country_id">
                        <option value="">Select Country</option>
                        @foreach ($data as $value)
                            <option value="{{ $value->id }}" @if ($value->id == $currency->country_id) selected @endif>
                                {{ $value->country }}
                            </option>
                        @endforeach
                    </select>

                    <span class="error">{{ $errors->first('country_id') }}</span>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="ecommerce-category-title">Currency</label>
                    <select class="form-control" name="currency_id" id="currency_id">
                        <option value="">Select Currency</option>
                        @foreach ($data as $value)
                            <option value="{{ $value->id }}">{{ $value->currency }}</option>
                        @endforeach
                    </select>
                    <span class="error">{{ $errors->first('currency_id') }}</span>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="ecommerce-category-title">Code</label>
                    <select class="form-control" name="code_id" id="code_id">
                        <option value="">Select Currency</option>
                        @foreach ($data as $value)
                            <option value="{{ $value->id }}">{{ $value->code }}</option>
                        @endforeach
                    </select>
                    <span class="error">{{ $errors->first('code_id') }}</span>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="ecommerce-category-title">Symbol</label>
                    <select class="form-control" name="symbol_id" id="symbol_id">
                        <option value="">Select Currency</option>
                        @foreach ($data as $value)
                            <option value="{{ $value->id }}">{{ $value->symbol }}</option>
                        @endforeach
                    </select>
                    <span class="error">{{ $errors->first('symbol_id') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center  pt-3">
                    <span class="mb-0 h6">Status</span>
                    <div class="w-25 d-flex justify-content-end">
                        <label class="switch switch-primary switch-sm me-4 pe-2">
                            <input type="checkbox" name="status" class="switch-input"
                                @if ($currency->status == '1') checked @endif>
                            <span class="switch-toggle-slider">
                                <span class="switch-on">
                                    <span class="switch-off"></span>
                                </span>
                            </span>
                        </label>
                    </div>
                </div>
                <span class="text-muted fw-light">(Please Enable For Currency)</span>
                <br>
                <br>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection

<script>
    $(document).ready(function() {
        $('#country_id').on('change', function(e) {
            // $('#country_id').ready(function() {
            var country_id = $('#country_id').val();
            $.ajax({
                url: "{{ route('get-currency') }}",
                type: "get",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    country_id: country_id
                },
                success: function(data) {
                    console.log(data);
                    $('#currency_id').empty();
                    $('#code_id').empty();
                    $('#symbol_id').empty();
                    // $.each(data.currencyData, function(index, value) {
                    // console.log(value);
                    $('#currency_id').append('<option value="' + data.id +
                        '">' + data.currency + '</option>');
                    $('#code_id').append('<option value="' + data.id + '">' +
                        data.code + '</option>');
                    $('#symbol_id').append('<option value="' + data.id +
                        '">' + data.symbol + '</option>');
                    // })
                }
            })
        });
    });
    $(document).ready(function() {
        // $('#country_id').on('change', function(e) {
        $('#country_id').ready(function() {
            var country_id = $('#country_id').val();
            $.ajax({
                url: "{{ route('get-currency') }}",
                type: "get",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    country_id: country_id
                },
                success: function(data) {
                    console.log(data);
                    $('#currency_id').empty();
                    $('#code_id').empty();
                    $('#symbol_id').empty();
                    // $.each(data.currencyData, function(index, value) {
                    // console.log(value);
                    $('#currency_id').append('<option value="' + data.id +
                        '">' + data.currency + '</option>');
                    $('#code_id').append('<option value="' + data.id + '">' +
                        data.code + '</option>');
                    $('#symbol_id').append('<option value="' + data.id +
                        '">' + data.symbol + '</option>');
                    // })
                }
            })
        });
    });
</script>
