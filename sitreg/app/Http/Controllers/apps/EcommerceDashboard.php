<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EcommerceDashboard extends Controller
{
  public function index()
  {
    $totalSales = Order::sum('total_amount');
    $totalProduct = Product::count('id');
    $totalUser = User::count('id');
    $totalOrders = Order::count('id');
    $totalcatgories = Category::count('id');
    return view('content.apps.app-ecommerce-dashboard', compact('totalSales', 'totalProduct', 'totalUser', 'totalOrders', 'totalcatgories'));
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
    $totalRecordswithFilter = Order::select('count(*) as allcount')->where('orders.order_id', 'like', '%' . $searchValue . '%')->count();
    // dd($totalRecordswithFilter);
    $records = Order::select('users.id', 'users.first_name', 'users.last_name', 'users.email', 'users.mobile', 'users.image', 'orders.user_id', 'orders.id as orders_id', 'orders.order_status', 'orders.order_id', 'orders.created_at', 'orders.admin')->orderBy($columnName, $columnSortOrder)
      ->join('users', 'users.id', '=', 'orders.user_id')
      ->where('orders.order_id', 'like', '%' . $searchValue . '%')
      ->skip($start)
      ->limit(10)
      ->get();
    $data_arr = array();
    foreach ($records as $record) {
      $data_arr[] = array(
        "orders_id" => $record->orders_id,
        "order_id" => $record->order_id,
        "user_id" => $record->id,
        "created_at" => Carbon::parse($record->created_at)->format('l, j M'),
        "first_name" => $record->first_name,
        "last_name" => $record->last_name,
        "email" => $record->email,
        "mobile" => $record->mobile,
        "image" => $record->image,
        "status" => $record->order_status,
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
  public function getUserListData(Request $request)
  {
    $draw = $request->get('draw');
    $start = $request->get("start");
    $rowperpage = $request->get("length"); // Rows display per page
    $columnIndex_arr = $request->get('order');
    $columnName_arr = $request->get('columns');
    $order_arr = $request->get('order');
    $search_arr = $request->get('search');
    $columnIndex = $columnIndex_arr[0]['column']; // Column index
    $columnName = $columnName_arr[$columnIndex]['data']; // Column name
    $columnSortOrder = $order_arr[0]['dir']; // asc or desc
    $searchValue = $search_arr['value']; // Search value
    $totalRecords = User::select('count(*) as allcount')->count();
    $totalRecordswithFilter = User::select('count(*) as allcount')->where('first_name', 'like', '%' . $searchValue . '%')->count();
    $records = User::orderBy($columnName, $columnSortOrder)
      ->where('users.first_name', 'like', '%' . $searchValue . '%')
      ->select('users.*')
      ->skip($start)
      ->limit(10)
      ->get();
    $data_arr = array();
    foreach ($records as $record) {
      $data_arr[] = array(
        "id" => $record->id,
        "first_name" => $record->first_name . ' ' . $record->last_name,
        "email" => $record->email,
        "mobile" => $record->mobile,
        "image" => $record->image,
        "status" => $record->status,
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
  public function dashboardCharData()
  {
    $currentYear = Carbon::now()->year;
    // $currentDateAsString = $currentDate->toDateString();
    // dd($currentYear);
    $jan = Order::whereBetween('created_at', [$currentYear . '-01-01', $currentYear . '-01-31'])->sum('total_amount');
    $feb = Order::whereBetween('created_at', [$currentYear . '-02-01', $currentYear . '-02-29'])->sum('total_amount');
    $march = Order::whereBetween('created_at', [$currentYear . '-03-01', $currentYear . '-03-31'])->sum('total_amount');
    $april = Order::whereBetween('created_at', [$currentYear . '-04-01', $currentYear . '-04-30'])->sum('total_amount');
    $may = Order::whereBetween('created_at', [$currentYear . '-05-01', $currentYear . '-05-31'])->sum('total_amount');
    $june = Order::whereBetween('created_at', [$currentYear . '-06-01', $currentYear . '-06-30'])->sum('total_amount');
    $july = Order::whereBetween('created_at', [$currentYear . '-07-01', $currentYear . '-07-31'])->sum('total_amount');
    $august = Order::whereBetween('created_at', [$currentYear . '-08-01', $currentYear . '-08-31'])->sum('total_amount');
    $sept = Order::whereBetween('created_at', [$currentYear . '-09-01', $currentYear . '-09-30'])->sum('total_amount');
    $oct = Order::whereBetween('created_at', [$currentYear . '-10-01', $currentYear . '-10-31'])->sum('total_amount');
    $nov = Order::whereBetween('created_at', [$currentYear . '-11-01', $currentYear . '-11-30'])->sum('total_amount');
    $dec = Order::whereBetween('created_at', [$currentYear . '-12-01', $currentYear . '-11-31'])->sum('total_amount');
    $chartData = [$jan, $feb, $march, $april, $may, $june, $july, $august, $sept, $oct, $nov, $dec];
    // dd($chartData);
    return response()->json($chartData);
  }
  public function dashboardOrderDelete($id)
  {
    OrderItem::where('order_items.order_id', $id)->delete();
    Order::where('id', $id)->delete();
    return response()->json(['message' => 'Order deleted successfully']);
  }
  public function dashboardUserDelete($id)
  {
    User::where('id', $id)->delete();
    return response()->json(['message' => 'User deleted successfully']);
  }
}
