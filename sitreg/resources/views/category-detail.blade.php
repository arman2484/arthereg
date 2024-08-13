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

    {{-- <!-- Model -->
    <div class="modal fade quickview-modal" id="quickview" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered cr-modal-dialog">
            <div class="modal-content">
                <button type="button" class="cr-close-model btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 col-sm-12 col-xs-12">
                            <div class="zoom-image-hover modal-border-image">
                                <img src="assets/img/product/tab-1.jpg" alt="product-tab-2" class="product-image">
                            </div>
                        </div>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <div class="cr-size-and-weight-contain">
                                <h2 class="heading">Peach Seeds Of Change Oraganic Quinoa, Brown fruit</h2>
                                <p>Lorem Ipsum is simply dummy text of the printing and
                                    typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever
                                    since the 1900s,</p>
                            </div>
                            <div class="cr-size-and-weight">
                                <div class="cr-review-star">
                                    <div class="cr-star">
                                        <i class="ri-star-fill"></i>
                                        <i class="ri-star-fill"></i>
                                        <i class="ri-star-fill"></i>
                                        <i class="ri-star-fill"></i>
                                        <i class="ri-star-fill"></i>
                                    </div>
                                    <p>( 75 Review )</p>
                                </div>
                                <div class="cr-product-price">
                                    <span class="new-price">$120.25</span>
                                    <span class="old-price">$123.25</span>
                                </div>
                                <div class="cr-size-weight">
                                    <h5><span>Size</span>/<span>Weight</span> :</h5>
                                    <div class="cr-kg">
                                        <ul>
                                            <li class="active-color">500gm</li>
                                            <li>1kg</li>
                                            <li>2kg</li>
                                            <li>5kg</li>
                                        </ul>
                                    </div>
                                </div>
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
    </div> --}}

    <!-- Cart -->
    @include('layouts.cart')

    <!-- Side-tool -->
    @include('layouts.sidetool')

    <!-- Main Scripts -->
    @include('layouts.webscripts')

    <script src="{{ asset('assets/js/store-detail.js') }}"></script>
    <script src="{{ asset('assets/js/productsonstore.js') }}"></script>

</body>

</html>
