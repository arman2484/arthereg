<?php

namespace App\Http\Controllers;

use App\Models\CountryCurrencies;
use App\Models\Currencies;
use Illuminate\Http\Request;

class CountryCurrenciesController extends Controller
{
  public function index()
  {
    $data = CountryCurrencies::get();
    return view('content.apps.app-ecommerce-currecy-country-list', compact('data'));
  }
  public function getCurrencyList(Request $request)
  {
    $draw = $request->get('draw');
    $start = $request->get("start");
    $rowPerPage = $request->get("length");
    $columnIndex_arr = $request->get('order');
    $columnName_arr = $request->get('columns');
    $order_arr = $request->get('order');
    $search_arr = $request->get('search');
    $columnIndex = $columnIndex_arr[0]['column'];
    $columnName = $columnName_arr[$columnIndex]['data'];
    $columnSortOrder = $order_arr[0]['dir'];
    $searchValue = $search_arr['value'];
    $totalRecords = CountryCurrencies::with('country', 'currency', 'code')->count();
    $totalRecordswithFilter = CountryCurrencies::with('country', 'currency', 'code')
      ->whereHas('country', function ($query) use ($searchValue) {
        $query->where('country', 'like', '%' . $searchValue . '%');
      })
      ->orWhereHas('currency', function ($query) use ($searchValue) {
        $query->where('currency', 'like', '%' . $searchValue . '%');
      })
      ->orWhereHas('code', function ($query) use ($searchValue) {
        $query->where('code', 'like', '%' . $searchValue . '%');
      })
      ->count();

    $records = CountryCurrencies::with('country', 'currency', 'code')
      ->whereHas('country', function ($query) use ($searchValue) {
        $query->where('country', 'like', '%' . $searchValue . '%');
      })
      ->orWhereHas('currency', function ($query) use ($searchValue) {
        $query->where('currency', 'like', '%' . $searchValue . '%');
      })
      ->orWhereHas('code', function ($query) use ($searchValue) {
        $query->where('code', 'like', '%' . $searchValue . '%');
      })
      ->orderBy($columnName, $columnSortOrder)
      ->skip($start)
      ->take($rowPerPage)
      ->get();

    $data_arr = array();
    foreach ($records as $record) {
      $data_arr[] = array(
        "id" => $record->id,
        "country" => $record->country->country ?? "",
        "currency" => $record->currency->currency ?? "",
        "code" => $record->code->code ?? "",
        "symbol" => $record->symbol->symbol ?? "",
        "status" => $record->status,
        "action" => '',
      );
    }
    // dd($data_arr);
    $response = array(
      "draw" => intval($draw),
      "iTotalRecords" => $totalRecords,
      "iTotalDisplayRecords" => $totalRecordswithFilter,
      "aaData" => $data_arr,
    );

    echo json_encode($response);
    exit;
  }
  public function addCurrency()
  {
    $data = Currencies::get();
    return view('content.apps.app-add-currencies', compact('data'));
  }
  public function storeCurrency(Request $request)
  {
    // dd($request->all());
    $rules = [
      'country_id' => 'required',
      'currency_id' => 'required',
      'code_id' => 'required',
      'status' => 'required',

    ];

    $customMessages = [
      'country_id.required' => 'Please select country.',
      'currency_id.required' => 'Please select currency.',
      'code_id.required' => 'Please select code.',
      'status.required' => 'Please select status.',
    ];
    $this->validate($request, $rules, $customMessages);
    $data = new CountryCurrencies();
    $data->country_id = $request->country_id;
    $data->currency_id = $request->currency_id;
    $data->code_id = $request->code_id;
    $data->symbol_id = $request->symbol_id;
    $data->status = $request->status ? 1 : 0;
    $data->save();
    return redirect()->route('app-currencies-list')->with('message', 'Currency added successfully');
  }
  public function getCurrency(Request $request)
  {
    $currency = Currencies::find($request->country_id);
    return response()->json($currency);
  }
  public function changeCurrencyStatus($id, Request $request)
  {
    $changeStatus = 0;
    $statusCount = CountryCurrencies::where('status', 1)->count();

    if ($request->status == 1) {
      $status = 0;
      $statusCount = CountryCurrencies::where('status', 1)->count();
      if ($statusCount > 1) {
        $data = CountryCurrencies::where('id', $id)->update(['status' => $status]);
        $currencyData = CountryCurrencies::where('id', $id)->first();
        return response()->json(['message' => 'Status changed successfully', 'data' => $data, 'currencyData' => $currencyData]);
      } else {
        $currencyData = CountryCurrencies::where('id', $id)->first();
        return response()->json(['status' => $changeStatus, 'currencyData' => $currencyData]);
      }
    } else {
      $status = 1;
      $data = CountryCurrencies::where('id', $id)->update(['status' => $status]);
      $currencyData = CountryCurrencies::where('id', $id)->first();
      return response()->json(['message' => 'Status changed successfully', 'data' => $data, 'currencyData' => $currencyData]);
    }
  }
  public function deleteCurrency($id)
  {
    $data = CountryCurrencies::where('id', $id)->delete();
    return response()->json(['message' => 'Currency deleted successfully']);
  }
  public function editCurrency($id)
  {
    $data = Currencies::get();
    $currency = CountryCurrencies::with('country', 'currency', 'code')->find($id);
    return view('content.apps.app-edit-currencies', compact('data', 'currency'));
  }
  public function updateCurrency($id, Request $request)
  {
    // dd($request->all(), $id);
    $rules = [
      'country_id' => 'required',
      'currency_id' => 'required',
      'code_id' => 'required',
      'status' => 'required',

    ];

    $customMessages = [
      'country_id.required' => 'Please select country.',
      'currency_id.required' => 'Please select currency.',
      'code_id.required' => 'Please select code.',
      'status.required' => 'Please select status.',
    ];
    $this->validate($request, $rules, $customMessages);
    $data = CountryCurrencies::find($id);
    $data->country_id = $request->country_id;
    $data->currency_id = $request->currency_id;
    $data->code_id = $request->code_id;
    $data->symbol_id = $request->symbol_id;
    $data->status = $request->status ? 1 : 0;
    $data->save();
    return redirect()->route('app-currencies-list')->with('message', 'Currency updated successfully');
  }
}
