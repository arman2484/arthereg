<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    @include('layouts.head')
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js" defer>
    </script>

    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    </script>
    {{-- TO CHANGE SLICK SLIDER ARROW COLOR --}}
    <style>
        .slick-prev:before,
        .slick-next:before {
            color: transparent;
        }

        .icon_color {
            color: red;
        }

        #no-product-message {
            display: none;
            font-size: 24px;
            color: grey;
            text-align: center;
            margin-top: 200px;
            font-weight: italic;
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

    <script>
        sessionStorage.removeItem('category_id');
        sessionStorage.removeItem('sub_category_id');
    </script>


    <!-- Loader -->
    <div id="cr-overlay">
        <span class="loader"></span>
    </div>

    <!-- Header -->
    @include('layouts.header')

    <!-- Mobile menu -->
    @include('layouts.mobilemenu')

    <!-- Hero slider -->
    @include('layouts.home.herosection')

    {{-- Category section --}}
    @include('layouts.home.category')

    <!-- Popular product -->
    @include('layouts.home.popularproducts')

    <!-- Services -->
    @include('layouts.home.services')

    <!--  special offers -->
    @include('layouts.home.specialoffers')

    <!-- Product banner -->
    @include('layouts.home.productbanner')

    <!-- Explore Store -->
    @include('layouts.home.explorestore')

    <!-- Best Selling Products -->
    @include('layouts.home.bestsellingproduct')

    <!-- Best Selling Products -->
    @include('layouts.home.trendingproducts')

    <!-- Footer -->
    @include('layouts.footer')

    <!-- Tab to top -->
    @include('layouts.tabtotop')

    <!-- Product Model -->
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
                                <div id="variantErrorMessage" style="color: red; display: none; padding: 5px;">The size
                                    and color you selected are unavailable. Please select an alternative.</div>
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

    {{-- Base Url --}}
    <script>
        let baseUrlLive = '{{ env('BASE_URL_LIVE') }}';
        sessionStorage.setItem('baseUrlLive', baseUrlLive);
    </script>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>


    {{-- Scripts --}}
    <script src="{{ asset('assets/js/index-slider.js') }}"></script>
    <script src="{{ asset('assets/js/product-detail.js') }}"></script>
    <script src="{{ asset('assets/js/web-product-modal.js') }}"></script>
    <script src="{{ asset('assets/js/web-productlist.js') }}"></script>
    <script src="{{ asset('assets/js/indexpopularproduct.js') }}"></script>

</body>

</html>
