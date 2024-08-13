 <!DOCTYPE html>
 <html lang="en" dir="ltr">

 <head>
     @include('layouts.head')
     <style>
         .icon_color {
             color: red;
         }

         #wishlist {
             display: flex;
             min-height: 100px;
             font-size: larger;
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
                             <h2>Wishlist</h2>
                             <span> <a href="shop">Home</a> - Wishlist</span>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </section>

     <!-- Wishlist -->
     <section class="section-wishlist padding-tb-100">
         <div class="container">
             <div class="row d-none">
                 <div class="col-lg-12">
                     <div class="mb-30" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="400">
                         <div class="cr-banner">
                             <h2>Wishlist</h2>
                         </div>
                         <div class="cr-banner-sub-title">
                             <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                                 incididunt
                                 ut labore lacus vel facilisis. </p>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="row mb-minus-24" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="400"
                 id="wishlist">
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


     {{-- Scripts --}}
     <script src="{{ asset('assets/js/wishlist.js') }}"></script>
     <script src="{{ asset('assets/js/web-product-modal.js') }}"></script>
     <script src="{{ asset('assets/js/product-detail.js') }}"></script>
     <script src="{{ asset('assets/js/web-product-modal.js') }}"></script>
     <script src="{{ asset('assets/js/web-productlist.js') }}"></script>

 </body>

 </html>
