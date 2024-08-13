<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserList extends Controller
{
  public function index()
  {
    $user = User::get();
    return view('content.apps.app-user-list');
  }
  public function getUserListData(Request $request)
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

    // Get total number of records
    $totalRecords = User::count();

    // Get total number of records with filter
    $totalRecordswithFilter = User::where(function ($query) use ($searchValue) {
      $query
        ->where('first_name', 'like', '%' . $searchValue . '%')
        ->orWhere('last_name', 'like', '%' . $searchValue . '%')
        ->orWhere('email', 'like', '%' . $searchValue . '%')
        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $searchValue . '%']);
    })->count();

    // Fetch records
    $records = User::orderBy($columnName, $columnSortOrder)
      ->where(function ($query) use ($searchValue) {
        $query
          ->where('first_name', 'like', '%' . $searchValue . '%')
          ->orWhere('last_name', 'like', '%' . $searchValue . '%')
          ->orWhere('email', 'like', '%' . $searchValue . '%')
          ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $searchValue . '%']);
      })
      ->skip($start)
      ->take($rowperpage)
      ->get();

    $data_arr = [];
    foreach ($records as $record) {
      $data_arr[] = [
        'id' => $record->id,
        'first_name' => $record->first_name . ' ' . $record->last_name,
        'email' => $record->email,
        'mobile' => $record->mobile,
        'image' => $record->image,
        'status' => $record->status,
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

  public function edit($id)
  {
    $user = User::find($id);
    dd($user);
    return view('content.apps.app-user-edit');
  }
  public function delete($id)
  {
    $data = User::where('id', $id)->delete();
    return redirect()
      ->back()
      ->with('message', 'User deleted successfully');
  }
  public function logout()
  {
    Auth::guard('admin')->logout();
    return redirect()->route('login');
  }
}
