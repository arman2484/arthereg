<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    @include('layouts.head')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* CSS (styles.css) */
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
            /* Adjust as needed for save state */
        }

        .cr-paking-delivery {
            margin-top: 0px !important;
            border: none !important;
            padding: 0px !important;
        }

        .nav.nav-tabs {
            border: none !important;
        }

        .error-message {
            display: block;
            /* Make sure error messages are block-level elements */
            font-size: 0.875rem;
            /* Adjust font size as needed */
            color: #f87171;
            /* Red color for error messages */
        }

        .hidden {
            display: none;
            /* Hide the element */
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
                            <h2>Referral Code</h2>
                            <span> <a href="index">Home</a> / Referral Code</span>
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

    <div class="w-full max-w-[70rem] mb-5 mx-auto py-3 gap-y-3 ">

        <div class="w-full  mb-5 mt-28 lg:mt-auto mx-auto p-3 border flex flex-col items-center rounded-lg">
            <div class="grid max-w-[30rem] place-content-center gap-3 text-center min-h-[25rem]">
                <img class="h-44 w-48 mx-auto object-contain" src="/assets/images/refral_image.png" alt="">
                <div class="font-semibold">Invite Your Friends & Businesses</div>
                <div class="font-semibold text-[#63B496]">Copy your code and share your friends</div>
                <div
                    class="flex justify-between text-[#63B496] border-2 !border-dashed border-[#919191] rounded-lg py-2 p-3 w-full">
                    <div id="referCode"></div>
                    <img class="h-7 w-7 cursor-pointer" id="copyIcon" src="/assets/images/copy_icon.png"
                        alt="Copy">
                </div>
                <div class="text-center">OR SHARE</div>
                <div class="flex justify-center gap-3">
                    <img class="h-14 w-14" src="/assets/images/watsapp_icon.png" alt="">
                    <img class="h-14 w-14" src="/assets/images/gmail_icon.png" alt="">
                </div>

                <div class="bg-gray-100 max-w-[30rem] p-3 px-4 rounded-lg mt-3 text-left">
                    <h3 class="font-semibold text-xl">How To Use</h3>
                    <div class="space-x-2">
                        <i class="ri-circle-fill text-xs text-[#63B496]"></i>
                        <span class="text-xs">Invite your friends & businesses.</span>
                    </div>
                    <div class="space-x-2">
                        <i class="ri-circle-fill text-xs text-[#63B496]"></i>
                        <span class="text-xs">They register with ecommerce website with special offer</span>
                    </div>
                    <div class="space-x-2">
                        <i class="ri-circle-fill text-xs text-[#63B496]"></i>
                        <span class="text-xs">You made your earning</span>
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
    <script src="{{ asset('assets/js/refercode.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

</body>

</html>
