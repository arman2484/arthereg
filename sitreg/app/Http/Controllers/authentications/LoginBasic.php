<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginBasic extends Controller
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-login-basic', ['pageConfigs' => $pageConfigs]);
  }
  public function login(Request $request)
  {
    // dd($request->all());
    $rules = [
      'email' => 'required',
      'password' => 'required'

    ];

    $customMessages = [
      'email.required' => 'Please enter your email',
      'password.required' => 'Please enter your password',
    ];
    $this->validate($request, $rules, $customMessages);
    if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
      // dd("login success");
      return redirect()->route('app-ecommerce-dashboard');
    } else {
      // dd("login failed");
      return redirect()->back()->with('message', 'Invalid credentials');
    }
  }
}
