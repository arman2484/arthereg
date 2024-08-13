<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    @include('layouts.head')
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
        }

        .cr-paking-delivery {
            margin-top: 0px !important;
            border: none !important;
            padding: 0px !important;
        }

        .nav.nav-tabs {
            border: none !important;
        }

        #couponlistdata {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100px;
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
                            <h2>Coupons</h2>
                            <span> <a href="index">Home</a> / Coupons</span>
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

    <div class="w-full max-w-[70rem] mb-5 mx-auto p-3  rounded-lg border gap-4 grid grid-cols-1 lg:grid-cols-2 "
        id="couponlistdata">
        {{-- Dynamic Coupon Data --}}
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

    <script>
        function copyToClipBoard(code) {
            navigator.clipboard.writeText(code)
                .then(() => {
                    Toastify({
                        text: "Coupon code copied to clipboard!",
                        duration: 1000,
                        close: true,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: 'linear-gradient(to right, #00438F, #B2CBDD)',
                        stopOnFocus: true,
                    }).showToast();
                })
                .catch(error => {
                    console.error('Error copying to clipboard: ', error);
                });
        }
    </script>

    {{-- Scripts --}}
    <script src="{{ asset('assets/js/couponlist.js') }}"></script>

</body>

</html>
