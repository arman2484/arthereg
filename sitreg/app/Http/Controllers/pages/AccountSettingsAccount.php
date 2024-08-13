<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class AccountSettingsAccount extends Controller
{
  public function index()
  {
    $data = Admin::first();
    return view('content.pages.pages-account-settings-account', compact('data'));
  }
}
