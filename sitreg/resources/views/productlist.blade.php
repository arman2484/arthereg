<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    @include('layouts.head')

    <style>
        .icon_color {
            color: red;
        }
    </style>
    <style>
        .checkbox-group input {
            margin-right: .5rem;
        }

        .cr-checkbox {
            padding-top: 0 !important;
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

        .accordion-button {
            background-color: transparent;
        }

        .accordion-item {
            border: none;
            padding: 0rem !important;
            / margin-block: 1rem;/ border: top !important;
            background-color: transparent;
        }

        @media only screen and (max-width: 600px) {
            .accordion-item {
                border: none;
                padding: 0rem !important;
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

        .accordion-body {
            padding: 0rem !important;
            padding-left: 2rem !important;
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

    <style>
        .product-bradcum-other {
            background: url('/assets/images/category-breadcumb.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            height: 12rem;
            background-position: center;
        }

        .product-bradcum {
            background-color: rgba(0, 0, 0, 0.5);
            height: 100%;
            width: 100%;
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
                            <span><a href="shop">Home</a> / products</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-shop padding-tb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-12 md-30" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="400">
                    <div class="cr-shop-sideview">
                        <div class="cr-shop-categories">
                            <div class="filterdataCat cr-shop-sub-title"
                                style="display: flex; justify-content:space-between; border-bottom: 1px solid #e9e9e9;">
                                <h6 class="cr-shop-sub-title-name">Category</h6>
                                <h6 class="cr-shop-sub-title-name" style="cursor: pointer">Clear Filter</h6>
                            </div>
                            {{-- Accordion Start --}}
                            <div class="accordion" id="productAccordian">

                            </div>
                            {{-- Accordion End --}}
                        </div>
                        {{-- <div class="cr-shop-price">
                            <h4 class="cr-shop-sub-title">Price</h4>
                            <div class="cr-checkbox">
                                <div class="checkbox-group">
                                    <input type="checkbox" id="up_to_500" value="0-500">
                                    <label for="up_to_500">up to 500</label>
                                </div>
                                <div class="checkbox-group">
                                    <input type="checkbox" id="500_to_1000" value="500-1000">
                                    <label for="500_to_1000">500 to 1000</label>
                                </div>
                                <div class="checkbox-group">
                                    <input type="checkbox" id="1000_to_10000" value="1000-10000">
                                    <label for="1000_to_10000">1000 to 10000</label>
                                </div>
                                <div class="checkbox-group">
                                    <input type="checkbox" id="10000_and_above" value="10000_and_above">
                                    <label for="10000_and_above">10000 & above</label>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>

                <div class="col-lg-9 col-12 md-30" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="600">
                    <div class="row">
                        <div class="col-12">
                            <div class="cr-shop-bredekamp">
                                <div class="cr-toggle">
                                    <a href="javascript:void(0)" class="gridCol active-grid">
                                        <i class="ri-grid-line"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="gridRow">
                                        <i class="ri-list-check-2"></i>
                                    </a>
                                </div>
                                <div class="center-content">
                                </div>
                                <div class="cr-select" id="SortingProducts">
                                    <label>Sort By :</label>
                                    <select class="form-select" aria-label="Default select example">
                                        <option value="latest">Latest</option>
                                        <option value="asc">Low To High</option>
                                        <option value="desc">High to Low</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row col-50 mb-minus-24" id="productlist">
                        <!-- Product list will be dynamically populated here -->
                        <div id="noProductMessage" style="display: none; text-align: center; margin-top: 20px;">
                            No product found
                        </div>
                    </div>

                    {{-- Pagination dynamically here --}}
                    <nav aria-label="..." class="cr-pagination" id="pagelist">
                        <ul class="pagination">
                            <li class="page-item disabled">
                                <span class="page-link">Previous</span>
                            </li>
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">1</span>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>

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


    {{-- Scripts --}}
    <script src="{{ asset('assets/js/web-productlist.js') }}"></script>
    <script src="{{ asset('assets/js/web-product-modal.js') }}"></script>
    <script src="{{ asset('assets/js/productcategories.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


</body>

</html>
