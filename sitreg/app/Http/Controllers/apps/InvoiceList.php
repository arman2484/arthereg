<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceList extends Controller
{
  public function index()
  {
    return view('content.apps.app-invoice-list');
  }
  public function invoiceData(Request $request)
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
    // dd($columnSortOrder, $columnName);
    $records = Order::select('users.id as user_id', 'users.first_name', 'users.last_name', 'users.email', 'users.image', 'orders.user_id', 'orders.id', 'orders.order_status', 'orders.order_id', 'orders.created_at', 'orders.total_amount')->orderBy($columnName, $columnSortOrder)
      ->leftjoin('users', 'users.id', '=', 'orders.user_id')
      ->where('orders.order_id', 'like', '%' . $searchValue . '%')
      ->orWhere('orders.created_at', 'like', '%' . $searchValue . '%')
      ->orWhere('users.first_name', 'like', '%' . $searchValue . '%')
      ->orWhere('orders.total_amount', 'like', '%' . $searchValue . '%')
      ->skip($start)
      ->take($rowperpage)
      ->get();
    $data_arr = array();
    // dd($records);
    foreach ($records as $record) {
      $data_arr[] = array(
        "id" => $record->id,
        "user_id" => $record->user_id,
        "invoice_id" => $record->order_id,
        "issued_date" => Carbon::parse($record->created_at)->format('j M Y, H:i'),
        "client_name" => $record->first_name . ' ' . $record->last_name,
        "image" => $record->image,
        "total" => $record->total_amount,
        "invoice_status" => $record->order_status,
        "action" => ''
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
