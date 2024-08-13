<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Module;
use Illuminate\Http\Request;

class EcommerceProductCategory extends Controller
{
  public function index()
  {
    $module = Module::all(); // Retrieve all modules
    return view('content.apps.app-ecommerce-category-list', compact('module'));
  }
  public function getCategoryListData(Request $request)
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
    $totalRecords = Category::select('count(*) as allcount')->count();
    $totalRecordswithFilter = Category::select('count(*) as allcount')
      ->where('category_name', 'like', '%' . $searchValue . '%')
      ->count();
    // Fetch filtered records with join to get module_name
    $records = Category::select('categories.*', 'module.name')
      ->leftJoin('module', 'categories.module_id', '=', 'module.id')
      ->where('categories.category_name', 'like', '%' . $searchValue . '%')
      ->orderBy($columnName, $columnSortOrder)
      ->skip($start)
      ->take($rowperpage)
      ->get();
    $data_arr = [];
    foreach ($records as $record) {
      $data_arr[] = [
        'id' => $record->id,
        'category_name' => $record->category_name,
        'module_id' => $record->module_id,
        'module_name' => $record->name, // Added module_name field
        'status' => $record->status,
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
    $rules = [
      'category_name' => 'required',
      'category_image' => 'required',
      'status' => 'required',
      'module_id' => 'required',
    ];

    $customMessages = [
      'category_name.required' => 'Please enter category name.',
      'status.required' => 'Please select status.',
    ];
    $this->validate($request, $rules, $customMessages);

    if ($request->hasFile('category_image')) {
      $image = $request->file('category_image');
      $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
      $image->move(public_path('assets/images/category_images'), $imageName);
    }


    $data = new Category();
    $data->category_name = $request->category_name;
    $data->module_id = $request->module_id;
    $data->status = $request->status;
    $data->category_icon = '';
    $data->category_image = $imageName;
    $data->save();
    return redirect()->route('app-ecommerce-product-category');
  }

  public function edit($id)
  {
    $data = Category::find($id);
    $module = Module::all();
    return view('content.apps.app-ecommerce-category-edit', compact('data', 'module'));
  }
  public function update($id, Request $request)
  {
     if ($request->hasFile('category_image')) {
      $image = $request->file('category_image');
      $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
      $image->move(public_path('assets/images/category_images'), $imageName);
    }
    $data = Category::find($id);
    $data->category_name = $request->category_name;
    $data->module_id = $request->module_id;
    $data->status = $request->status;
    $data->category_image = $imageName ?? $data->category_image;
    $data->save();
    return redirect()
      ->route('app-ecommerce-product-category')
      ->with('message', 'Category updated successfully');
  }
  public function delete($id)
  {
    $data = Category::find($id);
    if ($data->category_image) {
      $image_path = public_path('assets/images/category_images/' . $data->category_image);
      if (file_exists($image_path)) {
        unlink($image_path);
      }
    }
    $data->delete();
    return redirect()
      ->back()
      ->with('message', 'Category deleted successfully');
  }
}
