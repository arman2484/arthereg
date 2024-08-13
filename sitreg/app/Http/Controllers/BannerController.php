<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Modulebanner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
  public function index()
  {
    $banners = Modulebanner::all();
    return view('content.apps.banner-list', compact('banners'));
  }

  // Datatables
  public function getBannerData(Request $request)
  {
    $draw = $request->get('draw');
    $start = $request->get('start');
    $rowperpage = $request->get('length'); // Rows display per page

    $columnIndex_arr = $request->get('order');
    $columnName_arr = $request->get('columns');
    $order_arr = $request->get('order');
    $search_arr = $request->get('search');

    $columnIndex = $columnIndex_arr[0]['column']; // Column index
    $columnName = $columnName_arr[$columnIndex]['data']; // Column name
    $columnSortOrder = $order_arr[0]['dir']; // asc or desc
    $searchValue = $search_arr['value']; // Search value

    // Total records
    $totalRecords = Modulebanner::count();

    // Total records with filter
    $totalRecordswithFilter = Modulebanner::select('module_banner.*')
      ->join('module', 'module_banner.module_id', '=', 'module.id')
      ->where('module_banner.image', 'like', '%' . $searchValue . '%')
      ->orWhere('module.name', 'like', '%' . $searchValue . '%')
      ->count();

    // Fetch records
    $records = Modulebanner::select('module_banner.*', 'module.name as module_name')
      ->join('module', 'module_banner.module_id', '=', 'module.id')
      ->where('module_banner.image', 'like', '%' . $searchValue . '%')
      ->orWhere('module.name', 'like', '%' . $searchValue . '%')
      ->orderBy($columnName, $columnSortOrder)
      ->skip($start)
      ->take($rowperpage)
      ->get();

    $data_arr = [];
    foreach ($records as $record) {
      $data_arr[] = [
        'id' => $record->id,
        'image' => $record->image,
        'module_id' => $record->module_name,
        'type' => $record->type,
        'action' => '',
      ];
    }

    $response = [
      'draw' => intval($draw),
      'iTotalRecords' => $totalRecords,
      'iTotalDisplayRecords' => $totalRecordswithFilter,
      'aaData' => $data_arr,
    ];

    return response()->json($response);
  }

  // Add slider
  public function addSlider()
  {
    $services = Module::all();
    return view('content.apps.sliders-add', compact('services'));
  }

  public function saveSlider(Request $request)
  {
    $rules = [
      'module_id' => 'required',
      'image' => 'required',
    ];

    $customMessages = [
      'module_id.required' => 'Please select Module.',
      'image.required' => 'Please select image.',
    ];

    $this->validate($request, $rules, $customMessages);

    $slider = new Modulebanner();
    $slider->type = $request->input('type');
    $slider->module_id = $request->input('module_id', auth()->id());

    if ($request->hasFile('image')) {
      $image = $request->file('image');
      $imageName = time() . '.' . $image->getClientOriginalExtension();
      $image->move(public_path('assets/images/module_banner'), $imageName);
      $slider->image = $imageName;
    }

    $slider->save();
    return redirect()
      ->route('banner-list')
      ->with('message', 'Data added successfully');
  }

  // ChangeSliderStatus
  public function ChangeSliderStatus($id, Request $request)
  {
    // Check the current type and toggle it
    $currentType = Modulebanner::where('id', $id)->value('type');
    $status = $currentType == 0 ? 1 : 0;

    // Update the status
    $data = Modulebanner::where('id', $id)->update(['type' => $status]);

    return response()->json(['message' => 'Status changed successfully', 'data' => $data]);
  }

  public function ChangeBannerStatus($id, Request $request)
  {
    $type = $request->input('type');

    // Assuming 'type' 1 means active and 0 means inactive
    $status = $type == 1 ? 1 : 0;

    $data = Modulebanner::where('id', $id)->update(['type' => $status]);

    return response()->json(['message' => 'Status changed successfully', 'data' => $data]);
  }

  public function deleteSlider($id)
  {
    Modulebanner::find($id)->delete();
    return response()->json(['message' => 'Slider deleted successfully', 'id' => $id]);
  }
}
