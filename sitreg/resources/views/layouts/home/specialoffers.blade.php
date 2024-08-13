    <style>
        .bg-color {
            background-color: rgba(0, 67, 143, 0.14);
            padding: 5rem;
        }

        .cr-banner {
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 3rem;
            height: 100%;
            width: 50%;
        }

        @media only screen and (max-width: 600px) {
            .cr-banner {
                width: 100%;
            }

            .cr-banner p {
                display: none;
            }

            .cr-banner div {
                display: none;
            }
        }

        .icon_color {
            color: red;
        }
    </style>

    <section class="section-popular margin-b-100">
        <div class=" bg-color">
            <div class="row">
                <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-12 d-flex justify-content-center align-items-center"
                    data-aos="fade-up" data-aos-duration="2000">
                    <div class="cr-banner text-start">
                        <h2>Special Offer</h2>
                        <p class="cr-services-contain">Choose your necessary products from
                            this feature categories</p>
                        <div class="rightbar-buttons">
                            <a href="productlist" class="cr-button py-2">
                                View More Products
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-8 col-xl-8 col-lg-6 col-md-12" data-aos="fade-up" data-aos-duration="2000">
                    <div class="cr-twocolumns-special" id="indexspecialofferproductdata">
                        {{-- Special offer index dynamic here --}}
                    </div>
                </div>

            </div>
        </div>
    </section>
