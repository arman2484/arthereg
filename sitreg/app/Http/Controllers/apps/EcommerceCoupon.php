<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class EcommerceCoupon extends Controller
{
  public function index()
  {
    return view('content.apps.app-ecommerce-coupon');
  }
  public function couponList(Request $request)
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
    $totalRecords = Coupon::select('count(*) as allcount')->count();
    $totalRecordswithFilter = Coupon::select('count(*) as allcount')->count();
    // dd($start);
    $records = Coupon::orderBy($columnName, $columnSortOrder)
      ->skip($start)
      ->take($rowperpage)
      ->get();
    // dd($records);
    $data_arr = array();
    foreach ($records as $record) {
      $data_arr[] = array(
        "id" => $record->id,
        "coupon_code" => $record->coupon_code,
        "discount_amount" => $record->discount_amount,
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
  public function add(Request $request)
  {
    // dd($request->all());
    $rules = [
      'coupon_code' => 'required',
      'discount_amount' => 'required',
      'status' => 'required',

    ];

    $customMessages = [
      'coupon_code.required' => 'Please enter coupon code.',
      'discount_amount.required' => 'Please enter coupon amount.',
      'status.required' => 'Please select status.'
    ];
    $this->validate($request, $rules, $customMessages);
    $data = new Coupon();
    $data->coupon_code = $request->coupon_code;
    $data->discount_amount = $request->discount_amount;
    $data->description = $request->description;
    $data->status = $request->status;
    $data->save();
    return redirect()->route('app-ecommerce-coupon')->with('message', 'Coupon added successfully');
  }
  public function couponEdit($id)
  {
    $data = Coupon::find($id);
    return response()->json(['data' => $data]);
  }
  public function update(Request $request)
  {
    $rules = [
      'coupon_code' => 'required',
      'discount_amount' => 'required',
      'status' => 'required',

    ];

    $customMessages = [
      'coupon_code.required' => 'Please enter coupon code.',
      'discount_amount.required' => 'Please enter coupon amount.',
      'status.required' => 'Please select status.'
    ];
    $this->validate($request, $rules, $customMessages);
    $data = Coupon::where('id', $request->coupon_id)->first();
    $data->coupon_code = $request->coupon_code;
    $data->discount_amount = $request->discount_amount;
    $data->description = $request->description;
    $data->status = $request->status;
    $data->save();
    return redirect()->route('app-ecommerce-coupon')->with('message', 'Coupon update successfully');
  }
  public function couponDelete($id)
  {
    $data = Coupon::where('id', $id)->delete();
    return response()->json(['message' => 'Coupon deleted successfully']);
  }
}
