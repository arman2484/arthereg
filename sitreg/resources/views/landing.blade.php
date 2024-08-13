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
    <style>
        .slick-prev:before,
        .slick-next:before {
            color: transparent;
        }

        .hero-sec {
            padding: 3rem;
        }

        .hero-sec-left {
            display: flex;
            flex-direction: column;
            gap: 1rem !important;
            justify-content: center;
        }

        .hero-sec-right {
            display: grid;
            place-content: center;
        }

        .landing_hero_image {
            height: 30rem;
        }

        .hero-sec-left-box {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #EEEEEE;
            height: 13rem;
            width: 12rem;
            font-weight: 500;
            border-radius: .5rem;
        }

        .hero-sec-left-box img {
            height: 8rem;
            width: 8rem;
            object-fit: contain;
        }

        .hero-sec-left-box-container {
            display: flex;
            gap: 2rem;
            margin-top: 2rem
        }

        .selected {
            border: 2px solid goldenrod;
            animation: border-pulse 0.5s infinite alternate;
        }

        @keyframes border-pulse {
            0% {
                transform: scale(1);
            }

            100% {
                transform: scale(1.1);
            }
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

    {{-- Hero --}}
    <section class="section-popular margin-b-100">
        <div class="container hero-sec">
            <div class="row">
                <div class="col-12 col-xl-6 hero-sec-left">
                    <h5 style="font-weight: 500;font-size: 2rem;margin-bottom: 2rem;">Starts Ecommerce Journey With
                        Siterg</h5>
                    <div>

                        <h5 style="font-weight: 700;">Choose Any Module To Get Started</h5>
                        <div style="color: #A8A8A8;">It goes beyond being a trustworthy eCommerce solution</div>
                    </div>
                    <div class="hero-sec-left-box-container" id="Dynamiclandingdata">
                        {{-- Dynamic module data here --}}
                    </div>
                </div>
                <div class="col-12 col-xl-6 hero-sec-right">
                    <img class="landing_hero_image" style="" src="/assets/images/landing_hero_iamge.png"
                        alt="">
                </div>
            </div>
        </div>
    </section>


    <section class="section-popular margin-b-100">
        <div class="container">
            <h2 style="color: #004390; font-weight: 700;">Siterg</h2>
            <h5 style="font-weight: 700;">The Finest Delivery Service Available Nearby</h5>
            <div>Siterg offers a comprehensive range of products to cater to all your daily needs. Whether you require
                groceries, pharmacy items, food delivery, or apparel shopping, you can conveniently access all these
                services from the comfort of your own home.</div>
        </div>
    </section>



    <!-- Footer -->
    @include('layouts.home.landingpagedownload');

    <section class="section-popular margin-b-100">
        <div class="container">
            <h2 style=" font-weight: 700;">Explore User, Delivery & Vendor App</h2>
            <h5 style="font-weight: 400;color: #737373;">Check the App of user, delivery and vendor.
                <div style="" class="row ">
                    <div style="max-width: 20rem;" class="col-lg-4 col-12 mt-5">
                        <div class="rightbar-buttons">
                            <a href="shop-left-sidebar.html" class="cr-button">
                                User App
                            </a>
                        </div>
                    </div>
                    <div style="max-width: 20rem;" class="col-lg-4 col-12 mt-5">
                        <div class="rightbar-buttons">
                            <a href="shop-left-sidebar.html" class="cr-button">
                                Delivery App
                            </a>
                        </div>
                    </div>
                    <div style="max-width: 20rem;" class="col-lg-4 col-12 mt-5">
                        <div class="rightbar-buttons">
                            <a href="shop-left-sidebar.html" class="cr-button">
                                Vendor App
                            </a>
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

    <!-- Cart -->
    @include('layouts.cart')

    <!-- Side-tool -->
    @include('layouts.sidetool')

    <!-- Main Scripts -->
    @include('layouts.webscripts')

    <script>
        // Define redirectToIndex function outside of DOMContentLoaded event listener
        function redirectToIndex(element) {
            // Remove 'selected' class from all other boxes
            const allBoxes = document.querySelectorAll('.hero-sec-left-box');
            allBoxes.forEach(box => {
                if (box !== element) {
                    box.classList.remove('selected');
                }
            });

            // Add selected class to the clicked item
            element.classList.add('selected');

            // Get the module_id from the clicked element's data attribute
            const moduleId = element.dataset.moduleId;

            // Store module_id locally
            localStorage.setItem('module_id', moduleId);

            // Redirect to index page after a delay (500 milliseconds)
            setTimeout(function() {
                window.location.href = 'shop';
            }, 500);
        }

        document.addEventListener("DOMContentLoaded", function() {
            let baseUrl = '{{ env('BASE_URL_LIVE') }}';

            function LandingList() {
                fetch(baseUrl + '/api/get-module')
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.success && data.data && data.data.length > 0) {
                            displayLandingdata(data.data);
                        } else {
                            console.error('No modules found');
                        }
                    })
                    .catch(error => console.error('Fetch error:', error));
            }

            function displayLandingdata(data) {
                const container = document.getElementById('Dynamiclandingdata');
                container.innerHTML = '';

                data.forEach(item => {
                    let content = `
                    <div class="hero-sec-left-box" onclick="redirectToIndex(this)" data-module-id="${item.id}">
                        <img src="${item.image}" alt="${item.name}">
                        <div style="padding-top: 9px;">${item.name}</div>
                    </div>
                `;
                    container.innerHTML += content;
                });
            }

            LandingList();
        });
    </script>


    {{-- Base Url --}}
    <script>
        let baseUrlLive = '{{ env('BASE_URL_LIVE') }}';
        sessionStorage.setItem('baseUrlLive', baseUrlLive);
    </script>

    <script>
       // Call fetchProductList function when the page is loaded
      $(document).ready(function() {
          fetchProductList();
      });
    </script>

</body>

</html>
