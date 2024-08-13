<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\User;
use App\Models\Variant;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Illuminate\Support\Facades\Auth;

class StripeController extends Controller
{
  // public function stripeCheckout(Request $request)
  // {
  //   $myId = Auth::guard('sanctum')->user()->id;
  //   Stripe::setApiKey(
  //     'sk_test_51OP303SJayPbST1lMDr6nn6WieehdLmIpiG2pgVil38DVNPjDqKcFG87d1GMOk10WWtoqZIxvSx2WLAP7G1GMkWu00SJWzq7cn'
  //   );
  //   $YOUR_DOMAIN = 'http://127.0.0.1:8000';

  //   $products = [];
  //   foreach (CartItem::where('user_id', $myId)
  //     ->orderByDesc('id')
  //     ->get()
  //     as $item) {
  //     $product = Product::where('id', $item->product_id)->first();
  //     $imgs = ProductImages::where('product_id', $product->id)
  //       ->pluck('product_image')
  //       ->toArray();
  //     $pro_img = [];

  //     foreach ($imgs as $img) {
  //       $pro_img[] = $YOUR_DOMAIN . '/assets/images/product_images/' . $img;
  //     }


  //     $products[] = [
  //       'name' => $product->product_name,
  //       'image' => $pro_img,
  //       'description' => $product->product_about,
  //       'unit_amount' => $product->product_sale_price
  //         ? $product->product_sale_price * 100
  //         : $product->product_price * 100,
  //       'currency' => 'usd',
  //       'quantity' => $item->quantity,
  //     ];
  //   }


  //   $lineItems = [];
  //   foreach ($products as $product) {
  //     $lineItems[] = [
  //       'price_data' => [
  //         'currency' => $product['currency'],
  //         'product_data' => [
  //           'name' => $product['name'],
  //           'images' => $product['image'],
  //           'description' => $product['description'],
  //         ],
  //         'unit_amount' => $product['unit_amount'],
  //       ],
  //       'quantity' => $product['quantity'],
  //     ];
  //   }

  //   try {
  //     $checkout_session = Session::create([
  //       'shipping_options' => [
  //         [
  //           'shipping_rate_data' => [
  //             'type' => 'fixed_amount',
  //             'fixed_amount' => [
  //               'amount' => 1500,
  //               'currency' => 'usd',
  //             ],
  //             'display_name' => 'Next day air',
  //             'delivery_estimate' => [
  //               'minimum' => [
  //                 'unit' => 'business_day',
  //                 'value' => 1,
  //               ],
  //               'maximum' => [
  //                 'unit' => 'business_day',
  //                 'value' => 1,
  //               ],
  //             ],
  //           ],
  //         ],
  //       ],
  //       'line_items' => $lineItems,
  //       'mode' => 'payment',
  //       'success_url' => $YOUR_DOMAIN . '/paymentsuccess',
  //       'cancel_url' => $YOUR_DOMAIN . '/paymentfailed',
  //       'billing_address_collection' => 'auto',
  //     ]);

  //     return response()->json(['checkout_url' => $checkout_session->url], 200);
  //   } catch (\Throwable $th) {
  //     return response()->json(['error' => 'error', 'message' => $th->getMessage()], 500);
  //   }
  // }

