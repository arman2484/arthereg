<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    @include('layouts.head')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAMZ4GbRFYSevy7tMaiH5s0JmMBBXc0qBA&callback=initAutocomplete&libraries=places&v=weekly"
        async></script>

    {{-- Lato fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&display=swap" rel="stylesheet">

    <style>
        .font-lato {
            font-family: 'Lato', sans-serif;
        }

        .otp-container {
            display: flex;
            gap: 0.5rem;
            justify-content: center
        }

        .otp-input {
            width: 3.5rem;
            height: 3.5rem;
            font-size: 1.5rem;
            text-align: center;
            background-color: #F5F5F5;
            border-radius: 0.5rem;
            outline: none;
        }

        .otp-input:focus {
            border-color: #000;
        }

        .hover-effect:hover {
            background-color: #002855;
        }

        .cr-button {
            background-color: #64b496;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .save-button {
            background-color: #64b496;
        }

        #add_address_container {
            display: none;
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body class="body-bg-6">
    <!-- Loader -->
    <div id="cr-overlay">
        <span class="loader"></span>
    </div>

    <!-- Header -->
    @include('layouts.header')

    <!-- Mobile menu -->
    @include('layouts.mobilemenu')

    <div class="mx-auto w-fit min-h-[70vh] grid place-content-center">
        {{-- Phone Number --}}
        <div id="phone-signin-form"
            class="flex flex-col font-lato items-center justify-center w-full gap-4 py-7 shadow-xl p-4">
            <img class="h-20 w-36 object-contain" src="/assets/images/sitreg.png" alt="">
            <div class="space-y-3 text-center">
                <h3 class="font-bold text-2xl">Sign In</h3>
            </div>
            <div class="w-full px-5 flex flex-col gap-2 justify-center">
                <input type="tel" id="phone" class="rounded-lg bg-[#F5F5F5] w-full py-[12px] outline-none"
                    name="phone" placeholder="Enter your mobile number" aria-label="Enter your mobile number"
                    aria-describedby="basic-addon3">
                <div class="text-right text-[#00438F] w-fit ml-auto mr-0 text-sm font-semibold cursor-pointer"
                    id="use-email-link">Use Email</div>
                <div id="phoneErrorMessage" class="text-red-500 text-sm mt-2"></div>
            </div>
            <div class="flex w-80 items-center justify-center gap-3">
                <hr class="border w-full" />
                <span class="w-20">OR</span>
                <hr class="border w-full" />
            </div>
            <div class="flex gap-3 w-96 justify-evenly">
                <div id="apple-login"
                    class="bg-[#F5F5F5] h-14 w-14 rounded-lg grid place-content-center cursor-pointer">
                    <img src="/assets/images/apple_logo.png" class="h-10 w-10 p-1" alt="" />
                </div>
                <div id="buttonDiv" class="bg-[#F5F5F5] h-14 w-14 rounded-lg grid place-content-center cursor-pointer">
                    <img src="/assets/images/google_logo.png" class="h-10 w-10 p-1" alt="" />
                </div>
            </div>
            <label for="accept_privacy" class="text-sm flex items-center">
                <input class="h-4 w-4" type="checkbox" name="accept_privacy" id="accept_privacy">
                <span class="pl-3">
                    On clicking continue, I agree to <span class="text-[#00438F]">Terms of Service</span> & <span
                        class="text-[#00438F]">Privacy Policy</span>
                </span>
            </label>
            <button id="continue-button"
                class="bg-[#00438F] rounded-lg w-96 text-lg Lato text-white font-medium text-center py-2 cursor-pointer hover-effect">
                Continue
            </button>
        </div>

        {{-- Email --}}
        <div id="email-signin-form"
            class="flex flex-col font-lato items-center justify-center w-full gap-4 py-7 shadow-xl p-4 hidden">
            <img class="h-20 w-36 object-contain" src="/assets/images/sitreg.png" alt="">
            <div class="space-y-3 text-center">
                <h3 class="font-bold text-2xl">Sign In</h3>
            </div>
            <div class="w-full px-5 flex flex-col gap-2 justify-center">
                <input type="email" id="email"
                    class="bg-[#F5F5F5] w-full rounded-lg px-3 py-[12px] mx-auto outline-none"
                    placeholder="Enter your email address">
                <div class="text-right cursor-pointer text-[#00438F] w-fit ml-auto mr-0 text-sm font-semibold"
                    id="use-phone-link">Use Mobile Number</div>
                <div id="emailErrorMessage" class="text-red-500 text-sm mt-2"></div>
            </div>
            <div class="flex w-80 items-center justify-center gap-3">
                <hr class="border w-full" />
                <span class="w-20">OR</span>
                <hr class="border w-full" />
            </div>
            <div class="flex gap-3 w-96 justify-evenly">
                <div id="apple-signlogin"
                    class="bg-[#F5F5F5] h-14 w-14 rounded-lg grid place-content-center cursor-pointer">
                    <img src="/assets/images/apple_logo.png" class="h-10 w-10 p-1" alt="" />
                </div>
                <div id="google-signlogin"
                    class="bg-[#F5F5F5] h-14 w-14 rounded-lg grid place-content-center cursor-pointer">
                    <img src="/assets/images/google_logo.png" class="h-10 w-10 p-1" alt="" />
                </div>
            </div>
            <label for="accept_privacy_email" class="text-sm flex items-center">
                <input class="h-4 w-4" type="checkbox" name="accept_privacy_email" id="accept_privacy_email">
                <span class="pl-3">
                    On clicking continue, I agree to <span class="text-[#00438F]">Terms of Service</span> &
                    <span class="text-[#00438F]"> Privacy Policy</span>
                </span>
            </label>
            <button id="continue-email-button" data-bs-toggle="modal" href="#enter_otp_modal"
                class="bg-[#00438F] rounded-lg w-96 text-lg Lato text-white font-medium text-center py-2 cursor-pointer hover-effect">
                Continue
            </button>
        </div>
    </div>

    {{-- Enter Phone Otp --}}
    <div class="modal fade" id="enter_phone_otp_modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered cr-modal-dialog">
            <div class="modal-content">
                <button type="button" class="cr-close-model btn-close mt-2 mr-2" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <div class="modal-body">
                    <div class="flex flex-col font-lato items-center justify-center w-full gap-5 py-7">
                        <img class="h-20 w-36 object-contain" src="/assets/images/sitreg.png" alt="">
                        <div class="space-y-3 text-center">
                            <h3 class="font-bold text-2xl">Enter OTP</h3>
                            <div class="font-medium text-lg text-[#858585]" id="otpMessage">

                                <!-- Dynamic message will be inserted here -->
                            </div>
                        </div>
                        <div class="w-full px-5 flex flex-col gap-2 justify-center">
                            <div class="otp-container" id="otpContainer">
                                <input type="text" maxlength="1" class="otp-input">
                                <input type="text" maxlength="1" class="otp-input">
                                <input type="text" maxlength="1" class="otp-input">
                                <input type="text" maxlength="1" class="otp-input">
                                <input type="text" maxlength="1" class="otp-input">
                                <input type="text" maxlength="1" class="otp-input">
                            </div>
                            <div class="flex justify-between">
                                <div id="editPhoneNumber" class="text-[#00438F] text-sm font-semibold cursor-pointer"
                                    data-bs-dismiss="modal">
                                    Edit Phone Number
                                </div>
                                <div id="resendOTPButton" class="text-[#858585] text-sm font-semibold cursor-pointer">
                                    Resend OTP in <span id="resendTimerDisplay">60</span> sec
                                </div>
                            </div>
                        </div>
                        <div id="otpErrorMessage" class="text-red-500 font-medium hidden">Incorrect OTP.
                            Please try again.</div>
                        <button id="otpContinueButton" onclick="continueWithOTP()"
                            class="bg-[#00438F] rounded-lg w-96 text-lg Lato text-white font-medium text-center py-2 cursor-pointer hover-effect">
                            Continue
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="enter_personal_data_modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered cr-modal-dialog">
            <div class="modal-content">
                <button type="button" class="cr-close-model btn-close mt-2 mr-2" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <div class="modal-body">
                    <div class="flex flex-col font-lato items-center justify-center w-full gap-5 py-7">
                        <img class="h-20 w-36 object-contain" src="/assets/images/sitreg.png" alt="">
                        <div class="space-y-3 text-center">
                            <h3 class="font-bold text-2xl">Enter Your Details</h3>
                        </div>
                        <div class="w-full px-5 flex flex-col gap-4">
                            <input type="text" id="firstName" placeholder="First Name" class="form-control">
                            <input type="text" id="lastName" placeholder="Last Name" class="form-control">
                            <input type="text" id="mobile" placeholder="Phone Number" class="form-control">
                            <input type="text" id="user_refer_code" placeholder="Referral Code (Optional)"
                                class="form-control">
                        </div>
                        <!-- Add this div to display error messages -->
                        <div id="RedalerterrorMessage" class="text-red-500"></div>
                        <button onclick="NamesRegister()"
                            class="bg-[#00438F] rounded-lg w-96 text-lg Lato text-white font-medium text-center py-2 cursor-pointer hover-effect">
                            Continue
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="enter_personalemail_data_modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered cr-modal-dialog">
            <div class="modal-content">
                <button type="button" class="cr-close-model btn-close mt-2 mr-2" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <div class="modal-body">
                    <div class="flex flex-col font-lato items-center justify-center w-full gap-5 py-7">
                        <img class="h-20 w-36 object-contain" src="/assets/images/sitreg.png" alt="">
                        <div class="space-y-3 text-center">
                            <h3 class="font-bold text-2xl">Enter Your Details</h3>
                        </div>
                        <div class="w-full px-5 flex flex-col gap-4">
                            <input type="text" id="firstNameEmail" placeholder="First Name" class="form-control">
                            <input type="text" id="lastNameEmail" placeholder="Last Name" class="form-control">
                            <input type="text" id="emailEmail" placeholder="Email" class="form-control">
                            <input type="text" id="user_refer_codeEmail" placeholder="Referral Code (Optional)"
                                class="form-control">
                        </div>
                        <!-- Add this div to display error messages -->
                        <div id="RedalerterrorMessage" class="text-red-500"></div>
                        <button onclick="NamesRegisterEmail()"
                            class="bg-[#00438F] rounded-lg w-96 text-lg Lato text-white font-medium text-center py-2 cursor-pointer hover-effect">
                            Continue
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Enter Email Otp --}}
    <div class="modal fade" id="enter_email_otp_modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered cr-modal-dialog">
            <div class="modal-content">
                <button type="button" class="cr-close-model btn-close mt-2 mr-2" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <div class="modal-body">
                    <div class="flex flex-col font-lato items-center justify-center w-full gap-5 py-7">
                        <img class="h-20 w-36 object-contain" src="/assets/images/sitreg.png" alt="">
                        <div class="space-y-3 text-center">
                            <h3 class="font-bold text-2xl ">Enter Otp</h3>
                            <div class="font-medium text-lg text-[#858585]" id="otpMessageEmail">

                                <!-- Dynamic message will be inserted here -->
                            </div>
                        </div>
                        <div class="w-full px-5 flex flex-col gap-2 justify-center">
                            <div class="otp-container" id="otpContainer">
                                <input type="text" maxlength="1" class="otp-input">
                                <input type="text" maxlength="1" class="otp-input">
                                <input type="text" maxlength="1" class="otp-input">
                                <input type="text" maxlength="1" class="otp-input">
                                <input type="text" maxlength="1" class="otp-input">
                                <input type="text" maxlength="1" class="otp-input">
                            </div>
                            <div class="flex justify-between">
                                <div id="editEmail" class="text-[#00438F] text-sm font-semibold cursor-pointer"
                                    data-bs-dismiss="modal">
                                    Edit Email
                                </div>
                                <div id="resendEmailOTPButton"
                                    class="text-[#858585] text-sm font-semibold cursor-pointer">
                                    Resend OTP in <span id="resendEmailTimerDisplay">60</span> sec
                                </div>
                            </div>
                        </div>
                        <div id="otpEmailErrorMessage" class="text-red-500 font-medium hidden">Incorrect OTP.
                            Please try again.</div>
                        <button id="otpEmailContinueButton" onclick="continueWithEmailOTP()"
                            class="bg-[#00438F] rounded-lg w-96 text-lg Lato text-white font-medium text-center py-2 cursor-pointer hover-effect">
                            Continue
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('layouts.footer')

    <!-- Tab to top -->
    @include('layouts.tabtotop')

    <!-- Cart -->
    @include('layouts.cart')

    <!-- Side-tool -->
    @include('layouts.sidetool')

    <!-- Main Scripts -->
    @include('layouts.webscripts')

    {{-- For Phone Input --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>


    {{-- Phone Number Validation --}}
    <script>
        let baseUrl = '{{ env('BASE_URL_LIVE') }}';
        var iti;
        $(document).ready(function() {
            var input = document.querySelector("#phone");
            var acceptPrivacyCheckbox = document.getElementById("accept_privacy");
            var continueButton = $('#continue-button');
            var phoneErrorMessage = document.getElementById("phoneErrorMessage");

            // Function to enable or disable the continue button based on checkbox status and phone number validity
            function toggleContinueButton() {
                if (input.value.trim()) {
                    continueButton.prop('disabled', false);
                    phoneErrorMessage.textContent = "";
                } else {
                    continueButton.prop('disabled', true);
                    if (input.value.trim() === "") {
                        phoneErrorMessage.textContent = "";
                    }
                }
            }

            // Toggle button status when checkbox status changes
            acceptPrivacyCheckbox.addEventListener('change', toggleContinueButton);

            // Toggle button status when phone input changes
            input.addEventListener('input', toggleContinueButton);

            iti = window.intlTelInput(input, {
                initialCountry: "IN",
                separateDialCode: true,
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
            });

            iti.setCountry("in");

            $('#continue-button').click(function(e) {
                e.preventDefault();
                var mobile = iti.getNumber();

                // Check if the phone number is valid
                if (!iti.isValidNumber()) {
                    phoneErrorMessage.textContent = "Please enter a valid phone number.";
                    return;
                }

                // Check if the checkbox is checked
                if (!acceptPrivacyCheckbox.checked) {
                    phoneErrorMessage.textContent =
                        "Please agree to the Terms of Service and Privacy Policy.";
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/api/new-register',
                    data: {
                        mobile: mobile
                    },
                    success: function(response) {
                        if (response.Message === "OTP sent successfully") {
                            sessionStorage.setItem("mobile", mobile);
                            displayOTPMessage();
                            $('#userPhoneNumber').text(mobile);
                            var otpModal = new bootstrap.Modal(document.getElementById(
                                'enter_phone_otp_modal'));
                            otpModal.show();
                        }
                    },
                    error: function(xhr, status, error) {
                        phoneErrorMessage.textContent = 'Error: ' + error;
                    }
                });
            });

            $('#use-email-link').click(function() {
                $('#phone-signin-form').addClass('hidden');
                $('#email-signin-form').removeClass('hidden');
            });

            $('#use-phone-link').click(function() {
                $('#email-signin-form').addClass('hidden');
                $('#phone-signin-form').removeClass('hidden');
            });
        });
    </script>

    {{-- Email Validation --}}
    <script>
        $(document).ready(function() {
            var emailInput = document.querySelector("#email");
            var acceptPrivacyEmailCheckbox = document.getElementById("accept_privacy_email");
            var continueEmailButton = $('#continue-email-button');
            var emailErrorMessage = document.getElementById("emailErrorMessage");
            let baseUrl = '{{ env('BASE_URL_LIVE') }}';

            // Function to validate email format
            function validateEmail(email) {
                // Regular expression for email validation
                var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailPattern.test(email);
            }

            // Function to enable or disable the continue button based on checkbox status and email validity
            function toggleContinueEmailButton() {
                if (validateEmail(emailInput.value)) {
                    continueEmailButton.prop('disabled', false);
                    emailErrorMessage.textContent = "";
                } else {
                    continueEmailButton.prop('disabled', true);
                    if (emailInput.value !== "") {
                        emailErrorMessage.textContent = "Please enter a valid email address.";
                    } else {
                        emailErrorMessage.textContent = "";
                    }
                }
            }

            // Toggle button status when checkbox status changes
            acceptPrivacyEmailCheckbox.addEventListener('change', toggleContinueEmailButton);

            // Toggle button status when email input changes
            emailInput.addEventListener('input', toggleContinueEmailButton);

            // Handle continue button click for email
            continueEmailButton.click(function(e) {
                e.preventDefault();
                var email = emailInput.value;

                // Check if the email address is valid
                if (!validateEmail(email)) {
                    emailErrorMessage.textContent = "Please enter a valid email address.";
                    return;
                }

                // Check if the checkbox is checked
                if (!acceptPrivacyEmailCheckbox.checked) {
                    emailErrorMessage.textContent =
                        "Please agree to the Terms of Service and Privacy Policy.";
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/api/new-register',
                    data: {
                        email: email
                    },
                    success: function(response) {
                        if (response.message === "OTP sent successfully") {
                            sessionStorage.setItem("email", email);
                            displayEmailOtp();
                            var otpModal = new bootstrap.Modal(document.getElementById(
                                'enter_email_otp_modal'));
                            otpModal.show();
                        }
                    },

                    error: function(xhr, status, error) {
                        phoneErrorMessage.textContent = 'Error: ' + error;
                    }
                });
            });
            $('#use-email-link').click(function() {
                $('#phone-signin-form').addClass('hidden');
                $('#email-signin-form').removeClass('hidden');
            });

            $('#use-phone-link').click(function() {
                $('#email-signin-form').addClass('hidden');
                $('#phone-signin-form').removeClass('hidden');
            });
        });
    </script>


    {{-- Resend otp for phone --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('.otp-input');
            const otpContainer = document.getElementById('otpContainer');

            const handleInputChange = (index, value) => {
                if (value && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
                const otpValues = Array.from(otpInputs).map(input => input.value);
                if (otpValues.every(val => val !== '')) {
                    const enteredOtp = otpValues.join('');
                    console.log('Entered OTP:', enteredOtp);
                    document.getElementById('otpContinueButton').click();
                }
            };

            const handleInputBackspace = (index, value) => {
                if (value === '' && index > 0) {
                    otpInputs[index - 1].focus();
                }
            };

            otpInputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    handleInputChange(index, e.target.value);
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace') {
                        handleInputBackspace(index, e.target.value);
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const resendLink = document.getElementById('resendOTPButton');
            const resendTimer = document.getElementById('resendTimerDisplay');
            const timerDuration = 60;
            let resendCountdown = timerDuration;
            let resendInterval;

            function updateTimer() {
                resendTimer.innerHTML = resendCountdown;
            }

            function startCountdown() {
                resendLink.style.pointerEvents = 'none';
                resendLink.style.color = 'grey';
                resendCountdown = timerDuration;
                updateTimer();

                resendInterval = setInterval(function() {
                    resendCountdown--;
                    updateTimer();

                    if (resendCountdown <= 0) {
                        clearInterval(resendInterval);
                        resendLink.style.pointerEvents = 'auto';
                        resendLink.style.color = '#00438F';
                        resendTimer.innerHTML = '0';
                    }
                }, 1000);
            }

            startCountdown();

            resendLink.addEventListener('click', function() {
                resendLink.style.pointerEvents = 'none';
                resendLink.style.color = 'grey';

                startCountdown();
                resendCode();
            });

            function resendCode() {
                const mobile = sessionStorage.getItem("mobile");
                $.ajax({
                    type: 'POST',
                    url: '{{ env('BASE_URL') }}/api/new-register',
                    data: {
                        mobile: mobile
                    },
                    success: function(response) {
                        console.log('Code resent successfully');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error resending code:', error);
                    }
                });
            }
        });
    </script>


    {{-- Resend otp for email --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('.otp-input');
            const otpContainer = document.getElementById('otpContainer');

            const handleInputChange = (index, value) => {
                if (value && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
                const otpValues = Array.from(otpInputs).map(input => input.value);
                if (otpValues.every(val => val !== '')) {
                    const enteredOtp = otpValues.join('');
                    console.log('Entered OTP:', enteredOtp);
                    document.getElementById('otpEmailContinueButton').click();
                }
            };

            const handleInputBackspace = (index, value) => {
                if (value === '' && index > 0) {
                    otpInputs[index - 1].focus();
                }
            };

            otpInputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    handleInputChange(index, e.target.value);
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace') {
                        handleInputBackspace(index, e.target.value);
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const resendLink = document.getElementById('resendEmailOTPButton');
            const resendTimer = document.getElementById('resendEmailTimerDisplay');
            const timerDuration = 60;
            let resendCountdown = timerDuration;
            let resendInterval;

            function updateTimer() {
                resendTimer.innerHTML = resendCountdown;
            }

            function startCountdown() {
                resendLink.style.pointerEvents = 'none';
                resendLink.style.color = 'grey';
                resendCountdown = timerDuration;
                updateTimer();

                resendInterval = setInterval(function() {
                    resendCountdown--;
                    updateTimer();

                    if (resendCountdown <= 0) {
                        clearInterval(resendInterval);
                        resendLink.style.pointerEvents = 'auto';
                        resendLink.style.color = '#00438F';
                        resendTimer.innerHTML = '0';
                    }
                }, 1000);
            }

            startCountdown();

            resendLink.addEventListener('click', function() {
                resendLink.style.pointerEvents = 'none';
                resendLink.style.color = 'grey';

                startCountdown();
                resendCodeemail();
            });

            function resendCodeemail() {
                const email = sessionStorage.getItem("email");
                $.ajax({
                    type: 'POST',
                    url: '{{ env('BASE_URL') }}/api/new-register',
                    data: {
                        email: email
                    },
                    success: function(response) {
                        console.log('Code resent successfully');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error resending code:', error);
                    }
                });
            }
        });
    </script>


    {{-- Verify Phone User --}}
    <script>
        function continueWithOTP() {
            var mobile = sessionStorage.getItem("mobile");
            var otpInputs = document.querySelectorAll('.otp-input');
            var otp = Array.from(otpInputs).map(input => input.value).join('');

            $.ajax({
                type: 'POST',
                url: baseUrl + '/api/verify-user',
                data: {
                    mobile: mobile,
                    otp: otp
                },
                success: function(response) {
                    if (response.message === "Otp Verified..!") {
                        // Store user_id and token in local storage
                        localStorage.setItem('user_id', response.user_id);
                        localStorage.setItem('token', response.token);
                        // Check if first_name is empty
                        if (!response.first_name) {

                            // Close the current modal
                            var otpModal = bootstrap.Modal.getInstance(document.getElementById(
                                'enter_phone_otp_modal'));
                            if (otpModal) {
                                otpModal.hide();
                            }
                            // Show the personal data modal
                            var personalDataModal = new bootstrap.Modal(document.getElementById(
                                'enter_personalemail_data_modal'));
                            personalDataModal.show();
                        } else {
                            // Redirect to /shop
                            // window.location.href = baseUrl + '/shop';
                            history.back()
                        }

                    } else {
                        // Display error message
                        $('#otpErrorMessage').text('Incorrect OTP. Please try again.').removeClass('hidden');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error: ' + error);
                    // Display generic error message if request fails
                    $('#otpErrorMessage').text('An error occurred. Please try again later.').removeClass(
                        'hidden');
                }
            });
        }
    </script>

    {{-- <script>
        function continueWithOTP() {
            var mobile = sessionStorage.getItem("mobile");
            var otpInputs = document.querySelectorAll('.otp-input');
            var otp = Array.from(otpInputs).map(input => input.value).join('');

            $.ajax({
                type: 'POST',
                url: baseUrl + '/api/verify-user',
                data: {
                    mobile: mobile,
                    otp: otp
                },
                success: function(response) {
                    if (response.message === "Otp Verified..!") {
                        // Store user_id and token in local storage
                        localStorage.setItem('user_id', response.user_id);
                        localStorage.setItem('token', response.token);

                        // Fetch cart items from local storage
                        var cartItems = JSON.parse(localStorage.getItem('cart_items')) || [];
                        var token = localStorage.getItem('token');

                        // Process each cart item
                        cartItems.forEach(item => {
                            $.ajax({
                                type: 'POST',
                                url: baseUrlLive + '/api/add-cart',
                                headers: {
                                    Authorization: `Bearer ${token}`
                                },
                                data: {
                                    product_id: item.product_id,
                                    product_color: item.product_color,
                                    product_size: item.product_size,
                                    quantity: item.quantity
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response && response.message ===
                                        'Product added to cart successfully ...!') {
                                        // Cart addition successful, show toast notification
                                        Toastify({
                                            text: 'Product added to cart successfully!',
                                            theme: 'light',
                                            duration: 3000,
                                            close: true,
                                            gravity: 'top',
                                            position: 'right',
                                            backgroundColor: 'linear-gradient(to right, #00438F, #B2CBDD)',
                                            stopOnFocus: true
                                        }).showToast();
                                        $('.cr-add-button button').text(
                                        'Added'); // Assuming you have a class for the add to cart button
                                        // Change text to 'Add to Cart' after 1 second
                                        setTimeout(function() {
                                            $('.cr-add-button button').text(
                                                'Add to Cart');
                                        }, 1000);
                                        fetchProductList
                                    (); // Function to fetch updated product list if needed
                                    } else {
                                        console.error('Error adding product to cart:',
                                            response);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error adding product to cart:', error);
                                }
                            });
                        });

                        // Check if first_name is empty
                        if (!response.first_name) {
                            // Close the current modal
                            var otpModal = bootstrap.Modal.getInstance(document.getElementById(
                                'enter_phone_otp_modal'));
                            if (otpModal) {
                                otpModal.hide();
                            }
                            // Show the personal data modal
                            var personalDataModal = new bootstrap.Modal(document.getElementById(
                                'enter_personalemail_data_modal'));
                            personalDataModal.show();
                        } else {
                            // Redirect to /shop
                            // window.location.href = baseUrl + '/shop';
                            history.back();
                        }

                    } else {
                        // Display error message
                        $('#otpErrorMessage').text('Incorrect OTP. Please try again.').removeClass('hidden');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error: ' + error);
                    // Display generic error message if request fails
                    $('#otpErrorMessage').text('An error occurred. Please try again later.').removeClass(
                        'hidden');
                }
            });
        }
    </script> --}}



    {{-- Verify Email  User --}}
    <script>
        function continueWithEmailOTP() {
            var email = sessionStorage.getItem("email");
            var otpInputs = document.querySelectorAll('.otp-input');
            var otp = Array.from(otpInputs).map(input => input.value).join('');

            $.ajax({
                type: 'POST',
                url: baseUrl + '/api/verify-user',
                data: {
                    email: email,
                    otp: otp
                },
                success: function(response) {
                    if (response.message === "Otp Verified..!") {
                        // Store user_id and token in local storage
                        localStorage.setItem('user_id', response.user_id);
                        localStorage.setItem('token', response.token);

                        // Check if first_name is empty
                        if (!response.first_name) {

                            // Close the current modal
                            var otpModal = bootstrap.Modal.getInstance(document.getElementById(
                                'enter_email_otp_modal'));
                            if (otpModal) {
                                otpModal.hide();
                            }
                            // Show the personal data modal
                            var personalDataModal = new bootstrap.Modal(document.getElementById(
                                'enter_personal_data_modal'));
                            personalDataModal.show();
                        } else {
                            // Redirect to /shop
                            history.back()
                        }

                    } else {
                        // Display error message
                        $('#otpEmailErrorMessage').text('Incorrect OTP. Please try again.').removeClass(
                            'hidden');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error: ' + error);
                    // Display generic error message if request fails
                    $('#otpEmailErrorMessage').text('An error occurred. Please try again later.').removeClass(
                        'hidden');
                }
            });
        }
    </script>


    {{-- Display Phone Number Dynamic --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to display dynamic OTP message
            window.displayOTPMessage = function() {
                var mobile = sessionStorage.getItem("mobile");
                var message = document.getElementById("otpMessage");
                message.textContent = "Enter the 6-digit OTP sent on " + mobile;
            }

            // Call function to display OTP message when DOM is loaded
            displayOTPMessage();
        });
    </script>

    {{-- Display Email Dynamic --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to display dynamic OTP message
            window.displayEmailOtp = function() {
                var email = sessionStorage.getItem("email");
                var message = document.getElementById("otpMessageEmail");
                message.textContent = "Enter the 6-digit OTP sent on " + email;
            }

            // Call function to display OTP message when DOM is loaded
            displayEmailOtp();
        });
    </script>


    {{-- Social Logins --}}
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <script type="text/javascript"
        src="https://appleid.cdn-apple.com/appleauth/static/jsapi/appleid/1/en_US/appleid.auth.js"></script>


    {{-- Social login for Phone Section --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let baseUrl = '{{ env('BASE_URL_LIVE') }}';
            const googleLoginButton = document.getElementById('google-login');

            // Google Sign-In initialization
            gapi.load('auth2', function() {
                const auth2 = gapi.auth2.init({
                    client_id: '685660303526-ne6vcidgb7ko949s5rflm3qe61k1slia'
                });

                googleLoginButton.addEventListener('click', function() {
                    auth2.signIn().then(function(googleUser) {
                        const profile = googleUser.getBasicProfile();
                        const email = profile.getEmail();
                        socialLogin('google', email);
                    });
                });
            });

            // Apple Sign-In initialization
            AppleID.auth.init({
                clientId: 'YOUR_APPLE_CLIENT_ID',
                scope: 'name email',
                redirectURI: 'YOUR_REDIRECT_URI',
                usePopup: true //or false defaults to false
            });

            document.getElementById('apple-login').addEventListener('click', function() {
                AppleID.auth.signIn().then(function(response) {
                    const email = response.user.email;
                    socialLogin('apple', email);
                }).catch(function(error) {
                    console.error('Apple Sign-In Error:', error);
                });
            });

            function socialLogin(provider, email) {
                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/api/social-login',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        email: email,
                        login_type: provider,
                    }),
                    success: function(data) {
                        if (data.user_id) {
                            console.log(data.message);
                            // Optionally, you can redirect the user or perform other actions
                        } else {
                            console.error(data.error); // Handle error message
                        }
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            }
        });
    </script>

    {{-- Social Login for Email Section --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let baseUrl = '{{ env('BASE_URL_LIVE') }}';
            const googleLoginButton = document.getElementById('google-signlogin');

            // Google Sign-In initialization
            gapi.load('auth2', function() {
                const auth2 = gapi.auth2.init({
                    client_id: 'YOUR_GOOGLE_CLIENT_ID.apps.googleusercontent.com'
                });

                googleLoginButton.addEventListener('click', function() {
                    auth2.signIn().then(function(googleUser) {
                        const profile = googleUser.getBasicProfile();
                        const email = profile.getEmail();
                        socialLogin('google', email);
                    });
                });
            });

            // Apple Sign-In initialization
            AppleID.auth.init({
                clientId: 'YOUR_APPLE_CLIENT_ID',
                scope: 'name email',
                redirectURI: 'YOUR_REDIRECT_URI',
                usePopup: true //or false defaults to false
            });

            document.getElementById('apple-signlogin').addEventListener('click', function() {
                AppleID.auth.signIn().then(function(response) {
                    const email = response.user.email;
                    socialLogin('apple', email);
                }).catch(function(error) {
                    console.error('Apple Sign-In Error:', error);
                });
            });

            function socialLogin(provider, email) {
                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/api/social-login',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        email: email,
                        login_type: provider,
                    }),
                    success: function(data) {
                        if (data.user_id) {
                            console.log(data.message);
                            // Optionally, you can redirect the user or perform other actions
                        } else {
                            console.error(data.error); // Handle error message
                        }
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            }
        });
    </script>


    {{-- Show / Hide Email and Phone Number --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('use-email-link').addEventListener('click', function() {
                document.getElementById('phone-signin-form').classList.add('hidden');
                document.getElementById('email-signin-form').classList.remove('hidden');
            });

            document.getElementById('use-phone-link').addEventListener('click', function() {
                document.getElementById('email-signin-form').classList.add('hidden');
                document.getElementById('phone-signin-form').classList.remove('hidden');
            });
        });
    </script>


    <script>
        function NamesRegister() {
            let first_name = document.getElementById('firstName').value;
            let last_name = document.getElementById('lastName').value;
            let mobile = document.getElementById('mobile').value;
            let user_refer_code = document.getElementById('user_refer_code').value;
            let user_id = localStorage.getItem("user_id");
            let baseUrl = '{{ env('BASE_URL_LIVE') }}';

            // Check if first name and last name are not empty
            if (!first_name || !last_name) {
                let phoneErrorMessage = document.getElementById('RedalerterrorMessage');
                phoneErrorMessage.textContent = 'Please enter the above details';
                return;
            }

            $.ajax({
                type: 'POST',
                url: baseUrl + '/api/names-register',
                data: {
                    id: user_id,
                    first_name: first_name,
                    last_name: last_name,
                    mobile: mobile,
                    user_refer_code: user_refer_code
                },
                success: function(response) {
                    if (response.message === "Profile updated successfully!") {
                        history.back() // Redirect to /shop on success
                    }
                },
                error: function(xhr, status, error) {
                    let phoneErrorMessage = document.getElementById('RedalerterrorMessage');
                    phoneErrorMessage.textContent = 'Error: Please enter the above details';
                }
            });
        }
    </script>


    <script>
        function NamesRegisterEmail() {
            let first_name = document.getElementById('firstNameEmail').value;
            let last_name = document.getElementById('lastNameEmail').value;
            let email = document.getElementById('emailEmail').value;
            let user_refer_code = document.getElementById('user_refer_codeEmail').value;
            let user_id = localStorage.getItem("user_id");
            let baseUrl = '{{ env('BASE_URL_LIVE') }}';

            // Check if first name and last name are not empty
            if (!first_name || !last_name) {
                let phoneErrorMessage = document.getElementById('RedalerterrorMessage');
                phoneErrorMessage.textContent = 'Please enter the above details';
                return;
            }

            $.ajax({
                type: 'POST',
                url: baseUrl + '/api/names-registeremail',
                data: {
                    id: user_id,
                    first_name: first_name,
                    last_name: last_name,
                    email: email,
                    user_refer_code: user_refer_code
                },
                success: function(response) {
                    if (response.message === "Profile updated successfully!") {
                        history.back() // Redirect to /shop on success
                    }
                },
                error: function(xhr, status, error) {
                    let phoneErrorMessage = document.getElementById('RedalerterrorMessage');
                    phoneErrorMessage.textContent = 'Error: Please enter the above details';
                }
            });
        }
    </script>


</body>

</html>
