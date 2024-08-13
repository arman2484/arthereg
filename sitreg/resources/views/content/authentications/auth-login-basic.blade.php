@php
    $customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Login')

@section('vendor-style')
    <!-- Vendor -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
@endsection

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/pages-auth.js') }}"></script>
@endsection

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card">
                    <div class="card-body">
                        <!-- Image instead of Logo -->
                        <div class="app-brand justify-content-center">
                            <img src="{{ asset('assets/images/sitreg.png') }}" alt="Sitreg"
                                style="width: 180px; height: 170px;">
                        </div>

                        {{-- Auth Login --}}
                        <form id="formAuthentication" class="mb-3" action="{{ route('auth-login') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email or Username</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="Enter your email" autofocus>
                                @if ($errors->has('email'))
                                    <p class="error-message">{{ $errors->first('email') }}</p>
                                @endif
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Password</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="•••••••••" aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    @if ($errors->has('password'))
                                        <p class="error-message">{{ $errors->first('password') }}</p>
                                    @endif
                                </div>
                            </div>
                            @if (Session::has('success'))
                                <p class="success-message">{{ Session::get('success') }}</p>
                            @endif
                            @if ($errors->has('error'))
                                <p class="error-message" style="color: red;">{{ $errors->first('error') }}</p>
                            @endif
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary d-grid w-100"
                                    style="background-color: #00438F; color: white; border-color: #00438F;">Sign in</button>
                            </div>
                        </form>

                        <style>
                            /* Override Bootstrap's default hover color */
                            .btn-primary:hover {
                                background-color: #00438F !important;
                                border-color: #00438F !important;
                            }

                            .table-box table {
                                width: 100%;
                                border-collapse: collapse;
                            }

                            .table-box td,
                            .table-box th {
                                border: 1px solid #ccc;
                                padding: 8px;
                            }

                            /* Style for the button */
                            .copy-button {
                                background-color: #00438F;
                                color: white;
                                border: none;
                                padding: 8px 16px;
                                border-radius: 4px;
                                cursor: pointer;
                            }

                            .copy-button:hover {
                                background-color: #00438F;
                            }
                        </style>

                        <!-- Table -->
                        <div class="table-box">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Email</td>
                                        <td id="copyemail">primocys@gmail.com</td>
                                    </tr>
                                    <tr>
                                        <td>Password</td>
                                        <td id="copypassword">123456</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="center">
                                            <button class="copy-button" onclick="copyCredentials()">Copy</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    function copyCredentials() {
        var email = document.getElementById('copyemail').innerText;
        var password = document.getElementById('copypassword').innerText;

        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }
</script>
