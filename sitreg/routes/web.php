<?php

use App\Http\Controllers\apps\AcademyCourse;
use App\Http\Controllers\apps\IndexController;
use App\Http\Controllers\apps\UserLoginController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CountryCurrenciesController;
use App\Http\Controllers\front_pages\Checkout;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\laravel_example\UserManagement;
use App\Http\Controllers\MediaManagerController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TaxRateController;
use App\Http\Controllers\VariantController;
use App\Http\Controllers\wizard_example\Checkout as Wizard_exampleCheckout;
use App\Http\Controllers\ZoneController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$controller_path = 'App\Http\Controllers';

// authentication
Route::get('/admin', $controller_path . '\authentications\LoginBasic@index')->name('login');
Route::post('/auth/login', $controller_path . '\authentications\LoginBasic@login')->name('auth-login');
// Route::get('/auth/login-cover', $controller_path . '\authentications\LoginCover@index')->name('auth-login-cover');
// Route::get('/auth/register-basic', $controller_path . '\authentications\RegisterBasic@index')->name('auth-register-basic');
// Route::get('/auth/register-cover', $controlladmin_web.phper_path . '\authentications\RegisterCover@index')->name('auth-register-cover');
// Route::get('/auth/register-multisteps', $controller_path . '\authentications\RegisterMultiSteps@index')->name('auth-register-multisteps');
// Route::get('/auth/verify-email-basic', $controller_path . '\authentications\VerifyEmailBasic@index')->name('auth-verify-email-basic');
// Route::get('/auth/verify-email-cover', $controller_path . '\authentications\VerifyEmailCover@index')->name('auth-verify-email-cover');
// Route::get('/auth/reset-password-basic', $controller_path . '\authentications\ResetPasswordBasic@index')->name('auth-reset-password-basic');
// Route::get('/auth/reset-password-cover', $controller_path . '\authentications\ResetPasswordCover@index')->name('auth-reset-password-cover');
// Route::get('/auth/forgot-password-basic', $controller_path . '\authentications\ForgotPasswordBasic@index')->name('auth-reset-password-basic');
// Route::get('/auth/forgot-password-cover', $controller_path . '\authentications\ForgotPasswordCover@index')->name('auth-forgot-password-cover');
// Route::get('/auth/two-steps-basic', $controller_path . '\authentications\TwoStepsBasic@index')->name('auth-two-steps-basic');
// Route::get('/auth/two-steps-cover', $controller_path . '\authentications\TwoStepsCover@index')->name('auth-two-steps-cover');


