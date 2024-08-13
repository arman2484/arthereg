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
                            <h2>Loyalty Points</h2>
                            <span> <a href="index">Home</a> / Loyalty Points</span>
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
            <div class="bg-[#00438F] bg-opacity-50 h-full w-full p-3 rounded-lg grid grid-cols-1 grid-rows-2  ">
                <div class="w-full flex justify-around items-center gap-3">
                    <img class="h-24 w-24" src="/assets/images/loyalty_points_new.png" alt="">
                    <div class="font-bold text-xl text-center">
                        <div>Total Points</div>
                        <div id="total_loyaltypoints" class="text-2xl"></div>
                    </div>
                </div>
                <div class="w-full flex justify-center h-fit mt-4">
                    <button
                        class="model-oraganic-product bg-[#00438F] text-white px-2 lg:px-4 py-2 rounded-xl font-medium "
                        data-bs-toggle="modal" href="#amountview" role="button">
                        <span>Convert to Currency</span>
                    </button>
                </div>
            </div>
            {{-- How to use --}}
            <div class="bg-gray-100 p-3 px-4 rounded-lg mt-3">
                <h3 class="font-semibold text-xl">How To Use</h3>
                <div class="space-x-2">
                    <i class="ri-circle-fill text-xs text-[#00438F]"></i>
                    <span class="text-xs">Convert your loyalty point to wallet money.</span>
                </div>
                <div class="space-x-2">
                    <i class="ri-circle-fill text-xs text-[#00438F]"></i>
                    <span class="text-xs">Minimum 200 Points required to convert into currency</span>
                </div>
            </div>
        </div>
        <div class="col-span-2 ">
            {{-- Transaction History --}}
            <div class="w-full max-w-[70rem] mb-5 mx-auto p-3 border rounded-lg min-h-[30rem]">
                <div class="flex justify-between items-center mb-4 ">
                    <div class="text-base lg:text-2xl font-semibold">Transaction History</div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between  bg-[#00438F] bg-opacity-20 py-3 rounded-lg px-4">
                        <div>Amount</div>
                        <div>Date & Time</div>
                    </div>
                    <div id="transactiondatapoints" class="space-y-2 text-sm">
                    </div>
                </div>
                <!-- Inside the HTML body -->
                <div class="grid place-content-center min-h-[25rem]" id="notransactionfound">
                    <img class="h-40 w-44 object-contain" src="/assets/images/multiple_cards.png" alt="">
                    <div class="font-semibold">No Transactions Are Found</div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="amountview" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered cr-modal-dialog">
            <div class="modal-content">
                <button type="button" class="cr-close-model btn-close mt-2 mr-2" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <div class="modal-body">
                    <div class=" flex flex-col items-center justify-center w-full gap-7 py-7 ">
                        <h3 class="font-semibold text-lg ">Convert Loyalty Points to Wallet Currency</h3>
                        <div class="text-xs text-center font-medium">Your loyalty points will be converted to currency
                            and transferred
                            to your wallet.</div>
                        <div class="text-base font-medium text-[#00438F]">Conversion Rate: 10 points = $1.00</div>
                        <input type="text" id="amount_add" placeholder="Enter Amount"
                            class="border w-full max-w-96 rounded-lg px-3 py-3 mx-auto outline-none text-center ">
                        <div class="text-base font-medium text-[#00438F]">Your current loyalty points: <span
                                id="loyalty_points_value"></span></div>
                        <button
                            onclick="PointstoAmount($('#amount_add').val()); ShowLoyaltyPoints(); fetchPointsTransaction();"
                            class="w-full max-w-96 rounded-lg py-[12px] px-3 text-white bg-[#00438F]">Submit</button>
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


    {{-- Scripts --}}
    <script src="{{ asset('assets/js/loyaltypoints.js') }}"></script>
    <script src="{{ asset('assets/js/pointstransaction.js') }}"></script>

</body>

</html>
