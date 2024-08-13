<?php

namespace App\Http\Controllers;

use App\Models\CountryDetail;
use App\Models\State;
use App\Models\Zone;
use App\Models\ZoneDetail;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
  public function index()
  {
    return view('content.apps.app-ecommerce-zone-list');
  }
  public function zoneListData(Request $request)
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
    $totalRecords = Zone::select('count(*) as allcount')->count();
    $totalRecordswithFilter = Zone::select('count(*) as allcount')->with('zoneDetail', 'country')
      ->whereHas('country', function ($query) use ($searchValue) {
        $query->where('name', 'like', '%' . $searchValue . '%');
      })->where('name', 'like', '%' . $searchValue . '%')
      ->count();
    $records = Zone::with('zoneDetail', 'country')->orderBy($columnName, $columnSortOrder)
      ->whereHas('country', function ($query) use ($searchValue) {
        $query->where('name', 'like', '%' . $searchValue . '%');
      })
      ->where('name', 'like', '%' . $searchValue . '%')
      ->skip($start)
      ->take($rowperpage)
      ->get();
    // dd($records);
    $data_arr = array();
    foreach ($records as $record) {
      $zone = [];
      foreach ($record->zoneDetail as $value) {
        $zone[] = [
          'state_id' => $value->state_id,
          'sate_name' => State::where('id', $value->state_id)->first()->name,
        ];
      }
      $data_arr[] = array(
        "id" => $record->id,
        "zone_name" => $record->name,
        "country" => $record->country->name,
        "zone" => $zone,
        // "code" => $record->code->currency,
        "status" => $record->status,
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
  public function addZone(Request $request)
  {
    $data = CountryDetail::get();
    return view('content.apps.app-ecommerce-zone-add', compact('data'));
  }
  public function getStatusData(Request $request)
  {
    $state = State::where('country_id', $request->country_id)->get();
    // dd($state);
    return response()->json(['data' => $state]);
  }
  public function storeZone(Request $request)
  {
    // dd($request->all());
    $rules = [
      'name' => 'required',
      'country_id' => 'required',
      'zone_id' => 'required',
      'status' => 'required',
      // 'price' => 'required',

    ];

    $customMessages = [
      'name.required' => 'Please enter title.',
      'country_id.required' => 'Please select country.',
      'zone_id.required' => 'Please select zone.',
      'status.required' => 'Please select status.',
      // 'price.required' => 'Please enter price.'
    ];
    $this->validate($request, $rules, $customMessages);
    $data = new Zone();
    $data->name = $request->name;
    $data->country_id = $request->country_id;
    $data->status = $request->status ? 1 : 0;
    $data->save();
    if (!empty($request->zone_id)) {
      foreach ($request->zone_id as $zoneId) {
        $data->zoneDetail()->create([
          'zone_id' => $data->id,
          'state_id' => $zoneId
        ]);
      }
    }

    return redirect()->route('app-ecommerce-zone-list')->with('message', 'Zone added successfully');
  }
  public function changeZoneStatus($id, Request $request)
  {
    if ($request->status == 1) {
      $status = 0;
      $data = Zone::where('id', $id)->update(['status' => $status]);
      return response()->json(['message' => 'Status changed successfully', 'data' => $data]);
    } else {
      $status = 1;
      $data = Zone::where('id', $id)->update(['status' => $status]);
      return response()->json(['message' => 'Status changed successfully', 'data' => $data]);
    }
  }
  public function editZone($id)
  {
    $zone = Zone::find($id);
    $data = CountryDetail::get();
    $zoneDetailData = ZoneDetail::where('zone_id', $id)->get();
    $zoneDetail = [];
    foreach ($zoneDetailData as $value) {
      $zoneDetail[] = [
        'state_id' => $value->state_id,
        'zone' => State::where('id', $value->state_id)->first()->name,
      ];
    }
    // dd($zoneDetail);
    return view('content.apps.app-ecommerce-zone-edit', compact('zoneDetail', 'zone', 'data'));
  }
  public function updateZone(Request $request, $id)
  {
    // dd($request->all(), $id);
    $rules = [
      'name' => 'required',
      'country_id' => 'required',
      'zone_id' => 'required',
      'status' => 'required',
      // 'price' => 'required',

    ];

    $customMessages = [
      'name.required' => 'Please enter title.',
      'country_id.required' => 'Please select country.',
      'zone_id.required' => 'Please select zone.',
      'status.required' => 'Please select status.',
      // 'price.required' => 'Please enter price.'
    ];
    $this->validate($request, $rules, $customMessages);
    $data = Zone::find($id);
    $data->name = $request->name;
    $data->country_id = $request->country_id;
    $data->status = $request->status ? 1 : 0;
    $data->save();
    if (!empty($request->zone_id)) {
      $data->zoneDetail()->delete();
      foreach ($request->zone_id as $zoneId) {
        $data->zoneDetail()->create([
          'zone_id' => $data->id,
          'state_id' => $zoneId
        ]);
      }
    }

    return redirect()->route('app-ecommerce-zone-list')->with('message', 'Zone updated successfully');
  }
  public function deleteZone($id)
  {
    Zone::find($id)->delete();
    ZoneDetail::where('zone_id', $id)->delete();
    return response()->json(['message' => 'Country deleted successfully', 'id' => $id]);
  }
}
