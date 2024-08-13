<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Notifications;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\UserAddress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EcommerceOrderDetails extends Controller
{
  public function index($id)
  {
    // dd($id);
    $data = OrderItem::select('orders.id', 'orders.order_id as OrderID', 'order_items.user_id', 'order_items.order_id', 'order_items.product_id', 'order_items.address_id', 'order_items.quantity', 'order_items.is_status', 'users.id', 'users.image', 'users.first_name', 'users.last_name', 'users.email', 'users.mobile', 'order_items.coupon_id', 'coupons.discount_amount')
      ->leftjoin('users', 'users.id', '=', 'order_items.user_id')
      ->leftjoin('orders', 'orders.id', '=', 'order_items.order_id')
      ->leftjoin('coupons', 'coupons.id', '=', 'order_items.coupon_id')
      ->where('order_items.order_id', $id)->first();
    if ($data->address_id) {
      $address = UserAddress::select(
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
    } elseif ($data->address_id == null) {
      $address = UserAddress::select(
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
        ->where('user_id', $data->id)
        ->where('last_address_status', '1')
        ->first();
    }
    $records = OrderItem::with('productImages')->select('products.id', 'products.product_name', 'products.product_sale_price', 'products.product_price', 'products.product_about', 'order_items.order_id', 'order_items.product_id', 'order_items.quantity', 'order_items.coupon_id', 'coupons.discount_amount', 'order_items.product_size', 'order_items.product_color',)
      ->leftjoin('products', 'products.id', '=', 'order_items.product_id')
      ->leftjoin('coupons', 'coupons.id', '=', 'order_items.coupon_id')
      ->where('order_items.order_id', '=', $id)
      ->get();
    foreach ($records as $value) {
      if ($value->product_sale_price != null || $value->product_sale_price > 0) {
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
    $subTotal = 0;
    foreach ($amount as $value) {
      $subTotal += $value;
    }
    $total = $subTotal - $discount_amount;
    return view('content.apps.app-ecommerce-order-details', compact('data', 'records', 'address', 'subTotal', 'total', 'discount_amount', 'address'));
  }
  public function getOrderDetails(Request $request, $id)
  {
    $draw = $request->get('draw');
    $start = $request->get("start");
    $rowperpage = $request->get("length");
    $columnIndex_arr = $request->get('order');
    $columnName_arr = $request->get('columns');
    $order_arr = $request->get('order');
    $search_arr = $request->get('search');
    $columnIndex = $columnIndex_arr[0]['column'];
    $columnName = $columnName_arr[$columnIndex]['data'];
    $columnSortOrder = $order_arr[0]['dir'];
    $searchValue = $search_arr['value'];
    $totalRecords = OrderItem::where('order_id', $id)->select('count(*) as allcount')->count();
    $totalRecordswithFilter = OrderItem::select('count(*) as allcount')->where('order_id', 'like', '%' . $searchValue . '%')->where('order_id', $id)->count();
    $records = OrderItem::with('productImages')->select('products.id', 'products.product_name', 'products.product_about', 'products.product_sale_price', 'order_items.is_status', 'order_items.order_id', 'order_items.product_id', 'order_items.quantity',)
      ->orderBy($columnName, $columnSortOrder)
      ->join('products', 'products.id', '=', 'order_items.product_id')
      ->where('order_items.order_id', 'like', '%' . $searchValue . '%')
      ->where('order_id', $id)
      ->skip($start)
      ->take($rowperpage)
      ->get();
    // dd($records);
    $data_arr = array();
    foreach ($records as $record) {
      $data_arr[] = array(
        "id" => '',
        "product_name" => $record->product_name,
        "product_image" => $record->productImages->product_image ?? "",
        "product_about" => $record->product_about,
        "price" => $record->product_sale_price,
        "qty" => $record->quantity,
      );
    }
    $response = array(
      "draw" => intval($draw),
      "iTotalRecords" => $totalRecords,
      "iTotalDisplayRecords" => $totalRecordswithFilter,
      "aaData" => $data_arr
    );
    echo json_encode($response);
    exit;
  }

  public function orderStatus(Request $request)
  {
    $user_id = OrderItem::where('order_items.order_id', $request->order_id)->first()->user_id;
    OrderItem::where('order_items.order_id', $request->order_id)->update(['is_status' => $request->is_status]);
    Order::where('id', $request->order_id)->update(['order_status' => $request->is_status]);

    if ($request->is_status == '2') {
      $user = User::where('id', $user_id)->first();
      $notification = new Notifications([
        'title' => 'Your order has been delivered.',
        'message' => 'Your order has been placed.',
        'sender_id' => Admin::first()->id,
      ]);
      $FcmToken = User::select('device_token')->where('id', $user_id)->first()->device_token;
      $data = [
        "registration_ids" => array($FcmToken),
        "notification" => [
          "title" => 'Your order has been delivered.',
          "message" => 'Your order has been delivered.',
          "type" => 'verified',
          "sender_id" => Admin::first()->id,
        ],
        "data" => [
          "title" => 'Your order has been delivered.',
          'sender_id' =>  Admin::first()->id,
          'message' => 'Your order has been delivered.',
          'user_id' => $user_id,
          "type" => "verified"
        ]
      ];

      $this->sendNotification($data);
      $user->notifications()->save($notification);
    }

    return response()->json(['message' => 'Status updated successfully']);
  }
  public function orderDelete($id)
  {
    $data = OrderItem::where('order_items.order_id', $id)->delete();
    $data = Order::where('id', $id)->delete();
    return response()->json(['message' => 'Order deleted successfully']);
  }
  function sendNotification($data)
  {
    $url = 'https://fcm.googleapis.com/fcm/send';

    // $serverKey = getenv('FIREBSE_SERVERKEY');
    $serverKey = 'AAAAX26WaPo:APA91bFF8vpxBlzyLUzetmH_ytImj3iJ-9cASib10YymuwuMF_SmDvEo4kcZHQzlRYBLEE5_8ud-K1rdrusQe7gSess0bcDGJuKAVNNeB9ls3NmJAoQ3BMSIfNVsAb4yJdqbVV9ngSAk';
    $encodedData = json_encode($data);
    $headers = [
      'Authorization:key=' . $serverKey,
      'Content-Type: application/json',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    // Disabling SSL Certificate support temporarly
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
    // Execute post
    $result = curl_exec($ch);
    if ($result === FALSE) {
      die('Curl failed: ' . curl_error($ch));
    }
    // Close connection
    curl_close($ch);
    return true;

    // // FCM response
    // echo "--";
    // print_r($result);
    // echo "--";
  }
}
