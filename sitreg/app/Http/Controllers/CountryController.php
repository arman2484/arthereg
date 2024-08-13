<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Currencies;
use Illuminate\Http\Request;
use App\Models\CountryCurrencies;
use App\Models\CountryDetail;
use PHPUnit\Framework\Constraint\Count;

class CountryController extends Controller
{
  public function index()
  {
    return view('content.apps.app-ecommerce-country-list');
  }
  public function countryList(Request $request)
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
    $totalRecords = Country::select('count(*) as allcount')->count();
    $totalRecordswithFilter = Country::select('count(*) as allcount')->with('country', 'code')
      ->whereHas('country', function ($query) use ($searchValue) {
        $query->where('name', 'like', '%' . $searchValue . '%');
      })
      ->orWhereHas('code', function ($query) use ($searchValue) {
        $query->where('currency', 'like', '%' . $searchValue . '%');
      })
      ->count();
    $records = Country::with('country', 'code')->orderBy($columnName, $columnSortOrder)
      ->whereHas('country', function ($query) use ($searchValue) {
        $query->where('name', 'like', '%' . $searchValue . '%');
      })
      ->orWhereHas('code', function ($query) use ($searchValue) {
        $query->where('currency', 'like', '%' . $searchValue . '%');
      })
      ->skip($start)
      ->take($rowperpage)
      ->get();
    // dd($records);
    $data_arr = array();


    foreach ($records as $record) {
      $data_arr[] = array(
        "id" => $record->id,
        "name" => $record->country->name,
        "code" => $record->code->currency,
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
  public function addCountry(Request $request)
  {
    $data = CountryDetail::get();
    // dd($data->toArray());
    return view('content.apps.app-ecommerce-country-add', compact('data'));
  }
  public function storeCountry(Request $request)
  {
    // dd($request->all());
    $rules = [
      'country_id' => 'required',
      'code_id' => 'required',
      'status' => 'required',
      // 'price' => 'required',

    ];

    $customMessages = [
      'country_id.required' => 'Please select country.',
      'code_id.required' => 'Please select code.',
      'status.required' => 'Please select status.',
    ];
    $this->validate($request, $rules, $customMessages);
    $data = new Country();
    $data->country_id = $request->country_id;
    $data->code_id = $request->code_id;
    $data->status = $request->status ? 1 : 0;
    $data->save();
    return redirect()->route('app-ecommerce-country-list')->with('message', 'Country added successfully');
  }
  public function getCurrencyData(Request $request)
  {
    $country = CountryDetail::find($request->country_id);
    return response()->json($country);
  }
  public function changeCountryStatus($id, Request $request)
  {
    if ($request->status == 1) {
      $status = 0;
      $data = Country::where('id', $id)->update(['status' => $status]);
      return response()->json(['message' => 'Status changed successfully', 'data' => $data]);
    } else {
      $status = 1;
      $data = Country::where('id', $id)->update(['status' => $status]);
      return response()->json(['message' => 'Status changed successfully', 'data' => $data]);
    }
  }
  public function editCountry($id)
  {
    $data = Country::find($id);
    $country = CountryDetail::get();
    // dd($country);
    return view('content.apps.app-ecommerce-country-edit', compact('data', 'country'));
  }
  public function updateCountry(Request $request, $id)
  {
    $rules = [
      'country_id' => 'required',
      'code_id' => 'required',
      'status' => 'required',
      // 'price' => 'required',

    ];

    $customMessages = [
      'country_id.required' => 'Please select country.',
      'code_id.required' => 'Please select code.',
      'status.required' => 'Please select status.',
    ];
    $this->validate($request, $rules, $customMessages);
    $data = Country::find($id);
    $data->country_id = $request->country_id;
    $data->code_id = $request->code_id;
    $data->status = $request->status ? 1 : 0;
    $data->save();
    return redirect()->route('app-ecommerce-country-list')->with('message', 'Country updated successfully');
  }
  public function deleteCountry($id)
  {
    $data = Country::find($id)->delete();
    return response()->json(['message' => 'Country deleted successfully', 'id' => $id]);
  }
}
