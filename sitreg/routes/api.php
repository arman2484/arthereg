<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RazorPayController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\StripeController;
use App\Http\Controllers\Api\PayPalController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\WebApiController;

// UserAuthController
Route::post('/new-register', [UserAuthController::class, 'register']);
Route::post('/verify-user', [UserAuthController::class, 'verifyUser']);
Route::post('/product-search', [UserAuthController::class, 'searchProduct']);
Route::post('/guest-user', [UserAuthController::class, 'GuestUser']);


// Vendor Controller
Route::post('/vendor-register', [VendorController::class, 'vendorRegister']);
Route::post('/vendor-login', [VendorController::class, 'vendorLogin']);
Route::post('/forgot-password', [VendorController::class, 'forgotPassword']);
Route::post('/change-password', [VendorController::class, 'ChangePassword']);
Route::post('/reset-password', [VendorController::class, 'ResetPassword']);
Route::post('/add-storebanner', [VendorController::class, 'addStoreBanner']);



// UserApiController
Route::get('/get-module', [UserApiController::class, 'GetModule']);
Route::post('/get-category', [UserApiController::class, 'GetCategory']);
Route::post('/get-subcategory', [UserApiController::class, 'getSubCategory']);
Route::post('/get-productonstore', [UserApiController::class, 'getProductonStore']);
Route::post('/get-store', [UserApiController::class, 'getStore']);
Route::post('/productdetail', [UserApiController::class, 'productDetail']);
Route::post('/storedetail', [UserApiController::class, 'storeDetail']);
Route::post('/social-login', [UserApiController::class, 'SocialLogin']);
Route::post('/home', [UserApiController::class, 'home']);
Route::post('/add-variants', [UserApiController::class, 'addVariants']);
Route::get('/get-units', [UserApiController::class, 'GetUnits']);
Route::post('/show-variants', [UserApiController::class, 'showVariants']);





// Extra Apis
Route::get('/banner', [UserAuthController::class, 'banner']);
Route::post('/product-trending', [UserAuthController::class, 'productTrending']);
Route::post('/sort-product', [UserAuthController::class, 'filterProduct']);
Route::post('/post-home', [UserAuthController::class, 'postHome']);
Route::post('/vendor_delete', [VendorController::class, 'vendor_delete']);
Route::post('/product-details', [VendorController::class, 'getProductdetails']);
Route::post('/social_login_vendor', [VendorController::class, 'social_login_vendor']);




Route::middleware('sanctum')->group(function () {

  // Vendor Controller
  Route::post('/add-store', [VendorController::class, 'addStore']);
  Route::post('/add-product', [VendorController::class, 'addProduct']);
  Route::post('/update-productvendor', [VendorController::class, 'updateProductVendor']);
  Route::post('/update-product', [VendorController::class, 'updateProduct']);
  Route::post('/update-store', [VendorController::class, 'updateStore']);
  Route::post('/get-storeonvendor', [VendorController::class, 'getStoreonVendor']);
  Route::post('/remove-product', [VendorController::class, 'removeProduct']);
  Route::post('/add-couponbyvendor', [VendorController::class, 'addCouponbyVendor']);
  Route::get('/coupons-listofvendor', [VendorController::class, 'couponListofVendor']);
  Route::post('/remove-couponbyvendor', [VendorController::class, 'removeCouponbyvendor']);
  Route::post('/add-module', [VendorController::class, 'addModule']);
  Route::post('/get-moduleofvendor', [VendorController::class, 'GetModuleofVendor']);
  Route::post('/get-orderonvendor', [VendorController::class, 'getOrderListofVendor']);
  Route::post('/order-detailofvendor', [VendorController::class, 'orderDetailofVendor']);
  Route::post('/order-conformbyvendor', [VendorController::class, 'orderConformByVendor']);
  Route::post('/update-vendor', [VendorController::class, 'updateVendor']);
  Route::get('/get-vendorprofile', [VendorController::class, 'getVendorProfile']);


  // UserAuthController
  Route::post('/user-profile', [UserAuthController::class, 'UserProfile']);
  Route::get('/edit-profile', [UserAuthController::class, 'editProfile']);
  Route::post('/update-profile', [UserAuthController::class, 'updateProfile']);











  Route::post('/wallet-success', [UserAuthController::class, 'WalletSuccess']);

  Route::post('/pointstoamount', [UserAuthController::class, 'PointsToAmount']);
  Route::post('/showloyaltypoints', [UserAuthController::class, 'showLoyaltyPoints']);
  Route::get('/points-data', [UserAuthController::class, 'PointsData']);
  Route::post('/order-detail', [UserAuthController::class, 'orderDetail']);
  Route::get('/get-order', [UserAuthController::class, 'getOrder']);
  Route::post('/review-post', [UserAuthController::class, 'reviewPost']);
  Route::post('/user-feedback', [UserAuthController::class, 'userFeedback']);
  Route::post('/get-support', [UserAuthController::class, 'getSupport']);
  Route::post('/add-support', [UserAuthController::class, 'addSupport']);
  Route::post('/post-chat-user', [UserAuthController::class, 'postChatUser']);
  Route::post('/get-chat-user', [UserAuthController::class, 'chatUser']);
  Route::post('/showwalletbalance', [UserAuthController::class, 'showWalletBalance']);
  Route::get('/wallet-data', [UserAuthController::class, 'WalletData']);
  Route::get('/refer-code', [UserAuthController::class, 'getReferCode']);

  // AddWallet
  Route::post('/add-walletstripe', [StripeController::class, 'AddWalletStripe']);
  Route::post('/add-walletrazorpay', [RazorPayController::class, 'AddWalletRazorPay']);
  Route::post('/add-walletpaypal', [PayPalController::class, 'AddWalletPaypal']);

  //  Payment Implementation





  // Extra Apis
  Route::post('review-rating', [UserAuthController::class, 'reviewRating']);
  Route::post('diliver_details', [UserAuthController::class, 'diliver_details']);
  Route::get('delete-account', [UserAuthController::class, 'deleteAccount']);
  Route::post('contact_us', [UserAuthController::class, 'contactUs']);
  Route::get('get-contact_us', [UserAuthController::class, 'getContactUs']);
  Route::get('get-review-rating', [UserAuthController::class, 'getReviewRating']);
  Route::post('notificatoin-list', [UserAuthController::class, 'getNotification']);

  Route::post('/update_status', [VendorController::class, 'update_status']);
  Route::get('get_vendor_orders', [UserAuthController::class, 'get_vendor_orders']);
  Route::get('get_vendor_cancel_orders', [UserAuthController::class, 'get_vendor_cancel_orders']);
  Route::get('get_vendor_diliver_orders', [UserAuthController::class, 'get_vendor_diliver_orders']);
  Route::post('/walletpaymentsuccess', [UserAuthController::class, 'WalletPaymentSuccess']);

});