Route::group(['middleware' => ['admin']], function () {
  $controller_path = 'App\Http\Controllers';

  // Main Page Route
  // Route::get('/', $controller_path . '\dashboard\Analytics@index')->name('dashboard-analytics');
  Route::get('/dashboard/analytics', $controller_path . '\dashboard\Analytics@index')->name('dashboard-analytics');
  Route::get('/dashboard/crm', $controller_path . '\dashboard\Crm@index')->name('dashboard-crm');
  Route::get('/dashboard/ecommerce', $controller_path . '\dashboard\Ecommerce@index')->name('dashboard-ecommerce');

  // locale
  Route::get('lang/{locale}', $controller_path . '\language\LanguageController@swap');

  // layout
  Route::get('/layouts/collapsed-menu', $controller_path . '\layouts\CollapsedMenu@index')->name('layouts-collapsed-menu');
  Route::get('/layouts/content-navbar', $controller_path . '\layouts\ContentNavbar@index')->name('layouts-content-navbar');
  Route::get('/layouts/content-nav-sidebar', $controller_path . '\layouts\ContentNavSidebar@index')->name('layouts-content-nav-sidebar');
  Route::get('/layouts/navbar-full', $controller_path . '\layouts\NavbarFull@index')->name('layouts-navbar-full');
  Route::get('/layouts/navbar-full-sidebar', $controller_path . '\layouts\NavbarFullSidebar@index')->name('layouts-navbar-full-sidebar');
  //   Route::get('/layouts/horizontal', $controller_path . '\layouts\Horizontal@index')->name('dashboard-analytics');
  //   Route::get('/layouts/vertical', $controller_path . '\layouts\Vertical@index')->name('dashboard-analytics');
  Route::get('/layouts/without-menu', $controller_path . '\layouts\WithoutMenu@index')->name('layouts-without-menu');
  Route::get('/layouts/without-navbar', $controller_path . '\layouts\WithoutNavbar@index')->name('layouts-without-navbar');
  Route::get('/layouts/fluid', $controller_path . '\layouts\Fluid@index')->name('layouts-fluid');
  Route::get('/layouts/container', $controller_path . '\layouts\Container@index')->name('layouts-container');
  Route::get('/layouts/blank', $controller_path . '\layouts\Blank@index')->name('layouts-blank');

  // Front Pages
  Route::get('/front-pages/landing', $controller_path . '\front_pages\Landing@index')->name('front-pages-landing');
  Route::get('/front-pages/pricing', $controller_path . '\front_pages\Pricing@index')->name('front-pages-pricing');
  Route::get('/front-pages/payment', $controller_path . '\front_pages\Payment@index')->name('front-pages-payment');
  Route::get('/front-pages/checkout', $controller_path . '\front_pages\Checkout@index')->name('front-pages-checkout');
  Route::get('/front-pages/help-center', $controller_path . '\front_pages\HelpCenter@index')->name('front-pages-help-center');
  Route::get('/front-pages/help-center-article', $controller_path . '\front_pages\HelpCenterArticle@index')->name('front-pages-help-center-article');

  // apps
  Route::get('/app/email', $controller_path . '\apps\Email@index')->name('app-email');
  Route::get('/app/chat', $controller_path . '\apps\Chat@index')->name('app-chat');
  Route::get('/app/calendar', $controller_path . '\apps\Calendar@index')->name('app-calendar');
  Route::get('/app/kanban', $controller_path . '\apps\Kanban@index')->name('app-kanban');

  Route::get('/app/ecommerce/dashboard', $controller_path . '\apps\EcommerceDashboard@index')->name('app-ecommerce-dashboard');
  Route::get('/app/ecommerce/dashboard/user/list', $controller_path . '\apps\EcommerceDashboard@getUserListData')->name('app-ecommerce-dashboard-user-list');
  Route::get('/app/ecommerce/dashboard/order/list', $controller_path . '\apps\EcommerceDashboard@getOrderListData')->name('app-ecommerce-dashboard-order-list');
  Route::post('/app/ecommerce/dashboard/order/delete/{id}', $controller_path . '\apps\EcommerceDashboard@dashboardOrderDelete')->name('app-ecommerce-dashboard-order-delete');
  Route::post('/app/ecommerce/dashboard/user/delete/{id}', $controller_path . '\apps\EcommerceDashboard@dashboardUserDelete')->name('app-ecommerce-dashboard-user-delete');
  Route::get('/app/ecommerce/dashboard/chart/data', $controller_path . '\apps\EcommerceDashboard@dashboardCharData')->name('app-ecommerce-dashboard-chart-data');
  Route::get('/app/ecommerce/product/list', $controller_path . '\apps\EcommerceProductList@index')->name('app-ecommerce-product-list');
  Route::get('/app/ecommerce/product/list/data', $controller_path . '\apps\EcommerceProductList@productListData')->name('app-ecommerce-product-list-data');
  Route::get('/app/ecommerce/product/show/{id}', $controller_path . '\apps\EcommerceProductList@show')->name('app-ecommerce-product-show');
  Route::get('/app/ecommerce/product/add', $controller_path . '\apps\EcommerceProductAdd@index')->name('app-ecommerce-product-add');
  Route::post('/app/ecommerce/product/store', $controller_path . '\apps\EcommerceProductAdd@store')->name('app-ecommerce-product-store');
  Route::get('/app/ecommerce/product/edit/{id}', $controller_path . '\apps\EcommerceProductAdd@edit')->name('app-ecommerce-product-edit');
  Route::post('/app/ecommerce/product/update/{id}', $controller_path . '\apps\EcommerceProductAdd@update')->name('app-ecommerce-product-update');
  Route::post('/app/ecommerce/product/delete/{id}', $controller_path . '\apps\EcommerceProductAdd@delete')->name('app-ecommerce-product-delete');
  Route::post('/app/ecommerce/product/image/delete/{id}', $controller_path . '\apps\EcommerceProductAdd@productImageDelete')->name('app-ecommerce-product-image-delete');
  Route::delete('/app/ecommerce/product/variant/delete/{id}', $controller_path . '\apps\EcommerceProductAdd@Deletevariant')->name('app-ecommerce-product-variant-delete');




  Route::get('/app/ecommerce/product/category', $controller_path . '\apps\EcommerceProductCategory@index')->name('app-ecommerce-product-category');
  Route::post('/app/ecommerce/product/category/add', $controller_path . '\apps\EcommerceProductCategory@add')->name('app-ecommerce-product-category-add');
  Route::get('/app/ecommerce/product/category/list/data', $controller_path . '\apps\EcommerceProductCategory@getCategoryListData')->name('app-ecommerce-category-list-data');
  Route::get('/app/ecommerce/product/category/edit/{id}', $controller_path . '\apps\EcommerceProductCategory@edit')->name('app-ecommerce-product-category-edit');
  Route::post('/app/ecommerce/product/category/delete/{id}', $controller_path . '\apps\EcommerceProductCategory@delete')->name('app-ecommerce-product-category-delete');
  Route::post('app/ecommerce/product/category/update/{id}', $controller_path . '\apps\EcommerceProductCategory@update')->name('app-ecommerce-product-category-update');

  Route::get('/app/ecommerce/product/subcategory', $controller_path . '\apps\EcommerceProductSubCategory@index')->name('app-ecommerce-product-subcategory');
  Route::post('/app/ecommerce/product/subcategory/add', $controller_path . '\apps\EcommerceProductSubCategory@add')->name('app-ecommerce-product-subcategory-add');
  Route::post('/app-ecommerce-product-get-subcategory', $controller_path . '\apps\EcommerceProductSubCategory@getSubcategory')->name('app-ecommerce-product-get-subcategory');
  Route::get('/app/ecommerce/product/subcategory/list/data', $controller_path . '\apps\EcommerceProductSubCategory@getSubCategoryListData')->name('app-ecommerce-subcategory-list-data');
  Route::get('/app/ecommerce/product/subcategory/edit/{id}', $controller_path . '\apps\EcommerceProductSubCategory@edit')->name('app-ecommerce-product-subcategory-edit');
  Route::post('/app/ecommerce/product/subcategory/delete/{id}', $controller_path . '\apps\EcommerceProductSubCategory@delete')->name('app-ecommerce-product-subcategory-delete');
  Route::post('/app/ecommerce/product/subcategory/update/{id}', $controller_path . '\apps\EcommerceProductSubCategory@update')->name('app-ecommerce-product-subcategory-update');
  Route::get('/app/ecommerce/order/list', $controller_path . '\apps\EcommerceOrderList@index')->name('app-ecommerce-order-list');
  Route::post('/app/ecommerce/order/status', $controller_path . '\apps\EcommerceOrderDetails@orderStatus')->name('app-ecommerce-order-status');
  Route::get('app/ecommerce/order/list/data', $controller_path . '\apps\EcommerceOrderList@getOrderListData')->name('app-ecommerce-order-list-data');
  Route::get('/app/ecommerce/order/pending', $controller_path . '\apps\EcommerceOrderList@getPendingOrderList')->name('app-ecommerce-pending-order-list');
  Route::get('/app/ecommerce/order/completed', $controller_path . '\apps\EcommerceOrderList@getCompletedOrderList')->name('app-ecommerce-completed-order-list');
  Route::get('/app/ecommerce/order/cancle', $controller_path . '\apps\EcommerceOrderList@getCancleOrderList')->name('app-ecommerce-completed-cancel-list');
  Route::get('app/ecommerce/order/details/{id}', $controller_path . '\apps\EcommerceOrderDetails@index')->name('app-ecommerce-order-details');
  Route::get('/app/ecommerce/order/details/list/{id}', $controller_path . '\apps\EcommerceOrderDetails@getOrderDetails')->name('app-ecommerce-order-details-list');
  Route::post('/app/ecommerce/order/delete/{id}', $controller_path . '\apps\EcommerceOrderDetails@orderDelete')->name('app-ecommerce-order-delete');
  Route::get('/app/ecommerce/customer/all', $controller_path . '\apps\EcommerceCustomerAll@index')->name('app-ecommerce-customer-all');
  Route::get('app/ecommerce/customer/details/overview', $controller_path . '\apps\EcommerceCustomerDetailsOverview@index')->name('app-ecommerce-customer-details-overview');
  Route::get('app/ecommerce/customer/details/security', $controller_path . '\apps\EcommerceCustomerDetailsSecurity@index')->name('app-ecommerce-customer-details-security');
  Route::get('app/ecommerce/customer/details/billing', $controller_path . '\apps\EcommerceCustomerDetailsBilling@index')->name('app-ecommerce-customer-details-billing');
  Route::get('app/ecommerce/customer/details/notifications', $controller_path . '\apps\EcommerceCustomerDetailsNotifications@index')->name('app-ecommerce-customer-details-notifications');

  Route::get('/app/ecommerce/coupon', $controller_path . '\apps\EcommerceCoupon@index')->name('app-ecommerce-coupon');
  Route::get('/app/ecommerce/coupon/list', $controller_path . '\apps\EcommerceCoupon@couponList')->name('app-ecommerce-coupon-list');
  Route::post('/app/ecommerce/coupon/delete/{id}', $controller_path . '\apps\EcommerceCoupon@couponDelete')->name('app-ecommerce-coupon-delete');
  Route::post('/app/ecommerce/coupon/add', $controller_path . '\apps\EcommerceCoupon@add')->name('app-ecommerce-coupon-add');
  Route::get('/app/ecommerce/coupon/edit/{id}', $controller_path . '\apps\EcommerceCoupon@couponEdit')->name('app-ecommerce-coupon-edit');
  Route::post('/app/ecommerce/coupon/update', $controller_path . '\apps\EcommerceCoupon@update')->name('app-ecommerce-coupon-update');
  Route::get('/app/ecommerce/contactus', $controller_path . '\apps\EcommerceContactUs@index')->name('app-ecommerce-contactus');
  Route::get('/app/ecommerce/contactus/list', $controller_path . '\apps\EcommerceContactUs@contactusList')->name('app-ecommerce-contactus-list');
  Route::get('/app/ecommerce/contactus/close/ticket/{id}', $controller_path . '\apps\EcommerceContactUs@closeTicket')->name('app-ecommerce-close-ticket');
  Route::match(['get', 'post'], '/app/ecommerce/user/reply/{id}', $controller_path . '\apps\EcommerceContactUs@userReply')->name('app-ecommerce-user-reply');
  Route::get('/app/ecommerce/chat/user/{id}', $controller_path . '\apps\EcommerceContactUs@chatUser')->name('app-ecommerce-chat-user');

  Route::get('/app/ecommerce/manage/reviews', $controller_path . '\apps\EcommerceManageReviews@index')->name('app-ecommerce-manage-reviews');
  Route::get('/app/ecommerce/reviews/list', $controller_path . '\apps\EcommerceManageReviews@getReviewList')->name('app-ecommerce-reviews-list');
  Route::post('/app/ecommerce/review/delete/{id}', $controller_path . '\apps\EcommerceManageReviews@reviewDelete')->name('app-ecommerce-reviews-delete');
  Route::get('/app/ecommerce/referrals', $controller_path . '\apps\EcommerceReferrals@index')->name('app-ecommerce-referrals');
  Route::get('/app/ecommerce/settings/details', $controller_path . '\apps\EcommerceSettingsDetails@index')->name('app-ecommerce-settings-details');
  Route::get('/app/ecommerce/settings/payments', $controller_path . '\apps\EcommerceSettingsPayments@index')->name('app-ecommerce-settings-payments');
  Route::get('/app/ecommerce/settings/checkout', $controller_path . '\apps\EcommerceSettingsCheckout@index')->name('app-ecommerce-settings-checkout');
  Route::get('/app/ecommerce/settings/shipping', $controller_path . '\apps\EcommerceSettingsShipping@index')->name('app-ecommerce-settings-shipping');
  Route::get('/app/ecommerce/settings/locations', $controller_path . '\apps\EcommerceSettingsLocations@index')->name('app-ecommerce-settings-locations');
  Route::get('/app/ecommerce/settings/notifications', $controller_path . '\apps\EcommerceSettingsNotifications@index')->name('app-ecommerce-settings-notifications');
  Route::get('/app/academy/dashboard', $controller_path . '\apps\AcademyDashboard@index')->name('app-academy-dashboard');
  Route::get('/app/academy/course', $controller_path . '\apps\AcademyCourse@index')->name('app-academy-course');
  Route::get('/app/academy/course-details', $controller_path . '\apps\AcademyCourseDetails@index')->name('app-academy-course-details');
  Route::get('/app/istics/dashboard', $controller_path . '\apps\LogisticsDashboard@index')->name('app-logistics-dashboard');
  Route::get('/app/logistics/fleet', $controller_path . '\apps\LogisticsFleet@index')->name('app-logistics-fleet');
  Route::get('/app/invoice/list', $controller_path . '\apps\InvoiceList@index')->name('app-invoice-list');
  Route::get('/app/invoice/data', $controller_path . '\apps\InvoiceList@invoiceData')->name('app-invoice-data');
  Route::get('/app/invoice/preview', $controller_path . '\apps\InvoicePreview@index')->name('app-invoice-preview');
  Route::get('/app/invoice/preview/{id}', $controller_path . '\apps\InvoicePreview@index')->name('app-invoice-preview');
  Route::get('/app/invoice/print', $controller_path . '\apps\InvoicePrint@index')->name('app-invoice-print');
  Route::get('/app/invoice/print/{id}', $controller_path . '\apps\InvoicePrint@index')->name('app-invoice-print');
  Route::get('/app/invoice/edit', $controller_path . '\apps\InvoiceEdit@index')->name('app-invoice-edit');
  Route::get('/app/invoice/add', $controller_path . '\apps\InvoiceAdd@index')->name('app-invoice-add');
  Route::get('/app/user/list', $controller_path . '\apps\UserList@index')->name('app-user-list');
  Route::get('/app/user/list/data', $controller_path . '\apps\UserList@getUserListData')->name('app-user-list-data');
  Route::get('/app/user/edit/{id}', $controller_path . '\apps\UserList@edit')->name('app-user-edit');
  Route::post('/app/user/delete/{id}', $controller_path . '\apps\UserList@delete')->name('app-user-delete');
  Route::get('/app/user/logout', $controller_path . '\apps\UserList@logout')->name('logout');

  Route::get('/app/user/view/account/{id}', $controller_path . '\apps\UserViewAccount@index')->name('app-user-view-account');
  Route::get('/app/user/view/account/order/list/{id}', $controller_path . '\apps\UserViewAccount@getOrderListData')->name('app-user-view-account-order-list');
  // Route::get('/app/user/view/account/order/list', $controller_path . '\apps\UserViewAccount@getOrderListData')->name('app-user-view-account-order-list');

  Route::get('/app/user/view/security', $controller_path . '\apps\UserViewSecurity@index')->name('app-user-view-security');
  Route::get('/app/user/view/billing', $controller_path . '\apps\UserViewBilling@index')->name('app-user-view-billing');
  Route::get('/app/user/view/notifications', $controller_path . '\apps\UserViewNotifications@index')->name('app-user-view-notifications');
  Route::get('/app/user/view/connections', $controller_path . '\apps\UserViewConnections@index')->name('app-user-view-connections');
  Route::get('/app/access-roles', $controller_path . '\apps\AccessRoles@index')->name('app-access-roles');
  Route::get('/app/access-permission', $controller_path . '\apps\AccessPermission@index')->name('app-access-permission');

  // pages
  Route::get('/pages/profile-user', $controller_path . '\pages\UserProfile@index')->name('pages-profile-user');
  Route::post('/profile/update/{id}', $controller_path . '\pages\UserProfile@update')->name('profile-update');
  Route::get('/pages/profile-teams', $controller_path . '\pages\UserTeams@index')->name('pages-profile-teams');
  Route::get('/pages/profile-projects', $controller_path . '\pages\UserProjects@index')->name('pages-profile-projects');
  Route::get('/pages/profile-connections', $controller_path . '\pages\UserConnections@index')->name('pages-profile-connections');
  Route::get('/pages/account-settings-account', $controller_path . '\pages\AccountSettingsAccount@index')->name('pages-account-settings-account');
  Route::get('/pages/account-settings-security', $controller_path . '\pages\AccountSettingsSecurity@index')->name('pages-account-settings-security');


  Route::post('/admin/change/password', $controller_path . '\pages\AccountSettingsSecurity@changePassword')->name('admin-change-password');
  // Route::get('/pages/account-settings-billing', $controller_path . '\pages\AccountSettingsBilling@index')->name('pages-account-settings-billing');
  // Route::get('/pages/account-settings-notifications', $controller_path . '\pages\AccountSettingsNotifications@index')->name('pages-account-settings-notifiocations');
  // Route::get('/pages/account-settings-connections', $controller_path . '\pages\AccountSettingsConnections@index')->name('pages-account-settings-connectins');
  Route::get('/pages/faq', $controller_path . '\pages\Faq@index')->name('pages-faq');
  Route::get('/pages/pricing', $controller_path . '\pages\Pricing@index')->name('pages-pricing');
  Route::get('/pages/misc-error', $controller_path . '\pages\MiscError@index')->name('pages-misc-error');
  Route::get('/pages/misc-under-maintenance', $controller_path . '\pages\MiscUnderMaintenance@index')->name('pages-misc-under-maintenance');
  Route::get('/pages/misc-comingsoon', $controller_path . '\pages\MiscComingSoon@index')->name('pages-misc-comingsoon');
  Route::get('/pages/misc-not-authorized', $controller_path . '\pages\MiscNotAuthorized@index')->name('pages-misc-not-authorized');


  // wizard example
  Route::get('/wizard/ex-checkout', $controller_path . '\wizard_example\Checkout@index')->name('wizard-ex-checkout');
  Route::get('/wizard/ex-property-listing', $controller_path . '\wizard_example\PropertyListing@index')->name('wizard-ex-property-listing');
  Route::get('/wizard/ex-create-deal', $controller_path . '\wizard_example\CreateDeal@index')->name('wizard-ex-create-deal');

  // modal
  Route::get('/modal-examples', $controller_path . '\modal\ModalExample@index')->name('modal-examples');



  // routes/web.php

  Route::post('/cart/add', [AcademyCourse::class, 'add'])->name('cart.add');
  Route::delete('/cart/{itemId}', [AcademyCourse::class, 'removeItem'])->name('cart.remove');
  Route::patch('/cart/{itemId}', [AcademyCourse::class, 'updateQuantity']);
  Route::post('/checkout/add-address', [Wizard_exampleCheckout::class, 'addAddress'])->name('checkout.add-address');
  Route::post('/checkout/update-cart-address', [Wizard_exampleCheckout::class, 'updateCartAddress'])->name('updateCartAddress');
  Route::delete('/delete-address/{id}', [Wizard_exampleCheckout::class, 'deleteAddress'])->name('deleteAddress');
  Route::post('/checkout/pay', [Wizard_exampleCheckout::class, 'payNow'])->name('checkout.pay');
  Route::post('/update-cart-user-id', [Wizard_exampleCheckout::class, 'updateCartUserId']);



  // cards
  Route::get('/cards/basic', $controller_path . '\cards\CardBasic@index')->name('cards-basic');
  Route::get('/cards/advance', $controller_path . '\cards\CardAdvance@index')->name('cards-advance');
  Route::get('/cards/statistics', $controller_path . '\cards\CardStatistics@index')->name('cards-statistics');
  Route::get('/cards/analytics', $controller_path . '\cards\CardAnalytics@index')->name('cards-analytics');
  Route::get('/cards/gamifications', $controller_path . '\cards\CardGamifications@index')->name('cards-gamifications');
  Route::get('/cards/actions', $controller_path . '\cards\CardActions@index')->name('cards-actions');

  // User Interface
  Route::get('/ui/accordion', $controller_path . '\user_interface\Accordion@index')->name('ui-accordion');
  Route::get('/ui/alerts', $controller_path . '\user_interface\Alerts@index')->name('ui-alerts');
  Route::get('/ui/badges', $controller_path . '\user_interface\Badges@index')->name('ui-badges');
  Route::get('/ui/buttons', $controller_path . '\user_interface\Buttons@index')->name('ui-buttons');
  Route::get('/ui/carousel', $controller_path . '\user_interface\Carousel@index')->name('ui-carousel');
  Route::get('/ui/collapse', $controller_path . '\user_interface\Collapse@index')->name('ui-collapse');
  Route::get('/ui/dropdowns', $controller_path . '\user_interface\Dropdowns@index')->name('ui-dropdowns');
  Route::get('/ui/footer', $controller_path . '\user_interface\Footer@index')->name('ui-footer');
  Route::get('/ui/list-groups', $controller_path . '\user_interface\ListGroups@index')->name('ui-list-groups');
  Route::get('/ui/modals', $controller_path . '\user_interface\Modals@index')->name('ui-modals');
  Route::get('/ui/navbar', $controller_path . '\user_interface\Navbar@index')->name('ui-navbar');
  Route::get('/ui/offcanvas', $controller_path . '\user_interface\Offcanvas@index')->name('ui-offcanvas');
  Route::get('/ui/pagination-breadcrumbs', $controller_path . '\user_interface\PaginationBreadcrumbs@index')->name('ui-pagination-breadcrumbs');
  Route::get('/ui/progress', $controller_path . '\user_interface\Progress@index')->name('ui-progress');
  Route::get('/ui/spinners', $controller_path . '\user_interface\Spinners@index')->name('ui-spinners');
  Route::get('/ui/tabs-pills', $controller_path . '\user_interface\TabsPills@index')->name('ui-tabs-pills');
  Route::get('/ui/toasts', $controller_path . '\user_interface\Toasts@index')->name('ui-toasts');
  Route::get('/ui/tooltips-popovers', $controller_path . '\user_interface\TooltipsPopovers@index')->name('ui-tooltips-popovers');
  Route::get('/ui/typography', $controller_path . '\user_interface\Typography@index')->name('ui-typography');

  // extended ui
  Route::get('/extended/ui-avatar', $controller_path . '\extended_ui\Avatar@index')->name('extended-ui-avatar');
  Route::get('/extended/ui-blockui', $controller_path . '\extended_ui\BlockUI@index')->name('extended-ui-blockui');
  Route::get('/extended/ui-drag-and-drop', $controller_path . '\extended_ui\DragAndDrop@index')->name('extended-ui-drag-and-drop');
  Route::get('/extended/ui-media-player', $controller_path . '\extended_ui\MediaPlayer@index')->name('extended-ui-media-player');
  Route::get('/extended/ui-perfect-scrollbar', $controller_path . '\extended_ui\PerfectScrollbar@index')->name('extended-ui-perfect-scrollbar');
  Route::get('/extended/ui-star-ratings', $controller_path . '\extended_ui\StarRatings@index')->name('extended-ui-star-ratings');
  Route::get('/extended/ui-sweetalert2', $controller_path . '\extended_ui\SweetAlert@index')->name('extended-ui-sweetalert2');
  Route::get('/extended/ui-text-divider', $controller_path . '\extended_ui\TextDivider@index')->name('extended-ui-text-divider');
  Route::get('/extended/ui-timeline-basic', $controller_path . '\extended_ui\TimelineBasic@index')->name('extended-ui-timeline-basic');
  Route::get('/extended/ui-timeline-fullscreen', $controller_path . '\extended_ui\TimelineFullscreen@index')->name('extended-ui-timeline-fullscreen');
  Route::get('/extended/ui-tour', $controller_path . '\extended_ui\Tour@index')->name('extended-ui-tour');
  Route::get('/extended/ui-treeview', $controller_path . '\extended_ui\Treeview@index')->name('extended-ui-treeview');
  Route::get('/extended/ui-misc', $controller_path . '\extended_ui\Misc@index')->name('extended-ui-misc');

  // icons
  Route::get('/icons/boxicons', $controller_path . '\icons\Boxicons@index')->name('icons-boxicons');
  Route::get('/icons/font-awesome', $controller_path . '\icons\FontAwesome@index')->name('icons-font-awesome');

  // form elements
  Route::get('/forms/basic-inputs', $controller_path . '\form_elements\BasicInput@index')->name('forms-basic-inputs');
  Route::get('/forms/input-groups', $controller_path . '\form_elements\InputGroups@index')->name('forms-input-groups');
  Route::get('/forms/custom-options', $controller_path . '\form_elements\CustomOptions@index')->name('forms-custom-options');
  Route::get('/forms/editors', $controller_path . '\form_elements\Editors@index')->name('forms-editors');
  Route::get('/forms/file-upload', $controller_path . '\form_elements\FileUpload@index')->name('forms-file-upload');
  Route::get('/forms/pickers', $controller_path . '\form_elements\Picker@index')->name('forms-pickers');
  Route::get('/forms/selects', $controller_path . '\form_elements\Selects@index')->name('forms-selects');
  Route::get('/forms/sliders', $controller_path . '\form_elements\Sliders@index')->name('forms-sliders');
  Route::get('/forms/switches', $controller_path . '\form_elements\Switches@index')->name('forms-switches');
  Route::get('/forms/extras', $controller_path . '\form_elements\Extras@index')->name('forms-extras');

  // form layouts
  Route::get('/form/layouts-vertical', $controller_path . '\form_layouts\VerticalForm@index')->name('form-layouts-vertical');
  Route::get('/form/layouts-horizontal', $controller_path . '\form_layouts\HorizontalForm@index')->name('form-layouts-horizontal');
  Route::get('/form/layouts-sticky', $controller_path . '\form_layouts\StickyActions@index')->name('form-layouts-sticky');

  // form wizards
  Route::get('/form/wizard-numbered', $controller_path . '\form_wizard\Numbered@index')->name('form-wizard-numbered');
  Route::get('/form/wizard-icons', $controller_path . '\form_wizard\Icons@index')->name('form-wizard-icons');
  Route::get('/form/validation', $controller_path . '\form_validation\Validation@index')->name('form-validation');

  // tables
  Route::get('/tables/basic', $controller_path . '\tables\Basic@index')->name('tables-basic');
  Route::get('/tables/datatables-basic', $controller_path . '\tables\DatatableBasic@index')->name('tables-datatables-basic');
  Route::get('/tables/datatables-advanced', $controller_path . '\tables\DatatableAdvanced@index')->name('tables-datatables-advanced');
  Route::get('/tables/datatables-extensions', $controller_path . '\tables\DatatableExtensions@index')->name('tables-datatables-extensions');

  // charts
  Route::get('/charts/apex', $controller_path . '\charts\ApexCharts@index')->name('charts-apex');
  Route::get('/charts/chartjs', $controller_path . '\charts\ChartJs@index')->name('charts-chartjs');

  // maps
  Route::get('/maps/leaflet', $controller_path . '\maps\Leaflet@index')->name('maps-leaflet');

  // laravel example
  Route::get('/laravel/user-management', [UserManagement::class, 'UserManagement'])->name('laravel-example-user-management');
  Route::resource('/user-list', UserManagement::class);
  Route::get('/app/ecommerce/setting/mobile', $controller_path . '\apps\Setting@settingList')->name('app-ecommerce-setting-mobile');
  Route::post('/app/ecommerce/setting', $controller_path . '\apps\Setting@settingAdd')->name('app-setting-add');
  Route::get('/app/ecommerce/setting/stripe', $controller_path . '\apps\Setting@paymentStripeKey')->name('app-ecommerce-payment-setting-stripe');
  Route::get('/app/ecommerce/settings/razer', $controller_path . '\apps\Setting@paymentRozerKey')->name('app-ecommerce-payment-setting-razer');
  Route::get('/app/ecommerce/settings/flutterwave', $controller_path . '\apps\Setting@paymentFlutterwaveKey')->name('app-ecommerce-payment-setting-flutterwave');
  Route::get('/app/ecommerce/payment/cod', $controller_path . '\apps\Setting@paymentCOD')->name('app-ecommerce-payment-cod');
  Route::get('/app/ecommerce/payment/cheque', $controller_path . '\apps\Setting@paymentCheque')->name('app-ecommerce-payment-cheque');
  Route::post('/app/ecommerce/setting/stripe', $controller_path . '\apps\Setting@settingStripeAdd')->name('app-setting-stripe-add');
  Route::post('/app/ecommerce/payment/cod', $controller_path . '\apps\Setting@addCODPayment')->name('app-payment-cod');
  Route::get('/app/setting/custom/page', $controller_path . '\apps\Setting@customPage')->name('custom-page');
  Route::post('/app/setting/custom/page/add', $controller_path . '\apps\Setting@customPageAdd')->name('app-setting-custom-page-add');
  Route::get('/app/ecommerce/notification', $controller_path . '\apps\Notification@index')->name('app-ecommerce-notification');
  Route::get('/app/ecommerce/notification/list', $controller_path . '\apps\Notification@notificationList')->name('app-ecommerce-notification-list');
  Route::get('/app/ecommerce/notification/list/data', $controller_path . '\apps\Notification@getNotificationListData')->name('app-ecommerce-notification-list-data');
  Route::post('/app/ecommerce/notification/add', $controller_path . '\apps\Notification@add')->name('app-ecommerce-notification-add');
  Route::get('/app/ecommerce/currencies', [CountryCurrenciesController::class, 'index'])->name('app-currencies-list');
  Route::get('/app/ecommerce/currencies/list/data', [CountryCurrenciesController::class, 'getCurrencyList'])->name('app-currencies-list-data');
  Route::get('/get/currency', [CountryCurrenciesController::class, 'getCurrency'])->name('get-currency');
  Route::get('/app/ecommerce/currencies/add', [CountryCurrenciesController::class, 'addCurrency'])->name('app-currencies-add');
  Route::post('/app/ecommerce/currencies/add', [CountryCurrenciesController::class, 'storeCurrency'])->name('app-currencies-add');
  Route::get('/change/currency/status/{id}', [CountryCurrenciesController::class, 'changeCurrencyStatus'])->name('change-currency-status');
  Route::post('/delete/currency/status/{id}', [CountryCurrenciesController::class, 'deleteCurrency'])->name('delete-currency-status');
  Route::get('/app/ecommerce/currencies/edit/{id}', [CountryCurrenciesController::class, 'editCurrency'])->name('app-currencies-edit');
  Route::post('/app/ecommerce/currencies/update/{id}', [CountryCurrenciesController::class, 'updateCurrency'])->name('app-currencies-update');
  // store
  Route::get('/app/ecommerce/store/list', [StoreController::class, 'index'])->name('app-ecommerce-store-list');
  Route::get('/app/ecommerce/store/list/data', [StoreController::class, 'storeListData'])->name('app-ecommerce-store-list-data');
  Route::post('/app/ecommerce/store/image/delete/{id}', [StoreController::class, 'storeImageDelete'])->name('app-ecommerce-store-image-delete');
  Route::get('/app/ecommerce/store/edit/{id}', [StoreController::class, 'storeEdit'])->name('app-ecommerce-store-edit');
  Route::post('/app/ecommerce/store/update/{id}', [StoreController::class, 'storeUpdate'])->name('app-ecommerce-store-update');
  Route::get('/app/ecommerce/store/show/{id}', [StoreController::class, 'storeShow'])->name('app-ecommerce-store-show');
  Route::get('/app/ecommerce/store/add', [StoreController::class, 'storeAdd'])->name('app-ecommerce-store-add');
  Route::post('/app/ecommerce/store/save', [StoreController::class, 'storeSave'])->name('app-ecommerce-store-save');
  Route::get('/app/ecommerce/store/request', [StoreController::class, 'storeRequestList'])->name('app-ecommerce-store-request');
  Route::get('/app/ecommerce/store/request/list/data', [StoreController::class, 'storeRequestListData'])->name('app-ecommerce-store-request-list-data');
  Route::post('/app/ecommerce/store/request/accepted/{id}', [StoreController::class, 'storeRequestAccepted'])->name('app-ecommerce-store-request-list-data');
  Route::get('/change/store/request/status/{id}', [StoreController::class, 'changeRequestStatus'])->name('change-request-status');
  Route::post('/app/ecommerce/store/delete/{id}', [StoreController::class, 'storeDelete'])->name('app-ecommerce-store-delete');

  //media manger
  Route::get('/app/ecommerce/media/manager', [MediaManagerController::class, 'index'])->name('app-ecommerce-media-manager');
  Route::get('/app/eccommerce/media/product', [MediaManagerController::class, 'getProduct'])->name('app-ecommerce-media-manager');
  Route::get('/app/eccommerce/media/category', [MediaManagerController::class, 'getCategory'])->name('app-ecommerce-media-manager');
  Route::post('/file-upload', [MediaManagerController::class, 'fileUpload'])->name('file-upload');
  Route::get('/media/product-image/delete/{id}', [MediaManagerController::class, 'productImageDelete'])->name('product-image-delete');
  Route::get('/media/category-image/delete/{id}', [MediaManagerController::class, 'categoryImageDelete'])->name('product-image-delete');
  //variant
  Route::get('/app/ecommerce/variant/list', [VariantController::class, 'index'])->name('app-ecommerce-variant-list');
  Route::get('/app/ecommerce/variant/list/data', [VariantController::class, 'variantListData'])->name('app-ecommerce-variant-list-data');
  Route::get('/app/ecommerce/variant/add', [VariantController::class, 'addVariant'])->name('app-ecommerce-variant-add');
  Route::post('/app/ecommerce/variant/add', [VariantController::class, 'storeVariant'])->name('app-ecommerce-variant-add');
  Route::get('/app/ecommerce/variant/edit/{id}', [VariantController::class, 'editVariant'])->name('app-ecommerce-variant-list-edit');
  Route::post('/app/ecommerce/variant/update/{id}', [VariantController::class, 'updateVariant'])->name('app-ecommerce-variant-update');
  Route::post('/app/ecommerce/variant/delete/{id}', [VariantController::class, 'deleteVariant'])->name('app-ecommerce-variant-delete');
  Route::get('/app/ecommerce/variant/status/{id}', [VariantController::class, 'statusVariant'])->name('app-ecommerce-variant-status');

  //shipping
  Route::get('/app/ecommerce/shipping/list', [ShippingController::class, 'index'])->name('app-ecommerce-shipping-list');
  Route::get('/app/ecommerce/shipping/list/data', [ShippingController::class, 'shippingListData'])->name('app-ecommerce-shipping-list-data');
  Route::get('/app/ecommerce/shipping/add/', [ShippingController::class, 'addShippingData'])->name('app-ecommerce-shipping-add');
  Route::post('/app/ecommerce/shipping/add/', [ShippingController::class, 'storeShippingData'])->name('app-ecommerce-shipping-store');
  Route::post('/app/ecommerce/shipping/delete/{id}', [ShippingController::class, 'deleteShippingData'])->name('app-ecommerce-shipping-delete');
  Route::get('/app/ecommerce/shipping/edit/{id}', [ShippingController::class, 'editShippingData'])->name('app-ecommerce-shipping-edit');
  Route::post('/app/ecommerce/shipping/update/{id}', [ShippingController::class, 'updateShippingData'])->name('app-ecommerce-shipping-update');
  Route::get('/app/ecommerce/shipping/status/{id}', [ShippingController::class, 'statusShipping'])->name('app-ecommerce-shipping-status');
  //location
  Route::get('/app/ecommerce/country/list', [CountryController::class, 'index'])->name('app-ecommerce-country-list');
  Route::get('/app/ecommerce/country/list/data', [CountryController::class, 'countryList'])->name('app-ecommerce-country-list-data');
  Route::get('/app/ecommerce/country/add', [CountryController::class, 'addCountry'])->name('app-ecommerce-country-add');
  Route::post('/app/ecommerce/country/add', [CountryController::class, 'storeCountry'])->name('app-ecommerce-country-store');
  Route::get('/app/ecommerce/country/data', [CountryController::class, 'getCurrencyData'])->name('get-country-data');
  Route::get('/app/ecommerce/country/status/{id}', [CountryController::class, 'changeCountryStatus'])->name('country-status-change');
  Route::get('/app/ecommerce/country/edit/{id}', [CountryController::class, 'editCountry'])->name('app-ecommerce-country-edit');
  Route::post('/app/ecommerce/country/update/{id}', [CountryController::class, 'updateCountry'])->name('app-ecommerce-country-update');
  Route::post('/app/ecommerce/country/delete/{id}', [CountryController::class, 'deleteCountry'])->name('app-ecommerce-country-delete');

  //zone
  Route::get('/app/ecommerce/zone/list', [ZoneController::class, 'index'])->name('app-ecommerce-shipping-zone-list');
  Route::get('/app/ecommerce/zone/list/data', [ZoneController::class, 'zoneListData'])->name('app-ecommerce-zone-list-data');
  Route::get('/app/ecommerce/zone/add', [ZoneController::class, 'addZone'])->name('app-ecommerce-zone-add');
  Route::get('/app/ecommerce/state/data', [ZoneController::class, 'getStatusData'])->name('get-state-data');
  Route::post('/app/ecommerce/zone/add', [ZoneController::class, 'storeZone'])->name('app-ecommerce-zone-store');
  Route::get('/app/ecommerce/zone/status/{id}', [ZoneController::class, 'changeZoneStatus'])->name('app-ecommerce-zone-status');
  Route::get('/app/ecommerce/zone/edit/{id}', [ZoneController::class, 'editZone'])->name('app-ecommerce-zone-edit');
  Route::post('/app/ecommerce/zone/update/{id}', [ZoneController::class, 'updateZone'])->name('app-ecommerce-zone-update');
  Route::post('/app/ecommerce/zone/delete/{id}', [ZoneController::class, 'deleteZone'])->name('app-ecommerce-zone-delete');

  //tax
  Route::get('/app/ecommerce/tax/list', [TaxRateController::class, 'index'])->name('app-ecommerce-tax-list');
  Route::get('/app/ecommerce/tax/list/data', [TaxRateController::class, 'taxRateListData'])->name('app-ecommerce-tax-list-data');
  Route::get('/app/ecommerce/tax/rate/add', [TaxRateController::class, 'addTaxRate'])->name('app-ecommerce-tax-add');
  Route::post('/app/ecommerce/tax/rate/store', [TaxRateController::class, 'storeTaxRate'])->name('app-ecommerce-tax-store');
  Route::get('/app/ecommerce/tax/edit/{id}', [TaxRateController::class, 'editTaxRate'])->name('app-ecommerce-tax-edit');
  Route::post('/app/ecommerce/tax/update/{id}', [TaxRateController::class, 'updateTaxRate'])->name('app-ecommerce-tax-edit');
  Route::post('/app/ecommerce/tax/delete/{id}', [TaxRateController::class, 'deleteTaxRate'])->name('app-ecommerce-tax-delete');
  Route::get('/app/ecommerce/tax/rate/status/{id}', [TaxRateController::class, 'changeTaxStatus'])->name('app-ecommerce-tax-status');



  // Coupons
  Route::get('banner-list', [BannerController::class, 'index'])->name('banner-list');
  Route::get('getbannerdata', [BannerController::class, 'getBannerData'])->name('getbannerdata');
  Route::get('sliders-add', [BannerController::class, 'addSlider'])->name('sliders-add');
  Route::post('sliders-save', [BannerController::class, 'saveSlider'])->name('sliders-save');
  Route::post('sliders-delete/{id}', [BannerController::class, 'deleteSlider'])->name('sliders-delete');
  Route::get('change-bannerstatus/{id}',  [BannerController::class, 'ChangeBannerStatus'])->name('change-bannerstatus');
  Route::get('change-sliderstatus/{id}',  [BannerController::class, 'ChangeSliderStatus'])->name('change-sliderstatus');
});




