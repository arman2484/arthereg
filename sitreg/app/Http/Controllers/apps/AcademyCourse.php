<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\VendorStore;
use Illuminate\Http\Request;
use App\Models\CartItem;
use Auth;


class AcademyCourse extends Controller
{
  public function index(Request $request)
  {
    $stores = VendorStore::all();
    $storeId = $request->get('store_id');

    $query = Product::with(['productImages', 'category']);

    if ($storeId) {
      $query->where('store_id', $storeId);
    }

    $products = $query->orderBy('created_at', 'desc')->paginate(9);
    $cartItems = CartItem::where('admin_cart', 1)->pluck('product_id')->toArray();

    return view('content.apps.app-academy-course', compact('stores', 'products', 'storeId', 'cartItems'));
  }


  public function add(Request $request)
  {
    $validatedData = $request->validate([
      'product_id' => 'required|exists:products,id',
      'quantity' => 'required|integer|min:1',
    ]);

    $cartItem = new CartItem();
    $cartItem->product_id = $validatedData['product_id'];
    $cartItem->quantity = $validatedData['quantity'];
    $cartItem->admin_cart = 1; // Setting admin_cart as 1
    $cartItem->save();

    return response()->json(['message' => 'Product added to cart successfully.']);
  }

  public function removeItem($itemId)
  {
    $cartItem = CartItem::find($itemId);

    if (!$cartItem) {
      return response()->json(['error' => 'Cart item not found.'], 404);
    }

    $cartItem->delete();

    return response()->json(['success' => true, 'message' => 'Item removed from cart successfully.']);
  }


  public function updateQuantity(Request $request, $itemId)
  {
    $validatedData = $request->validate([
      'quantity' => 'required|integer|min:1',
    ]);

    $cartItem = CartItem::find($itemId);

    if (!$cartItem) {
      return response()->json(['error' => 'Cart item not found.'], 404);
    }

    $cartItem->quantity = $validatedData['quantity'];
    $cartItem->save();

    return response()->json(['success' => true, 'message' => 'Item quantity updated successfully.']);
  }
}
