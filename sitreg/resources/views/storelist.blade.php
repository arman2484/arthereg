<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    @include('layouts.head')

    <style>
        .store-card-custom {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            font-weight: 600
        }

        .store-card-img-top {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .store-card-body-custom {
            text-align: left;
            padding: 1rem;
        }

        .store-card-title {
            font-size: 1rem;
        }

        .store-card-text {
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

        .fa-star,
        .fa-star-half-alt {
            color: #f0c14b;
        }

        .product-logo {
            width: 4rem;
            height: 4rem;
            border-radius: 50%;
            position: absolute;
            bottom: -1.5rem;
            right: 1.5rem;
            background-color: white;
            padding: 3px;
            object-fit: contain !important;
        }

        .store-bradcum-other {
            background: url('/assets/images/category-breadcumb.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            height: 12rem;
            background-position: center;
        }

        .store-bradcum {
            background-color: rgba(0, 0, 0, 0.5);
            height: 100%;
            width: 100%;
        }

        .icon_color {
            color: red;
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

    <!-- Breadcrumb -->
    <section class="section-breadcrumb">
        <div class="cr-breadcrumb-image">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="cr-breadcrumb-title">
                            <h2>Stores</h2>
                            <span> <a href="shop">Home</a> / stores</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-shop padding-tb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-12 md-30" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="600">
                    <div class="row" id="getmodulestoreinnerdata">
                        {{-- Store List Dynamic displayed here --}}
                    </div>
                    <nav aria-label="..." class="cr-pagination" id="pagelistfind">
                        <ul class="pagination"></ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>

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
    <script src="{{ asset('assets/js/web-storelist.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

</body>

</html>