  public function stripeCheckout(Request $request)
  {
    // Validate that user_id is provided
    $request->validate([
      'user_id' => 'required|exists:users,id',
    ]);

    $user_id = $request->input('user_id');
    Stripe::setApiKey('sk_test_51OP303SJayPbST1lMDr6nn6WieehdLmIpiG2pgVil38DVNPjDqKcFG87d1GMOk10WWtoqZIxvSx2WLAP7G1GMkWu00SJWzq7cn');
    $YOUR_DOMAIN = 'http://127.0.0.1:8000';

    $products = [];
    foreach (CartItem::where('user_id', $user_id)->orderByDesc('id')->get() as $item) {
      $product = Product::where('id', $item->product_id)->first();

      // Check if the product has a variant
      $variant = Variant::where('product_id', $item->product_id)->first();
      $price = $variant ? ($variant->price * 100) : ($product->product_sale_price ? $product->product_sale_price * 100 : $product->product_price * 100);

      $imgs = ProductImages::where('product_id', $product->id)->pluck('product_image')->toArray();
      $pro_img = [];

      foreach ($imgs as $img) {
        $pro_img[] = $YOUR_DOMAIN . '/assets/images/product_images/' . $img;
      }

      $products[] = [
        'name' => $product->product_name,
        'image' => $pro_img,
        'description' => $product->product_about,
        'unit_amount' => $price,
        'currency' => 'usd',
        'quantity' => $item->quantity,
      ];
    }

    $lineItems = [];
    foreach ($products as $product) {
      $lineItems[] = [
        'price_data' => [
          'currency' => $product['currency'],
          'product_data' => [
            'name' => $product['name'],
            'images' => $product['image'],
            'description' => $product['description'],
          ],
          'unit_amount' => $product['unit_amount'],
        ],
        'quantity' => $product['quantity'],
      ];
    }

    try {
      $checkout_session = Session::create([
        'shipping_options' => [
          [
            'shipping_rate_data' => [
              'type' => 'fixed_amount',
              'fixed_amount' => [
                'amount' => 1500,
                'currency' => 'usd',
              ],
              'display_name' => 'Next day air',
              'delivery_estimate' => [
                'minimum' => [
                  'unit' => 'business_day',
                  'value' => 1,
                ],
                'maximum' => [
                  'unit' => 'business_day',
                  'value' => 1,
                ],
              ],
            ],
          ],
        ],
        'line_items' => $lineItems,
        'mode' => 'payment',
        'success_url' => $YOUR_DOMAIN . '/paymentsuccess',
        'cancel_url' => $YOUR_DOMAIN . '/paymentfailed',
        'billing_address_collection' => 'auto',
      ]);

      return response()->json(['checkout_url' => $checkout_session->url], 200);
    } catch (\Throwable $th) {
      return response()->json(['error' => 'error', 'message' => $th->getMessage()], 500);
    }
  }




  // public function AddWalletStripe(Request $request)
  // {
  //   $myId = Auth::guard('sanctum')->user()->id;
  //   Stripe::setApiKey(
  //     'sk_test_51OP303SJayPbST1lMDr6nn6WieehdLmIpiG2pgVil38DVNPjDqKcFG87d1GMOk10WWtoqZIxvSx2WLAP7G1GMkWu00SJWzq7cn'
  //   );
  //   $YOUR_DOMAIN = 'http://192.168.0.7:8010';
  //   // $YOUR_DOMAIN = env('BASE_URL_LIVE');

  //   // dd($YOUR_DOMAIN);

  //   // Get the amount to add to wallet from the request
  //   $amountToAdd = $request->input('amount');

  //   try {
  //     $checkout_session = Session::create([
  //       'payment_method_types' => ['card'],
  //       'line_items' => [
  //         [
  //           'price_data' => [
  //             'currency' => 'usd',
  //             'product_data' => [
  //               'name' => 'Wallet Top-up', // Name of the wallet top-up product
  //             ],
  //             'unit_amount' => $amountToAdd * 100, // Convert amount to cents
  //           ],
  //           'quantity' => 1, // Assuming adding money to wallet is a one-time transaction
  //         ],
  //       ],
  //       'mode' => 'payment',
  //       'success_url' => $YOUR_DOMAIN . '/walletpaymentsuccess',
  //       'cancel_url' => $YOUR_DOMAIN . '/paymentfailed',
  //       'billing_address_collection' => 'auto',
  //     ]);

  //     return response()->json(['checkout_url' => $checkout_session->url], 200);
  //   } catch (\Throwable $th) {
  //     return response()->json(['error' => 'error', 'message' => $th->getMessage()], 500);
  //   }
  // }

  // public function AddWalletStripe(Request $request)
  // {
  //   $myId = Auth::guard('sanctum')->user()->id;
  //   Stripe::setApiKey(
  //     'sk_test_51OP303SJayPbST1lMDr6nn6WieehdLmIpiG2pgVil38DVNPjDqKcFG87d1GMOk10WWtoqZIxvSx2WLAP7G1GMkWu00SJWzq7cn'
  //   );
  //   $YOUR_DOMAIN = 'http://192.168.0.7:8010';
  //   // $YOUR_DOMAIN = env('BASE_URL_LIVE');

