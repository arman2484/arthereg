<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminCouponController extends Controller
{
  public function createCoupon(Request $request)
  {
    $user = Auth::guard('sanctum')->user()->id;
    // dd($user);
    if ($user) {
      $validator = Validator::make($request->all(), [
        'coupon_code' => 'required|unique:coupons',
        'discount_amount' => 'required|numeric',
        'status' => 'required',
        'description' => 'nullable',
      ]);
      if ($validator->fails()) {
        return response()->json([
          'message' => 'Validation failed',
          'errors' => $validator->errors(),
        ]);
      } else {

        $coupon = new Coupon();
        $coupon->coupon_code = $request->input('coupon_code');
        $coupon->discount_amount = $request->input('discount_amount');
        $coupon->status = $request->input('status') ? '1' : '0';
        $coupon->description = $request->input('description');
        $coupon->save();
        return response()->json(
          ['message' => 'Coupon created successfully'],
          201
        );
      }
    }

    // dd($request->all());
  }
  public function couponList(Request $request)
  {
    $user = Auth::guard('sanctum')->user()->id;
    if ($user) {
      $couponList = Coupon::get();
      return response()->json([
        'message' => 'Success...!',
        'data' => $couponList
      ], 201);
    }
  }
  public function appliedCoupon(Request $request)
  {
    $user_id = Auth::guard('sanctum')->user()->id;
    if ($request->coupon_code != '') {
      $coupon = Coupon::where('coupon_code', $request->coupon_code)->first();
      $couponId = $coupon->id;
      if (empty($coupon)) {
        return response()->json([
          'message' => 'Invalid Coupon ...!',
        ], 401);
      }
      $coupon = CartItem::where('user_id', $user_id)->first();
      if (Order::where('user_id', $user_id)->where('coupon_id', $couponId)->exists()) {
        return response()->json([
          'message' => 'Coupon Used ...!',
        ], 401);
      } else if ($coupon->coupon_id != $couponId) {
        CartItem::where('user_id', $user_id)->update(['coupon_id' => $couponId]);
        $data = CartItem::where('coupon_id', $couponId)->first();
        Coupon::where('id', $data->coupon_id)->update(['status' => '1']);
        return response()->json([
          'message' => 'Coupon Applied Successfully ...!',
        ], 201);
      } else {
        return response()->json([
          'message' => 'Coupon Already Applied ...!',
        ], 401);
      }
    }
  }
  public function removeCoupon(Request $request)
  {
    $user_id = Auth::guard('sanctum')->user()->id;
    if ($user_id) {
      CartItem::where('user_id', $user_id)->update(['coupon_id' => null]);
      Coupon::where('coupon_code', $request->coupon_code)->update(['status' => '1']);
      return response()->json([
        'message' => 'Coupon removed Successfully ...!',
      ], 201);
    }
  }

  
}