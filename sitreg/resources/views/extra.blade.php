<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    @include('layouts.head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
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

    <!-- Breadcrumb -->
    <section class="section-breadcrumb">
        <div class="cr-breadcrumb-image">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="cr-breadcrumb-title">
                            <h2>Checkout</h2>
                            <span> <a href="index">Home</a> - Checkout</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Checkout section -->
    <section class="cr-checkout-section padding-tb-100">
        <div class="container">
            <div class="row">
                <!-- Sidebar Area Start -->
                <div class="cr-checkout-rightside col-lg-4 col-md-12">
                    <div class="cr-sidebar-wrap">
                        <!-- Sidebar Summary Block -->
                        <div class="cr-sidebar-block">
                            <div class="cr-sb-title">
                                <h3 class="cr-sidebar-title">Summary</h3>
                            </div>
                            <div class="cr-sb-block-content">
                                <div class="cr-checkout-summary">
                                    <div>
                                        <span class="text-left">Sub-Total</span>
                                        <span class="text-right">$0</span>
                                    </div>
                                    <div class="cr-checkout-summary-total">
                                        <span class="text-left">Total Amount</span>
                                        <span class="text-right">$0</span>
                                    </div>
                                </div>
                                <div class="cr-checkout-pro" id="cartCheckoutList">
                                    <!-- Cart items will be appended here dynamically -->
                                </div>
                            </div>
                        </div>
                        <!-- Sidebar Summary Block -->
                    </div>
                    {{-- <div class="cr-sidebar-wrap cr-checkout-del-wrap">
                        <!-- Sidebar Summary Block -->
                        <div class="cr-sidebar-block">
                            <div class="cr-sb-title">
                                <h3 class="cr-sidebar-title">Delivery Method</h3>
                            </div>
                            <div class="cr-sb-block-content">
                                <div class="cr-checkout-del">
                                    <div class="cr-del-desc">Please select the preferred shipping method to use on this
                                        order.</div>
                                    <form action="#">
                                        <span class="cr-del-option">
                                            <span>
                                                <span class="cr-del-opt-head">Free Shipping</span>
                                                <input type="radio" id="del1" name="radio-group" checked>
                                                <label for="del1">Rate - $0 .00</label>
                                            </span>
                                            <span>
                                                <span class="cr-del-opt-head">Flat Rate</span>
                                                <input type="radio" id="del2" name="radio-group">
                                                <label for="del2">Rate - $5.00</label>
                                            </span>
                                        </span>
                                        <span class="cr-del-commemt">
                                            <span class="cr-del-opt-head">Add Comments About Your Order</span>
                                            <textarea name="your-commemt" placeholder="Comments"></textarea>
                                        </span>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Sidebar Summary Block -->
                    </div> --}}
                    <div class="cr-sidebar-wrap cr-checkout-pay-wrap">
                        <!-- Sidebar Payment Block -->
                        <div class="cr-sidebar-block">
                            <div class="cr-sb-title">
                                <h3 class="cr-sidebar-title">Payment Method</h3>
                            </div>

                            <div class="cr-sb-block-content">
                                <div class="cr-checkout-pay">
                                    <div class="cr-pay-desc">Please select the preferred payment method to use on this
                                        order.</div>
                                    <form action="#" class="payment-options">
                                        <span class="cr-pay-option">
                                            <span>
                                                <input type="radio" id="pay1" name="radio-group" checked>
                                                <label for="pay1">Cash On Delivery</label>
                                            </span>
                                        </span>
                                        <span class="cr-pay-option">
                                            <span>
                                                <input type="radio" id="pay2" name="radio-group">
                                                <label for="pay2">Stripe</label>
                                            </span>
                                        </span>
                                        <span class="cr-pay-option">
                                            <span>
                                                <input type="radio" id="pay3" name="radio-group">
                                                <label for="pay3">Razor Pay</label>
                                            </span>
                                        </span>
                                        <span class="cr-pay-option">
                                            <span>
                                                <input type="radio" id="pay4" name="radio-group">
                                                <label for="pay4">Paypal</label>
                                            </span>
                                        </span>
                                        <span class="cr-pay-option">
                                            <span>
                                                <input type="radio" id="pay5" name="radio-group">
                                                <label for="pay5">Wallet</label>
                                            </span>
                                        </span>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Sidebar Payment Block -->
                    </div>
                    <div class="cr-sidebar-wrap cr-check-pay-img-wrap">
                        <!-- Sidebar Payment Block -->
                        <div class="cr-sidebar-block">
                            <div class="cr-sb-title">
                                <h3 class="cr-sidebar-title">Payment Method</h3>
                            </div>
                            <div class="cr-sb-block-content">
                                <div class="cr-check-pay-img-inner">
                                    <div class="cr-check-pay-img">
                                        <img src="assets/img/banner/payment.png" alt="payment">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Sidebar Payment Block -->
                    </div>

                    <!-- Hidden Modal Container -->
                    <div class="modal fade" id="paypalModal" tabindex="-1" role="dialog"
                        aria-labelledby="paypalModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h5 class="modal-title" id="paypalModalLabel">Payment with PayPal</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <!-- Modal Body -->
                                <div class="modal-body">
                                    <div id="paypal-button-container"></div>
                                    <!-- Place your PayPal button container here -->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="cr-checkout-leftside col-lg-8 col-md-12 m-t-991">
                    <!-- checkout content Start -->
                    <div class="cr-checkout-content">
                        <div class="cr-checkout-inner">
                            {{-- <div class="cr-checkout-wrap mb-30">
                                <div class="cr-checkout-block cr-check-new">
                                    <h3 class="cr-checkout-title">New Customer</h3>
                                    <div class="cr-check-block-content">
                                        <div class="cr-check-subtitle">Checkout Options</div>
                                        <form action="#">
                                            <span class="cr-new-option">
                                                <span>
                                                    <input type="radio" id="account1" name="radio-group" checked>
                                                    <label for="account1">Register Account</label>
                                                </span>
                                                <span>
                                                    <input type="radio" id="account2" name="radio-group">
                                                    <label for="account2">Guest Account</label>
                                                </span>
                                            </span>
                                        </form>
                                        <div class="cr-new-desc">By creating an account you will be able to shop
                                            faster,
                                            be up to date on an order's status, and keep track of the orders you have
                                            previously made.
                                        </div>
                                        <span>
                                            <button class="cr-button mt-30" type="submit">Continue</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="cr-checkout-block cr-check-login">
                                    <h3 class="cr-checkout-title">Returning Customer</h3>
                                    <div class="cr-check-login-form">
                                        <form action="#" method="post">
                                            <span class="cr-check-login-wrap">
                                                <label>Email Address</label>
                                                <input type="text" name="name"
                                                    placeholder="Enter your email address" required>
                                            </span>
                                            <span class="cr-check-login-wrap">
                                                <label>Password</label>
                                                <input type="password" name="password"
                                                    placeholder="Enter your password" required>
                                            </span>

                                            <span class="cr-check-login-wrap cr-check-login-btn">
                                                <button class="cr-button mr-15" type="submit">Login</button>
                                                <a class="cr-check-login-fp" href="#">Forgot Password?</a>
                                            </span>
                                        </form>
                                    </div>
                                </div>

                            </div> --}}
                            <div class="cr-checkout-wrap">
                                <div class="cr-checkout-block cr-check-bill">
                                    <h3 class="cr-checkout-title">Billing Details</h3>
                                    <div class="cr-bl-block-content">
                                        <div class="cr-check-subtitle">Checkout Options</div>
                                        <span class="cr-bill-option">
                                            <span>
                                                <input type="radio" id="bill1" name="radio-group">
                                                <label for="bill1">I want to use an existing address</label>
                                            </span>
                                            <span>
                                                <input type="radio" id="bill2" name="radio-group" checked>
                                                <label for="bill2">I want to use new address</label>
                                            </span>
                                        </span>
                                        <div class="cr-check-bill-form mb-minus-24" id="billingForm">
                                            <form action="#" method="post">
                                                <span class="cr-bill-wrap cr-bill-half">
                                                    <label>First Name*</label>
                                                    <input type="text" name="firstname"
                                                        placeholder="Enter your first name" required>
                                                </span>
                                                <span class="cr-bill-wrap cr-bill-half">
                                                    <label>Last Name*</label>
                                                    <input type="text" name="lastname"
                                                        placeholder="Enter your last name" required>
                                                </span>
                                                <span class="cr-bill-wrap">
                                                    <label>Address*</label>
                                                    <input type="text" name="address"
                                                        placeholder="Enter Your Address">
                                                </span>
                                                <span class="cr-bill-wrap">
                                                    <label>Locality*</label>
                                                    <input type="text" name="locality"
                                                        placeholder="Enter Your Locality">
                                                </span>
                                                <span class="cr-bill-wrap cr-bill-half">
                                                    <label>Phone Number*</label>
                                                    <input type="tel" name="mobile"
                                                        placeholder="Enter your phone number">
                                                </span>
                                                <span class="cr-bill-wrap cr-bill-half">
                                                    <label>Country Code*</label>
                                                    <input type="text" name="country_code"
                                                        placeholder="Enter your country code">
                                                </span>
                                                <span class="cr-bill-wrap cr-bill-half">
                                                    <label>City*</label>
                                                    <input type="text" name="city"
                                                        placeholder="Enter your city" required>
                                                </span>
                                                <span class="cr-bill-wrap cr-bill-half">
                                                    <label>Pin Code*</label>
                                                    <input type="number" name="pincode"
                                                        placeholder="Enter your pin code" required>
                                                </span>
                                                <span class="cr-bill-wrap cr-bill-half">
                                                    <label>State*</label>
                                                    <input type="text" name="state"
                                                        placeholder="Enter your state" required>
                                                </span>
                                                <span class="cr-bill-wrap cr-bill-half">
                                                    <label>Type</label>
                                                    <span class="cr-bl-select-inner">
                                                        <select name="cr_select_type" class="cr-bill-select">
                                                            <option selected disabled>Type</option>
                                                            <option value="Work">Work</option>
                                                            <option value="Home">Home</option>
                                                        </select>
                                                    </span>
                                                </span>
                                                <span style="margin-left: 15px; margin-top: -10px;">
                                                    <button class="cr-button" type="button"
                                                        onclick="FillBillingDetails()">Save</button>
                                                </span>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <span class="cr-check-order-btn">
                                <a class="cr-button mt-30" href="#">Place Order</a>
                            </span>
                        </div>
                    </div>
                    <!--cart content End -->
                </div>
            </div>
        </div>
    </section>
    <!-- Checkout section End -->


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

    <!-- Include necessary JavaScript files -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>
    <script src="{{ asset('assets/js/cart-checkout.js') }}"></script>
    <script src="{{ asset('assets/js/billing-details.js') }}"></script>
    <script src="{{ asset('assets/js/cartpayment.js') }}"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script
        src="https://www.paypal.com/sdk/js?client-id=AVzMVWctLyouPgmfv9Nh6E5KakydG4JHiFGm-fgg6HRqFYUW-gHVKS1ebRfPgDOr2uYABGGcnU_3RaSL">
    </script>

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#openPaypalModal').click(function() {
                $('#paypalModal').modal('show'); // Opens the PayPal modal
            });
        });
    </script>


</body>

</html>
