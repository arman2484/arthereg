<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class UserProfile extends Controller
{
  public function index()
  {
    // dd("kapil");
    return view('content.pages.pages-profile-user');
  }
  public function update(Request $request, $id)
  {
    $data = Admin::find($request->id);
    $oldImage = $data->image;
    if ($request->hasfile('image')) {
      $imageName = time() . '.' . $request->image->extension();
      $request->image->move(public_path() . '/assets/images/admin_image/', $imageName);
    }
    $data = [
      'first_name' => $request->first_name,
      'last_name' => $request->last_name,
      'email' => $request->email,
      'mobile' => $request->mobile,
      'image' => $imageName ?? $oldImage,
    ];
    $update = Admin::where('id', $id)->update($data);
    return redirect()->back()->with('message', 'Profile updated successfully');
  }
}
