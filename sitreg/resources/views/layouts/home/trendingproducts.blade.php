<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Cards</title>
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}
    <style>
        .trending-card {
            border-radius: 1rem !important;
            overflow: hidden;
            height: 15rem !important;
        }


        .trending-card-body-custom {
            text-align: left;
            padding: 1rem;
        }

        .trending-card-title {
            font-size: 1rem;
            /* margin-bottom: 0.5rem; */
        }

        .trending-card-text {
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

        .trending-product-logo {
            width: 4rem;
            height: 4rem;
            border-radius: 50%;

            /* position: absolute; */
            /* bottom: -1.5rem; */
            /* right: 1.5rem; */
            background-color: white;
            padding: 3px;
            object-fit: contain !important;
        }

        .trending-left {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
        }

        .trending-card-body-custom {
            text-align: center;
        }

        .right-box {
            display: flex;
            flex-direction: column;
            gap: .5rem;
        }

        .inner-right-box {
            display: flex;
            width: 100%;
            gap: 1rem;
            text-align: left;
            font-weight: 0;
            background-color: #F2F2F2;
            border-radius: 1rem;
            padding: .5rem;
        }

        .inner-right-box img {
            height: 3rem;
            width: 3rem;
            border-radius: .5rem;
            object-fit: cover
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="mb-30 " data-aos="fade-up" data-aos-duration="2000">
                    <div class="cr-banner text-start">
                        <h2>Trending Stores</h2>
                    </div>
                    <div class="cr-banner-sub-title text-start">
                        <p class="text-start" style="max-width: 100% !important">Explore the trending products from
                            different categories </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="displayindexpopulardatastorerelated">
            {{-- Dynamic Popular Product data as per store --}}
        </div>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>
