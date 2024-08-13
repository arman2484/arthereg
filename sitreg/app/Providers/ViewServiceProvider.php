<?php

namespace App\Providers;

use App\Models\CartItem;
use App\Models\Vendor;
use App\Models\VendorStore;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;



class ViewServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot()
  {
    // Using view composer to share notifications and cart count with all views
    View::composer('*', function ($view) {
      // Fetch stores with pending vendor requests
      $stores = VendorStore::where('vendor_request', 0)
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get(['store_name', 'store_logo', 'created_at', 'id']);

      // Map the stores to notifications
      $notifications = $stores->map(function ($store) {
        return [
          'title' => $store->store_name,
          'message' => 'You have a new request for approval of store',
          'time' => $store->created_at->diffForHumans(),
          'id' => $store->id,
          'image' => $store->store_logo ? url("assets/images/store_logo/{$store->store_logo}") : null
        ];
      });

      // Fetch cart count where admin_cart is 1
      $cartCount = CartItem::where('admin_cart', 1)->count();

      // Share notifications and cart count with the view
      $view->with([
        'notifications' => $notifications,
        'cartCount' => $cartCount
      ]);
    });
  }
}
