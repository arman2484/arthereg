<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    @include('layouts.head')
    <style>
        .cr-button {
            background-color: #00438F;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .save-button {
            background-color: #00438F;
        }

        .cr-paking-delivery {
            margin-top: 0px !important;
            border: none !important;
            padding: 0px !important;
        }

        .nav.nav-tabs {
            border: none !important;
        }
    </style>

    <script src="https://cdn.tailwindcss.com"></script>
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
        <div class="cr-breadcrumb-image h-20">
            <div class="container ">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="cr-breadcrumb-title">
                            <h2>Wallet </h2>
                            <span> <a href="index">Home</a> / Wallet </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Amount In Wallet -->
    @include('layouts.amountinwallet')

    <!-- profileTabbar -->
    @include('layouts.profileTabbar')


    {{-- Upcoming Orders and Previous Orders --}}

    <div class="w-full max-w-[70rem] mb-5 mx-auto grid grid-cols-1 lg:grid-cols-3 lg:gap-3 py-3 gap-y-3 ">
        <div style="background-image: url('/assets/images/wallet_bg.jpg')"
            class="col-span-1  bg-cover bg-center rounded-lg h-52 ">
            <div class="bg-[#00438F] bg-opacity-50 h-full w-full p-3 rounded-lg grid grid-cols-2  ">
                <div class="w-full flex items-center flex-col gap-3">
                    <img class="h-24 w-24" src="/assets/images/wallet_icon.png" alt="">
                    <div class="font-bold text-xl text-center">
                        <div>Total Balance</div>
                        <!-- Add an element with id="total_balance" to display the total balance -->
                        <div id="total_balance" class="text-2xl">$0</div>
                    </div>
                </div>
                <div class="w-full flex justify-center h-fit mt-4">
                    <button
                        class="model-oraganic-product bg-[#00438F] text-white px-2 lg:px-4 py-2 rounded-xl font-medium "
                        data-bs-toggle="modal" href="#quickview" role="button">
                        <i class="ri-add-line"></i>
                        <span>Add Balance</span>
                    </button>
                </div>
                <div class="modal fade" id="paypalModal" tabindex="-1" role="dialog"
                    aria-labelledby="paypalModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h5 class="modal-title" id="paypalModalLabel">Payment with PayPal</h5>
                                <button type="button" class="close" onclick="closePaypalModal()" aria-label="Close">
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
        </div>
        <div class="col-span-2 ">
            {{-- Transaction History --}}
            <div class="w-full max-w-[70rem] mb-5 mx-auto p-3 border rounded-lg">
                <div class="flex justify-between items-center mb-4 ">
                    <div class="text-base lg:text-2xl font-semibold">Transaction History</div>
                    {{-- <div class="dropdown">
                        <button
                            class="border !border-[#00438F] cursor-pointer bg-[#00438F]  bg-opacity-20 rounded-lg px-3 text-sm py-2 dropdown-toggle"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            All Transaction
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">All Transaction</a></li>
                            <li><a class="dropdown-item" href="#">Order Transaction</a></li>
                            <li><a class="dropdown-item" href="#">Add Fund</a></li>
                        </ul>
                    </div> --}}
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between  bg-[#00438F] bg-opacity-20 py-3 rounded-lg px-4">
                        <div>Transaction Type</div>
                        <div>Amount</div>
                        <div>Date & Time</div>
                    </div>
                    <div id="transactiondata" class="space-y-2 text-sm">
                    </div>
                </div>
                <div class="grid place-content-center min-h-[25rem]" id="notransaction">
                    <img class="h-40 w-44 object-contain" src="/assets/images/multiple_cards.png" alt="">
                    <div class="font-semibold">No Transactions Are Found</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Balance Modal -->
    <div class="modal fade" id="quickview" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered cr-modal-dialog">
            <div class="modal-content">
                <button type="button" class="cr-close-model btn-close mt-2 mr-2" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <div class="modal-body">
                    <div class=" flex flex-col items-center justify-center w-full gap-7 py-7 ">
                        <h3 class="font-semibold text-xl ">Add Fund To Wallet</h3>
                        <div class="text-sm font-medium">Add fund by from secured digital payment gateways</div>
                        <input type="text" id="amount_input" placeholder="Enter Amount"
                            class="border w-full max-w-96 rounded-lg px-3 py-3 mx-auto outline-none text-center ">
                        <div class="text-base font-medium">Payment Methods(Faster & secure way to pay bill)</div>

                        {{-- Payment Options --}}
                        <div class="hidden flex-col w-full px-10 " id="payment_options">
                            <div class="flex  gap-y-2 flex-col w-full">
                                <label for="paypal"
                                    class="flex gap-5 items-center cursor-pointer rounded-lg hover:bg-[#F4F7FA] px-4 py-1 w-full">
                                    <input type="radio" name="payment_options" id="paypal"
                                        class="h-5 w-5 accent-[#000]" />
                                    <span class="h-14 text-Dark rounded-lg flex gap-3 items-center">
                                        <img src="/assets/images/paypal.png" class="h-16 w-16  object-contain"
                                            alt="" />
                                    </span>
                                </label>
                                {{-- <label for="razorpay"
                                    class="flex gap-5 items-center  px-4  rounded-lg hover:bg-[#F4F7FA] w-full cursor-pointer">
                                    <input type="radio" name="payment_options" id="razorpay"
                                        class="h-5 w-5 accent-[#000] object-contain" />
                                    <span class="h-14 text-Dark rounded-lg flex gap-3 items-center">
                                        <img src="/assets/images/razorpay.png" class="h-24 w-24" alt="" />
                                    </span>
                                </label> --}}
                                <label for="stripe"
                                    class="flex gap-5 items-center px-4 rounded-lg hover:bg-[#F4F7FA] w-full cursor-pointer">
                                    <input type="radio" name="payment_options" id="stripe"
                                        class="h-5 w-5 accent-[#000]">
                                    <span class="h-14 text-Dark rounded-lg flex gap-3 items-center">
                                        <img src="/assets/images/stripe.png" class="h-16 w-16 object-contain"
                                            alt="">
                                    </span>
                                </label>
                            </div>
                        </div>
                        <button onclick="handleAddFund()"
                            class="w-full max-w-96 rounded-lg py-[12px] px-3 text-white bg-[#00438F]">Add Fund</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let amount_input = document.getElementById("amount_input");
        amount_input.addEventListener("input", (e) => {
            console.log(e.target.value);

            if (e.target.value != "" && e.target.value != undefined && e.target.value != null) {
                document.getElementById("payment_options").style.display = "flex"
            } else {
                document.getElementById("payment_options").style.display = "none"

            }

        });

        function handleAddFund() {
            const selectedOption = document.querySelector('input[name="payment_options"]:checked');
            if (selectedOption) {
                const paymentMethod = selectedOption.id;
                if (paymentMethod === "paypal") {
                    openPaypalModal();
                } else if (paymentMethod === "stripe") {
                    addFundToWallet();
                }
            } else {
                alert('Please select a payment method.');
            }
        }
    </script>

    {{-- Scripts --}}
    <script src="{{ asset('assets/js/addamounttowallet.js') }}"></script>
    <script src="{{ asset('assets/js/transactionhistory.js') }}"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script
        src="https://www.paypal.com/sdk/js?client-id=AVzMVWctLyouPgmfv9Nh6E5KakydG4JHiFGm-fgg6HRqFYUW-gHVKS1ebRfPgDOr2uYABGGcnU_3RaSL"
        data-namespace="paypal_sdk">
        >
    </script>

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function openPaypalModal() {
            $('#quickview').modal('hide');
            $('#paypalModal').modal('show');
        }

        function closePaypalModal() {
            $('#paypalModal').modal('hide');
        }
    </script>

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

</body>

</html>
