<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductLike;
use App\Models\User;
use App\Models\UserAddress;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller

{
  public function register(Request $request)
  {
    if ($request->mobile != "") {
      if (!User::where('mobile', $request->mobile)->first()) {
        // dd("kapil");
        $data['mobile'] = $request->mobile;
        $otp = rand(100000, 999999);
        $message = "Otp is " . $otp;
        try {
          $twilioSID = "ACbee0a250ee80195623b8c8c905977b76";
          $twilioAuthToken = "f6ac4f26580343d2311dcd01fc45c985";
          $twilioPhoneNumber = "+18665176269";
          $client = new Client($twilioSID, $twilioAuthToken);
          if ($client->messages->create($request->mobile, ['from' => $twilioPhoneNumber, 'body' => $message])) {
            $data['email'] = $request->email;
            $data['otp'] = $otp;
            $user = User::create($data);
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['name'] =  $user->name;
            $userDetail = User::where('mobile', $request->mobile)->first();
          }
          return response()->json(['user_id' => $userDetail->id, 'mobile' => $request->mobile,  'Message' => "OTP sent successfully"], 200);
        } catch (Exception $e) {
          return response()->json(['success' => false, 'Message' => "OTP not send" . $e->getMessage()], 400);
        }
      } else {
        $otp = rand(100000, 999999);
        User::where('mobile', $request->mobile)->update(['otp' => $otp]);
        $userDetail = User::select('id', 'mobile', 'otp')->where('mobile', $request->mobile)->first();
        $message = "Otp is " . $otp;
        try {
          $twilioSID = "ACbee0a250ee80195623b8c8c905977b76";
          $twilioAuthToken = "f6ac4f26580343d2311dcd01fc45c985";
          $twilioPhoneNumber = "+18665176269";
          $client = new Client($twilioSID, $twilioAuthToken);
          if ($client->messages->create($request->mobile, ['from' => $twilioPhoneNumber, 'body' => $message])) {
            return response()->json(['user_id' => $userDetail->id, 'mobile' => $request->mobile, 'Message' => "OTP sent successfully"], 200);
          }
        } catch (Exception $e) {
          return response()->json(['success' => false, 'Message' => "OTP not sent" . $e->getMessage()], 400);
        }
      }
    } else {
      if ($request->email != "") {
        // dd($request->all());
        if (!User::where('email', $request->email)->first()) {
          $otp = rand(100000, 999999);
          $data['email'] = $request->email;
          $data['otp'] = $otp;
          $user = User::create($data);
          $success['token'] =  $user->createToken('MyApp')->plainTextToken;
          $success['name'] =  $user->email;
          $email = $request->email;
          $userDetails = User::where('email', $request->email)->first();
          $messageData = ['email' => $userDetails->email, 'otp' => $userDetails->otp];
          try {
            Mail::send('otp', $messageData, function ($message) use ($email) {
              $message->to($email)->subject('Your OTP Code');
            });
            return response()->json(['user_id' => $userDetails->id, 'email' => $request->email, 'message' => 'OTP sent successfully'], 200);
          } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to send OTP', 'error' => $e->getMessage()], 400);
          }
        } else {
          $email = $request->email;
          $otp = rand(100000, 999999);
          User::where('email', $request->email)->update(['otp' => $otp]);
          $userDetails = User::where('email', $request->email)->first();
          // dd($userDetails);
          $messageData = ['email' => $userDetails->email, 'otp' => $userDetails->otp];
          try {
            Mail::send('otp', $messageData, function ($message) use ($email) {
              $message->to($email)->subject('Your OTP Code');
            });
            return response()->json(['user_id' => $userDetails->id, 'email' => $request->email, 'message' => 'OTP sent successfully'], 200);
          } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to send OTP', 'error' => $e->getMessage()], 400);
          }
        }
      }
    }
  }
  public function verifyUser(Request $request)
  {
    if ($request->mobile != '') {
      $otpVerifcation = User::where('mobile', $request->mobile)->first();
      if ($request->otp == $otpVerifcation->otp) {
        return response([
          'user_id' => (string)  $otpVerifcation->id,
          'mobile' => $otpVerifcation->mobile,
          'token' =>  $otpVerifcation->createToken('MyApp')->plainTextToken,
          'message' => 'Otp Varified..!'
        ]);
      } else {
        return response([
          'mobile' => $request->mobile,
          'message' => 'Otp Not Varified..!'
        ]);
      }
    } else if ($request->email != '') {
      $otpVerifcation = User::where('email', $request->email)->first();
      if ($request->otp == $otpVerifcation->otp) {
        return response([
          'user_id' => (string) $otpVerifcation->id,
          'email' => $otpVerifcation->email,
          'token' =>  $otpVerifcation->createToken('MyApp')->plainTextToken,
          'message' => 'Otp Varified..!'
        ]);
      } else {
        return response([
          'user_id' => "",
          'email' => (string) $request->email,
          'token' => "",
          'message' => 'Otp Not Varified..!'
        ]);
      }
    }
  }
  public function profile(Request $request)
  {
    $userEmail = Auth::guard('sanctum')->user()->email;
    $userMobile = Auth::guard('sanctum')->user()->mobile;
    if ($userEmail != '') {
      // $data = User::select('id', 'name', 'email as email / mobile', 'image')->where('id', Auth::guard('sanctum')->user()->id)->first();
      $data = User::where('id', Auth::guard('sanctum')->user()->id)->first();
      // dd($data);
      // if ($data->image) {
      //   $data->image = url('assets/images/users_images/' . $data->image);
      // }
      // else {
      //   $data->image = "";
      // }
      // if ($data->name != null) {
      //   $data->name;
      // } else {
      //   $data->name = "";
      // }
      if (!$data) {
        return response([
          'message' => 'User not found ...!'
        ]);
      } else {
        return response([
          'user' => new UserResource($data),
          'message' => 'User found ...!'
        ]);
      }
    }
    if ($userMobile != '') {
      $data = User::select('id', 'name', 'mobile as email / mobile', 'image')->where('id',  Auth::guard('sanctum')->user()->id)->first();
      if ($data->image) {
        $data->image = url('assets/images/users_images/' . $data->image);
      }

      if (!$data) {
        return response([
          'message' => 'User not found ...!'
        ]);
      } else {
        return response([
          'data' => $data,
          'message' => 'User found ...!'
        ]);
      }
    }
  }
  public function addAddress(Request $request)
  {
    $user_id = Auth::guard('sanctum')->user()->id;
    $validator = Validator::make($request->all(), [
      'first_name' => 'required',
      'last_name' => 'required',
      'mobile' => 'required',
      'pincode' => 'required',
      'address' => 'required',
      'locality' => 'required',
      'city' => 'required',
      'state' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json([
        'message' => 'Validation failed',
        'errors' => $validator->errors(),
      ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    $data = new UserAddress();
    $data->first_name = $request->first_name;
    $data->last_name = $request->last_name;
    $data->mobile = $request->mobile;
    $data->pincode = $request->pincode;
    $data->address = $request->address;
    $data->locality = $request->locality;
    $data->city = $request->city;
    $data->state = $request->state;
    $data->user_id = $user_id;
    $data->type = $request->type;
    $data->last_address_status =  UserAddress::where('user_id', $user_id)->where('last_address_status', '1')->exists() ? 0 : 1;
    if ($request->default_address == 'true') {
      UserAddress::where('user_id', $user_id)->update(['default_address' => 'false']);
    }
    $data->default_address = $request->default_address;
    $data->save();
    return response([
      'message' => 'Address added successfully..!',
      'data' => $data
    ]);
  }

  public function editProfile()
  {
    $user_id = Auth::guard('sanctum')->user()->id;
    $data = User::where('id', $user_id)->first();
    if (!$data) {
      return response([
        'message' => 'User not found ...!'
      ]);
    } else {
      $userResource = new UserResource($data);
      $userResourceData = $userResource->showWithDetails($data);
      return response([
        'user' => $userResourceData,
        'message' => 'User found ...!'
      ]);
    }
  }

  public function updateProfile(Request $request)
  {
    // dd($request->all());
    $user_id = Auth::guard('sanctum')->user()->id;
    $dateOfbirth = date("Y-m-d", strtotime($request->dob));
    $data = [
      'first_name' => $request->first_name,
      'last_name' => $request->last_name,
      'username' => $request->username,
      'dob' => $dateOfbirth,
      'gender' => $request->gender,
    ];
    $data = User::where('id', $user_id)->update($data);
    return response([
      'message' => 'Profile updated successfully ...!'
    ]);
  }
}