// User Auth Controller
Route::post('/product-like', [UserAuthController::class, 'productLike']);
Route::post('/product-wishlist', [UserAuthController::class, 'productWishList']);
Route::post('/store-like', [UserAuthController::class, 'storeLike']);
Route::post('/store-wishlist', [UserAuthController::class, 'storeWishList']);
Route::post('/add-cart', [UserAuthController::class, 'AddtoCart']);
Route::post('/cart-list', [UserAuthController::class, 'cartList']);
Route::post('/cart-remove', [UserAuthController::class, 'cartRemove']);
Route::post('/cart-clear', [UserAuthController::class, 'clearCart']);
Route::post('/cart-update', [UserAuthController::class, 'updateCart']);
Route::post('/get-checkout', [UserAuthController::class, 'getCheckOut']);
Route::post('/add-address', [UserAuthController::class, 'addAddress']);
Route::post('/get-address', [UserAuthController::class, 'getAddress']);
Route::post('/address', [UserAuthController::class, 'address']);
Route::post('/edit-address', [UserAuthController::class, 'editAddress']);
Route::post('/update-address', [UserAuthController::class, 'updateAddress']);
Route::post('/remove-address', [UserAuthController::class, 'removeAddress']);
Route::post('/coupons-list', [UserAuthController::class, 'couponList']);
Route::post('/remove-coupon', [UserAuthController::class, 'removeCoupon']);
Route::post('/applied-coupon', [UserAuthController::class, 'appliedCoupon']);
Route::post('/checkout', [UserAuthController::class, 'Checkout']);
Route::post('/stripecheckout', [StripeController::class, 'StripeCheckout']);
Route::post('/razorpaycheckout', [RazorPayController::class, 'RazorPayCheckout']);
Route::post('/paypalcheckout', [PayPalController::class, 'PayPalCheckout']);
Route::post('/walletcheckout', [UserAuthController::class, 'WalletCheckout']);


// UserApiController
Route::post('/get-product', [UserApiController::class, 'getProduct']);


// Extra Apis

Route::post('/sort-product', [UserAuthController::class, 'sortProduct']);
Route::post('/product-filter', [UserAuthController::class, 'productFilter']);
Route::post('/product-review-list', [UserAuthController::class, 'productReviewList']);
Route::get('privacy-policy', [UserAuthController::class, 'privacyPolicy']);
Route::get('setting', [UserAuthController::class, 'setting']);






// Website Api
Route::post('/product-list', [WebApiController::class, 'productList']);
Route::post('/sort-product', [WebApiController::class, 'sortProduct']);
Route::post('/product-listonstore', [WebApiController::class, 'productListonStore']);
Route::post('/add-walletstripeweb', [StripeController::class, 'AddWalletStripeWeb']);
Route::post('/web-home', [WebApiController::class, 'Webhome']);
Route::post('/get-storeonweb', [WebApiController::class, 'getStoreonweb']);
Route::post('/fetch-categoriesfilter', [WebApiController::class, 'FetchCategoriesFilter']);
Route::post('/show-variantsprice', [WebApiController::class, 'showVariantsPrice']);
Route::post('/names-register', [WebApiController::class, 'namesRegister']);
Route::post('/names-registeremail', [WebApiController::class, 'namesRegisterEmail']);
// Route::post('/web-homeontrendproducts', [WebApiController::class, 'WebHomeTrendingProducts']);
