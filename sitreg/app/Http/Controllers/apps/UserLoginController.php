<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserLoginController extends Controller
{
  public function index()
  {
    return view('register');
  }


  public function showOTPForm()
  {
    return view('login');
  }
}