  //   // Get the amount to add to the wallet from the request
  //   $amountToAdd = $request->input('amount');

  //   try {
  //     $checkout_session = Session::create([
  //       'payment_method_types' => ['card'],
  //       'line_items' => [
  //         [
  //           'price_data' => [
  //             'currency' => 'usd',
  //             'product_data' => [
  //               'name' => 'Wallet Top-up', // Name of the wallet top-up product
  //             ],
  //             'unit_amount' => $amountToAdd * 100, // Convert amount to cents
  //           ],
  //           'quantity' => 1, // Assuming adding money to the wallet is a one-time transaction
  //         ],
  //       ],
  //       'mode' => 'payment',
  //       'success_url' => $YOUR_DOMAIN . '/',
  //       'cancel_url' => $YOUR_DOMAIN . '/',
  //       'billing_address_collection' => 'auto',
  //     ]);

  //     // Assuming the payment is successful, update the user's wallet balance
  //     $user = User::findOrFail($myId);
  //     $user->wallet_balance += $amountToAdd;
  //     $user->save();

  //     return response()->json(['checkout_url' => $checkout_session->url], 200);
  //   } catch (\Throwable $th) {
  //     return response()->json(['error' => 'error', 'message' => $th->getMessage()], 500);
  //   }
  // }
  public function AddWalletStripe(Request $request)
  {
    $myId = Auth::guard('sanctum')->user()->id;
    Stripe::setApiKey(
      'sk_test_51OP303SJayPbST1lMDr6nn6WieehdLmIpiG2pgVil38DVNPjDqKcFG87d1GMOk10WWtoqZIxvSx2WLAP7G1GMkWu00SJWzq7cn'
    );
    $YOUR_DOMAIN = 'http://192.168.0.7:8010';
    // $YOUR_DOMAIN = env('BASE_URL_LIVE');

    // Get the amount to add to the wallet from the request
    $amountToAdd = $request->input('amount');

    try {
      $checkout_session = Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [
          [
            'price_data' => [
              'currency' => 'usd',
              'product_data' => [
                'name' => 'Wallet Top-up', // Name of the wallet top-up product
              ],
              'unit_amount' => $amountToAdd * 100, // Convert amount to cents
            ],
            'quantity' => 1, // Assuming adding money to the wallet is a one-time transaction
          ],
        ],
        'mode' => 'payment',
        'success_url' => $YOUR_DOMAIN . '/',
        'cancel_url' => $YOUR_DOMAIN . '/',
        'billing_address_collection' => 'auto',
      ]);

      return response()->json(['checkout_url' => $checkout_session->url], 200);
    } catch (\Throwable $th) {
      return response()->json(['error' => 'error', 'message' => $th->getMessage()], 500);
    }
  }


  public function AddWalletStripeWeb(Request $request)
  {
    $myId = Auth::guard('sanctum')->user()->id;
    Stripe::setApiKey(
      'sk_test_51OP303SJayPbST1lMDr6nn6WieehdLmIpiG2pgVil38DVNPjDqKcFG87d1GMOk10WWtoqZIxvSx2WLAP7G1GMkWu00SJWzq7cn'
    );
    $YOUR_DOMAIN = 'http://192.168.0.7:8010';
    // $YOUR_DOMAIN = env('BASE_URL_LIVE');

    // Get the amount to add to the wallet from the request
    $amountToAdd = $request->input('amount');

    try {
      $checkout_session = Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [
          [
            'price_data' => [
              'currency' => 'usd',
              'product_data' => [
                'name' => 'Wallet Top-up', // Name of the wallet top-up product
              ],
              'unit_amount' => $amountToAdd * 100, // Convert amount to cents
            ],
            'quantity' => 1, // Assuming adding money to the wallet is a one-time transaction
          ],
        ],
        'mode' => 'payment',
        'success_url' => $YOUR_DOMAIN . '/walletsuccess',
        'cancel_url' => $YOUR_DOMAIN . '/',
        'billing_address_collection' => 'auto',
      ]);

      return response()->json(['checkout_url' => $checkout_session->url], 200);
    } catch (\Throwable $th) {
      return response()->json(['error' => 'error', 'message' => $th->getMessage()], 500);
    }
  }
}
