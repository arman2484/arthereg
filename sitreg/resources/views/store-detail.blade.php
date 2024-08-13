<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    @include('layouts.head')

    <style>
        .store-details-header {
            display: flex;
            gap: 2rem;
            position: absolute;
            bottom: 0rem;
            /* background-color: black !important; */
            z-index: 99;
            width: 100%;
            padding-inline: 2rem;
            transform: translateY(4rem);
            height: 7rem;
        }

        .store-details-header-left {
            display: flex;
            background-color: #EFEFEF !important;
            width: 100%;
            padding-inline: 2rem;
            border-radius: 1rem;
            height: 100%;
            gap: 0rem !important;
            font-size: 1rem;
            padding-block: .7rem;
        }

        .store-details-header-right {
            display: flex;
            background-color: #fff !important;
            width: 15rem;
            border-radius: 20rem;
            width: 8rem;
            height: 7rem;
            box-shadow: 5px 10px 10px grey;
            overflow: hidden;
        }

        .selling-card-title {
            margin-bottom: 0rem !important;
        }

        * {
            font-family: 'Lato', sans-serif;
        }

        /* Modal Styles */
        .modal-dialog {
            max-width: 600px;
            /* Adjust the width to make it smaller */
        }

        .modal-body {
            max-height: 550px;
            /* Adjust the height as necessary */
            overflow-y: auto;
        }

        .cr-tab-content-from .post {
            margin-bottom: 20px;
        }

        .cr-tab-content-from .content {
            display: flex;
            align-items: center;
        }

        .cr-tab-content-from .content img {
            width: 50px;
            height: 50px;
            margin-right: 15px;
            border-radius: 50%;
        }

        .cr-tab-content-from .details {
            display: flex;
            flex-direction: column;
        }

        .cr-tab-content-from .details .date,
        .cr-tab-content-from .details .name {
            font-size: 14px;
        }

        .cr-t-review-rating {
            display: flex;
        }

        .cr-t-review-rating i {
            color: #f5c518;
            margin-right: 2px;
        }

        .heading {
            margin-bottom: 20px;
        }

        .cr-ratting-input {
            margin-bottom: 15px;
        }

        .form-submit {
            display: flex;
            flex-direction: column;
        }

        .cr-button {
            background-color: #0056b3;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }

        .cr-button:hover {
            background-color: #0056b3;
        }

        /* Close button styles */
        .close {
            padding: 0;
            background: none;
            border: none;
            font-size: 1.5rem;
            line-height: 1;
            color: #000;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            opacity: 0.75;
        }

        .icon_color {
            color: red;
        }

        /* Add styles for disabled button */
        .cr-add-button button.disabled-button {
            background-color: #ccc;
            /* Grey background */
            color: #888;
            /* Grey text color */
            border: 1px solid #ccc;
            /* Grey border */
            cursor: not-allowed;
            /* Change cursor to not-allowed */
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

    <!-- Breadcrumb -->
    <section class="section-breadcrumb">
        <div class="container" id="displaystoredetaildata" style="">
            {{-- Store detail Dynamic --}}
        </div>
    </section>




    <!-- Shop -->
    <section class="section-shop padding-tb-100">
        <div class="container">
            <div class="row d-none">
                <div class="col-lg-12">
                    <div class="mb-30" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="400">
                        <div class="cr-banner">
                            <h2>Categories</h2>
                        </div>
                        <div class="cr-banner-sub-title">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt
                                ut labore lacus vel facilisis. </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="600">
                    <div class="col-lg-12">
                        <div class="mb-30">
                            <div class="cr-banner">
                                <h2>Store Products</h2>
                            </div>
                            <div class="cr-banner-sub-title">
                                <p>Discover an expansive array of products closely aligned with our store offerings.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row col-100 mb-minus-24" id="getproductonstoredatacapture">
                        {{-- Dynamic more products --}}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    @include('layouts.footer')

    <!-- Tab to top -->
    @include('layouts.tabtotop')

    <!-- Model -->
    <div class="modal fade quickview-modal" id="quickview" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered cr-modal-dialog">
            <div class="modal-content">
                <button type="button" class="cr-close-model btn-close" data-bs-dismiss="modal"
                    onClick="sessionStorage.removeItem('product_id'); $('#variantErrorMessage').hide();"
                    aria-label="Close"></button>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 col-sm-12 col-xs-12">
                            <div class="zoom-image-hover modal-border-image">
                                <div class="carousel slide" data-bs-ride="carousel" id="imageCarousel">
                                    <div class="carousel-inner">
                                        <!-- Images will be added dynamically here -->
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel"
                                        data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel"
                                        data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <div class="cr-size-and-weight-contain">
                                <h2 class="heading" id="modalproductname"></h2>
                                <h5 class="heading" style="font-size: 1em; color: #555;" id="modalstorename"></h5>
                                <p id="modal_about"></p>
                            </div>
                            <div class="cr-size-and-weight">
                                <div class="cr-review-star">
                                    <div class="cr-star" id="reviewstars"></div>
                                </div>
                                <div class="cr-product-price">
                                    <span class="new-price" id="newprice"></span>
                                    <span class="old-price" id="oldprice"></span>
                                </div>
                                <div class="cr-size-weight size" id="SizeSeen">
                                    <h5><span>Size</span>:</h5>
                                    <div class="cr-kg">
                                        <ul></ul>
                                    </div>
                                </div>
                                <div class="cr-size-weight color" id="Colorseen">
                                    <h5><span>Color</span>:</h5>
                                    <div class="cr-kg">
                                        <ul></ul>
                                    </div>
                                </div>
                                <div id="variantErrorMessage" style="color: red; display: none; padding: 5px;">The
                                    size and color you selected are unavailable. Please select an alternative.</div>
                                <div class="cr-add-card">
                                    <div class="cr-qty-main">
                                        <input type="text" placeholder="." value="1" minlength="1"
                                            maxlength="20" class="quantity">
                                        <button type="button" id="add_model" class="plus">+</button>
                                        <button type="button" id="sub_model" class="minus">-</button>
                                    </div>
                                    <div class="cr-add-button">
                                        <button type="button" class="cr-button">Add to cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Model -->
    <div class="modal fade quickview-modal" id="storereview" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered cr-modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Store Reviews</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="margin-top: 0px !important; margin-bottom: auto;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="cr-tab-content-from" style="padding-top: 0px !important;">
                        <!-- Reviews will be dynamically inserted here -->
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Cart -->
    @include('layouts.cart')

    <!-- Side-tool -->
    @include('layouts.sidetool')

    <!-- Main Scripts -->
    @include('layouts.webscripts')

    <script src="{{ asset('assets/js/store-detail.js') }}"></script>
    <script src="{{ asset('assets/js/productsonstore.js') }}"></script>
    <script src="{{ asset('assets/js/web-product-modal.js') }}"></script>
    <script src="{{ asset('assets/js/web-productlist.js') }}"></script>
    <script src="{{ asset('assets/js/product-detail.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#storereview .close').click(function() {
                $('#storereview').modal('hide');
            });
        });
    </script>

</body>

</html>
