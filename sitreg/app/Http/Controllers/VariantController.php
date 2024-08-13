<?php

namespace App\Http\Controllers;

use App\Models\Variant;
use Illuminate\Http\Request;

class VariantController extends Controller
{
  public function index()
  {
    return view('content.apps.app-ecommerce-variant-list');
  }
  public function variantListData(Request $request)
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
    $totalRecords = Variant::select('count(*) as allcount')->count();
    $totalRecordswithFilter = Variant::select('count(*) as allcount')->where('name', 'like', '%' . $searchValue . '%')->count();
    $records = Variant::orderBy($columnName, $columnSortOrder)
      ->where('name', 'like', '%' . $searchValue . '%')
      ->skip($start)
      ->take($rowperpage)
      ->get();
    // dd($records);
    $data_arr = array();


    foreach ($records as $record) {
      $data_arr[] = array(
        "id" => $record->id,
        "name" => $record->name,
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
  public function addVariant(Request $request)
  {
    return view('content.apps.app-ecommerce-variant-add');
  }
  public function storeVariant(Request $request)
  {
    $rules = [
      'name' => 'required',
      'status' => 'required',
      // 'price' => 'required',

    ];

    $customMessages = [
      'name.required' => 'Please enter title.',
      'status.required' => 'Please select status.',
      // 'price.required' => 'Please enter price.'
    ];
    $this->validate($request, $rules, $customMessages);
    $data = new Variant();
    $data->name = $request->name;
    $data->status = $request->status ? 1 : 0;
    $data->save();
    return redirect()->route('app-ecommerce-variant-list')->with('message', 'Variant added successfully');
  }
  public function editVariant($id)
  {
    $data = Variant::find($id);
    return view('content.apps.app-ecommerce-variant-edit', compact('data'));
  }
  public function updateVariant(Request $request, $id)
  {
    // dd($request->all());
    $rules = [
      'name' => 'required',
      // 'status' => 'required',
      // 'price' => 'required',

    ];

    $customMessages = [
      'name.required' => 'Please enter name.',
      // 'status.required' => 'Please select status.',
      // 'price.required' => 'Please enter price.'
    ];
    $this->validate($request, $rules, $customMessages);
    $data = Variant::find($id);
    $data->name = $request->name;
    $data->status = $request->status ? 1 : 0;
    $data->save();
    return redirect()->route('app-ecommerce-variant-list')->with('message', 'Variant updated successfully');
  }
  public function deleteVariant($id)
  {
    $data = Variant::find($id)->delete();
    return response()->json(['message' => 'Variant deleted successfully', 'id' => $id]);
  }
  public function statusVariant($id, Request $request)
  {
    if ($request->status == 1) {
      $status = 0;
      $data = Variant::where('id', $id)->update(['status' => $status]);
      return response()->json(['message' => 'Status changed successfully', 'data' => $data]);
    } else {
      $status = 1;
      $data = Variant::where('id', $id)->update(['status' => $status]);
      return response()->json(['message' => 'Status changed successfully', 'data' => $data]);
    }
  }
}
