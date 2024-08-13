<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
  public function index()
  {
    return view('content.apps.app-ecommerce-shipping-list');
  }
  public function shippingListData(Request $request)
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
    $totalRecords = Shipping::select('count(*) as allcount')->count();
    $totalRecordswithFilter = Shipping::select('count(*) as allcount')->count();
    $records = Shipping::orderBy($columnName, $columnSortOrder)
      ->where('delivery_title', 'like', '%' . $searchValue . '%')
      ->skip($start)
      ->take($rowperpage)
      ->get();
    $data_arr = array();


    foreach ($records as $record) {
      $data_arr[] = array(
        "id" => $record->id,
        "delivery_title" => $record->delivery_title,
        "price" => $record->price,
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
  public function addShippingData(Request $request)
  {
    return view('content.apps.app-ecommerce-shipping-add');
  }
  public function storeShippingData(Request $request)
  {
    $rules = [
      'delivery_title' => 'required',
      'status' => 'required',
      // 'price' => 'required',

    ];

    $customMessages = [
      'delivery_title.required' => 'Please enter delivery title.',
      'status.required' => 'Please select status.',
      // 'price.required' => 'Please enter price.'
    ];
    $this->validate($request, $rules, $customMessages);
    $data = new Shipping();
    $data->delivery_title = $request->delivery_title;
    $data->price = $request->price;
    $data->status = $request->status ? 1 : 0;
    $data->save();
    return redirect()->route('app-ecommerce-shipping-list')->with('message', 'Shipping added successfully');
  }
  public function deleteShippingData($id)
  {
    $data = Shipping::find($id)->delete();
    return response()->json(['message' => 'Shipping deleted successfully', 'id' => $id]);
  }
  public function editShippingData($id)
  {
    $data = Shipping::find($id);
    return view('content.apps.app-ecommerce-shipping-edit', compact('data'));
  }
  public function updateShippingData(Request $request, $id)
  {
    $rules = [
      'delivery_title' => 'required',
      // 'status' => 'required',
      // 'price' => 'required',

    ];

    $customMessages = [
      'delivery_title.required' => 'Please enter delivery title.',
      // 'status.required' => 'Please select status.',
      // 'price.required' => 'Please enter price.'
    ];
    $this->validate($request, $rules, $customMessages);
    $data = Shipping::find($id);
    $data->delivery_title = $request->delivery_title;
    $data->price = $request->price;
    $data->status = $request->status ? 1 : 0;
    $data->save();
    return redirect()->route('app-ecommerce-shipping-list')->with('message', 'Shipping updated successfully');
  }
  public function statusShipping($id, Request $request)
  {
    if ($request->status == 1) {
      $status = 0;
      $data = Shipping::where('id', $id)->update(['status' => $status]);
      return response()->json(['message' => 'Status changed successfully', 'data' => $data]);
    } else {
      $status = 1;
      $data = Shipping::where('id', $id)->update(['status' => $status]);
      return response()->json(['message' => 'Status changed successfully', 'data' => $data]);
    }
  }
}
