<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountSettingsSecurity extends Controller
{
  public function index()
  {
    return view('content.pages.pages-account-settings-security');
  }
  public function changePassword(Request $request)
  {
    // dd($request->all());
    // $request->validate([
    //   'current_password' => 'required',
    //   'new_password' => [
    //     'required', 'string', 'min:8', 'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/',
    //   ],
    //   'password_confirmation' => 'required|same:new_password',
    // ]);
    $rules = [
      'current_password' => 'required',
      'new_password' => [
        'required', 'string', 'min:6', 'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/',
      ],
      'password_confirmation' => 'required|same:new_password',
    ];

    $customMessages = [
      'current_password.required' => 'Please enter current password.',
      'new_password.required' => 'Please enter new password.',
      'password_confirmation.required' => 'Please enter confirm password.',
      'password_confirmation.same' => 'The new password and confirm password does not match.',
    ];

    $this->validate($request, $rules, $customMessages);


    $user = Auth::guard('admin')->user();
    // dd($user->password);
    if (!Hash::check($request->current_password, $user->password)) {
      return redirect()->back()->with('error', 'The current password is incorrect.');
    }
    if ($request->new_password != $request->password_confirmation) {
      return redirect()->back()->with('error', 'The new password and confirm password does not match.');
    }

    $user->password = bcrypt($request->new_password);
    $user->save();
    return redirect()->back()->with('message', 'Password changed successfully!');
  }
}
