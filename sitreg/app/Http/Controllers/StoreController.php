<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\StoreImages;
use App\Models\Vendor;
use App\Models\VendorStore;
use Illuminate\Http\Request;

class StoreController extends Controller
{
  public function index()
  {
    $data = VendorStore::with('storeImages')->get();
    return view('content.apps.app-ecommerce-store-list', compact('data'));
  }

  public function storeListData(Request $request)
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
    $totalRecords = VendorStore::select('count(*) as allcount')->count();
    $totalRecordswithFilter = VendorStore::select('count(*) as allcount')
      ->where('store_name', 'like', '%' . $searchValue . '%')
      ->where('vendor_request', 1)
      ->count();

    $records = VendorStore::orderBy($columnName, $columnSortOrder)
      ->where(function ($query) use ($searchValue) {
        $query->where('store_name', 'like', '%' . $searchValue . '%');
      })
      ->where('vendor_request', 1)
      ->skip($start)
      ->take($rowperpage)
      ->get();

    $data_arr = array();
    foreach ($records as $record) {
      // Retrieve vendor's first and last name
      $vendor = Vendor::find($record->vendor_id);
      $vendorUsername = $vendor ? $vendor->first_name . ' ' . $vendor->last_name : '';
      $data_arr[] = array(
        "id" => $record->id,
        "store_name" => $record->store_name,
        "vendor_id" => $vendorUsername,
        "store_description" => $record->store_description,
        "store_address" => $record->store_address,
        "store_logo" => $record->store_logo,
        "vendor_request" => $record->vendor_request,
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

  public function storeRequestList()
  {
    $data = VendorStore::get();
    return view('content.apps.app-ecommerce-store-request-list', compact('data'));
  }

  public function storeRequestListData(Request $request)
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
    $totalRecords = VendorStore::select('count(*) as allcount')->count();
    $totalRecordswithFilter = VendorStore::select('count(*) as allcount')
      ->where('store_name', 'like', '%' . $searchValue . '%')
      ->where('vendor_request', 0)
      ->count();

    $records = VendorStore::orderBy($columnName, $columnSortOrder)
      ->where(function ($query) use ($searchValue) {
        $query->where('store_name', 'like', '%' . $searchValue . '%');
      })
      ->where('vendor_request', 0)
      ->skip($start)
      ->take($rowperpage)
      ->get();

    $data_arr = array();
    foreach ($records as $record) {
      // Retrieve vendor's first and last name
      $vendor = Vendor::find($record->vendor_id);
      $vendorUsername = $vendor ? $vendor->first_name . ' ' . $vendor->last_name : '';
      $data_arr[] = array(
        "id" => $record->id,
        "store_name" => $record->store_name,
        "vendor_id" => $vendorUsername,
        "store_description" => $record->store_description,
        "store_address" => $record->store_address,
        "store_logo" => $record->store_logo,
        "vendor_request" => $record->vendor_request,
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

  public function storeRequestAccepted($id)
  {
    $data = VendorStore::where('id', $id)->update(['vendor_request' => 1]);
    return response()->json(['message' => 'Request accepted successfully', 'id' => $id]);
  }

  public function changeRequestStatus($id, Request $request)
  {
    if ($request->status == 1) {
      $status = 0;
      $data = VendorStore::where('id', $id)->update(['status' => $status]);
      return response()->json(['message' => 'Status changed successfully', 'data' => $data]);
    } else {
      $status = 1;
      $data = VendorStore::where('id', $id)->update(['status' => $status]);
      return response()->json(['message' => 'Status changed successfully', 'data' => $data]);
    }
  }

  public function storeDelete($id, Request $request)
  {

    $datas = StoreImages::where('store_id', $id)->delete();
    $data = VendorStore::find($id)->delete();
    return response()->json(['message' => 'Store deleted successfully', 'id' => $id]);
  }

  public function storeAdd()
  {
    $module = Module::all();
    $vendors = Vendor::all();
    return view('content.apps.app-ecommerce-store-add', compact('module', 'vendors'));
  }

  public function storeSave(Request $request)
  {
    $rules = [
      'store_name' => 'required',
      'store_description' => 'required',
      'store_images' => 'required',
      'store_logo' => 'required',
      'module_id' => 'required',
      'vendor_id' => 'required',
      'min_time' => 'required',
      'max_time' => 'required',
      'time_type' => 'required',
      'address' => 'required',
      'mfo' => 'required',
    ];

    $customMessages = [
      'store_name.required' => 'Please enter store name.',
      'store_description.required' => 'Please enter store description.',
      'store_images.required' => 'Please upload store images.',
      'store_logo.required' => 'Please upload a store logo.',
      'module_id.required' => 'Please select a module.',
      'vendor_id.required' => 'Please select a vendor.',
      'min_time.required' => 'Please specify the minimum time.',
      'max_time.required' => 'Please specify the maximum time.',
      'time_type.required' => 'Please select the time type.',
      'address.required' => 'Please enter the address.',
      'mfo.required' => 'Please enter the days.',
    ];

    $this->validate($request, $rules, $customMessages);

    $data = new VendorStore();
    $data->store_name = $request->input('store_name');
    $data->store_description = $request->input('store_description');
    $data->module_id = $request->input('module_id');
    $data->vendor_id = $request->input('vendor_id');
    $data->min_time = $request->input('min_time');
    $data->max_time = $request->input('max_time');
    $data->mfo = implode(',', $request->input('mfo'));
    $data->open_time = $request->input('open_time');
    $data->close_time = $request->input('close_time');
    $data->time_type = $request->input('time_type');


    $address = $request->input('address');
    $address = str_replace(',,', ',', $address);
    $address = str_replace(', ,', ',', $address);
    $address = str_replace(" ", "", $address);

    $json = file_get_contents('https://maps.google.com/maps/api/geocode/json?address=' . $address . '&key=AIzaSyAMZ4GbRFYSevy7tMaiH5s0JmMBBXc0qBA');
    $json1 = json_decode($json);

    if (isset($json1->results)) {
      $data->lat = ($json1->results[0]->geometry->location->lat);
      $data->lon = ($json1->results[0]->geometry->location->lng);
    }


    if ($request->hasFile('store_logo')) {
      $logo = $request->file('store_logo');
      $logoName = time() . '_logo.' . $logo->getClientOriginalExtension();
      $logo->move(public_path('assets/images/store_logo'), $logoName);
      $data->store_logo = $logoName;
    }

    $data->store_address = $address;

    $data->save();

    if ($request->hasFile('store_images')) {
      foreach ($request->file('store_images') as $file) {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('assets/images/store_images'), $fileName);

        $data->storeImages()->create([
          'store_id' => $data->id,
          'store_images' => $fileName
        ]);
      }
    }


    return redirect()->route('app-ecommerce-store-request')->with('message', 'Store added successfully');
  }


  public function storeEdit($id)
  {
    $data = VendorStore::with('storeImages')->find($id);

    $existingDays = explode(',', $data->mfo);
    $existingOpenTime = $data->open_time;
    $existingCloseTime = $data->close_time;
    $vendors = Vendor::all();
    $module = Module::all();
    $lat = $data->lat;
    $lon = $data->lon;

    return view('content.apps.app-ecommerce-store-edit', compact('vendors', 'data', 'module', 'existingDays', 'existingOpenTime', 'existingCloseTime', 'lat', 'lon'));
  }




  public function storeUpdate($id, Request $request)
  {

    $data = VendorStore::findOrFail($id); // Fetch the existing store by ID
    $data->store_name = $request->input('store_name');
    $data->store_description = $request->input('store_description');
    $data->module_id = $request->input('module_id');
    $data->vendor_id = $request->input('vendor_id');
    $data->min_time = $request->input('min_time');
    $data->max_time = $request->input('max_time');
    $data->mfo = implode(',', $request->input('mfo'));
    $data->open_time = $request->input('open_time');
    $data->close_time = $request->input('close_time');
    $data->time_type = $request->input('time_type');
    $data->store_address = $request->input('address');




    // Handle updating store logo if provided
    if ($request->hasFile('store_logo')) {
      $logo = $request->file('store_logo');
      $logoName = time() . '_logo.' . $logo->getClientOriginalExtension();
      $logo->move(public_path('assets/images/store_logo'), $logoName);
      $data->store_logo = $logoName;
    }

    // Handle geocoding for latitude and longitude
    $address = str_replace(" ", "+", $request->input('address'));
    $json = file_get_contents('https://maps.google.com/maps/api/geocode/json?address=' . $address . '&key=AIzaSyAMZ4GbRFYSevy7tMaiH5s0JmMBBXc0qBA');
    $json1 = json_decode($json);
    if (isset($json1->results[0])) {
      $data->lat = $json1->results[0]->geometry->location->lat;
      $data->lon = $json1->results[0]->geometry->location->lng;
    }

    $data->save();

    if ($request->hasFile('store_images')) {
      foreach ($request->file('store_images') as $file) {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('assets/images/store_images'), $fileName);

        $data->storeImages()->create([
          'store_id' => $data->id,
          'store_images' => $fileName
        ]);
      }
    }
    return redirect()->route('app-ecommerce-store-list')->with('message', 'Store updated successfully');
  }


  public function storeImageDelete($id)
  {
    $data = StoreImages::find($id);
    $image_path = public_path('assets/images/store_images/' . $data->store_images);
    if (file_exists($image_path)) {
      unlink($image_path);
    }
    $data->delete();
    return response()->json(['success' => 'sucess']);
  }




  public function storeShow($id)
  {
    $data = VendorStore::with('storeImages')->find($id);

    $existingDays = explode(',', $data->mfo);
    $existingOpenTime = $data->open_time;
    $existingCloseTime = $data->close_time;
    $vendors = Vendor::all();
    $module = Module::all();
    $lat = $data->lat;
    $lon = $data->lon;

    return view('content.apps.app-ecommerce-store-show', compact('vendors', 'data', 'module', 'existingDays', 'existingOpenTime', 'existingCloseTime', 'lat', 'lon'));
  }
}
