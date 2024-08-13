<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Cards</title>

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
    </style>
</head>

<body>
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="mb-30 " data-aos="fade-up" data-aos-duration="2000">
                    <div class="cr-banner text-start">
                        <h2>Explore Stores</h2>
                    </div>
                    <div class="cr-banner-sub-title text-start">
                        <p class="text-start" style="max-width: 100% !important">Our Best Selling Product Catalogue </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="displayindexstoredata">
            {{-- Dynamic store data here --}}
        </div>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>
