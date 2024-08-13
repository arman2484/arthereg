<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImages;
use Illuminate\Http\Request;

class IndexController extends Controller
{
  public function index()
  {
    return view('shop');
  }

  public function ProductList()
  {
    return view('productlist');
  }

  public function StoreList()
  {
    return view('storelist');
  }

  public function Wishlist()
  {
    return view('wishlist');
  }

  public function ProductDetail()
  {
    // Get the product_id from the query string
    $product_id = request()->query('product_id');

    // Fetch the product details using the product_id
    $product = Product::where('id', $product_id)->first();

    if (!$product) {
      abort(404); // Handle product not found
    }

    // Fetch the first product image from the product_images table
    $productImage = ProductImages::where('product_id', $product_id)->value('product_image');

    if ($productImage) {
      // Attach the full image URL to the product object
      $product->product_image = url('assets/images/product_images/' . $productImage);
    } else {
      // Handle case where no image is found, if needed
      $product->product_image = null;
    }

    // Uncomment this if you want to check the image URL
    // dd($product->product_image);

    // Return the view with product details
    return view('product-detail', ['product' => $product]);
  }




  public function CategoryDetail()
  {
    return view('category-detail');
  }

  public function StoreDetail()
  {
    return view('store-detail');
  }


  public function ViewCart()
  {
    return view('view-cart');
  }

  public function CartCheckout()
  {
    return view('cart-checkout');
  }

  public function Profile()
  {
    return view('profile');
  }

  public function CategoryList()
  {
    return view('categories');
  }

  public function PaymentSuccess()
  {
    return view('paymentsuccess');
  }

  public function PaymentFailed()
  {
    return view('paymentfailed');
  }

  public function Orders()
  {
    return view('orders');
  }

  public function Wallet()
  {
    return view('wallet');
  }

  public function LoyaltyPoints()
  {
    return view('loyaltyPoints');
  }
  public function Coupons()
  {
    return view('coupons');
  }
  public function ReferralCode()
  {
    return view('referralCode');
  }
  public function WalletPaymentSuccess()
  {
    return view('walletsuccess');
  }
  public function Landing()
  {
    return view('landing');
  }
}
