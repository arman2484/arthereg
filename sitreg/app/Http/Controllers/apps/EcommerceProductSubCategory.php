<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Module;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class EcommerceProductSubCategory extends Controller
{
  public function index()
  {
    $category = Category::get();
    $module = Module::get();
    return view('content.apps.app-ecommerce-subcategory-list', compact('category', 'module'));
  }
  public function getSubCategoryListData(Request $request)
  {
    $draw = $request->get('draw');
    $start = $request->get('start');
    $rowperpage = $request->get('length');

    $columnIndex_arr = $request->get('order');
    $columnName_arr = $request->get('columns');
    $order_arr = $request->get('order');
    $search_arr = $request->get('search');
    $columnIndex = $columnIndex_arr[0]['column'];
    $columnName = $columnName_arr[$columnIndex]['data'];
    $columnSortOrder = $order_arr[0]['dir'];
    $searchValue = $search_arr['value'];
    $totalRecords = SubCategory::select('count(*) as allcount')->count();
    $totalRecordswithFilter = SubCategory::select('count(*) as allcount')
      ->where('sub_category_name', 'like', '%' . $searchValue . '%')
      ->count();

    $records = SubCategory::select(
      'sub_categories.id',
      'sub_categories.category_id',
      'sub_categories.sub_category_name',
      'sub_categories.sub_category_status',
      'categories.category_name',
      'categories.category_image'
    )
      ->leftjoin('categories', 'category_id', '=', 'categories.id')
      ->orderBy($columnName, $columnSortOrder)
      ->where('sub_category_name', 'like', '%' . $searchValue . '%')
      ->skip($start)
      ->take($rowperpage)
      ->get();
    // dd($records);
    $data_arr = [];
    foreach ($records as $record) {
      $data_arr[] = [
        // "id" => '',
        'id' => $record->id,
        'sub_category_name' => $record->sub_category_name,
        'category_image' => $record->category_image,
        'category_name' => ucfirst($record->category_name),
        'sub_category_status' => $record->sub_category_status,
        'category_image' => $record->category_image,
        'action' => '',
      ];
    }
    $response = [
      'draw' => intval($draw),
      'iTotalRecords' => $totalRecords,
      'iTotalDisplayRecords' => $totalRecordswithFilter,
      'aaData' => $data_arr,
    ];

    echo json_encode($response);
    exit();
  }
  public function add(Request $request)
  {
    // dd($request->all());
    $rules = [
      'sub_category_name' => 'required',
      'category' => 'required',
      'sub_category_status' => 'required',
      'module_id' => 'required',
    ];

    $customMessages = [
      'sub_category_name.required' => 'Please enter sub category name.',
      'category.required' => 'Please select category.',
      'sub_category_status.required' => 'Please select status.',
      'module_id.required' => 'Please select module.',
    ];
    $this->validate($request, $rules, $customMessages);
    $data = new SubCategory();
    $data->sub_category_name = $request->sub_category_name;
    $data->sub_category_status = $request->sub_category_status;
    $data->category_id = $request->category;
    $data->module_id = $request->module_id;
    $data->save();
    // dd($data);
    return redirect()->route('app-ecommerce-product-subcategory');
  }
  public function edit($id)
  {
    $category = Category::get();
    $data = SubCategory::find($id);
    $module = Module::get();
    return view('content.apps.app-ecommerce-subcategory-edit', compact('data', 'category', 'module'));
  }
  public function update($id, Request $request)
  {
    // dd($request->all());
    $rules = [
      'sub_category_name' => 'required',
      'category' => 'required',
      'sub_category_status' => 'required',
      'module_id' => 'required',
    ];

    $customMessages = [
      'sub_category_name.required' => 'Please enter sub category name.',
      'category.required' => 'Please select category.',
      'sub_category_status.required' => 'Please select status.',
      'module_id.required' => 'Please select module.',
    ];
    $this->validate($request, $rules, $customMessages);
    $data = SubCategory::find($id);
    $data->sub_category_name = $request->sub_category_name;
    $data->category_id = $request->category;
    $data->sub_category_status = $request->sub_category_status;
    $data->module_id = $request->module_id;
    $data->save();
    return redirect()->route('app-ecommerce-product-subcategory');
  }

  public function getSubcategory(Request $request)
  {
    // dd($request->all());
    $data = SubCategory::where('category_id', $request->category_id)->get();
    return response()->json(['data' => $data]);
  }
  public function delete($id)
  {
    $data = SubCategory::find($id)->delete();
    return redirect()
      ->back()
      ->with('message', 'Sub Category deleted successfully');
  }
}
