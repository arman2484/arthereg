<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserViewAccount extends Controller
{
  public function index($id)
  {
    $data = User::where('id', $id)->first();
    $orderCount = Order::where('user_id', $id)->count();
    return view('content.apps.app-user-view-account', compact('id', 'data', 'orderCount'));
  }
  public function getOrderListData(Request $request, $id)
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
    $totalRecords = Order::select('count(*) as allcount')->where('user_id', $id)->count();
    // dd($totalRecords);
    $totalRecordswithFilter = Order::select('count(*) as allcount')->where('order_id', 'like', '%' . $searchValue . '%')->where('user_id', $id)->count();
    $records = Order::orderBy($columnName, $columnSortOrder)
      ->where('orders.order_id', 'like', '%' . $searchValue . '%')
      ->where('orders.user_id', $id)
      ->skip($start)
      ->take($rowperpage)
      ->get();
    $data_arr = array();
    foreach ($records as $record) {
      $data_arr[] = array(
        "id" => $record->id,
        "order_id" => $record->order_id,
        "created_at" => Carbon::parse($record->created_at)->format('l, j M'),
        "order_status" => $record->order_status,
      );
    }
    // dd($data_arr);
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
