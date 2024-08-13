<?php

namespace App\Http\Controllers\wizard_example;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\CartItem;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class Checkout extends Controller
{
  public function index()
  {
    $addresses = UserAddress::where('admin', 1)->get();
    // Fetch cart items where admin_cart is 1
    $cartItems = CartItem::where('admin_cart', 1)->with(['productImages'])->get();

    $customer = User::all();

    // Pass the cart items to the view
    return view('content.wizard-example.wizard-ex-checkout', compact('cartItems', 'addresses', 'customer'));
  }


  public function addAddress(Request $request)
  {
    $request->validate([
      'first_name' => 'required',
      'last_name' => 'required',
      'mobile' => 'required',
      'address' => 'required',
      'locality' => 'required',
      'city' => 'required',
      'state' => 'required',
      'pincode' => 'required',
      'type' => 'required|in:home,office',
    ]);

    UserAddress::create([
      'first_name' => $request->first_name,
      'last_name' => $request->last_name,
      'mobile' => $request->mobile,
      'address' => $request->address,
      'locality' => $request->locality,
      'city' => $request->city,
      'state' => $request->state,
      'pincode' => $request->pincode,
      'type' => $request->type,
      'default_address' => $request->has('default_address') ? 1 : 0,
      'admin' => 1,
    ]);

    return response()->json(['message' => 'Address added successfully'], 200);
  }


  public function updateCartAddress(Request $request)
  {
    // Validate incoming request
    $request->validate([
      'address_id' => 'required|exists:user_address,id',
    ]);

    // Assuming you have a session or user context to associate the cart with
    $userId = auth()->id(); // Adjust as per your application logic

    // Update cart items with the selected address ID
    CartItem::where('admin_cart', 1)
      ->update(['address_id' => $request->address_id]);

    return response()->json(['success' => true]);
  }

  // Checkout controller
  public function deleteAddress($id)
  {
    $address = UserAddress::findOrFail($id);
    $address->delete();

    return response()->json(['message' => 'Address deleted successfully'], 200);
  }


  public function payNow(Request $request)
  {
    $user_id = $request->user_id; // Assuming user_id is passed in the request

    if (!$user_id) {
      return response()->json([
        'status' => false,
        'message' => 'Bad request...!',
      ], 401);
    }

    // Retrieve cart items with product details where admin_cart is 1
    $orderDetails = CartItem::select(
      'cart_items.user_id',
      'cart_items.product_id',
      'cart_items.quantity',
      'cart_items.address_id',
      'products.product_price',
      'products.product_sale_price'
    )
      ->leftJoin('products', 'products.id', '=', 'cart_items.product_id')
      ->where('cart_items.user_id', $user_id)
      ->where('cart_items.admin_cart', 1)
      ->get();

    // Check if there are no valid items in the cart
    if ($orderDetails->isEmpty()) {
      return response()->json([
        'status' => false,
        'message' => 'No valid items found in the cart with admin_cart as 1',
      ], 400);
    }

    // Generate new order ID
    $lastOrder = Order::orderBy('id', 'desc')->first();
    $orderId = $lastOrder ? str_pad($lastOrder->order_id + 1, 4, '0', STR_PAD_LEFT) : '0001';

    // Initialize total amount and address ID
    $totalAmount = 0;
    $addressId = 0;

    foreach ($orderDetails as $detail) {
      // Calculate total amount for the order
      $productPrice = $detail->product_sale_price ?? $detail->product_price;
      $totalAmount += $productPrice * $detail->quantity;

      // Assign address ID from cart item
      $addressId = $detail->address_id ?? 0;
    }

    // Create new order
    $order = new Order();
    $order->user_id = $user_id;
    $order->order_id = $orderId;
    $order->total_item = $orderDetails->count();
    $order->address_id = $addressId;
    $order->payment_mode = $request->payment_mode ?? '';
    $order->order_status = 1; // Assuming 1 represents an active order status
    $order->total_amount = $totalAmount;
    $order->admin = 1; // Set admin to 1
    $order->save();

    // Create order items
    foreach ($orderDetails as $detail) {
      $orderItem = new OrderItem();
      $orderItem->user_id = $user_id;
      $orderItem->order_id = $order->id;
      $orderItem->address_id = $detail->address_id;
      $orderItem->product_id = $detail->product_id;
      $orderItem->coupon_id = $request->coupon_id ?? null; // Assuming coupon_id is passed in the request
      $orderItem->quantity = $detail->quantity;
      $orderItem->admin = 1; // Set admin to 1
      $orderItem->save();
    }

    // Clear cart items after order placement
    CartItem::where('user_id', $user_id)
      ->where('admin_cart', 1)
      ->delete();

    // Prepare notification data
    $notificationData = [
      'title' => 'Order created',
      'message' => 'Your order has been created successfully',
      'type' => 'verified',
      'sender_id' => Admin::first()->id, // Ensure Admin model is correctly referenced
    ];

    // Prepare response data
    $responseData = [
      'status' => true,
      'message' => 'Order Placed Successfully...!',
    ];

    // Return JSON response
    return response()->json($responseData, 201);
  }





  public function updateCartUserId(Request $request)
  {
    $selectedCustomerId = $request->user_id;

    if (!$selectedCustomerId) {
      return response()->json([
        'status' => false,
        'message' => 'No customer ID provided',
      ], 400);
    }

    // Update cart_items where admin_cart is 1
    CartItem::where('admin_cart', 1)
      ->update(['user_id' => $selectedCustomerId]);

    return response()->json([
      'status' => true,
      'message' => 'User ID updated in cart_items successfully',
    ]);
  }
}
