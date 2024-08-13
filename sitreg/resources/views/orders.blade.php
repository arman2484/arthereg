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

        .collapse {
            visibility: visible !important;
        }

        .accordion-button:not(.collapsed) {
            background-color: transparent !important;
            outline: none !important;
            box-shadow: none !important;
        }

        .accordion-button:focus {
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
        }

        .accordion-item {
            border: none;
            padding: 1rem !important;
            margin-block: 1rem;
            border: top !important;
        }

        @media only screen and (max-width: 600px) {
            .accordion-item {
                border: none;
                padding: 0.4rem !important;
                margin-block: 1rem;
                border: top !important;
            }
        }

        .accordion-button::after {
            position: absolute;
            top: 15px;
            right: 15px;
        }

        @media only screen and (max-width: 500px) {
            .accordion-button::after {
                display: none;

            }
        }

        .no-order-message {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #B2CBDD;
            color: #00438F;
            border: 1px solid #B2CBDD;
            border-radius: 5px;
        }

        /* Hide modal by default */
        #addReviewModal {
            display: none;
        }

        /* Show modal when needed */
        .show-modal {
            display: block;
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
                            <h2>My Orders</h2>
                            <span> <a href="index">Home</a> / My Orders</span>
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
    <div class="w-full max-w-[70rem] mb-5 mx-auto p-4 lg:p-5 border rounded-lg">
        <div class="cr-paking-delivery" style="margin-top: 0px;">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                        data-bs-target="#description" type="button" role="tab" aria-controls="description"
                        aria-selected="true">Upcoming Orders</button>
                </li>
            </ul>
        </div>

        {{-- Tab Content =========================================================== --}}
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
            </div>
            <div class="tab-pane fade show pt-10" id="previous" role="tabpanel" aria-labelledby="previous-tab">
                <div class="grid place-content-center min-h-[20rem]">
                    <img class="h-40 w-44 object-contain" src="/assets/images/no_orders_found.png" alt="">
                    <div class="font-semibold">No Orders are found</div>
                </div>
            </div>
        </div>

        <!-- Add Review Modal -->
        {{-- <div class="modal fade" id="addReviewModal" tabindex="-1" aria-labelledby="addReviewModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered cr-modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addReviewModalLabel">Add Review</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="reviewForm">
                            <div class="form-group mb-4">
                                <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                                <div class="flex space-x-1 mt-2">
                                    <input type="radio" id="star5" name="rating" value="5" class="hidden" />
                                    <label for="star5" title="5 stars"
                                        class="cursor-pointer text-yellow-500 hover:text-yellow-600 text-2xl">
                                        ★
                                    </label>
                                    <input type="radio" id="star4" name="rating" value="4" class="hidden" />
                                    <label for="star4" title="4 stars"
                                        class="cursor-pointer text-yellow-500 hover:text-yellow-600 text-2xl">
                                        ★
                                    </label>
                                    <input type="radio" id="star3" name="rating" value="3" class="hidden" />
                                    <label for="star3" title="3 stars"
                                        class="cursor-pointer text-yellow-500 hover:text-yellow-600 text-2xl">
                                        ★
                                    </label>
                                    <input type="radio" id="star2" name="rating" value="2" class="hidden" />
                                    <label for="star2" title="2 stars"
                                        class="cursor-pointer text-yellow-500 hover:text-yellow-600 text-2xl">
                                        ★
                                    </label>
                                    <input type="radio" id="star1" name="rating" value="1" class="hidden" />
                                    <label for="star1" title="1 star"
                                        class="cursor-pointer text-yellow-500 hover:text-yellow-600 text-2xl">
                                        ★
                                    </label>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="reviewMessage"
                                    class="block text-sm font-medium text-gray-700">Message</label>
                                <textarea
                                    class="form-control mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    id="reviewMessage" name="reviewMessage" rows="4" placeholder="Write your review here..."></textarea>
                            </div>
                            <button class="btn-review bg-[#00438f] text-white py-1 px-3 rounded mt-2">
                                Submit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="modal fade" id="addReviewModal" tabindex="-1" aria-labelledby="addReviewModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered cr-modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addReviewModalLabel">Add Review</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="reviewForm">
                            <div class="form-group mb-4">
                                <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                                <div class="flex space-x-1 mt-2" id="ratingStars">
                                    <input type="hidden" id="product_id" name="product_id">
                                    <input type="radio" id="star1" name="rating" value="1" class="hidden" />
                                    <label for="star1" title="1 star" data-value="1"
                                        class="cursor-pointer text-gray-400 text-2xl">
                                        ★
                                    </label>
                                    <input type="radio" id="star2" name="rating" value="2" class="hidden" />
                                    <label for="star2" title="2 stars" data-value="2"
                                        class="cursor-pointer text-gray-400 text-2xl">
                                        ★
                                    </label>
                                    <input type="radio" id="star3" name="rating" value="3" class="hidden" />
                                    <label for="star3" title="3 stars" data-value="3"
                                        class="cursor-pointer text-gray-400 text-2xl">
                                        ★
                                    </label>
                                    <input type="radio" id="star4" name="rating" value="4" class="hidden" />
                                    <label for="star4" title="4 stars" data-value="4"
                                        class="cursor-pointer text-gray-400 text-2xl">
                                        ★
                                    </label>
                                    <input type="radio" id="star5" name="rating" value="5"
                                        class="hidden" />
                                    <label for="star5" title="5 stars" data-value="5"
                                        class="cursor-pointer text-gray-400 text-2xl">
                                        ★
                                    </label>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="reviewMessage"
                                    class="block text-sm font-medium text-gray-700">Message</label>
                                <textarea
                                    class="form-control mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    id="reviewMessage" name="reviewMessage" rows="4" placeholder="Write your review here..."></textarea>
                            </div>
                            <button type="submit"
                                class="btn-review-call bg-[#00438f] text-white py-1 px-3 rounded mt-2">
                                Submit
                            </button>
                        </form>
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
    <script src="{{ asset('assets/js/orderlist.js') }}"></script>


</body>

</html>
