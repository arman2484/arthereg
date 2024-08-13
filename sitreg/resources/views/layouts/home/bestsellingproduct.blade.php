<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Cards</title>

    <style>
        .selling-card-custom {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            font-weight: 600
        }

        .selling-card-img-top {
            width: 100%;
            height: 100%;
            min-height: 12rem;
            object-fit: cover;
        }

        .selling-card {
            border-radius: 1rem !important;
            overflow: hidden;
        }


        .selling-card-body-custom {
            text-align: left;
            padding: 1rem;
        }

        .selling-card-title {
            font-size: 1rem;
        }

        .selling-card-text {
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

        .add_to_cart_btn {
            color: #00438F;
            border: none;
            background-color: transparent;
            border: 2px solid #00438F;
            border-radius: .7rem;
            padding-inline: 1rem;
            padding-block: .3rem;
            margin-top: 1rem;
        }


        .cr-side-view-show {
            -webkit-transition: all 0.5s ease-in-out;
            transition: all 0.5s ease-in-out;
            position: absolute;
            z-index: 20;
            -webkit-transition: all 0.4s ease-in-out;
            transition: all 0.4s ease-in-out;
            top: 15px;
            right: -40px;
            display: -ms-grid;
            display: grid;
            opacity: 0;
        }

        .selling-card:hover .cr-side-view-show {
            opacity: 1;
            right: 10px;
        }

        .cr-side-view-show a {
            width: 35px;
            height: 35px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            margin: 0;
            padding: 0;
            border: none;
            background-color: transparent;
            background-color: #fff;
            border: 1px solid #e9e9e9;
            border-radius: 100%;
        }

        .cr-side-view-show a:last-child {
            margin-top: 5px;
        }

        .cr-side-view-show a i {
            font-size: 18px;
            line-height: 10px;
        }

        .cr-side-view-show .wishlist.active {
            background-color: #00438F;
            color: #fff;
        }

        .icon_color {
            color: red;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="mb-30 " data-aos="fade-up" data-aos-duration="2000">
                    <div class="cr-banner text-start">
                        <h2>Latest Products</h2>
                    </div>
                    <div class="cr-banner-sub-title text-start">
                        <p class="text-start" style="max-width: 100% !important">Our Best Selling Product Catalogue </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="bestsellingproductdata">
            {{-- Best Selling Product dynamic here --}}
        </div>
    </div>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>