// Website
Route::get('/register', [UserLoginController::class, 'index'])->name('register');
Route::get('/user-login', [UserLoginController::class, 'showOTPForm'])->name('otp');

Route::get('shop', [IndexController::class, 'index'])->name('shop');
Route::get('productlist', [IndexController::class, 'ProductList'])->name('productlist');
Route::get('storelist', [IndexController::class, 'StoreList'])->name('storelist');
Route::get('wishlist', [IndexController::class, 'Wishlist'])->name('wishlist');
Route::get('product-detail/{name}', [IndexController::class, 'ProductDetail'])->name('product-detail');
Route::get('category-detail', [IndexController::class, 'CategoryDetail'])->name('category-detail');
Route::get('store-detail/{name}', [IndexController::class, 'StoreDetail'])->name('store-detail');
Route::get('view-cart', [IndexController::class, 'ViewCart'])->name('view-cart');
Route::get('cart-checkout', [IndexController::class, 'CartCheckout'])->name('cart-checkout');
Route::get('profile', [IndexController::class, 'Profile'])->name('profile');
Route::get('categories', [IndexController::class, 'CategoryList'])->name('categories');
Route::get('paymentsuccess', [IndexController::class, 'PaymentSuccess'])->name('paymentsuccess');
Route::get('paymentfailed', [IndexController::class, 'PaymentFailed'])->name('paymentfailed');
Route::get('orders', [IndexController::class, 'Orders'])->name('orders');
Route::get('wallet', [IndexController::class, 'Wallet'])->name('wallet');
Route::get('coupons', [IndexController::class, 'Coupons'])->name('coupons');
Route::get('referralCode', [IndexController::class, 'ReferralCode'])->name('referralCode');
Route::get('loyalty-points', [IndexController::class, 'LoyaltyPoints'])->name('loyalty-points');
Route::get('walletsuccess', [IndexController::class, 'WalletPaymentSuccess'])->name('walletsuccess');
Route::get('walletpaymentsuccess', [IndexController::class, 'WalletPaymentSuccess'])->name('walletpaymentsuccess');
Route::get('/', [IndexController::class, 'Landing'])->name('landing');
