<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Wallet;
use PayPalHttp\HttpException;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class PayPalController extends Controller
{
  private $clientId = 'AVzMVWctLyouPgmfv9Nh6E5KakydG4JHiFGm-fgg6HRqFYUW-gHVKS1ebRfPgDOr2uYABGGcnU_3RaSL';
  private $clientSecret = 'EGWCyNAp9oTXjlmckT8DO9lepyKFrWQy2KvPPmrUsard4K98fuArUYbFQl7CaHdhk4Ehdg_hPkToods4';

  public function PayPalCheckout(Request $request)
  {
    // Validate that user_id is provided
    $request->validate([
      'user_id' => 'required|exists:users,id',
    ]);

    $userId = $request->input('user_id');

    try {
      $apiContext = new \PayPalCheckoutSdk\Core\PayPalHttpClient(
        new \PayPalCheckoutSdk\Core\SandboxEnvironment($this->clientId, $this->clientSecret)
      );

      $requestObj = new OrdersCreateRequest();
      $requestObj->prefer('return=representation');
      $requestObj->body = $this->buildRequestBody($userId); // Pass user to buildRequestBody

      $response = $apiContext->execute($requestObj);

      $orderId = $response->result->id;
      $amount = $this->calculateTotalAmount($userId); // Calculate total amount

      return response()->json(['order_id' => $orderId, 'amount' => $amount]);
    } catch (HttpException $ex) {
      return response()->json(['error' => 'error', 'message' => $ex->getMessage()], 500);
    }
  }


  private function calculateTotalAmount($userId)
  {
    $cartItems = $this->getCartItems($userId);

    if (empty($cartItems)) {
      return 0; // Return 0 if cart is empty
    }

    $totalAmount = array_sum(array_column($cartItems, 'amount'));

    return $totalAmount;
  }



  private function buildRequestBody($userId)
  {
    $cartItems = $this->getCartItems($userId);

    if (empty($cartItems)) {
      return null;
    }

    $items = [];
    $totalAmount = 0; // Initialize total amount

    foreach ($cartItems as $item) {
      $items[] = [
        'name' => $item['name'],
        'description' => $this->truncateDescription($item['description']),
        'unit_amount' => [
          'currency_code' => 'USD',
          'value' => number_format($item['amount'] / 100, 2), // Division by 100 is already done here
        ],
        'quantity' => $item['quantity'],
      ];

      // Update total amount with (unit_amount * quantity) for each item
      $totalAmount += $item['amount'] * $item['quantity'];
    }

    // Check if total amount is zero or less
    if ($totalAmount <= 0) {
      return null;
    }

    $requestBody = [
      'intent' => 'CAPTURE',
      'application_context' => [
        'return_url' => 'https://example.com/success',
        'cancel_url' => 'https://example.com/cancel',
      ],
      'purchase_units' => [
        [
          'reference_id' => 'order_rcptid_' . time(),
          'amount' => [
            'currency_code' => 'USD',
            'value' => number_format($totalAmount / 100, 2), // Use total amount here
            'breakdown' => [
              'item_total' => [
                'currency_code' => 'USD',
                'value' => number_format($totalAmount / 100, 2), // Use total amount here
              ],
            ],
          ],
          'items' => $items,
        ],
      ],
    ];

    return $requestBody;
  }


  private function getCartItems($userId)
  {
    if ($userId) {
      $cartItems = CartItem::where('user_id', $userId)->get();

      $items = [];

      foreach ($cartItems as $cartItem) {
        $product = Product::find($cartItem->product_id);

        if ($product) {
          $items[] = [
            'name' => $product->product_name,
            'description' => $product->product_about,
            'amount' => $product->product_sale_price ? $product->product_sale_price : $product->product_price,
            'quantity' => $cartItem->quantity,
          ];
        }
      }

      return $items;
    } else {
      return [];
    }
  }

  private function truncateDescription($description, $maxLength = 127)
  {
    return strlen($description) > $maxLength ? substr($description, 0, $maxLength) : $description;
  }


  public function AddWalletPaypal(Request $request)
  {
    $user = Auth::guard('sanctum')->user();
    if (!$user) {
      return response()->json(['error' => 'authentication_error', 'message' => 'User is not authenticated.'], 401);
    }

    $amount = $request->input('amount');

    // Generate order ID based on timestamp and user ID or any other logic
    $orderId = 'ORDER_' . time() . '_' . $user->id;

    return response()->json(['order_id' => $orderId]);
  }
}
