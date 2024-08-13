<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EcommerceOrderList extends Controller
{
  public function index()
  {
    $pendingOrder = Order::where('order_status', 0)->count();
    $completedOrder = Order::where('order_status', 2)->count();
    $cancleOrder = Order::where('order_status', 3)->count();
    return view('content.apps.app-ecommerce-order-list', compact('pendingOrder', 'completedOrder', 'cancleOrder'));
  }
  public function getOrderListData(Request $request)
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
    $totalRecords = Order::select('count(*) as allcount')->count();
    $totalRecordswithFilter = Order::select('count(*) as allcount')->where('order_id', 'like', '%' . $searchValue . '%')->count();
    // dd($columnName, $columnSortOrder);
    $records = Order::select('users.first_name', 'users.last_name', 'users.email', 'users.mobile', 'users.image', 'orders.user_id', 'orders.id', 'orders.order_status', 'orders.order_id', 'orders.created_at', 'orders.payment_mode', 'orders.admin')->orderBy($columnName, $columnSortOrder)
      ->leftjoin('users', 'users.id', '=', 'orders.user_id')
      ->where('orders.order_id', 'like', '%' . $searchValue . '%')
      ->skip($start)
      ->take($rowperpage)
      ->get();
    // dd($records);
    $data_arr = array();


    foreach ($records as $record) {
      $data_arr[] = array(
        "id" => $record->id,
        "order_id" => $record->order_id,
        "created_at" => Carbon::parse($record->created_at)->format('j M Y, H:i'),
        "first_name" => $record->first_name,
        "last_name" => $record->last_name,
        "email" => $record->email,
        "mobile" => $record->mobile,
        "image" => $record->image,
        "order_status" => $record->order_status,
        "payment_mode" => $record->payment_mode,
        "user_id" => $record->user_id,
        "admin" => $record->admin,
        "action" => ''
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

  public function getPendingOrderList(Request $request)
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
    $totalRecords = Order::select('count(*) as allcount')->where('order_status', 0)->count();
    $totalRecordswithFilter = Order::select('count(*) as allcount')->where('order_id', 'like', '%' . $searchValue . '%')->where('order_status', 0)->count();
    // dd($columnName, $columnSortOrder);
    $records = Order::select('users.first_name', 'users.last_name', 'users.email', 'users.mobile', 'users.image', 'orders.user_id', 'orders.id', 'orders.order_status', 'orders.order_id', 'orders.created_at', 'orders.payment_mode', 'orders.admin')
      ->orderBy($columnName, $columnSortOrder)
      ->leftjoin('users', 'users.id', '=', 'orders.user_id')
      ->where('order_status', 0)
      ->where('orders.order_id', 'like', '%' . $searchValue . '%')
      ->skip($start)
      ->take($rowperpage)
      ->get();
    // dd($records);
    $data_arr = array();


    foreach ($records as $record) {
      $data_arr[] = array(
        "id" => $record->id,
        "order_id" => $record->order_id,
        "created_at" => Carbon::parse($record->created_at)->format('j M Y, H:i'),
        "first_name" => $record->first_name,
        "last_name" => $record->last_name,
        "email" => $record->email,
        "mobile" => $record->mobile,
        "image" => $record->image,
        "order_status" => $record->order_status,
        "payment_mode" => $record->payment_mode,
        "user_id" => $record->user_id,
        "admin" => $record->admin,
        "action" => ''
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
  public function getCompletedOrderList(Request $request)
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
    $totalRecords = Order::select('count(*) as allcount')->where('order_status', 2)->count();
    $totalRecordswithFilter = Order::select('count(*) as allcount')->where('order_id', 'like', '%' . $searchValue . '%')->where('order_status', 2)->count();
    // dd($columnName, $columnSortOrder);
    $records = Order::select('users.first_name', 'users.last_name', 'users.email', 'users.mobile', 'users.image', 'orders.user_id', 'orders.id', 'orders.order_status', 'orders.order_id', 'orders.created_at', 'orders.payment_mode', 'orders.admin')
      ->orderBy($columnName, $columnSortOrder)
      ->leftjoin('users', 'users.id', '=', 'orders.user_id')
      ->where('order_status', 2)
      ->where('orders.order_id', 'like', '%' . $searchValue . '%')
      ->skip($start)
      ->take($rowperpage)
      ->get();
    // dd($records);
    $data_arr = array();


    foreach ($records as $record) {
      $data_arr[] = array(
        "id" => $record->id,
        "order_id" => $record->order_id,
        "created_at" => Carbon::parse($record->created_at)->format('j M Y, H:i'),
        "first_name" => $record->first_name,
        "last_name" => $record->last_name,
        "email" => $record->email,
        "mobile" => $record->mobile,
        "image" => $record->image,
        "order_status" => $record->order_status,
        "payment_mode" => $record->payment_mode,
        "user_id" => $record->user_id,
        "admin" => $record->admin,
        "action" => ''
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
  public function getCancleOrderList(Request $request)
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
    $totalRecords = Order::select('count(*) as allcount')->where('order_status', 3)->count();
    $totalRecordswithFilter = Order::select('count(*) as allcount')->where('order_id', 'like', '%' . $searchValue . '%')->where('order_status', 3)->count();
    // dd($columnName, $columnSortOrder);
    $records = Order::select('users.first_name', 'users.last_name', 'users.email', 'users.mobile', 'users.image', 'orders.user_id', 'orders.id', 'orders.order_status', 'orders.order_id', 'orders.created_at', 'orders.payment_mode', 'orders.admin')
      ->orderBy($columnName, $columnSortOrder)
      ->leftjoin('users', 'users.id', '=', 'orders.user_id')
      ->where('order_status', 3)
      ->where('orders.order_id', 'like', '%' . $searchValue . '%')
      ->skip($start)
      ->take($rowperpage)
      ->get();
    // dd($records);
    $data_arr = array();


    foreach ($records as $record) {
      $data_arr[] = array(
        "id" => $record->id,
        "order_id" => $record->order_id,
        "created_at" => Carbon::parse($record->created_at)->format('j M Y, H:i'),
        "first_name" => $record->first_name,
        "last_name" => $record->last_name,
        "email" => $record->email,
        "mobile" => $record->mobile,
        "image" => $record->image,
        "order_status" => $record->order_status,
        "payment_mode" => $record->payment_mode,
        "user_id" => $record->user_id,
        "admin" => $record->admin,
        "action" => ''
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
}
