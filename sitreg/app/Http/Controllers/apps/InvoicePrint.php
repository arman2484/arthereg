<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class InvoicePrint extends Controller
{
  public function index($id)
  {
    // dd($id);
    $records = OrderItem::select(
      'products.id',
      'products.product_name',
      'products.product_sale_price',
      'products.product_price',
      'products.product_about',
      'order_items.order_id',
      'order_items.product_id',
      'order_items.quantity',
      //  'user_address.first_name', 'user_address.last_name', 'user_address.address', 'user_address.locality', 'user_address.city', 'user_address.pincode', 'user_address.state', 
      'order_items.coupon_id',
      'coupons.discount_amount',
      'coupons.id'
    )
      ->leftjoin('products', 'products.id', '=', 'order_items.product_id')
      // ->leftjoin('user_address', 'order_items.user_id', '=', 'user_address.user_id')
      ->leftjoin('coupons', 'coupons.id', '=', 'order_items.coupon_id')
      ->where('order_items.order_id', $id)
      ->get();
    $data = OrderItem::select('order_items.user_id', 'order_items.address_id', 'orders.order_id', 'orders.created_at', 'order_items.payment_mode', 'orders.created_at', 'orders.payment_mode')
      ->leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
      ->where('order_items.order_id', $id)
      ->first();
    // dd($data);
    if ($data->address_id) {
      $userAddress = UserAddress::select(
        'id',
        'first_name',
        'last_name',
        'mobile',
        'pincode',
        'address',
        'locality',
        'city',
        'state',
        'type'
      )
        ->where('id', $data->address_id)
        ->first();
    } elseif ($data->address_id == 0 && $data->address_id == null) {
      $userAddress = UserAddress::select(
        'id',
        'first_name',
        'last_name',
        'mobile',
        'pincode',
        'address',
        'locality',
        'city',
        'state',
        'type'
      )
        ->where('user_id', $data->user_id)
        ->where('last_address_status', '1')
        ->first();
    }
    foreach ($records as $value) {
      if ($value->product_sale_price) {
        $amount[] = $value->product_sale_price * $value->quantity;
      } else {
        $amount[] = $value->product_price * $value->quantity;
      }
    }
    $discount_amount = 0;
    foreach ($records as $value) {
      if ($value->discount_amount) {
        $discount_amount = $value->discount_amount;
      }
    }
    // dd($userAddress);
    $subTotal = 0;
    foreach ($amount as $value) {
      $subTotal += $value;
    }
    $total = $subTotal - $discount_amount;
    $pageConfigs = ['myLayout' => 'blank'];
    // return view('content.apps.app-invoice-preview', compact('records', 'subTotal', 'data', 'userAddress', 'total', 'discount_amount', 'id'));
    return view('content.apps.app-invoice-print', ['pageConfigs' => $pageConfigs], compact('records', 'subTotal', 'data', 'userAddress', 'total', 'discount_amount', 'id'));
  }
}
