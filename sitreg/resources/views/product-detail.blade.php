<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    @include('layouts.head')

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="{{ $product->product_name }} - PrimoMart">
    <meta name="description" content="{{ $product->product_about }}">
    <meta name="image"
        content="{{ $product->product_image ?? 'https://primomart.theprimoapp.com/assets/images/product-default.png' }}">
    <meta property="og:title" content="{{ $product->product_name }} - PrimoMart">
    <meta property="og:description" content="{{ $product->product_about }}">
    <meta property="og:image"
        content="{{ $product->product_image ?? 'https://primomart.theprimoapp.com/assets/images/product-default.png' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <style>
        .fallback-image {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #fff;
            border-radius: 5px;
            text-transform: uppercase;
            margin-right: 24px;
        }

        .letter-A {
            background-color: #FF5733;
        }

        .letter-B {
            background-color: #33FF57;
        }

        .letter-C {
            background-color: #3357FF;
        }

        .letter-D {
            background-color: #FFC300;
        }

        .letter-E {
            background-color: #FF33A6;
        }

        .letter-F {
            background-color: #33FFF6;
        }

        .letter-G {
            background-color: #FF5733;
        }

        .letter-H {
            background-color: #33FF57;
        }

        .letter-I {
            background-color: #3357FF;
        }

        .letter-J {
            background-color: #FFC300;
        }

        .letter-K {
            background-color: #FF33A6;
        }

        .letter-L {
            background-color: #33FFF6;
        }

        .letter-M {
            background-color: #FF5733;
        }

        .letter-N {
            background-color: #33FF57;
        }

        .letter-O {
            background-color: #3357FF;
        }

        .letter-P {
            background-color: #FFC300;
        }

        .letter-Q {
            background-color: #FF33A6;
        }

        .letter-R {
            background-color: #33FFF6;
        }

        .letter-S {
            background-color: #FF5733;
        }

        .letter-T {
            background-color: #33FF57;
        }

        .letter-U {
            background-color: #3357FF;
        }

        .letter-V {
            background-color: #FFC300;
        }

        .letter-W {
            background-color: #FF33A6;
        }

        .letter-X {
            background-color: #33FFF6;
        }

        .letter-Y {
            background-color: #FF5733;
        }

        .letter-Z {
            background-color: #33FF57;
        }


        .see-more-link {
            color: #00438F;
            cursor: pointer;
            text-decoration: underline;
            display: inline-block;
            margin-top: 10px;
        }

        .product-about {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            max-height: 5.5em;
            /* Adjust based on your line height */
        }

        .slider-for,
        .slider-nav {
            margin-bottom: 20px;
        }

        .slider-banner-image,
        .thumbnail-image {
            text-align: center;
            padding: 0 10px;
        }

        .slick-slider .slick-slide {
            border: 1px solid #e9e9e9 !important;
            border-radius: 5px !important;
            margin-right: .5rem !important;
        }

        .slick-slider .slick-slide>img {
            border-radius: 5px;
        }

        .slick-slider .slick-track {
            display: flex !important;
            justify-content: flex-start;
        }

        .slick-list {
            padding: 0px 0px !important;
        }

        /* Add styles for disabled button */
        .cr-shopping-bag.disabled-button {
            background-color: #ccc;
            /* Grey background */
            color: #888;
            /* Grey text color */
            border: 1px solid #ccc;
            /* Grey border */
            cursor: not-allowed;
            /* Change cursor to not-allowed */
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

        .icon_color {
            color: red;
        }

        .share-circle {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: #ffffff;
            text-decoration: none;
            font-size: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .share-circle:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
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
        <div class="cr-breadcrumb-image">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="cr-breadcrumb-title">
                            <h6><a href="/shop">Home</a> / products / <span id="product-name"></span></h6>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product -->
    <section class="section-product padding-t-100">
        <div class="container">
            <div class="row mb-minus-24" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="600">
                <div class="col-xxl-4 col-xl-5 col-md-6 col-12 mb-24">
                    <div class="vehicle-detail-banner banner-content clearfix">
                        <div class="banner-slider">
                            <div class="slider-for">
                            </div>
                            <div class="slider-nav">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-8 col-xl-7 col-md-6 col-12 mb-24" id="singleproductdetail">
                    {{-- Dynamic product detail data here --}}
                </div>
            </div>
            <div class="row" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="600">
                <div class="col-12">
                    <div class="cr-paking-delivery">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                                    data-bs-target="#description" type="button" role="tab"
                                    aria-controls="description" aria-selected="true">Description</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="additional-tab" data-bs-toggle="tab"
                                    data-bs-target="#additional" type="button" role="tab"
                                    aria-controls="additional" aria-selected="false">Information</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review"
                                    type="button" role="tab" aria-controls="review"
                                    aria-selected="false">Review</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContentData"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- similar products -->
    <section class="section-popular-products padding-tb-100" data-aos="fade-up" data-aos-duration="2000"
        data-aos-delay="400">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-30">
                        <div class="cr-banner">
                            <h2>More Products</h2>
                        </div>
                        <div class="cr-banner-sub-title">
                            <p>Discover an expansive array of products closely aligned with our store offerings.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="sliderproducts">
                <div class="col-lg-12">
                    <div class="cr-popular-product">
                        <div class="slick-slide">
                        </div>
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
                                    <button class="carousel-control-prev" type="button"
                                        data-bs-target="#imageCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        data-bs-target="#imageCarousel" data-bs-slide="next">
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

    <!-- Cart -->
    @include('layouts.cart')

    <!-- Side-tool -->
    @include('layouts.sidetool')

    <!-- Main Scripts -->
    @include('layouts.webscripts')

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-zoom/1.7.21/jquery.zoom.min.js"></script>

    {{-- Scripts --}}
    <script src="{{ asset('assets/js/product-detail.js') }}"></script>
    <script src="{{ asset('assets/js/web-productlist.js') }}"></script>
    <script src="{{ asset('assets/js/similarproducts.js') }}"></script>
    <script src="{{ asset('assets/js/web-product-modal.js') }}"></script>


</body>

</html>
