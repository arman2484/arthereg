<?php

namespace App\Http\Controllers;

use App\Models\TaxRate;
use App\Models\Zone;
use Illuminate\Http\Request;

class TaxRateController extends Controller
{
  public function index()
  {
    return view('content.apps.app-ecommerce-tax-rate-list');
  }
  public function taxRateListData(Request $request)
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
    $totalRecords = TaxRate::select('count(*) as allcount')->count();
    $totalRecordswithFilter = TaxRate::select('count(*) as allcount')->where('name', 'like', '%' . $searchValue . '%')->count();
    $records = TaxRate::with('zoneDetail')->orderBy($columnName, $columnSortOrder)
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
        "tax_rate" => $record->tax_rate,
        "zone_name" => $record->zoneDetail->name,
        "status" => $record->status,
        "type" => $record->type,
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
  public function addTaxRate(Request $request)
  {
    $data = Zone::get();
    return view('content.apps.app-ecommerce-tax-rate-add', compact('data'));
  }
  public function storeTaxRate(Request $request)
  {
    // dd($request->all());
    $rules = [
      'name' => 'required',
      'zone_id' => 'required',
      'tax_rate' => 'required',
      'type' => 'required',
      'status' => 'required',
      // 'price' => 'required',

    ];

    $customMessages = [
      'name.required' => 'Please enter title.',
      'tax_rate.required' => 'Please enter tax rate.',
      'zone_id.required' => 'Please select zone.',
      'status.required' => 'Please select status.',
      'type.required' => 'Please select type.',
      // 'price.required' => 'Please enter price.'
    ];
    $this->validate($request, $rules, $customMessages);
    $data = new TaxRate();
    $data->zone_id = $request->zone_id;
    $data->name = $request->name;
    $data->tax_rate = $request->tax_rate;
    $data->type = $request->type;
    $data->status = $request->status ? 1 : 0;
    $data->save();

    return redirect()->route('app-ecommerce-tax-list')->with('message', 'Tax added successfully');
  }
  public function editTaxRate($id)
  {
    $taxRate = TaxRate::with('zoneDetail')->find($id);
    $data = Zone::get();
    return view('content.apps.app-ecommerce-tax-rate-edit', compact('data', 'taxRate'));
  }
  public function updateTaxRate(Request $request, $id)
  {
    // dd($request->all(), $id);
    $rules = [
      'name' => 'required',
      'zone_id' => 'required',
      'tax_rate' => 'required',
      'type' => 'required',
      'status' => 'required',
      // 'price' => 'required',

    ];

    $customMessages = [
      'name.required' => 'Please enter title.',
      'tax_rate.required' => 'Please enter tax rate.',
      'zone_id.required' => 'Please select zone.',
      'status.required' => 'Please select status.',
      'type.required' => 'Please select type.',
      // 'price.required' => 'Please enter price.'
    ];
    $this->validate($request, $rules, $customMessages);
    $data = TaxRate::find($id);
    $data->zone_id = $request->zone_id;
    $data->name = $request->name;
    $data->tax_rate = $request->tax_rate;
    $data->type = $request->type;
    $data->status = $request->status ? 1 : 0;
    $data->save();

    return redirect()->route('app-ecommerce-tax-list')->with('message', 'Tax updated successfully');
  }
  public function deleteTaxRate($id)
  {
    $data = TaxRate::find($id)->delete();
    return response()->json(['message' => 'Tax deleted successfully', 'id' => $id]);
  }
  public function changeTaxStatus($id, Request $request)
  {
    if ($request->status == 1) {
      $status = 0;
      $data = TaxRate::where('id', $id)->update(['status' => $status]);
      return response()->json(['message' => 'Status changed successfully', 'data' => $data]);
    } else {
      $status = 1;
      $data = TaxRate::where('id', $id)->update(['status' => $status]);
      return response()->json(['message' => 'Status changed successfully', 'data' => $data]);
    }
  }
}