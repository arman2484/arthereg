    <!-- Popular product -->


    <style>
        .bg-color {
            background-color: rgba(0, 67, 143, 0.14);
            padding: 5rem;
        }

        .landing_downlaod_image {
            height: 20rem;
        }
    </style>

    <section class="section-popular margin-b-100">
        <div class=" bg-color">
            <div class="row">
                <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-12 d-flex justify-content-center align-items-center "
                    data-aos="fade-up" data-aos-duration="2000">
                    <img class="landing_downlaod_image" src="/assets/images/landingpagedownload.png" alt="">
                </div>
                <div class="col-xxl-8 col-xl-8 col-lg-6 col-md-12" data-aos="fade-up" data-aos-duration="2000">
                    <div class="cr-footer-logo">
                        <h5 style="font-weight: 500;font-size: 2rem;margin-bottom: 2rem;">Comprehensive eBusiness Solution for
                            Various Needs</h5>
                        <p style="max-width: 90%;">Siterg is a comprehensive multi-vendor delivery system that
                            utilizes the Laravel and Flutter Frameworks. Designed to cater to various industries such as
                            food, grocery, apparel, and pharmacy, it offers a range of functionalities through its six
                            modules, ensuring all your business needs are met.</p>
                        <div class="download_now">
                            <img src="/assets/images/download_now_google.png" alt="">
                            <img src="/assets/images/download_now_apple.png" alt="">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            $('.cr-twocolumns-special').slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
                responsive: [{
                        breakpoint: 1600,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 1300,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });
        });
    </script>
