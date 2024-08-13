<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\FuncCall;

class Setting extends Controller
{
  public function settingList()
  {
    $data = Settings::where('id', 1)->first();
    return view('content.apps.app-setting', compact('data'));
  }
  public function settingAdd(Request $request)
  {
    $imageName = '';
    if ($request->hasfile('app_logo')) {
      // dd($request->app_logo);
      $imageName = time() . '.' . $request->app_logo->extension();
      $request->app_logo->move(public_path() . '/assets/images/app_images/', $imageName);
    }
    // dd($imageName);
    $data = Settings::where('id', 1)->first();
    if ($data) {
      $data->app_logo = $imageName ? $imageName : $data->app_logo;
      $data->app_name = $request->app_name;
      $data->app_color = $request->app_color;
      $data->save();
    } else {
      $data = new Settings();
      $data->app_logo = $imageName ? $imageName : $data->app_logo;
      $data->app_name = $request->app_name;
      $data->app_color = $request->app_color;
      $data->save();
    }

    return view('content.apps.app-setting', compact('data'))->with('message', 'Setting Updated Successfully');
  }
  public function paymentStripeKey()
  {
    $data = Settings::where('id', 1)->first();
    return view('content.apps.app-setting-payment-stripe', compact('data'));
  }
  public function settingStripeAdd(Request $request)
  {
    $data = Settings::where('id', $request->id)->first();
    $data->stripe_publish_key = $request->stripe_key;
    $data->stripe_secret = $request->stripe_secret;
    $data->status = $request->status ? 1 : 0;
    $data->save();
    return redirect()->back()->with('message', 'Setting Updated Successfully');
  }
  public function customPage(Request $request)
  {
    $data = Settings::where('id', 1)->first();
    return view('content.apps.app-setting-custom-page', compact('data'));
  }
  public function customPageAdd(Request $request)
  {
    $data = Settings::where('id', 1)->first();
    $data->privacy_policy = $request->privacy_policy;
    $data->term_policy = $request->term_policy;
    $data->save();
    return redirect()->back()->with('message', 'Setting Updated Successfully');
  }
  public function paymentRozerKey()
  {
    $data = Settings::where('id', 2)->first();
    return view('content.apps.app-setting-payment-razer', compact('data'));
  }
  public function paymentFlutterwaveKey()
  {
    $data = Settings::where('id', 3)->first();
    return view('content.apps.app-ecommerce-settings-flutterwave', compact('data'));
  }
  public function paymentCOD()
  {
    $data = Settings::where('id', 4)->first();
    return view('content.apps.app-ecommerce-payment-code', compact('data'));
  }
  public function paymentCheque()
  {
    $data = Settings::where('id', 5)->first();
    return view('content.apps.app-ecommerce-payment-cheque', compact('data'));
  }
}
