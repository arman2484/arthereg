 <!-- Footer -->


 <style>
     .left-footer {
         display: flex;
         flex-direction: column;
     }

     .download_now {
         display: flex;
         gap: 2rem;
     }

     .download_now img {
         height: auto;
         width: 10rem;
     }

     .right-footer {
         background-color: #00438F38;
         border-radius: 1rem;
         font-weight: 500;
     }

     .terms-div {
         display: flex;
         flex-direction: column;
         gap: 1rem;
     }

     .right-footer .row {
         font-size: 1rem !important;
     }
 </style>

 <footer class="footer padding-t-100 bg-off-white">
     <div class="container">
         <div class="row footer-top padding-b-100 mr-3">
             <div class="col-12 col-lg-5 left-footer">
                 <div class="cr-footer-logo">
                     <div class="image">
                         <a href="{{ url('/') }}">
                             <img src="/public/assets/images/sitreg.png" alt="logo" class="logo">
                         </a>
                         <a href="{{ url('/') }}">
                             <img src="/public/assets/images/sitreg.png" alt="logo" class="dark-logo">
                         </a>
                     </div>
                     <p style="max-width: 25rem;">Stay updated by connecting with our social media platforms and other
                         websites</p>
                     <div class="download_now">
                         <img src="/assets/images/download_now_google.png" alt="">
                         <img src="/assets/images/download_now_apple.png" alt="">
                     </div>
                 </div>
             </div>
             <div class="row col-12 col-lg-7 right-footer p-5">
                 <div class="col-lg-4 terms-div">
                     <div>Terms & Conditions</div>
                     <div>Privacy Policy</div>
                     <div>Refund Policy</div>
                     <div>Cancellation Policy</div>
                     <div>Shipping Policy</div>
                 </div>
                 <div class="col-lg-8  row ">
                     <div class="cr-footer cr-newsletter">
                         <div class="cr-footer-links cr-footer-dropdown">
                             <form class="cr-search-footer">
                                 <input class="search-input" type="text" placeholder="Subscribe to Newsletter...">
                                 <a href="javascript:void(0)" class="search-btn">
                                     <i class="ri-send-plane-fill"></i>
                                 </a>
                             </form>
                         </div>
                         <div class="row mt-4">
                             <div class="d-flex my-3 col-6">
                                 <i class="ri-mail-line"></i>
                                 <div class="ms-2"> info@siterg.com</div>
                             </div>
                             <div class="d-flex my-3 col-6">
                                 <div class="ms-2"> Get in Touch with us</div>
                             </div>
                         </div>
                         <div class="row ">
                             <div class="d-flex my-3 col-6">
                                 {{-- <i class="ri-phone-line"></i>
                                 <div class="ms-2"> info@siterg.com</div> --}}
                             </div>
                             <div class="d-flex col-6" style="margin-top: -1rem;">
                                 <div class="cr-social-media">
                                     <span><a href="javascript:void(0)"><i class="ri-facebook-line"></i></a></span>
                                     <span><a href="javascript:void(0)"><i class="ri-twitter-x-line"></i></a></span>
                                     <span><a href="javascript:void(0)"><i class="ri-dribbble-line"></i></a></span>
                                     <span><a href="javascript:void(0)"><i class="ri-instagram-line"></i></a></span>
                                 </div>
                             </div>
                         </div>
                         {{-- <div class="d-flex my-3">
                             <i class="ri-mail-line"></i>
                             <div class="ms-2"> info@siterg.com</div>
                         </div> --}}

                     </div>
                 </div>
             </div>
         </div>
         <div class="cr-last-footer">
             <p>&copy; <span id="copyright_year"></span> <a href="index.html">Primocys</a>, All rights reserved.</p>
         </div>
     </div>
 </footer>

 {{-- <div class="col-xl-4 col-lg-6 col-sm-12 col-12 cr-footer-border">
    <div class="cr-footer-logo">
        <div class="image">
            <img src="assets/images/sitreg.png" alt="logo" class="logo">
            <img src="assets/images/sitreg.png" alt="logo" class="dark-logo">
        </div>
        <p>Carrot is the biggest market of grocery products. Get your daily needs from our store.</p>
    </div>
    <div class="cr-footer">
        <h4 class="cr-sub-title cr-title-hidden">
            Contact us
            <span class="cr-heading-res"></span>
        </h4>
        <ul class="cr-footer-links cr-footer-dropdown">
            <li class="location-icon">
                51 Green St.Huntington ohaio beach ontario, NY 11746 KY 4783, USA.
            </li>
            <li class="mail-icon">
                <a href="javascript:void(0)">example@email.com</a>
            </li>
            <li class="phone-icon">
                <a href="javascript:void(0)"> +91 123 4567890</a>
            </li>
        </ul>
    </div>
</div>
<div class="col-xl-2 col-lg-3 col-sm-12 col-12 cr-footer-border">
    <div class="cr-footer">
        <h4 class="cr-sub-title">
            Company
            <span class="cr-heading-res"></span>
        </h4>
        <ul class="cr-footer-links cr-footer-dropdown">
            <li><a href="about.html">About Us</a></li>
            <li><a href="track-order.html">Delivery Information</a></li>
            <li><a href="policy.html">Privacy Policy</a></li>
            <li><a href="terms.html">Terms & Conditions</a></li>
            <li><a href="contact-us.html">contact Us</a></li>
            <li><a href="faq.html">Support Center</a></li>
        </ul>
    </div>
</div>
<div class="col-xl-2 col-lg-3 col-sm-12 col-12 cr-footer-border">
    <div class="cr-footer">
        <h4 class="cr-sub-title">
            Category
            <span class="cr-heading-res"></span>
        </h4>
        <ul class="cr-footer-links cr-footer-dropdown">
            <li><a href="shop-left-sidebar.html">Dairy & Bakery</a></li>
            <li><a href="shop-left-sidebar.html">Fruits & Vegetable</a></li>
            <li><a href="shop-left-sidebar.html">Snack & Spice</a></li>
            <li><a href="shop-left-sidebar.html">Juice & Drinks</a></li>
            <li><a href="shop-left-sidebar.html">Chicken & Meat</a></li>
            <li><a href="shop-left-sidebar.html">Fast Food</a></li>
        </ul>
    </div>
</div>
<div class="col-xl-4 col-lg-12 col-sm-12 col-12 cr-footer-border">
    <div class="cr-footer cr-newsletter">
        <h4 class="cr-sub-title">
            Subscribe Our Newsletter
            <span class="cr-heading-res"></span>
        </h4>
        <div class="cr-footer-links cr-footer-dropdown">
            <form class="cr-search-footer">
                <input class="search-input" type="text" placeholder="Search here...">
                <a href="javascript:void(0)" class="search-btn">
                    <i class="ri-send-plane-fill"></i>
                </a>
            </form>
        </div>
        <div class="cr-social-media">
            <span><a href="javascript:void(0)"><i class="ri-facebook-line"></i></a></span>
            <span><a href="javascript:void(0)"><i class="ri-twitter-x-line"></i></a></span>
            <span><a href="javascript:void(0)"><i class="ri-dribbble-line"></i></a></span>
            <span><a href="javascript:void(0)"><i class="ri-instagram-line"></i></a></span>
        </div>
        <div class="cr-payment">
            <div class="cr-insta-slider swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <a href="#" class="cr-payment-image">
                            <img src="assets/img/insta/1.jpg" alt="insta">
                            <div class="payment-overlay"></div>
                        </a>
                    </div>
                    <div class="swiper-slide">
                        <a href="#" class="cr-payment-image">
                            <img src="assets/img/insta/2.jpg" alt="insta">
                            <div class="payment-overlay"></div>
                        </a>
                    </div>
                    <div class="swiper-slide">
                        <a href="#" class="cr-payment-image">
                            <img src="assets/img/insta/3.jpg" alt="insta">
                            <div class="payment-overlay"></div>
                        </a>
                    </div>
                    <div class="swiper-slide">
                        <a href="#" class="cr-payment-image">
                            <img src="assets/img/insta/4.jpg" alt="insta">
                            <div class="payment-overlay"></div>
                        </a>
                    </div>
                    <div class="swiper-slide">
                        <a href="#" class="cr-payment-image">
                            <img src="assets/img/insta/5.jpg" alt="insta">
                            <div class="payment-overlay"></div>
                        </a>
                    </div>
                    <div class="swiper-slide">
                        <a href="#" class="cr-payment-image">
                            <img src="assets/img/insta/6.jpg" alt="insta">
                            <div class="payment-overlay"></div>
                        </a>
                    </div>
                    <div class="swiper-slide">
                        <a href="#" class="cr-payment-image">
                            <img src="assets/img/insta/7.jpg" alt="insta">
                            <div class="payment-overlay"></div>
                        </a>
                    </div>
                    <div class="swiper-slide">
                        <a href="#" class="cr-payment-image">
                            <img src="assets/img/insta/8.jpg" alt="insta">
                            <div class="payment-overlay"></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}
