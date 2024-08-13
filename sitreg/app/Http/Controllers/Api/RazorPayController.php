<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductImages;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Auth;

class RazorPayController extends Controller
{
  public function RazorPaycheckout(Request $request)
  {
    try {
      $userId = $request->input('user_id');
      if (!$userId) {
        return response()->json(['error' => 'missing_user_id', 'message' => 'User ID is required.'], 400);
      }

      $razorpayKeyId = 'rzp_test_ktbxSvVI7dsfn2';
      $razorpayKeySecret = 'bV0o6z2nrLvgSmiA1eIMCGYx';

      $api = new Api($razorpayKeyId, $razorpayKeySecret);

      $yourDomain = 'http://127.0.0.1:8000';

      $products = [];
      foreach (CartItem::where('user_id', $userId)->orderByDesc('id')->get() as $item) {
        $product = Product::find($item->product_id);
        if (!$product) {
          continue; // Skip if product not found
        }

        $imgs = ProductImages::where('product_id', $product->id)->pluck('product_image')->toArray();
        $pro_img = [];
        foreach ($imgs as $img) {
          $pro_img[] = $yourDomain . '/assets/images/product_images/' . $img;
        }

        $products[] = [
          'name' => $product->product_name,
          'image' => $pro_img,
          'description' => $product->product_about,
          'amount' => $product->product_sale_price ? $product->product_sale_price * 100 : $product->product_price * 100,
          'currency' => 'INR',
          'quantity' => $item->quantity,
        ];
      }

      $lineItems = [];
      foreach ($products as $product) {
        $lineItems[] = [
          'name' => $product['name'],
          'description' => $product['description'],
          'amount' => $product['amount'],
          'currency' => $product['currency'],
          'quantity' => $product['quantity'],
        ];
      }

      $orderData = [
        'receipt' => 'order_rcptid_' . time(),
        'amount' => array_sum(array_column($products, 'amount')), // total amount in paise
        'currency' => 'INR',
      ];

      $order = $this->createOrder($razorpayKeyId, $razorpayKeySecret, $orderData);

      return response()->json(['order_id' => $order['id']], 200);
    } catch (\Throwable $th) {
      return response()->json(['error' => 'error', 'message' => $th->getMessage()], 500);
    }
  }

  public function AddWalletRazorPay(Request $request)
  {
    try {
      $user = Auth::guard('sanctum')->user();
      if (!$user) {
        return response()->json(['error' => 'authentication_error', 'message' => 'User is not authenticated.'], 401);
      }

      $userId = $user->id;

      $razorpayKeyId = 'rzp_test_ktbxSvVI7dsfn2';
      $razorpayKeySecret = 'bV0o6z2nrLvgSmiA1eIMCGYx';

      $api = new Api($razorpayKeyId, $razorpayKeySecret);

      $yourDomain = 'https://ecommerce.theprimoapp.com';

      // Amount to be added to the wallet
      $amountToAdd = $request->input('amount');

      // Create order
      $orderData = [
        'receipt' => 'wallet_rcptid_' . time(),
        'amount' => $amountToAdd * 100, // amount in paise
        'currency' => 'INR',
        'payment_capture' => 1 // auto capture payment
      ];

      $order = $this->createOrder($razorpayKeyId, $razorpayKeySecret, $orderData);

      // Assuming order creation is successful, you can return the order ID to the client
      return response()->json(['order_id' => $order['id']], 200);
    } catch (\Throwable $th) {
      return response()->json(['error' => 'error', 'message' => $th->getMessage()], 500);
    }
  }

  private function createOrder($razorpayKeyId, $razorpayKeySecret, $orderData)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Authorization: Basic ' . base64_encode($razorpayKeyId . ':' . $razorpayKeySecret),
      'Content-Type: application/x-www-form-urlencoded'
    ]);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($orderData));

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
      throw new \Exception('cURL error: ' . curl_error($ch));
    }
    curl_close($ch);

    return json_decode($response, true);
  }
}
