<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\ProductLike;
use App\Models\ProductReview;
use App\Models\ReviewRating;
use App\Models\SubCategory;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\ContactUs;
use App\Models\Settings;
use App\Models\Wallet;
use App\Models\Admin;
use App\Models\PointsTransaction;
use App\Models\CreateTicket;
use App\Models\UserFeedback;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;
use App\Models\Notifications;
use App\Models\StoreImages;
use App\Models\StoreLike;
use App\Models\StoreReview;
use App\Models\TicketChat;
use App\Models\UserReferal;
use App\Models\Variant;
use App\Models\VendorStore;
use Illuminate\Http\JsonResponse;

class UserAuthController extends Controller
{
  // Register
  public function register(Request $request)
  {

    $ipAddress = $request->ip(); // Capture the IP address


    // Check if the IP address already exists in the database
    $existingUser = User::where('ip_address', $ipAddress)->first();

    // dd($existingUser);

    if ($existingUser) {

      if ($request->mobile != '') {
        // if (!User::where('mobile', $request->mobile)->first()) {
        //   // dd("kapil");
        //   $data['mobile'] = $request->mobile;
        //   $otp = rand(100000, 999999);
        //   $message = 'Otp is ' . $otp;
        //   try {
        //     $twilioSID = 'AC7ac341aa4a922ef155297b3234750b76';
        //     $twilioAuthToken = 'd134824e79896ef028b4f1afb4ff4c57';
        //     $twilioPhoneNumber = '+12564140894';

        //     $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        //     $refer_code = '';
        //     $max = strlen($characters) - 1;

        //     for ($i = 0; $i < 7; $i++) {
        //       $refer_code .= $characters[mt_rand(0, $max)];
        //     }



        //     $client = new Client($twilioSID, $twilioAuthToken);
        //     if ($client->messages->create($request->mobile, ['from' => $twilioPhoneNumber, 'body' => $message])) {
        //       $data['email'] = $request->email;
        //       $data['otp'] = $otp;
        //       $data['refer_code'] = $refer_code;
        //       $data['user_refer_code'] = $request->input('user_refer_code');
        //       $user = User::create($data);

        //       if ($request->user_refer_code != '') {
        //         $use_refer_user = User::where('refer_code', $request->user_refer_code)->first();

        //         //   $users = User::where('refer_code', $request->use_refer_code)->first();


        //         if ($use_refer_user) {

        //           $userReferal = [
        //             'user_id' => $user->id,
        //             'refer_code' => $refer_code,
        //             'user_refer_code' => $request->user_refer_code,
        //             'use_refer_code_by' => $use_refer_user->id,
        //           ];
        //           UserReferal::create($userReferal);


        //           $total_amount = User::where('id', $user->id)->first();

        //           User::where('id', $user->id)->update([
        //             'wallet_balance' => 20 + $total_amount->wallet_balance
        //           ]);

        //           $total_amount_done = User::where('id', $use_refer_user->id)->first();

        //           User::where('id', $use_refer_user->id)->update([
        //             'wallet_balance' => 20 + $total_amount_done->wallet_balance
        //           ]);
        //         }
        //       }


        //       $success['token'] = $user->createToken('MyApp')->plainTextToken;
        //       $success['name'] = $user->name;
        //       $userDetail = User::where('mobile', $request->mobile)->first();
        //     }
        //     return response()->json(
        //       ['user_id' => $userDetail->id, 'mobile' => $request->mobile, 'Message' => 'OTP sent successfully'],
        //       200
        //     );
        //   } catch (Exception $e) {
        //     return response()->json(['success' => false, 'Message' => 'OTP not send' . $e->getMessage()], 400);
        //   }
        // } else {

        $mobile = $request->mobile;
        $otp = rand(100000, 999999);
        User::where('ip_address', $ipAddress)->update(['mobile' => $mobile, 'otp' => $otp]);
        $userDetail = User::select('id', 'mobile', 'otp')
          ->where('mobile', $request->mobile)
          ->first();
        $message = 'Otp is ' . $otp;
        try {
          $twilioSID = 'AC7ac341aa4a922ef155297b3234750b76';
          $twilioAuthToken = 'd134824e79896ef028b4f1afb4ff4c57';
          $twilioPhoneNumber = '+12564140894';
          $client = new Client($twilioSID, $twilioAuthToken);
          if ($client->messages->create($request->mobile, ['from' => $twilioPhoneNumber, 'body' => $message])) {
            return response()->json(
              ['user_id' => $userDetail->id, 'mobile' => $request->mobile, 'Message' => 'OTP sent successfully'],
              200
            );
          }
        } catch (Exception $e) {
          return response()->json(['success' => false, 'Message' => 'OTP not sent' . $e->getMessage()], 400);
        }
        // }
      }

      if ($request->email != '') {
        // dd($request->all());
        // if (!User::where('email', $request->email)->first()) {
        //   $otp = rand(100000, 999999);
        //   $data['email'] = $request->email;
        //   $data['otp'] = $otp;

        //   $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        //   $refer_code = '';
        //   $max = strlen($characters) - 1;

        //   for ($i = 0; $i < 7; $i++) {
        //     $refer_code .= $characters[mt_rand(0, $max)];
        //   }

        //   $data['refer_code'] = $refer_code;

        //   $data['user_refer_code'] = $request->input('user_refer_code');

        //   $user = User::create($data);

        //   if ($request->user_refer_code != '') {
        //     $use_refer_user = User::where('refer_code', $request->user_refer_code)->first();

        //     //   $users = User::where('refer_code', $request->use_refer_code)->first();


        //     if ($use_refer_user) {

        //       $userReferal = [
        //         'user_id' => $user->id,
        //         'refer_code' => $refer_code,
        //         'user_refer_code' => $request->user_refer_code,
        //         'use_refer_code_by' => $use_refer_user->id,
        //       ];
        //       UserReferal::create($userReferal);


        //       $total_amount = User::where('id', $user->id)->first();

        //       User::where('id', $user->id)->update([
        //         'wallet_balance' => 20 + $total_amount->wallet_balance
        //       ]);

        //       $total_amount_done = User::where('id', $use_refer_user->id)->first();

        //       User::where('id', $use_refer_user->id)->update([
        //         'wallet_balance' => 20 + $total_amount_done->wallet_balance
        //       ]);
        //     }
        //   }

        //   $success['token'] = $user->createToken('MyApp')->plainTextToken;
        //   $success['name'] = $user->email;
        //   $email = $request->email;
        //   $userDetails = User::where('email', $request->email)->first();
        //   // $messageData = ['email' => $userDetails->email, 'otp' => $userDetails->otp];
        //   // try {
        //   //   Mail::send('otp', $messageData, function ($message) use ($email) {
        //   //     $message->to($email)->subject('Your OTP Code');
        //   //   });
        //   return response()->json(
        //     ['user_id' => $userDetails->id, 'email' => $request->email, 'message' => 'OTP sent successfully'],
        //     200
        //   );
        //   // } catch (\Exception $e) {
        //   //   return response()->json(['message' => 'Failed to send OTP', 'error' => $e->getMessage()], 400);
        //   // }
        // } else {
        $email = $request->email;
        $otp = rand(100000, 999999);
        // User::where('email', $request->email)->update(['otp' => $otp]);

        User::where('ip_address', $ipAddress)->update(['email' => $email, 'otp' => $otp]);
        $userDetails = User::where('email', $request->email)->first();
        // dd($userDetails);
        // $messageData = ['email' => $userDetails->email, 'otp' => $userDetails->otp];
        // try {
        //   Mail::send('otp', $messageData, function ($message) use ($email) {
        //     $message->to($email)->subject('Your OTP Code');
        //   });
        return response()->json(
          ['user_id' => $userDetails->id, 'email' => $request->email, 'message' => 'OTP sent successfully'],
          200
        );
        // } catch (\Exception $e) {
        //   return response()->json(['message' => 'Failed to send OTP', 'error' => $e->getMessage()], 400);
        // }
        // }
      }
    }

    if ($request->mobile != '') {
      if (!User::where('mobile', $request->mobile)->first()) {
        // dd("kapil");
        $data['mobile'] = $request->mobile;
        $otp = rand(100000, 999999);
        $message = 'Otp is ' . $otp;
        try {
          $twilioSID = 'AC7ac341aa4a922ef155297b3234750b76';
          $twilioAuthToken = 'd134824e79896ef028b4f1afb4ff4c57';
          $twilioPhoneNumber = '+12564140894';

          $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
          $refer_code = '';
          $max = strlen($characters) - 1;

          for ($i = 0; $i < 7; $i++) {
            $refer_code .= $characters[mt_rand(0, $max)];
          }



          $client = new Client($twilioSID, $twilioAuthToken);
          if ($client->messages->create($request->mobile, ['from' => $twilioPhoneNumber, 'body' => $message])) {
            $data['email'] = $request->email;
            $data['otp'] = $otp;
            $data['refer_code'] = $refer_code;
            $data['user_refer_code'] = $request->input('user_refer_code');
            $user = User::create($data);

            if ($request->user_refer_code != '') {
              $use_refer_user = User::where('refer_code', $request->user_refer_code)->first();

              //   $users = User::where('refer_code', $request->use_refer_code)->first();


              if ($use_refer_user) {

                $userReferal = [
                  'user_id' => $user->id,
                  'refer_code' => $refer_code,
                  'user_refer_code' => $request->user_refer_code,
                  'use_refer_code_by' => $use_refer_user->id,
                ];
                UserReferal::create($userReferal);


                $total_amount = User::where('id', $user->id)->first();

                User::where('id', $user->id)->update([
                  'wallet_balance' => 20 + $total_amount->wallet_balance
                ]);

                $total_amount_done = User::where('id', $use_refer_user->id)->first();

                User::where('id', $use_refer_user->id)->update([
                  'wallet_balance' => 20 + $total_amount_done->wallet_balance
                ]);
              }
            }


            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;
            $userDetail = User::where('mobile', $request->mobile)->first();
          }
          return response()->json(
            ['user_id' => $userDetail->id, 'mobile' => $request->mobile, 'Message' => 'OTP sent successfully'],
            200
          );
        } catch (Exception $e) {
          return response()->json(['success' => false, 'Message' => 'OTP not send' . $e->getMessage()], 400);
        }
      } else {
        $otp = rand(100000, 999999);
        User::where('mobile', $request->mobile)->update(['otp' => $otp]);
        $userDetail = User::select('id', 'mobile', 'otp')
          ->where('mobile', $request->mobile)
          ->first();
        $message = 'Otp is ' . $otp;
        try {
          $twilioSID = 'AC7ac341aa4a922ef155297b3234750b76';
          $twilioAuthToken = 'd134824e79896ef028b4f1afb4ff4c57';
          $twilioPhoneNumber = '+12564140894';
          $client = new Client($twilioSID, $twilioAuthToken);
          if ($client->messages->create($request->mobile, ['from' => $twilioPhoneNumber, 'body' => $message])) {
            return response()->json(
              ['user_id' => $userDetail->id, 'mobile' => $request->mobile, 'Message' => 'OTP sent successfully'],
              200
            );
          }
        } catch (Exception $e) {
          return response()->json(['success' => false, 'Message' => 'OTP not sent' . $e->getMessage()], 400);
        }
      }
    } else {
      if ($request->email != '') {
        // dd($request->all());
        if (!User::where('email', $request->email)->first()) {
          $otp = rand(100000, 999999);
          $data['email'] = $request->email;
          $data['otp'] = $otp;

          $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
          $refer_code = '';
          $max = strlen($characters) - 1;

          for ($i = 0; $i < 7; $i++) {
            $refer_code .= $characters[mt_rand(0, $max)];
          }

          $data['refer_code'] = $refer_code;

          $data['user_refer_code'] = $request->input('user_refer_code');

          $user = User::create($data);

          if ($request->user_refer_code != '') {
            $use_refer_user = User::where('refer_code', $request->user_refer_code)->first();

            //   $users = User::where('refer_code', $request->use_refer_code)->first();


            if ($use_refer_user) {

              $userReferal = [
                'user_id' => $user->id,
                'refer_code' => $refer_code,
                'user_refer_code' => $request->user_refer_code,
                'use_refer_code_by' => $use_refer_user->id,
              ];
              UserReferal::create($userReferal);


              $total_amount = User::where('id', $user->id)->first();

              User::where('id', $user->id)->update([
                'wallet_balance' => 20 + $total_amount->wallet_balance
              ]);

              $total_amount_done = User::where('id', $use_refer_user->id)->first();

              User::where('id', $use_refer_user->id)->update([
                'wallet_balance' => 20 + $total_amount_done->wallet_balance
              ]);
            }
          }

          $success['token'] = $user->createToken('MyApp')->plainTextToken;
          $success['name'] = $user->email;
          $email = $request->email;
          $userDetails = User::where('email', $request->email)->first();
          // $messageData = ['email' => $userDetails->email, 'otp' => $userDetails->otp];
          // try {
          //   Mail::send('otp', $messageData, function ($message) use ($email) {
          //     $message->to($email)->subject('Your OTP Code');
          //   });
          return response()->json(
            ['user_id' => $userDetails->id, 'email' => $request->email, 'message' => 'OTP sent successfully'],
            200
          );
          // } catch (\Exception $e) {
          //   return response()->json(['message' => 'Failed to send OTP', 'error' => $e->getMessage()], 400);
          // }
        } else {
          $email = $request->email;
          $otp = rand(100000, 999999);
          User::where('email', $request->email)->update(['otp' => $otp]);
          $userDetails = User::where('email', $request->email)->first();
          // dd($userDetails);
          // $messageData = ['email' => $userDetails->email, 'otp' => $userDetails->otp];
          // try {
          //   Mail::send('otp', $messageData, function ($message) use ($email) {
          //     $message->to($email)->subject('Your OTP Code');
          //   });
          return response()->json(
            ['user_id' => $userDetails->id, 'email' => $request->email, 'message' => 'OTP sent successfully'],
            200
          );
          // } catch (\Exception $e) {
          //   return response()->json(['message' => 'Failed to send OTP', 'error' => $e->getMessage()], 400);
          // }
        }
      }
    }
  }


  public function GuestUser(Request $request)
  {
    $ipAddress = $request->ip(); // Capture the IP address

    // Check if the IP address already exists in the database
    $existingUser = User::where('ip_address', $ipAddress)->first();

    if ($existingUser) {
      return response()->json([
        'status' => 'fail',
        'message' => 'IP address already exists.'
      ]);
    }

    // Create a new user with the captured IP address
    $user = User::create([
      'ip_address' => $ipAddress,
      'guest_user' => 1, // Set guest_user to 1 by default
    ]);

    // Return a response with the user details
    return response()->json([
      'status' => 'success',
      'message' => 'User created successfully.',
      'user' => $user
    ]);
  }

  //  Verify User
  public function verifyUser(Request $request)
  {
    if ($request->mobile != '') {
      User::where('mobile', $request->mobile)->update(['device_token' => $request->device_token]);
      // $data['device_token'] = $request->device_token;
      $otpVerifcation = User::where('mobile', $request->mobile)->first();
      if ($request->otp == $otpVerifcation->otp) {
        return response([
          'user_id' => (string) $otpVerifcation->id,
          'first_name' => $otpVerifcation->first_name ?? "",
          'mobile' => $otpVerifcation->mobile,
          'token' => $otpVerifcation->createToken('MyApp')->plainTextToken,
          'message' => 'Otp Verified..!',
        ]);
      } else {
        return response([
          'mobile' => $request->mobile,
          'message' => 'Otp Not Verified..!',
        ]);
      }
    } elseif ($request->email != '') {
      User::where('email', $request->email)->update(['device_token' => $request->device_token]);
      $otpVerifcation = User::where('email', $request->email)->first();
      if ($request->otp == $otpVerifcation->otp) {
        return response([
          'user_id' => (string) $otpVerifcation->id,
          'first_name' => $otpVerifcation->first_name ?? "",
          'email' => $otpVerifcation->email,
          'token' => $otpVerifcation->createToken('MyApp')->plainTextToken,
          'message' => 'Otp Verified..!',
        ]);
      } else {
        return response([
          'user_id' => '',
          'email' => (string) $request->email,
          'token' => '',
          'message' => 'Otp Not Verified..!',
        ]);
      }
    }
  }

  // User Profile
  public function UserProfile(Request $request)
  {
    if ($request->file('image')) {
      $fileName = time() . '.' . $request->image->getClientOriginalName();
      $request->image->move(public_path() . '/assets/images/users_images/', $fileName);

      User::where('id', Auth::guard('sanctum')->user()->id)->update(['image' => $fileName]);
      return response([
        'message' => 'Image updated successfully ...!',
      ]);
    }
    $userEmail = Auth::guard('sanctum')->user()->email;
    $userMobile = Auth::guard('sanctum')->user()->mobile;
    if ($userEmail != '') {
      $data = User::where('id', Auth::guard('sanctum')->user()->id)->first();
      if (!$data) {
        return response([
          'message' => 'User not found ...!',
        ]);
      } else {
        return response([
          'user' => new UserResource($data),
          'message' => 'User found ...!',
        ]);
      }
    }
    if ($userMobile != '') {
      $data = User::where('id', Auth::guard('sanctum')->user()->id)->first();
      if (!$data) {
        return response([
          'message' => 'User not found ...!',
        ]);
      } else {
        return response([
          'user' => new UserResource($data),
          'message' => 'User found ...!',
        ]);
      }
    }
  }

  // Get Edit Profile
  public function editProfile()
  {
    $user_id = Auth::guard('sanctum')->user()->id;
    $data = User::where('id', $user_id)->first();
    if (!$data) {
      return response([
        'message' => 'User not found ...!',
      ]);
    } else {
      $userResource = new UserResource($data);
      $userResourceData = $userResource->showWithDetails($data);
      return response([
        'user' => $userResourceData,
        'message' => 'User found ...!',
      ]);
    }
  }

  // Update Profile
  public function updateProfile(Request $request)
  {
    $user = Auth::guard('sanctum')->user();

    $data = [
      'first_name' => $request->has('first_name') ? $request->first_name : $user->first_name,
      'last_name' => $request->has('last_name') ? $request->last_name : $user->last_name,
      'mobile' => $request->has('mobile') ? $request->mobile : $user->mobile,
      'email' => $request->has('email') ? $request->email : $user->email,
      'username' => $request->has('username') ? $request->username : $user->username,
      'gender' => $request->has('gender') ? $request->gender : $user->gender,
      'dob' => $request->has('dob') ? $request->dob : $user->dob,
    ];

    // Handle image upload
    if ($request->hasFile('image')) {
      $image = $request->file('image');
      $imageName = time() . '.' . $image->getClientOriginalExtension();
      $image->move(public_path('assets/images/users_images'), $imageName);
      $data['image'] = $imageName;
    }

    $user->update($data);

    return response([
      'success' => true,
      'message' => 'Profile updated successfully!',
    ]);
  }

  // Product Like
  public function productLike(Request $request)
  {
    if (User::where('id', $request->user_id)->first()) {
      // dd($request->all());

      if (
        ProductLike::where('user_id', $request->user_id)
        ->where('product_id', $request->product_id)
        ->first()
      ) {
        $data = ProductLike::where('user_id', $request->user_id)
          ->where('product_id', $request->product_id)
          ->delete();
        // dd($data);
        return response()->json([
          'message' => 'product disliked',
        ]);
      } else {
        $data = $request->all();
        $data = ProductLike::create($data);
        return response()->json([
          'message' => 'product liked',
          'data' => $data,
        ]);
      }
    } else {
      return response()->json([
        'message' => 'User not found',
      ]);
    }
  }

  // Product WishList
  public function productWishList(Request $request)
  {
    if (ProductLike::where('user_id', $request->user_id)->exists()) {
      $data = Product::select(
        'products.id',
        'products.category_id',
        'products.sub_category_id',
        'products.product_name',
        'products.product_image',
        'products.product_sale_price',
        'products.product_price',
        'products.store_id',
        'products.module_id',
        DB::raw('GROUP_CONCAT(DISTINCT variants.color) as product_color'),
        DB::raw('GROUP_CONCAT(DISTINCT variants.size) as product_size')
      )
        ->leftjoin('product_likes', 'products.id', '=', 'product_likes.product_id')
        ->leftjoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
        ->leftJoin('variants', 'products.id', '=', 'variants.product_id') // Join with variants table
        ->where('product_likes.user_id', $request->user_id)
        ->groupBy(
          'products.id',
          'products.product_name',
          'products.product_sale_price',
          'products.product_price',
          'products.sub_category_id',
          'products.category_id', // Include category_id in the select statement
          'products.product_image',
          'products.store_id',
          'products.module_id',
        )
        ->get()
        ->transform(function ($tsr) {
          if ($tsr->product_sale_price != '' && $tsr->product_sale_price > 0) {
            $tsr->product_sale_price = $tsr->product_sale_price;
          } else {
            $tsr->product_sale_price = $tsr->product_price;
          }

          $totalProductReview = 0;
          $totalProductReview = ProductReview::select('product_id', 'review_star')
            ->where('product_id', $tsr->id)
            ->get();
          $totalReviewStar = 0;
          $totalAvgReview = 0;
          foreach ($totalProductReview as $val) {
            $reviewStar = floatval($val->review_star);
            $totalReviewStar = $totalReviewStar + $reviewStar;
          }
          $totalReviewCount = $totalProductReview->count();

          if ($totalReviewCount) {
            (int) ($tsr->totalReviewCount = (int) $totalReviewCount);
          } else {
            $tsr->totalReviewCount = 0;
          }
          if ($totalReviewCount > 0) {
            $totalAvgReview = round($totalReviewStar / $totalReviewCount, 1);
          }
          (int) ($tsr->totalAvgReview = (int) $totalAvgReview);

          $productImages = ProductImages::select('product_image')
            ->where('product_id', $tsr->id)
            ->get();
          if ($productImages->isNotEmpty()) {
            foreach ($productImages as $val) {
              if ($val->product_image) {
                $totalProductImages = url('/assets/images/product_images/' . $val->product_image);
                $tsr->product_image = $totalProductImages;
              }
            }
          } else {
            $tsr->product_image = '';
          }

          $tsr->product_color = $tsr->product_color ? explode(',', $tsr->product_color) : [];
          $tsr->product_size = $tsr->product_size ? explode(',', $tsr->product_size) : [];
          $tsr->isLike = 1;
          return $tsr;
        });
      return response()->json([
        'message' => 'Product wishlist found',
        'data' => $data,
      ]);
    } else {
      return response()->json([
        'message' => 'Product not found',
        'data' => [],
      ]);
    }
  }

  // Coupon List
  public function couponList(Request $request)
  {
    // Validate the user_id parameter
    $validator = Validator::make($request->all(), [
      'user_id' => 'required|exists:users,id',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => 'Validation failed',
        'errors' => $validator->errors(),
      ]);
    }

    // Get user_id from the request
    $user_id = $request->user_id;

    // Fetch and modify the coupon list
    $couponList = Coupon::orderBy('created_at', 'desc')->get();

    $couponList = $couponList->map(function ($coupon) {
      if (is_null($coupon->type)) {
        $coupon->type = '';
        $coupon->vendor_id = 0;
      }
      return $coupon;
    });

    return response()->json(
      [
        'success' => true,
        'data' => $couponList,
      ],
      201
    );
  }


  // Remove Coupon
  public function removeCoupon(Request $request)
  {
    // Validate the user_id and coupon_code parameters
    $validator = Validator::make($request->all(), [
      'user_id' => 'required|exists:users,id',
      'coupon_code' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'errors' => $validator->errors(),
      ], 400);
    }

    $user_id = $request->user_id;
    $coupon_code = $request->coupon_code;

    // Update the cart item to remove the coupon_id
    CartItem::where('user_id', $user_id)->update(['coupon_id' => null]);

    // Update the coupon status
    Coupon::where('coupon_code', $coupon_code)->update(['status' => '1']);

    return response()->json([
      'success' => true,
      'message' => 'Coupon removed Successfully ...!',
    ], 201);
  }


  // Applied Coupon
  public function appliedCoupon(Request $request)
  {
    // Validate the user_id and coupon_code parameters
    $validator = Validator::make($request->all(), [
      'user_id' => 'required|exists:users,id',
      'coupon_code' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => 'Validation failed',
        'errors' => $validator->errors(),
      ], 400);
    }

    $user_id = $request->user_id;
    $coupon_code = $request->coupon_code;

    $coupon = Coupon::where('coupon_code', $coupon_code)->first();

    if (!$coupon) {
      return response()->json([
        'message' => 'Invalid Coupon ...!',
      ], 401);
    }

    $couponId = $coupon->id;

    $cartItem = CartItem::where('user_id', $user_id)->first();

    if (!$cartItem) {
      return response()->json([
        'message' => 'No items in the cart ...!',
      ], 404);
    }

    if ($cartItem->coupon_id != $couponId) {
      CartItem::where('user_id', $user_id)->update(['coupon_id' => $couponId]);

      $data = CartItem::where('coupon_id', $couponId)->first();

      Coupon::where('id', $data->coupon_id)->update(['status' => '1']);
      Coupon::where('coupon_code', $coupon_code)->update(['status' => '0']);

      return response()->json([
        'message' => 'Coupon Applied Successfully ...!',
      ], 201);
    } else {
      return response()->json([
        'message' => 'Coupon Already Applied ...!',
      ], 401);
    }
  }


  // Add Address
  public function addAddress(Request $request)
  {
    // Add user_id to the validation rules
    $validator = Validator::make($request->all(), [
      'user_id' => 'required|exists:users,id',
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
      ]);
    }

    // Get user_id from the request
    $user_id = $request->user_id;

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
    $data->last_address_status = UserAddress::where('user_id', $user_id)
      ->where('last_address_status', '1')
      ->exists()
      ? 0
      : 1;

    if ($request->default_address == 'true') {
      UserAddress::where('user_id', $user_id)->update(['default_address' => '0']);
    }

    if ($request->default_address == 'true') {
      $data->default_address = '1';
    } else {
      $data->default_address = '0';
    }

    $data->country_code = $request->country_code ?? '';
    $data->save();

    return response([
      'success' => true,
      'message' => 'Address added successfully',
      'data' => $data,
    ]);
  }


  // Get Address
  public function getAddress(Request $request)
  {
    // Validate the user_id parameter
    $validator = Validator::make($request->all(), [
      'user_id' => 'required|exists:users,id',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => 'Validation failed',
        'errors' => $validator->errors(),
      ]);
    }

    // Get user_id from the request
    $user_id = $request->user_id;

    $defaultAddress = UserAddress::where('user_id', $user_id)
      ->where('default_address', '1')
      ->where('address_flag', 'Y')
      ->orderBy('id', 'desc')
      ->limit(1)
      ->get();

    $otherAddress = UserAddress::where('user_id', $user_id)
      ->where('default_address', '0')
      ->where('address_flag', 'Y')
      ->get();

    return response()->json(
      [
        'message' => 'Get Address ...!',
        'default_address' => $defaultAddress,
        'other_address' => $otherAddress,
      ],
      200
    );
  }

  public function address(Request $request)
  {
    // Validate the user_id and address_id parameters
    $validator = Validator::make($request->all(), [
      'user_id' => 'required|exists:users,id',
      'address_id' => 'required|exists:user_address,id',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => 'Validation failed',
        'errors' => $validator->errors(),
      ]);
    }

    // Get user_id from the request
    $user_id = $request->user_id;

    // Update the cart items with the provided address_id
    CartItem::where('user_id', $user_id)->update(['address_id' => $request->address_id]);

    // Update the user addresses to reset the last_address_status
    UserAddress::where('user_id', $user_id)->update(['last_address_status' => '0']);
    UserAddress::where('id', $request->address_id)->update(['last_address_status' => '1']);

    return response()->json(
      [
        'message' => 'Cart Address Updated ...!',
      ],
      200
    );
  }



  // Update Address
  public function updateAddress(Request $request)
  {
    // Validate the user_id and address_id parameters
    $validator = Validator::make($request->all(), [
      'user_id' => 'required|exists:users,id',
      'address_id' => 'required|exists:user_address,id',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => 'Validation failed',
        'errors' => $validator->errors(),
      ]);
    }

    // Get user_id and address_id from the request
    $user_id = $request->user_id;
    $address_id = $request->address_id;

    // Check if the address_id belongs to the provided user_id
    $address = UserAddress::where('id', $address_id)
      ->where('user_id', $user_id)
      ->first();

    // If the address_id doesn't belong to the user, return unauthorized response
    if (!$address) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Update only the parameters provided in the request
    if ($request->has('first_name')) {
      $address->first_name = $request->first_name;
    }
    if ($request->has('last_name')) {
      $address->last_name = $request->last_name;
    }
    if ($request->has('mobile')) {
      $address->mobile = $request->mobile;
    }
    if ($request->has('pincode')) {
      $address->pincode = $request->pincode;
    }
    if ($request->has('address')) {
      $address->address = $request->address;
    }
    if ($request->has('locality')) {
      $address->locality = $request->locality;
    }
    if ($request->has('city')) {
      $address->city = $request->city;
    }
    if ($request->has('state')) {
      $address->state = $request->state;
    }
    if ($request->has('type')) {
      $address->type = $request->type;
    }

    // Set last_address_status based on whether there's an existing address marked as the last one for the user
    $address->last_address_status = UserAddress::where('user_id', $user_id)
      ->where('last_address_status', '1')
      ->exists()
      ? 0
      : 1;

    // Set default_address based on the request
    $address->default_address = $request->boolean('default_address') ? '1' : '0';

    if ($request->has('country_code')) {
      $address->country_code = $request->country_code;
    }

    // Save the updated address data
    $address->save();

    // Return the response
    return response([
      'success' => true,
      'message' => 'Address updated successfully!',
      'data' => $address,
    ]);
  }

  // Edit Address
  public function editAddress(Request $request)
  {
    // Validate the user_id and address_id parameters
    $validator = Validator::make($request->all(), [
      'user_id' => 'required|exists:users,id',
      'address_id' => 'required|exists:user_address,id',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => 'Validation failed',
        'errors' => $validator->errors(),
      ]);
    }

    // Get user_id from the request
    $user_id = $request->user_id;

    // Check if the address exists and belongs to the user
    $address = UserAddress::where('id', $request->address_id)
      ->where('user_id', $user_id)
      ->first();

    if (!$address) {
      return response([
        'message' => 'Address not found or unauthorized.',
      ]);
    }

    // Adjust the default_address flag
    $address->default_address = $address->default_address == 1;

    return response([
      'success' => true,
      'message' => 'Address retrieved successfully.',
      'data' => $address,
    ]);
  }


  // removeAddress
  public function removeAddress(Request $request)
  {
    // Validate the user_id and address_id parameters
    $validator = Validator::make($request->all(), [
      'user_id' => 'required|exists:users,id',
      'address_id' => 'required|exists:user_address,id',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => 'Validation failed',
        'errors' => $validator->errors(),
      ]);
    }

    // Get user_id and address_id from the request
    $user_id = $request->user_id;
    $address_id = $request->address_id;

    // Update cart items and order items to remove the address reference
    CartItem::where('user_id', $user_id)
      ->where('address_id', $address_id)
      ->update(['address_id' => 0]);

    OrderItem::where('user_id', $user_id)
      ->where('address_id', $address_id)
      ->update(['address_id' => 0]);

    // Delete the address and update remaining addresses
    UserAddress::where('user_id', $user_id)
      ->where('id', $address_id)
      ->delete();

    UserAddress::where('user_id', $user_id)
      ->where('address_flag', 'Y')
      ->update(['last_address_status' => 1]);

    UserAddress::where('user_id', $user_id)
      ->where('id', $address_id)
      ->update(['address_flag' => 'N']);

    return response()->json(
      [
        'success' => true,
        'message' => 'Address removed successfully!',
      ],
      200
    );
  }


  //  Store Liked
  public function storeLike(Request $request)
  {
    if (User::where('id', $request->user_id)->first()) {
      // dd($request->all());

      if (
        StoreLike::where('user_id', $request->user_id)
        ->where('store_id', $request->store_id)
        ->first()
      ) {
        $data = StoreLike::where('user_id', $request->user_id)
          ->where('store_id', $request->store_id)
          ->delete();
        // dd($data);
        return response()->json([
          'message' => 'store disliked',
        ]);
      } else {
        $data = $request->all();
        $data = StoreLike::create($data);
        return response()->json([
          'message' => 'store liked',
          'data' => $data,
        ]);
      }
    } else {
      return response()->json([
        'message' => 'User not found',
      ]);
    }
  }

  // Store WishList
  public function storeWishList(Request $request)
  {
    if (StoreLike::where('user_id', $request->user_id)->exists()) {
      $data = VendorStore::select(
        'vendor_stores.id',
        'vendor_stores.store_name',
        'vendor_stores.store_description',
        'vendor_stores.store_address',
        'vendor_stores.store_logo',
        'vendor_stores.delivery_time',
        'vendor_stores.status'
      )
        ->leftJoin('store_likes', 'vendor_stores.id', '=', 'store_likes.store_id')
        ->where('store_likes.user_id', $request->user_id)
        ->get()
        ->transform(function ($tsr) {
          $totalStoreReview = StoreReview::select('review_star')
            ->where('store_id', $tsr->id)
            ->get();
          $totalReviewStar = $totalStoreReview->sum('review_star');
          $totalReviewCount = $totalStoreReview->count();

          $tsr->totalReviewCount = $totalReviewCount ? (string) $totalReviewCount : '';
          $tsr->totalAvgReview = $totalReviewCount > 0 ? (string) round($totalReviewStar / $totalReviewCount, 1) : '0';

          $storeimages = StoreImages::select('store_images')
            ->where('store_id', $tsr->id)
            ->get();

          $images = [];
          foreach ($storeimages as $val) {
            if ($val->store_images) {
              $images[] = url('/assets/images/store_images/' . $val->store_images);
            }
          }
          $tsr->store_images = $images;

          $tsr->store_logo = $tsr->store_logo ? url('/assets/images/store_logo/' . $tsr->store_logo) : null;

          $tsr->isLike = 1;
          return $tsr;
        });

      return response()->json([
        'message' => 'Store wishlist found',
        'data' => $data,
      ]);
    } else {
      return response()->json([
        'message' => 'Store not found',
        'data' => [],
      ]);
    }
  }

  // AddtoCart

  // public function AddtoCart(Request $request)
  // {
  //   $user_id = Auth::guard('sanctum')->user()->id;

  //   // Check if user exists
  //   $user = User::find($user_id);
  //   if (!$user) {
  //     return response()->json(['message' => 'User not found'], 500);
  //   }

  //   // Check if product exists
  //   $product = Product::find($request->product_id);
  //   if (!$product) {
  //     return response()->json(['message' => 'Product not found'], 500);
  //   }

  //   // Check if adding products from different stores
  //   $cartItems = CartItem::where('user_id', $user_id)->get();
  //   foreach ($cartItems as $cartItem) {
  //     $existingProduct = Product::find($cartItem->product_id);
  //     if ($existingProduct->store_id != $product->store_id) {
  //       return response()->json(
  //         [
  //           'message' => 'Products from different stores cannot be added to the cart simultaneously',
  //           'response_code' => 0,
  //         ],
  //         400
  //       );
  //     }
  //   }

  //   // Check if the same product with the same color and size exists in the cart
  //   $existingCartItem = CartItem::where('user_id', $user_id)
  //     ->where('product_id', $request->product_id)
  //     ->where('product_color', $request->product_color)
  //     ->where('product_size', $request->product_size)
  //     ->first();

  //   // If exists, update the quantity
  //   if ($existingCartItem) {
  //     if ($existingCartItem->quantity >= 5) {
  //       return response()->json(['message' => 'You cannot add more quantity'], 401);
  //     }
  //     $existingCartItem->quantity += 1;
  //     $existingCartItem->save();
  //   } else {
  //     // Find the variant if the product has variants
  //     $variant = Variant::where('product_id', $request->product_id)
  //       ->where('color', $request->product_color)
  //       ->where('size', $request->product_size)
  //       ->first();
  //     // If not, create a new cart item
  //     $cartItem = [
  //       'user_id' => $user_id,
  //       'product_id' => $request->product_id,
  //       'product_color' => $request->product_color,
  //       'product_size' => $request->product_size,
  //       'quantity' => $request->quantity,
  //       'address_id' =>
  //       UserAddress::where('user_id', $user_id)
  //         ->where('default_address', 1)
  //         ->value('id') ?? null,
  //       'coupon_id' => CartItem::where('user_id', $user_id)->value('coupon_id') ?? null,
  //       'store_id' => $product->store_id, // Store ID of the product being added to the cart
  //       'variant_id' => $variant ? $variant->id : null,
  //     ];
  //     CartItem::create($cartItem);
  //   }

  //   return response()->json(['message' => 'Product add to cart successfully ...!', 'response_code' => 1], 200);
  // }


  public function AddtoCart(Request $request)
  {
    // Check if user exists
    $user_id = $request->user_id;
    $user = User::find($user_id);
    if (!$user) {
      return response()->json(['message' => 'User not found'], 500);
    }

    // Check if product exists
    $product = Product::find($request->product_id);
    if (!$product) {
      return response()->json(['message' => 'Product not found'], 500);
    }

    // Check if adding products from different stores
    $cartItems = CartItem::where('user_id', $user_id)->get();
    foreach ($cartItems as $cartItem) {
      $existingProduct = Product::find($cartItem->product_id);
      if ($existingProduct->store_id != $product->store_id) {
        return response()->json(
          [
            'message' => 'Products from different stores cannot be added to the cart simultaneously',
            'response_code' => 0,
          ],
          400
        );
      }
    }

    // Check if the same product with the same color and size exists in the cart
    $existingCartItem = CartItem::where('user_id', $user_id)
      ->where('product_id', $request->product_id)
      ->where('product_color', $request->product_color)
      ->where('product_size', $request->product_size)
      ->first();

    // If exists, update the quantity
    if ($existingCartItem) {
      if ($existingCartItem->quantity >= 5) {
        return response()->json(['message' => 'You cannot add more quantity'], 401);
      }
      $existingCartItem->quantity += 1;
      $existingCartItem->save();
    } else {
      // Find the variant if the product has variants
      $variant = Variant::where('product_id', $request->product_id)
        ->where('color', $request->product_color)
        ->where('size', $request->product_size)
        ->first();

      // If not, create a new cart item
      $cartItem = [
        'user_id' => $user_id,
        'product_id' => $request->product_id,
        'product_color' => $request->product_color,
        'product_size' => $request->product_size,
        'quantity' => $request->quantity,
        'address_id' => UserAddress::where('user_id', $user_id)
          ->where('default_address', 1)
          ->value('id') ?? null,
        'coupon_id' => CartItem::where('user_id', $user_id)->value('coupon_id') ?? null,
        'store_id' => $product->store_id, // Store ID of the product being added to the cart
        'variant_id' => $variant ? $variant->id : null,
      ];
      CartItem::create($cartItem);
    }

    return response()->json(['message' => 'Product added to cart successfully ...!', 'response_code' => 1], 200);
  }


  // CartList

  // public function cartList(Request $request)
  // {
  //   $user_id = Auth::guard('sanctum')->user()->id;

  //   if ($request->product_id != '') {
  //     CartItem::where('product_id', $request->product_id)->update([
  //       'quantity' => $request->quantity,
  //     ]);
  //   }

  //   $data = CartItem::with('productImages')
  //     ->select(
  //       'cart_items.id',
  //       'cart_items.user_id',
  //       'cart_items.product_id',
  //       'cart_items.product_color',
  //       'cart_items.product_size',
  //       'cart_items.quantity',
  //       'products.product_name',
  //       'products.product_price',
  //       'products.product_sale_price',
  //       'sub_categories.sub_category_name',
  //       'coupon_id'
  //     )
  //     ->leftJoin('products', 'cart_items.product_id', '=', 'products.id')
  //     ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
  //     ->where('cart_items.user_id', $user_id)
  //     ->get()
  //     ->transform(function ($item) {
  //       // Fetch variant price if available
  //       $variant = Variant::where('product_id', $item->product_id)
  //         ->where('color', $item->product_color)
  //         ->where('size', $item->product_size)
  //         ->first();

  //       if ($variant) {
  //         $item->product_sale_price = $variant->price;
  //       }

  //       // Transformations
  //       $item->coupon_id = (string) $item->coupon_id;
  //       $item->product_color = $item->product_color ?? '';
  //       $item->product_size = $item->product_size ?? '';

  //       // Modify product image URLs
  //       $productImage = [];
  //       foreach ($item->productImages as $image) {
  //         $productImage[] = url('/assets/images/product_images/' . $image->product_image);
  //       }
  //       $item->productImage = $productImage;

  //       // Determine if liked
  //       $productLike = ProductLike::where('user_id', $item->user_id)
  //         ->where('product_id', $item->product_id)
  //         ->first();
  //       $item->is_Like = $productLike ? 1 : 0;

  //       return $item;
  //     });

  //   // Calculate bag total
  //   $bagTotal = 0;
  //   foreach ($data as $item) {
  //     $price = $item->product_sale_price ?? $item->product_price;
  //     $bagTotal += $price * $item->quantity;
  //   }

  //   // Fetch coupon details if applicable
  //   $couopnAmt = 0;
  //   $couoponCode = '';
  //   $coupon_id = $data->first()->coupon_id ?? null;
  //   if ($coupon_id) {
  //     $coupon = Coupon::where('id', $coupon_id)->first();
  //     $couopnAmt = $coupon->discount_amount;
  //     $couoponCode = $coupon->coupon_code;
  //   }

  //   // Fetch user address
  //   $addressId = $data->first()->address_id ?? null;
  //   $address = null;
  //   if ($addressId) {
  //     $address = UserAddress::where('id', $addressId)
  //       ->where('address_flag', 'Y')
  //       ->first();
  //   } else {
  //     $address = UserAddress::where('user_id', $user_id)
  //       ->where('address_flag', 'Y')
  //       ->where('last_address_status', '1')
  //       ->first();
  //   }

  //   return response()->json([
  //     'success' => true,
  //     'Address' => $address,
  //     'data' => $data,
  //     'bagTotal' => $bagTotal,
  //     'coupons' => (int) $couopnAmt,
  //     'couponCode' => $couoponCode,
  //     'totalAmount' => $bagTotal - $couopnAmt,
  //   ]);
  // }

  public function cartList(Request $request)
  {
    // Check if user exists
    $user_id = $request->user_id;
    $user = User::find($user_id);
    if (!$user) {
      return response()->json(['message' => 'User not found'], 500);
    }

    if ($request->product_id != '') {
      CartItem::where('product_id', $request->product_id)->update([
        'quantity' => $request->quantity,
      ]);
    }

    $data = CartItem::with('productImages')
      ->select(
        'cart_items.id',
        'cart_items.user_id',
        'cart_items.product_id',
        'cart_items.product_color',
        'cart_items.product_size',
        'cart_items.quantity',
        'products.product_name',
        'products.product_price',
        'products.product_sale_price',
        'sub_categories.sub_category_name',
        'coupon_id'
      )
      ->leftJoin('products', 'cart_items.product_id', '=', 'products.id')
      ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
      ->where('cart_items.user_id', $user_id)
      ->get()
      ->transform(function ($item) {
        // Fetch variant price if available
        $variant = Variant::where('product_id', $item->product_id)
          ->where('color', $item->product_color)
          ->where('size', $item->product_size)
          ->first();

        if ($variant) {
          $item->product_sale_price = $variant->price;
        }

        // Transformations
        $item->coupon_id = (string) $item->coupon_id;
        $item->product_color = $item->product_color ?? '';
        $item->product_size = $item->product_size ?? '';

        // Modify product image URLs
        $productImage = [];
        foreach ($item->productImages as $image) {
          $productImage[] = url('/assets/images/product_images/' . $image->product_image);
        }
        $item->productImage = $productImage;

        // Determine if liked
        $productLike = ProductLike::where('user_id', $item->user_id)
          ->where('product_id', $item->product_id)
          ->first();
        $item->is_Like = $productLike ? 1 : 0;

        return $item;
      });

    // Calculate bag total
    $bagTotal = 0;
    foreach ($data as $item) {
      $price = $item->product_sale_price ?? $item->product_price;
      $bagTotal += $price * $item->quantity;
    }

    // Fetch coupon details if applicable
    $couponAmt = 0;
    $couponCode = '';
    $coupon_id = $data->first()->coupon_id ?? null;
    if ($coupon_id) {
      $coupon = Coupon::where('id', $coupon_id)->first();
      $couponAmt = $coupon->discount_amount;
      $couponCode = $coupon->coupon_code;
    }

    // Fetch user address
    $addressId = $data->first()->address_id ?? null;
    $address = null;
    if ($addressId) {
      $address = UserAddress::where('id', $addressId)
        ->where('address_flag', 'Y')
        ->first();
    } else {
      $address = UserAddress::where('user_id', $user_id)
        ->where('address_flag', 'Y')
        ->where('last_address_status', '1')
        ->first();
    }

    return response()->json([
      'success' => true,
      'Address' => $address,
      'data' => $data,
      'bagTotal' => $bagTotal,
      'coupons' => (int) $couponAmt,
      'couponCode' => $couponCode,
      'totalAmount' => $bagTotal - $couponAmt,
    ]);
  }



  // Cart Remove
  public function cartRemove(Request $request)
  {
    if (CartItem::where('id', $request->cart_id)->first()) {
      $data = CartItem::find($request->cart_id)->delete();
      return response()->json(['message' => 'Cart item deleted successfully'], 201);
    } else {
      return response()->json(['message' => 'Cart item not found'], 405);
    }
  }

  // Update cart
  public function updateCart(Request $request)
  {
    // Get the user ID from the request
    $userId = $request->input('user_id');

    // Validate the user ID
    if ($userId) {
      // Retrieve the user object
      $user = User::find($userId);

      if ($user) {
        // Prepare the data for updating
        $data = [
          'quantity' => $request->quantity,
        ];

        // Update the cart item
        $cartItem = CartItem::where('id', $request->cart_id)
          ->where('user_id', $user->id)
          ->update($data);

        if ($cartItem) {
          return response()->json(
            [
              'message' => 'Cart updated successfully!',
            ],
            200
          );
        } else {
          return response()->json(
            [
              'message' => 'Cart item not found or does not belong to the user.',
            ],
            404
          );
        }
      } else {
        return response()->json(
          [
            'message' => 'User not found.',
          ],
          404
        );
      }
    } else {
      return response()->json(
        [
          'message' => 'User not authenticated.',
        ],
        401
      );
    }
  }


  public function clearCart(Request $request)
  {
    $user_id = $request->input('user_id');

    if (!$user_id) {
      return response()->json(['message' => 'User ID is required'], 400);
    }

    $cartItems = CartItem::where('user_id', $user_id)->get();

    if ($cartItems->isEmpty()) {
      return response()->json(['message' => 'No cart items found for the specified user'], 404);
    }

    foreach ($cartItems as $cartItem) {
      $cartItem->delete();
    }

    return response()->json(['message' => 'Cart cleared successfully'], 200);
  }

  //  Get Checkout
  // public function getCheckOut()
  // {
  //   $user_id = Auth::guard('sanctum')->user()->id;
  //   $data = CartItem::with('productImages')
  //     ->select(
  //       'cart_items.id',
  //       'cart_items.user_id',
  //       'cart_items.product_id',
  //       'cart_items.product_color',
  //       'cart_items.product_size',
  //       'cart_items.quantity',
  //       'cart_items.coupon_id',
  //       'products.product_name',
  //       'products.product_price',
  //       'products.product_sale_price',
  //       'sub_categories.sub_category_name',
  //       'vendor_stores.store_name', // Select store name
  //       'coupons.coupon_code', // Select coupon name
  //       'coupons.discount_amount' // Select coupon discount amount
  //     )
  //     ->leftJoin('products', 'cart_items.product_id', '=', 'products.id')
  //     ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
  //     ->leftJoin('vendor_stores', 'products.store_id', '=', 'vendor_stores.id') // Join stores table
  //     ->leftJoin('coupons', 'cart_items.coupon_id', '=', 'coupons.id')
  //     ->where('cart_items.user_id', $user_id)
  //     ->get();

  //   $bagTotal = 0;
  //   $addressId = '';
  //   $coupon_id = 0;
  //   $couopnAmt = 0; // Initialize coupon amount
  //   $couoponCode = ''; // Initialize coupon code
  //   // $totalAmount = 0;

  //   foreach ($data as $value) {
  //     $addressId = $value->address_id;
  //     // $totalAmount += $value->product_sale_price * $value->quantity;

  //     $productImage = [];
  //     foreach ($value->productImages as $image) {
  //       $productImage[] = url('assets/images/product_images/' . $image->product_image);
  //     }
  //     $value->productImage = $productImage;
  //     $value->product_color = $value->product_color ?? ''; // Add this line
  //     $value->product_size = $value->product_size ?? ''; // Add this line
  //     $value->coupon_id = $value->coupon_id ?? ''; // Add this line
  //     $value->coupon_code = $value->coupon_code ?? ''; // Add this line
  //     $value->discount_amount = $value->discount_amount ?? ''; // Add this line

  //     // Calculate bag total without modifying product_price and product_sale_price
  //     $bagTotal += ($value->product_sale_price != null && $value->product_sale_price > 0) ?
  //       ($value->product_sale_price * $value->quantity) : ($value->product_price * $value->quantity);
  //     $coupon_id = $value->coupon_id;
  //   }

  //   if ($coupon_id) {
  //     $couopnDetail = Coupon::where('id', $coupon_id)->first();
  //     $totalAmount = $bagTotal - $couopnDetail->discount_amount;
  //     $couopnAmt = $couopnDetail->discount_amount;
  //     $couoponCode = $couopnDetail->coupon_code;
  //   } else {
  //     $totalAmount = $bagTotal - 0;
  //   }

  //   if ($addressId) {
  //     $address = UserAddress::select(
  //       'id',
  //       'first_name',
  //       'last_name',
  //       'mobile',
  //       'pincode',
  //       'address',
  //       'locality',
  //       'city',
  //       'state',
  //       'type'
  //     )
  //       ->where('id', $addressId)
  //       ->first();
  //   } elseif (UserAddress::where('user_id', $user_id)
  //     ->where('last_address_status', '1')
  //     ->exists()
  //   ) {
  //     $address = UserAddress::select(
  //       'id',
  //       'first_name',
  //       'last_name',
  //       'mobile',
  //       'pincode',
  //       'address',
  //       'locality',
  //       'city',
  //       'state',
  //       'type'
  //     )
  //       ->where('user_id', $user_id)
  //       ->where('last_address_status', '1')
  //       ->first();
  //   } else {
  //     $address = [
  //       'id' => 0,
  //       'first_name' => '',
  //       'last_name' => '',
  //       'mobile' => '',
  //       'pincode' => '',
  //       'address' => '',
  //       'locality' => '',
  //       'city' => '',
  //       'state' => '',
  //       'type' => '',
  //     ];
  //   }

  //   return response()->json([
  //     'message' => 'Success ...!',
  //     'Address' => $address,
  //     'Item' => $data->count(),
  //     'data' => $data,
  //     'bagTotal' => $bagTotal,
  //     'coupons' => (int) $couopnAmt,
  //     'couponCode' => $couoponCode,
  //     'totalAmount' => $totalAmount,
  //   ]);
  // }

  public function getCheckOut(Request $request)
  {
    $user_id = $request->input('user_id');

    $data = CartItem::with('productImages')
      ->select(
        'cart_items.id',
        'cart_items.user_id',
        'cart_items.product_id',
        'cart_items.product_color',
        'cart_items.product_size',
        'cart_items.quantity',
        'cart_items.coupon_id',
        'products.product_name',
        'products.product_price',
        'products.product_sale_price',
        'sub_categories.sub_category_name',
        'vendor_stores.store_name', // Select store name
        'coupons.coupon_code', // Select coupon name
        'coupons.discount_amount' // Select coupon discount amount
      )
      ->leftJoin('products', 'cart_items.product_id', '=', 'products.id')
      ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
      ->leftJoin('vendor_stores', 'products.store_id', '=', 'vendor_stores.id') // Join stores table
      ->leftJoin('coupons', 'cart_items.coupon_id', '=', 'coupons.id')
      ->where('cart_items.user_id', $user_id)
      ->get()
      ->transform(function ($item) {
        // Fetch variant price if available
        $variant = Variant::where('product_id', $item->product_id)
          ->where('color', $item->product_color)
          ->where('size', $item->product_size)
          ->first();

        if ($variant) {
          $item->product_sale_price = $variant->price;
        }

        // Modify product image URLs
        $productImage = [];
        foreach ($item->productImages as $image) {
          $productImage[] = url('assets/images/product_images/' . $image->product_image);
        }
        $item->productImage = $productImage;

        // Set default values if null
        $item->product_color = $item->product_color ?? '';
        $item->product_size = $item->product_size ?? '';
        $item->coupon_id = $item->coupon_id ?? '';
        $item->coupon_code = $item->coupon_code ?? '';
        $item->discount_amount = $item->discount_amount ?? '';

        return $item;
      });

    $bagTotal = 0;
    $addressId = '';
    $coupon_id = 0;
    $couopnAmt = 0; // Initialize coupon amount
    $couoponCode = ''; // Initialize coupon code

    foreach ($data as $item) {
      $addressId = $item->address_id;

      // Calculate bag total using variant price if available
      $bagTotal +=
        $item->product_sale_price != null && $item->product_sale_price > 0
        ? $item->product_sale_price * $item->quantity
        : $item->product_price * $item->quantity;
      $coupon_id = $item->coupon_id;
    }

    $totalAmount = $bagTotal;

    if ($coupon_id) {
      $couopnDetail = Coupon::where('id', $coupon_id)->first();
      $totalAmount -= $couopnDetail->discount_amount;
      $couopnAmt = $couopnDetail->discount_amount;
      $couoponCode = $couopnDetail->coupon_code;
    }

    $address = null;
    if ($addressId) {
      $address = UserAddress::select(
        'id',
        'first_name',
        'last_name',
        'mobile',
        'pincode',
        'address',
        'locality',
        'city',
        'state',
        'type'
      )
        ->where('id', $addressId)
        ->first();
    } else {
      $address = UserAddress::select(
        'id',
        'first_name',
        'last_name',
        'mobile',
        'pincode',
        'address',
        'locality',
        'city',
        'state',
        'type'
      )
        ->where('user_id', $user_id)
        ->where('last_address_status', '1')
        ->first();
    }

    if (!$address) {
      $address = [
        'id' => 0,
        'first_name' => '',
        'last_name' => '',
        'mobile' => '',
        'pincode' => '',
        'address' => '',
        'locality' => '',
        'city' => '',
        'state' => '',
        'type' => '',
      ];
    }

    return response()->json([
      'message' => 'Success ...!',
      'Address' => $address,
      'Item' => $data->count(),
      'data' => $data,
      'bagTotal' => $bagTotal,
      'coupons' => (int) $couopnAmt,
      'couponCode' => $couoponCode,
      'totalAmount' => $totalAmount,
    ]);
  }

  // Checkout
  // public function Checkout(Request $request)
  // {
  //   $user_id = Auth::guard('sanctum')->user()->id;
  //   if ($user_id) {
  //     $orderDetail = CartItem::select(
  //       'cart_items.user_id',
  //       'cart_items.product_id',
  //       'cart_items.product_color',
  //       'cart_items.product_size',
  //       'cart_items.quantity',
  //       'cart_items.coupon_id',
  //       'cart_items.address_id',
  //       'products.id',
  //       'products.product_price',
  //       'products.product_sale_price',
  //       'products.store_id', // Added store_id here
  //       'products.vendor_id' // Added vendor_id here
  //     )
  //       ->leftjoin('products', 'products.id', '=', 'cart_items.product_id')
  //       ->where('user_id', $user_id)->get();

  //     $orderId = '';
  //     $coupon_id = 0;
  //     $address_id = 0;
  //     $lastOrder = Order::orderBy('id', 'desc')->first();
  //     if ($lastOrder) {
  //       $lastOrderId = $lastOrder->order_id;
  //       $orderId = str_pad($lastOrderId + 1, 4, '0', STR_PAD_LEFT);
  //     } else {
  //       $orderId = '0001';
  //     }

  //     $netAmount = 0;
  //     $total_amount = 0;
  //     $store_id = 0; // Initialize store_id
  //     $vendor_id = 0;

  //     foreach ($orderDetail as $value) {
  //       $prodcutDetail = Product::where('id', $value->product_id)->first();
  //       $CouponDetail = Coupon::where('id', $value->coupon_id)->first();
  //       $address_id = $value->address_id ?? 0;
  //       $coupon_id = $value->coupon_id ?? 0;
  //       $store_id = $value->store_id ?? 0; // Set store_id
  //       $vendor_id = $value->vendor_id ?? 0;

  //       if ($value->product_sale_price != null || $value->product_sale_price > 0) {
  //         $value->product_sale_price = $value->product_sale_price * $value->quantity;
  //         $total_amount += $value->product_sale_price;
  //       } else {
  //         $value->product_sale_price = $value->product_price * $value->quantity;
  //         $total_amount += $value->product_sale_price;
  //       }

  //       if ($CouponDetail) {
  //         $total_amount -= $CouponDetail->discount_amount;
  //       }
  //     }

  //     $order = new Order();
  //     $order->user_id = $user_id;
  //     $order->order_id = $orderId;
  //     $order->total_item = $orderDetail->count();
  //     $order->address_id = $address_id;
  //     $order->coupon_id = $coupon_id;
  //     $order->payment_mode = $request->payment_mode ?? "";
  //     $order->order_status = 1;
  //     $order->total_amount = $total_amount;
  //     $order->store_id = $store_id; // Save store_id
  //     $order->vendor_id = $vendor_id;
  //     $order->save();

  //     foreach ($orderDetail as $value) {
  //       $data = [
  //         'user_id' => $user_id,
  //         'order_id' => $order->id,
  //         'address_id' => $value->address_id,
  //         'product_id' => $value->product_id,
  //         'product_color' => $value->product_color,
  //         'product_size' => $value->product_size,
  //         'coupon_id' => $coupon_id,
  //         'quantity' => $value->quantity,
  //         'vendor_id' => $value->vendor_id // Add vendor_id here
  //       ];
  //       OrderItem::create($data);
  //     }

  //     CartItem::where('user_id', $user_id)->delete();

  //     if ($coupon_id) {
  //       Coupon::where('id', $coupon_id)->update(['status' => '1']);
  //     }

  //     $user = User::where('id', $user_id)->first();
  //     $notification = new Notifications([
  //       'title' => 'Order',
  //       'message' => 'Your order has been created successfully',
  //       'sender_id' => Admin::first()->id,
  //     ]);

  //     $FcmToken = User::select('device_token')->where('id', $user_id)->first()->device_token;
  //     $data = [
  //       "registration_ids" => array($FcmToken),
  //       "notification" => [
  //         "title" => 'Order created',
  //         "message" => 'Your order has been created successfully',
  //         "type" => 'verified',
  //         "sender_id" => Admin::first()->id,
  //       ],
  //       "data" => [
  //         "title" => 'Order created',
  //         'sender_id' => Admin::first()->id,
  //         'message' => 'Your order has been created successfully',
  //         'user_id' => $user_id,
  //         "type" => "verified"
  //       ]
  //     ];

  //     $this->sendNotification($data);
  //     $user->notifications()->save($notification);

  //     return response()->json(
  //       [
  //         'status' => true,
  //         'message' => 'Order Placed Successfully...!',
  //       ],
  //       201
  //     );
  //   } else {
  //     return response()->json(
  //       [
  //         'status' => false,
  //         'message' => 'Bad request...!',
  //       ],
  //       401
  //     );
  //   }
  // }

  // Working Checkout
  // public function Checkout(Request $request)
  // {
  //   $user_id = Auth::guard('sanctum')->user()->id;
  //   if ($user_id) {
  //     $orderDetail = CartItem::select(
  //       'cart_items.user_id',
  //       'cart_items.product_id',
  //       'cart_items.product_color',
  //       'cart_items.product_size',
  //       'cart_items.quantity',
  //       'cart_items.coupon_id',
  //       'cart_items.address_id',
  //       'cart_items.variant_id', // Assuming you have a variant_id in your cart_items table
  //       'products.id',
  //       'products.product_price',
  //       'products.product_sale_price',
  //       'products.store_id',
  //       'products.vendor_id'
  //     )
  //       ->leftJoin('products', 'products.id', '=', 'cart_items.product_id')
  //       ->where('user_id', $user_id)->get();

  //     $orderId = '';
  //     $coupon_id = 0;
  //     $address_id = 0;
  //     $lastOrder = Order::orderBy('id', 'desc')->first();
  //     if ($lastOrder) {
  //       $lastOrderId = $lastOrder->order_id;
  //       $orderId = str_pad($lastOrderId + 1, 4, '0', STR_PAD_LEFT);
  //     } else {
  //       $orderId = '0001';
  //     }

  //     $netAmount = 0;
  //     $total_amount = 0;
  //     $store_id = 0;
  //     $vendor_id = 0;

  //     foreach ($orderDetail as $value) {
  //       $prodcutDetail = Product::where('id', $value->product_id)->first();
  //       $CouponDetail = Coupon::where('id', $value->coupon_id)->first();
  //       $address_id = $value->address_id ?? 0;
  //       $coupon_id = $value->coupon_id ?? 0;
  //       $store_id = $value->store_id ?? 0;
  //       $vendor_id = $value->vendor_id ?? 0;

  //       // Fetch variant details if variant_id exists
  //       if ($value->variant_id) {
  //         $variantDetail = Variant::where('id', $value->variant_id)->first();
  //         if ($variantDetail) {
  //           $variant_price = $variantDetail->price * $value->quantity;
  //           $total_amount += $variant_price;
  //         }
  //       } else {
  //         if ($value->product_sale_price != null || $value->product_sale_price > 0) {
  //           $value->product_sale_price = $value->product_sale_price * $value->quantity;
  //           $total_amount += $value->product_sale_price;
  //         } else {
  //           $value->product_sale_price = $value->product_price * $value->quantity;
  //           $total_amount += $value->product_sale_price;
  //         }
  //       }

  //       if ($CouponDetail) {
  //         $total_amount -= $CouponDetail->discount_amount;
  //       }
  //     }

  //     $order = new Order();
  //     $order->user_id = $user_id;
  //     $order->order_id = $orderId;
  //     $order->total_item = $orderDetail->count();
  //     $order->address_id = $address_id;
  //     $order->coupon_id = $coupon_id;
  //     $order->payment_mode = $request->payment_mode ?? "";
  //     $order->order_status = 1;
  //     $order->total_amount = $total_amount;
  //     $order->store_id = $store_id;
  //     $order->vendor_id = $vendor_id;
  //     $order->save();

  //     foreach ($orderDetail as $value) {
  //       $data = [
  //         'user_id' => $user_id,
  //         'order_id' => $order->id,
  //         'address_id' => $value->address_id,
  //         'product_id' => $value->product_id,
  //         'product_color' => $value->product_color,
  //         'product_size' => $value->product_size,
  //         'variant_id' => $value->variant_id, // Save variant_id
  //         'coupon_id' => $coupon_id,
  //         'quantity' => $value->quantity,
  //         'vendor_id' => $value->vendor_id
  //       ];
  //       OrderItem::create($data);
  //     }

  //     CartItem::where('user_id', $user_id)->delete();

  //     if ($coupon_id) {
  //       Coupon::where('id', $coupon_id)->update(['status' => '1']);
  //     }

  //     $user = User::where('id', $user_id)->first();
  //     $notification = new Notifications([
  //       'title' => 'Order',
  //       'message' => 'Your order has been created successfully',
  //       'sender_id' => Admin::first()->id,
  //     ]);

  //     $FcmToken = User::select('device_token')->where('id', $user_id)->first()->device_token;
  //     $data = [
  //       "registration_ids" => array($FcmToken),
  //       "notification" => [
  //         "title" => 'Order created',
  //         "message" => 'Your order has been created successfully',
  //         "type" => 'verified',
  //         "sender_id" => Admin::first()->id,
  //       ],
  //       "data" => [
  //         "title" => 'Order created',
  //         'sender_id' => Admin::first()->id,
  //         'message' => 'Your order has been created successfully',
  //         'user_id' => $user_id,
  //         "type" => "verified"
  //       ]
  //     ];

  //     $this->sendNotification($data);
  //     $user->notifications()->save($notification);

  //     return response()->json(
  //       [
  //         'status' => true,
  //         'message' => 'Order Placed Successfully...!',
  //       ],
  //       201
  //     );
  //   } else {
  //     return response()->json(
  //       [
  //         'status' => false,
  //         'message' => 'Bad request...!',
  //       ],
  //       401
  //     );
  //   }
  // }

  // public function Checkout(Request $request)
  // {
  //   $user_id = Auth::guard('sanctum')->user()->id;
  //   if ($user_id) {
  //     $orderDetail = CartItem::select(
  //       'cart_items.user_id',
  //       'cart_items.product_id',
  //       'cart_items.product_color',
  //       'cart_items.product_size',
  //       'cart_items.quantity',
  //       'cart_items.coupon_id',
  //       'cart_items.address_id',
  //       'cart_items.variant_id', // Assuming you have a variant_id in your cart_items table
  //       'products.id',
  //       'products.product_price',
  //       'products.product_sale_price',
  //       'products.store_id',
  //       'products.vendor_id'
  //     )
  //       ->leftJoin('products', 'products.id', '=', 'cart_items.product_id')
  //       ->where('user_id', $user_id)->get();

  //     $orderId = '';
  //     $coupon_id = 0;
  //     $address_id = 0;
  //     $lastOrder = Order::orderBy('id', 'desc')->first();
  //     if ($lastOrder) {
  //       $lastOrderId = $lastOrder->order_id;
  //       $orderId = str_pad($lastOrderId + 1, 4, '0', STR_PAD_LEFT);
  //     } else {
  //       $orderId = '0001';
  //     }

  //     $netAmount = 0;
  //     $total_amount = 0;
  //     $store_id = 0;
  //     $vendor_id = 0;

  //     foreach ($orderDetail as $value) {
  //       $prodcutDetail = Product::where('id', $value->product_id)->first();
  //       $CouponDetail = Coupon::where('id', $value->coupon_id)->first();
  //       $address_id = $value->address_id ?? 0;
  //       $coupon_id = $value->coupon_id ?? 0;
  //       $store_id = $value->store_id ?? 0;
  //       $vendor_id = $value->vendor_id ?? 0;

  //       // Fetch variant details if variant_id exists
  //       if ($value->variant_id) {
  //         $variantDetail = Variant::where('id', $value->variant_id)->first();
  //         if ($variantDetail) {
  //           $variant_price = $variantDetail->price * $value->quantity;
  //           $total_amount += $variant_price;
  //         }
  //       } else {
  //         if ($value->product_sale_price != null || $value->product_sale_price > 0) {
  //           $value->product_sale_price = $value->product_sale_price * $value->quantity;
  //           $total_amount += $value->product_sale_price;
  //         } else {
  //           $value->product_sale_price = $value->product_price * $value->quantity;
  //           $total_amount += $value->product_sale_price;
  //         }
  //       }

  //       if ($CouponDetail) {
  //         $total_amount -= $CouponDetail->discount_amount;
  //       }
  //     }

  //     $order = new Order();
  //     $order->user_id = $user_id;
  //     $order->order_id = $orderId;
  //     $order->total_item = $orderDetail->count();
  //     $order->address_id = $address_id;
  //     $order->coupon_id = $coupon_id;
  //     $order->payment_mode = $request->payment_mode ?? "";
  //     $order->order_status = 1;
  //     $order->total_amount = $total_amount;
  //     $order->store_id = $store_id;
  //     $order->vendor_id = $vendor_id;
  //     $order->save();

  //     foreach ($orderDetail as $value) {
  //       $data = [
  //         'user_id' => $user_id,
  //         'order_id' => $order->id,
  //         'address_id' => $value->address_id,
  //         'product_id' => $value->product_id,
  //         'product_color' => $value->product_color,
  //         'product_size' => $value->product_size,
  //         'variant_id' => $value->variant_id, // Save variant_id
  //         'coupon_id' => $coupon_id,
  //         'quantity' => $value->quantity,
  //         'vendor_id' => $value->vendor_id
  //       ];
  //       OrderItem::create($data);
  //     }

  //     CartItem::where('user_id', $user_id)->delete();

  //     if ($coupon_id) {
  //       Coupon::where('id', $coupon_id)->update(['status' => '1']);
  //     }

  //     $user = User::where('id', $user_id)->first();
  //     $notification = new Notifications([
  //       'title' => 'Order',
  //       'message' => 'Your order has been created successfully',
  //       'sender_id' => Admin::first()->id,
  //     ]);

  //     $FcmToken = User::select('device_token')->where('id', $user_id)->first()->device_token;
  //     $data = [
  //       "registration_ids" => array($FcmToken),
  //       "notification" => [
  //         "title" => 'Order created',
  //         "message" => 'Your order has been created successfully',
  //         "type" => 'verified',
  //         "sender_id" => Admin::first()->id,
  //       ],
  //       "data" => [
  //         "title" => 'Order created',
  //         'sender_id' => Admin::first()->id,
  //         'message' => 'Your order has been created successfully',
  //         'user_id' => $user_id,
  //         "type" => "verified"
  //       ]
  //     ];

  //     $this->sendNotification($data);
  //     $user->notifications()->save($notification);

  //     return response()->json(
  //       [
  //         'status' => true,
  //         'message' => 'Order Placed Successfully...!',
  //       ],
  //       201
  //     );
  //   } else {
  //     return response()->json(
  //       [
  //         'status' => false,
  //         'message' => 'Bad request...!',
  //       ],
  //       401
  //     );
  //   }
  // }

  public function Checkout(Request $request)
  {
    // Validate the user_id and payment_mode parameters
    $validator = Validator::make($request->all(), [
      'user_id' => 'required|exists:users,id',
      'payment_mode' => 'required|string',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => false,
        'message' => 'Validation failed',
        'errors' => $validator->errors(),
      ], 400);
    }

    $user_id = $request->user_id;

    $orderDetail = CartItem::select(
      'cart_items.user_id',
      'cart_items.product_id',
      'cart_items.product_color',
      'cart_items.product_size',
      'cart_items.quantity',
      'cart_items.coupon_id',
      'cart_items.address_id',
      'cart_items.variant_id',
      'products.id',
      'products.product_price',
      'products.product_sale_price',
      'products.store_id',
      'products.vendor_id'
    )
      ->leftJoin('products', 'products.id', '=', 'cart_items.product_id')
      ->where('user_id', $user_id)
      ->get();

    $orderId = '';
    $coupon_id = 0;
    $address_id = 0;
    $lastOrder = Order::orderBy('id', 'desc')->first();
    if ($lastOrder) {
      $lastOrderId = $lastOrder->order_id;
      $orderId = str_pad($lastOrderId + 1, 4, '0', STR_PAD_LEFT);
    } else {
      $orderId = '0001';
    }

    $total_amount = 0;
    $store_id = 0;
    $vendor_id = 0;

    foreach ($orderDetail as $value) {
      $prodcutDetail = Product::where('id', $value->product_id)->first();
      $address_id = $value->address_id ?? 0;
      $coupon_id = $value->coupon_id ?? 0;
      $store_id = $value->store_id ?? 0;
      $vendor_id = $value->vendor_id ?? 0;

      // Fetch variant details if variant_id exists
      if ($value->variant_id) {
        $variantDetail = Variant::where('id', $value->variant_id)->first();
        if ($variantDetail) {
          $variant_price = $variantDetail->price * $value->quantity;
          $total_amount += $variant_price;
        }
      } else {
        if ($value->product_sale_price != null || $value->product_sale_price > 0) {
          $value->product_sale_price = $value->product_sale_price * $value->quantity;
          $total_amount += $value->product_sale_price;
        } else {
          $value->product_sale_price = $value->product_price * $value->quantity;
          $total_amount += $value->product_sale_price;
        }
      }
    }

    // Apply coupon discount to the total amount
    if ($coupon_id) {
      $CouponDetail = Coupon::where('id', $coupon_id)->first();
      if ($CouponDetail) {
        $total_amount -= $CouponDetail->discount_amount;
      }
    }

    $order = new Order();
    $order->user_id = $user_id;
    $order->order_id = $orderId;
    $order->total_item = $orderDetail->count();
    $order->address_id = $address_id;
    $order->coupon_id = $coupon_id;
    $order->payment_mode = $request->payment_mode ?? '';
    $order->order_status = 0;
    $order->total_amount = $total_amount;
    $order->store_id = $store_id;
    $order->vendor_id = $vendor_id;
    $order->save();

    foreach ($orderDetail as $value) {
      $price = 0;

      if ($value->variant_id) {
        $variantDetail = Variant::where('id', $value->variant_id)->first();
        if ($variantDetail) {
          $price = $variantDetail->price;
        }
      } else {
        if ($prodcutDetail->product_sale_price != null || $prodcutDetail->product_sale_price > 0) {
          $price = $prodcutDetail->product_sale_price;
        } else {
          $price = $prodcutDetail->product_price;
        }
      }

      $data = [
        'user_id' => $user_id,
        'order_id' => $order->id,
        'address_id' => $value->address_id,
        'product_id' => $value->product_id,
        'product_color' => $value->product_color,
        'product_size' => $value->product_size,
        'variant_id' => $value->variant_id,
        'coupon_id' => $coupon_id,
        'quantity' => $value->quantity,
        'vendor_id' => $value->vendor_id,
        'price' => $price,
      ];
      OrderItem::create($data);
    }

    CartItem::where('user_id', $user_id)->delete();

    if ($coupon_id) {
      Coupon::where('id', $coupon_id)->update(['status' => '1']);
    }

    $user = User::where('id', $user_id)->first();
    $notification = new Notifications([
      'title' => 'Order',
      'message' => 'Your order has been created successfully',
      'sender_id' => Admin::first()->id,
    ]);

    $FcmToken = User::select('device_token')
      ->where('id', $user_id)
      ->first()->device_token;
    $data = [
      'registration_ids' => [$FcmToken],
      'notification' => [
        'title' => 'Order created',
        'message' => 'Your order has been created successfully',
        'type' => 'verified',
        'sender_id' => Admin::first()->id,
      ],
      'data' => [
        'title' => 'Order created',
        'sender_id' => Admin::first()->id,
        'message' => 'Your order has been created successfully',
        'user_id' => $user_id,
        'type' => 'verified',
      ],
    ];

    $this->sendNotification($data);
    $user->notifications()->save($notification);

    return response()->json([
      'status' => true,
      'message' => 'Order Placed Successfully...!',
    ], 201);
  }

  // Points To Amount Convert
  public function PointsToAmount(Request $request)
  {
    $user = Auth::guard('sanctum')->user();
    $loyaltyPoints = $user->loyalty_points;
    $amount = $request->input('amount'); // Assuming amount is passed in the request

    // Check if the user has enough loyalty points for the conversion
    $conversionRate = 10; // 10 points = $1.00
    $loyaltyPointsNeeded = $amount * $conversionRate;

    if ($loyaltyPoints < $loyaltyPointsNeeded) {
      return response()->json(
        [
          'message' => 'Insufficient loyalty points.',
        ],
        400
      );
    }

    // Convert loyalty points to wallet balance
    $walletTransaction = new PointsTransaction(); // Assuming WalletTransaction model exists
    $walletTransaction->user_id = $user->id;
    $walletTransaction->amount = $amount;
    $walletTransaction->save();

    // Deduct loyalty points from the user's account and update wallet balance
    $user->loyalty_points -= $loyaltyPointsNeeded;
    $user->wallet_balance += $amount; // Add converted amount to wallet balance
    $user->save();

    return response()->json([
      'message' => 'Points converted to wallet balance successfully.',
      'data' => $walletTransaction,
    ]);
  }

  // showLoyaltyPoints
  public function showLoyaltyPoints()
  {
    // Get the authenticated user's ID
    $user_id = auth('sanctum')->user()->id;

    // Fetch the user's wallet balance from the database
    $user = User::findOrFail($user_id);
    $loyaltypoints = $user->loyalty_points ?? 0;

    // Return the user ID and wallet balance as JSON
    return response()->json([
      'user_id' => $user_id,
      'loyalty_points' => $loyaltypoints,
    ]);
  }

  // Points Data
  public function PointsData()
  {
    $user_id = Auth::guard('sanctum')->user()->id;
    $points = PointsTransaction::where('user_id', $user_id)->get();

    if ($points->isEmpty()) {
      return response()->json(
        [
          'message' => 'No points transaction data found for this user.',
        ],
        404
      );
    }

    return response()->json(
      [
        'points' => $points,
        'message' => 'Points data retrieved successfully.',
      ],
      200
    );
  }

  public function searchProduct(Request $request)
  {
    if ($request->product_name != '') {
      $product = Product::select(
        'products.id',
        'category_id',
        'sub_category_id',
        'product_name',
        'store_id',
        'product_sale_price',
        'product_price',
        DB::raw('GROUP_CONCAT(DISTINCT variants.color) as product_color'),
        DB::raw('GROUP_CONCAT(DISTINCT variants.size) as product_size')
      )
        ->where('product_name', 'LIKE', "%$request->product_name%")
        ->leftJoin('variants', 'products.id', '=', 'variants.product_id')
        ->groupBy(
          'products.id',
          'category_id',
          'sub_category_id',
          'product_name',
          'store_id',
          'product_sale_price',
          'product_price'
        )
        ->get();

      foreach ($product as $value) {
        $value->product_sale_price =
          $value->product_sale_price && $value->product_sale_price > 0
          ? $value->product_sale_price
          : $value->product_price;

        $subCategory = SubCategory::select('sub_category_name')
          ->where('category_id', $value->category_id)
          ->where('id', $value->sub_category_id)
          ->first();

        $totalProductImages = [];
        $productImages = ProductImages::select('product_image')
          ->where('product_id', $value->id)
          ->get();

        foreach ($productImages as $val) {
          $totalProductImages[] = $val->product_image
            ? url('/assets/images/product_images/' . $val->product_image)
            : [];
        }
        $value->product_image = $totalProductImages;

        $totalProductReview = ProductReview::select('product_id', 'review_star')
          ->where('product_id', $value->id)
          ->get();

        $totalReviewStar = $totalProductReview->sum('review_star');
        $totalReviewCount = $totalProductReview->count();

        $value->totalReviewCount = $totalReviewCount ? (string) $totalReviewCount : '';
        $value->totalAvgReview = $totalReviewCount > 0 ? round($totalReviewStar / $totalReviewCount, 1) : 0;

        $value->subCategoryName = $subCategory->sub_category_name ?? '';

        $productUserLike = ProductLike::where('user_id', $request->user_id)
          ->where('product_id', $value->id)
          ->first();

        $value->is_Like = $productUserLike ? 1 : 0;

        // Handle product colors and sizes
        $value->product_color = $value->product_color ? explode(',', $value->product_color) : [];
        $value->product_size = $value->product_size ? explode(',', $value->product_size) : [];
      }

      return response()->json([
        'message' => 'product found',
        'product' => $product,
      ]);
    } else {
      return response()->json([
        'message' => 'Product not found',
      ]);
    }
  }

  //  Get order List


  public function getOrder()
  {
    $user_id = Auth::guard('sanctum')->user()->id;

    // Fetch distinct orders with required details
    $mainOrderData = Order::select(
      'orders.id as order_id', // Alias 'id' as 'order_id'
      'orders.order_status',
      'orders.store_id',
      'orders.order_id',
      'orders.created_at AS order_date',
      DB::raw("IFNULL(vendor_stores.store_name, '') AS store_name"),
      DB::raw("IFNULL(vendor_stores.store_address, '') AS store_address"),
      'vendor_stores.store_logo',
      'orders.payment_mode',
      'orders.total_amount',
      'orders.coupon_id',
      'orders.address_id',
      DB::raw("IFNULL(user_address.address, '') AS user_address"),
      DB::raw("IFNULL(user_address.locality, '') AS locality"),
      DB::raw("IFNULL(user_address.city, '') AS user_city"),
      DB::raw("IFNULL(user_address.state, '') AS user_state"),
      DB::raw("IFNULL(user_address.pincode, '') AS user_zip_code")
    )
      ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
      ->leftJoin('vendor_stores', 'orders.store_id', '=', 'vendor_stores.id')
      ->leftJoin('user_address', 'orders.address_id', '=', 'user_address.id')
      ->where('order_items.user_id', $user_id)
      ->orderBy('orders.id', 'desc')
      ->groupBy(
        'orders.id',
        'orders.order_status',
        'orders.store_id',
        'orders.order_id',
        'orders.created_at',
        'vendor_stores.store_name',
        'vendor_stores.store_address',
        'vendor_stores.store_logo',
        'orders.payment_mode',
        'orders.total_amount',
        'orders.coupon_id',
        'orders.address_id',
        'user_address.address',
        'user_address.locality',
        'user_address.city',
        'user_address.state',
        'user_address.pincode'
      )

      ->get();

    // Transform each order
    $mainOrderData->transform(function ($order) use ($user_id) {
      $order->order_date = Carbon::parse($order->order_date)->format('l, j M g:i A');

      // Fetch store details based on store_id
      $store = VendorStore::select('store_name', 'store_address', 'store_logo')
        ->where('id', $order->store_id)
        ->first();

      if ($store) {
        $order->store_name = $store->store_name;
        $order->store_address = $store->store_address;
        $order->store_logo = $store->store_logo ? url('assets/images/store_logo/' . $store->store_logo) : '';
      } else {
        $order->store_logo = '';
      }

      // Fetch order items with product details
      $orderItems = OrderItem::with(['product', 'productImages'])
        ->where('order_items.user_id', $user_id)
        ->where('order_items.order_id', $order->order_id) // Use 'order_id' here
        ->get()
        ->transform(function ($item) use ($user_id) {
          // Adjustments for item status, defaults, and variant price handling
          $item->is_status = (int) $item->is_status;
          $item->product_color = $item->product_color ?? '';
          $item->product_size = $item->product_size ?? '';
          $item->coupon_id = $item->coupon_id ?? 0;
          $item->payment_id = $item->payment_id ?? '';

          if ($item->variant_id) {
            $variant = Variant::find($item->variant_id);
            if ($variant) {
              $item->product_price = $variant->price;
              $item->product_sale_price = $variant->price;
            }
          } else {
            $product = Product::find($item->product_id);
            if ($product) {
              $item->product_price = $product->product_price;
              $item->product_sale_price = $product->product_sale_price;
            }
          }

          // Calculate sale price based on quantity
          if ($item->product_sale_price != null && $item->product_sale_price > 0) {
            $item->product_sale_price = $item->product_sale_price * $item->quantity;
          } else {
            $item->product_sale_price = $item->product_price * $item->quantity;
          }

          // Fetch product images
          $item->productImage = $item->productImages->map(function ($image) {
            return url('assets/images/product_images/' . $image->product_image);
          });

          // Format delivery date
          $item->delivery_date = Carbon::parse($item->delivery_date)->format('l, j M');

          // Fetch user's ratings for the product
          $ratings = ProductReview::select('review_star')
            ->where('user_id', $user_id)
            ->where('product_id', $item->product_id)
            ->first();
          $item->ratings = $ratings ? $ratings->review_star : null;

          return [
            'product_name' => $item->product->product_name,
            'product_price' => (string) $item->product_price,
            'product_sale_price' => (float) $item->product_sale_price,
            'id' => $item->id,
            'user_id' => $item->user_id,
            'product_id' => $item->product_id,
            'coupon_id' => $item->coupon_id,
            'payment_id' => $item->payment_id,
            'product_color' => $item->product_color,
            'product_size' => $item->product_size,
            'delivery_date' => $item->delivery_date,
            'is_status' => $item->is_status,
            'quantity' => (string) $item->quantity,
            'variant_id' => $item->variant_id,
            'discount_amount' => (float) $item->discount_amount,
            'productImage' => $item->productImage->toArray(),
            'ratings' => $item->ratings,
          ];
        });

      // Calculate totals for the order
      $totalAmount = $order->total_amount;

      // Fetch discount amount from coupon table
      $totalDiscountAmount = 0;
      if ($order->coupon_id) {
        $coupon = Coupon::find($order->coupon_id);
        if ($coupon) {
          $totalDiscountAmount = $coupon->discount_amount;
        }
      }

      $order->itemsList = $orderItems->toArray();
      $order->totalProductPrice = $orderItems->sum(function ($item) {
        return (float) $item['product_price'] * (int) $item['quantity'];
      });
      $order->totalSaleProductPrice = $orderItems->sum(function ($item) {
        return (float) $item['product_sale_price'];
      });
      $order->totalAmount = (float) $totalAmount;
      $order->totalDiscountAmount = (float) $totalDiscountAmount;

      return $order;
    });

    $totalOrders = $mainOrderData->count();

    return response()->json(
      [
        'status' => 'success',
        'message' => 'Order List Found...!',
        'data' => $mainOrderData->toArray(),
        'totalOrders' => $totalOrders,
      ],
      200
    );
  }

  // public function getOrder()
  // {
  //   $user_id = Auth::guard('sanctum')->user()->id;

  //   // Fetch distinct orders with required details
  //   $mainOrderData = Order::select(
  //     'orders.id as order_id', // Alias 'id' as 'order_id'
  //     'orders.order_status',
  //     'orders.store_id',
  //     'orders.order_id',
  //     'orders.created_at AS order_date',
  //     DB::raw("IFNULL(vendor_stores.store_name, '') AS store_name"),
  //     DB::raw("IFNULL(vendor_stores.store_address, '') AS store_address"),
  //     'vendor_stores.store_logo',
  //     'orders.payment_mode',
  //     'orders.total_amount',
  //     'orders.coupon_id',
  //     'orders.address_id',
  //     DB::raw("IFNULL(user_address.address, '') AS user_address"),
  //     DB::raw("IFNULL(user_address.locality, '') AS locality"),
  //     DB::raw("IFNULL(user_address.city, '') AS user_city"),
  //     DB::raw("IFNULL(user_address.state, '') AS user_state"),
  //     DB::raw("IFNULL(user_address.pincode, '') AS user_zip_code")
  //   )
  //     ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
  //     ->leftJoin('vendor_stores', 'orders.store_id', '=', 'vendor_stores.id')
  //     ->leftJoin('user_address', 'orders.address_id', '=', 'user_address.id')
  //     ->where('order_items.user_id', $user_id)
  //     ->orderBy('orders.id', 'desc')
  //     ->groupBy(
  //       'orders.id',
  //       'orders.order_status',
  //       'orders.store_id',
  //       'orders.order_id',
  //       'orders.created_at',
  //       'vendor_stores.store_name',
  //       'vendor_stores.store_address',
  //       'vendor_stores.store_logo',
  //       'orders.payment_mode',
  //       'orders.total_amount',
  //       'orders.coupon_id',
  //       'orders.address_id',
  //       'user_address.address',
  //       'user_address.locality',
  //       'user_address.city',
  //       'user_address.state',
  //       'user_address.pincode'
  //     )
  //     ->get();

  //   // Transform each order
  //   $mainOrderData->transform(function ($order) use ($user_id) {
  //     $order->order_date = Carbon::parse($order->order_date)->format('l, j M g:i A');

  //     // Fetch store details based on store_id
  //     $store = VendorStore::select('store_name', 'store_address', 'store_logo')
  //       ->where('id', $order->store_id)
  //       ->first();

  //     if ($store) {
  //       $order->store_name = $store->store_name;
  //       $order->store_address = $store->store_address;
  //       $order->store_logo = $store->store_logo ? url('assets/images/store_logo/' . $store->store_logo) : '';
  //     } else {
  //       $order->store_logo = '';
  //     }

  //     // Fetch order items with product details
  //     $orderItems = OrderItem::with(['product', 'productImages'])
  //       ->where('order_items.user_id', $user_id)
  //       ->where('order_items.order_id', $order->order_id) // Use 'order_id' here
  //       ->get()
  //       ->transform(function ($item) use ($user_id) {
  //         // Adjustments for item status, defaults, and variant price handling
  //         $item->is_status = (int) $item->is_status;
  //         $item->product_color = $item->product_color ?? '';
  //         $item->product_size = $item->product_size ?? '';
  //         $item->coupon_id = $item->coupon_id ?? 0;
  //         $item->payment_id = $item->payment_id ?? '';

  //         if ($item->variant_id) {
  //           $variant = Variant::find($item->variant_id);
  //           if ($variant) {
  //             $item->product_price = $variant->price;
  //             $item->product_sale_price = $variant->price;
  //           }
  //         } else {
  //           $product = Product::find($item->product_id);
  //           if ($product) {
  //             $item->product_price = $product->product_price;
  //             $item->product_sale_price = $product->product_sale_price;
  //           }
  //         }

  //         // Calculate sale price based on quantity
  //         if ($item->product_sale_price != null && $item->product_sale_price > 0) {
  //           $item->product_sale_price = $item->product_sale_price * $item->quantity;
  //         } else {
  //           $item->product_sale_price = $item->product_price * $item->quantity;
  //         }

  //         // Fetch product images
  //         $item->productImage = $item->productImages->map(function ($image) {
  //           return url('assets/images/product_images/' . $image->product_image);
  //         });

  //         // Format delivery date
  //         $item->delivery_date = Carbon::parse($item->delivery_date)->format('l, j M');

  //         // Fetch user's ratings for the product
  //         $ratings = ProductReview::select('review_star')
  //           ->where('user_id', $user_id)
  //           ->where('product_id', $item->product_id)
  //           ->first();
  //         $item->ratings = $ratings ? $ratings->review_star : null;

  //         return [
  //           'product_name' => $item->product->product_name,
  //           'product_price' => (string) $item->product_price,
  //           'product_sale_price' => (float) $item->product_sale_price,
  //           'id' => $item->id,
  //           'user_id' => $item->user_id,
  //           'product_id' => $item->product_id,
  //           'coupon_id' => $item->coupon_id,
  //           'payment_id' => $item->payment_id,
  //           'product_color' => $item->product_color,
  //           'product_size' => $item->product_size,
  //           'delivery_date' => $item->delivery_date,
  //           'is_status' => $item->is_status,
  //           'quantity' => (string) $item->quantity,
  //           'variant_id' => $item->variant_id,
  //           'discount_amount' => (float) $item->discount_amount,
  //           'productImage' => $item->productImage->toArray(),
  //           'ratings' => $item->ratings,
  //         ];
  //       });

  //     // Calculate totals for the order
  //     $totalAmount = $order->total_amount;

  //     // Fetch discount amount from coupon table
  //     $totalDiscountAmount = 0;
  //     if ($order->coupon_id) {
  //       $coupon = Coupon::find($order->coupon_id);
  //       if ($coupon) {
  //         $totalDiscountAmount = $coupon->discount_amount;
  //       }
  //     }

  //     $order->itemsList = $orderItems->toArray();
  //     $order->totalProductPrice = $orderItems->sum(function ($item) {
  //       return (float) $item['product_price'] * (int) $item['quantity'];
  //     });
  //     $order->totalSaleProductPrice = $orderItems->sum(function ($item) {
  //       return (float) $item['product_sale_price'];
  //     });
  //     $order->totalAmount = (float) $totalAmount;
  //     $order->totalDiscountAmount = (float) $totalDiscountAmount;

  //     return $order;
  //   });

  //   $totalOrders = $mainOrderData->count();

  //   return response()->json(
  //     [
  //       'status' => 'success',
  //       'message' => 'Order List Found...!',
  //       'data' => $mainOrderData->toArray(),
  //       'totalOrders' => $totalOrders,
  //     ],
  //     200
  //   );
  // }


  // Order Detail
  public function orderDetail(Request $request)
  {
    $user_id = Auth::guard('sanctum')->user()->id;
    $product_id = $request->product_id;

    // Fetch the latest order item for mainData
    $query = OrderItem::with('productImages')
      ->select(
        'order_items.id as order_items_id',
        'order_items.order_id as orderID',
        'order_items.product_id',
        'order_items.delivery_date',
        'order_items.created_at',
        'order_items.user_id',
        'order_items.coupon_id',
        'order_items.payment_mode',
        'order_items.is_status',
        'order_items.address_id',
        'order_items.product_size',
        'order_items.product_color',
        'order_items.quantity',
        'orders.id',
        'orders.order_id',
        'products.id',
        'products.product_name',
        'products.product_price',
        'products.product_sale_price'
      )
      ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
      ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
      ->where('order_items.user_id', $user_id);

    // Check if product_id is provided
    if ($product_id) {
      $query->where('order_items.product_id', $product_id);
    } else {
      $query->where('order_items.order_id', $request->order_id);
    }

    $mainData = $query->latest()->first();

    if (!$mainData) {
      return response()->json(
        [
          'message' => 'Order not found ...!',
        ],
        200
      );
    }

    // Fetch all other order items for otherData
    $otherData = OrderItem::with('productImages')
      ->select(
        'order_items.id as order_items_id',
        'order_items.order_id as main_order_id',
        'order_items.product_id',
        'order_items.delivery_date',
        'order_items.created_at',
        'order_items.user_id',
        'order_items.coupon_id',
        'order_items.payment_mode',
        'order_items.is_status',
        'order_items.address_id',
        'order_items.product_size',
        'order_items.product_color',
        'order_items.quantity',
        'orders.id',
        'orders.order_id',
        'products.id',
        'products.product_name',
        'products.product_price',
        'products.product_sale_price'
      )
      ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
      ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
      ->where('order_items.order_id', $mainData->orderID)
      ->where('order_items.id', '!=', $mainData->order_items_id) // Exclude the mainData
      ->get();

    // Process mainData
    $bagTotalData = 0;
    $couponPrice = 0;
    if ($mainData->address_id == null) {
      $mainData->address_id = 0;
    }
    $productImage = [];
    foreach ($mainData->productImages as $value) {
      $productImage[] = url('/assets/images/product_images/' . $value->product_image);
    }
    $mainData->is_status = $mainData->is_status ? (int) $mainData->is_status : 0;
    $mainData->delivery_date = Carbon::parse($mainData->delivery_date)->format('l, j M');
    $createdAts = Carbon::parse($mainData->created_at)->format('l, j M');
    $mainData->createdAts = $createdAts;
    $mainData->productImage = $productImage;

    if ($mainData->product_sale_price != null || $mainData->product_sale_price > 0) {
      $mainData->product_sale_price = $mainData->product_sale_price * $mainData->quantity;
      $bagTotalData += $mainData->product_sale_price;
    } else {
      $mainData->product_sale_price = $mainData->product_price * $mainData->quantity;
      $bagTotalData += $mainData->product_sale_price;
    }
    if ($mainData->coupon_id) {
      $couponPrice = Coupon::where('id', $mainData->coupon_id)->first()->discount_amount ?? 0;
    }

    if ($mainData->payment_mode) {
      $mainData->payment_mode = $mainData->payment_mode;
    } else {
      $mainData->payment_mode = '';
    }

    $ratings = ProductReview::select('review_star')
      ->where('user_id', $user_id)
      ->where('product_id', $mainData->product_id)
      ->first();
    $mainData->ratings = $ratings->review_star ?? '';

    $addresss = $mainData->address_id
      ? UserAddress::select(
        'id',
        'first_name',
        'last_name',
        'mobile',
        'pincode',
        'address',
        'locality',
        'city',
        'state',
        'type'
      )
      ->where('id', $mainData->address_id)
      ->where('address_flag', 'Y')
      ->first()
      : null;

    // Process otherData
    foreach ($otherData as $value) {
      if ($value->address_id == null) {
        $value->address_id = 0;
      }
      $productImage = [];
      foreach ($value->productImages as $val) {
        $productImage[] = url('/assets/images/product_images/' . $val->product_image);
      }
      $value->is_status = $value->is_status ? (int) $value->is_status : 0;
      $value->delivery_date = Carbon::parse($value->delivery_date)->format('l, j M');
      $createdAts = Carbon::parse($value->created_at)->format('l, j M');
      $value->createdAts = $createdAts;
      $value->productImage = $productImage;

      if ($value->product_sale_price != null || $value->product_sale_price > 0) {
        $bagTotalData += $value->product_sale_price * $value->quantity;
      } else {
        $bagTotalData += $value->product_price * $value->quantity;
      }
      if ($value->coupon_id) {
        $couponPrice += Coupon::where('id', $value->coupon_id)->first()->discount_amount ?? 0;
      }
      if ($value->payment_mode) {
        $value->payment_mode = $value->payment_mode;
      } else {
        $value->payment_mode = '';
      }
      $ratings = ProductReview::select('review_star')
        ->where('user_id', $user_id)
        ->where('product_id', $value->product_id)
        ->first();
      $value->ratings = $ratings->review_star ?? '';
      $address = $value->address_id
        ? UserAddress::select(
          'id',
          'first_name',
          'last_name',
          'mobile',
          'pincode',
          'address',
          'locality',
          'city',
          'state',
          'type'
        )
        ->where('id', $value->address_id)
        ->first()
        : null;
    }
    // Calculate total bag total
    $totalBagTotal = $bagTotalData;

    // Construct order detail response
    $orderDetail = [
      'bagTotal' => $bagTotalData,
      'couponPrice' => $couponPrice,
      'totalAmount' => $totalBagTotal - $couponPrice,
    ];

    return response()->json(
      [
        'status' => 'true',
        'message' => 'Order Detail ...!',
        'mainData' => $mainData,
        'otherData' => $otherData,
        'orderDetail' => $orderDetail,
        'address' => $addresss,
      ],
      200
    );
  }

  // ReviewPost
  public function reviewPost(Request $request)
  {
    $user_id = Auth::guard('sanctum')->user()->id;
    // dd($user_id);
    if (
      OrderItem::where('user_id', $user_id)
      ->where('product_id', $request->product_id)
      ->where('is_status', 2)
      ->exists()
    ) {
      if (
        ProductReview::where('user_id', $user_id)
        ->where('product_id', $request->product_id)
        ->exists()
      ) {
        // dd($request->review_star);
        $data = [
          'user_id' => $user_id,
          'product_id' => $request->product_id,
          'review_star' => $request->review_star ?? '',
          'review_message' => $request->review_message ?? '',
        ];

        // dd($data);
        ProductReview::where('user_id', $user_id)
          ->where('product_id', $request->product_id)
          ->update($data);
        return response()->json([
          'success' => true,
          'message' => 'Your review added successfully',
        ]);
      } else {
        $data = [
          'user_id' => $user_id,
          'product_id' => $request->product_id,
          'review_star' => $request->review_star ?? '',
          'review_message' => $request->review_message ?? '',
        ];
        // $user = [
        //   'first_name' => $request->first_name,
        //   'last_name' => $request->last_name,
        // ];

        ProductReview::create($data);
        // User::where('id', $user_id)->update($user);
        return response()->json([
          'success' => true,
          'message' => 'Your review added successfully',
        ]);
      }
    } else {
      return response()->json([
        'success' => false,
        'message' => 'You are not authorised',
      ]);
    }
  }

  // User Feedback
  public function userFeedback(Request $request)
  {
    $user_id = Auth::guard('sanctum')->user()->id;
    if (OrderItem::where('user_id', $user_id)->exists()) {
      $data = [
        'user_id' => $user_id,
        'review_star' => $request->review_star,
        'review_message' => $request->review_message,
      ];
      UserFeedback::create($data);
      return response()->json([
        'success' => true,
        'message' => 'Your feedback added successfully',
      ]);
    }
  }

  // Get Support List
  public function getSupport(Request $request)
  {
    $user_id = Auth::guard('sanctum')->user()->id;
    if ($request->order_id) {
      $data = CreateTicket::where('create_tickets.user_id', $user_id)
        ->where('order_id', 'LIKE', "%$request->order_id%")
        ->orderBy('id', 'desc')
        ->get();
    } else {
      $data = CreateTicket::where('create_tickets.user_id', $user_id)
        ->orderBy('id', 'desc')
        ->get();
    }

    // Transform data to replace null with empty strings
    $data = $data->map(function ($ticket) {
      $ticket->subject = $ticket->subject ?? '';
      $ticket->message = $ticket->message ?? '';
      $ticket->image = $ticket->image ?? '';
      $ticket->created_at = $ticket->created_at ?? '';
      $ticket->updated_at = $ticket->updated_at ?? '';
      // Add more fields as needed
      return $ticket;
    });

    return response([
      'success' => true,
      'message' => 'Chat List Found...!',
      'data' => $data,
    ]);
  }

  // Add Support List
  public function addSupport(Request $request)
  {
    $user_id = Auth::guard('sanctum')->user()->id;

    // Validate the request data
    $validator = Validator::make($request->all(), [
      'order_id' => 'required|exists:orders,order_id', // Ensure order exists in the orders table
      'subject' => 'required',
      'message' => 'required',
      'image' => 'image',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      $errorMessage = $errors->first('order_id', 'The selected order id is invalid.');
      return response()->json(
        [
          'success' => false,
          'message' => $errorMessage,
        ],
        422
      );
    }

    // Check if the order exists in the orders table
    $order = Order::find($request->order_id);
    if (!$order) {
      return response()->json(
        [
          'success' => false,
          'message' => 'Order not found.',
        ],
        404
      );
    }

    // Check if the ticket already exists and has status 1 in the create_tickets table
    $existingTicket = CreateTicket::where('order_id', $request->order_id)->first();
    if ($existingTicket && $existingTicket->status == 1) {
      return response()->json(
        [
          'success' => false,
          'message' => 'Support ticket cannot be created for this order.',
        ],
        403
      );
    }

    // Handle the image upload
    $fileName = '';
    if ($request->hasFile('image')) {
      $file = $request->file('image');
      $fileName = time() . '.' . $file->getClientOriginalExtension();
      try {
        $file->move(public_path('assets/images/support_images'), $fileName);
      } catch (\Exception $e) {
        return response()->json(
          [
            'success' => false,
            'message' => 'File upload error: ' . $e->getMessage(),
          ],
          500
        );
      }
    }

    // Create a new support ticket
    $ticket = new CreateTicket();
    $ticket->user_id = $user_id;
    $ticket->order_id = $request->order_id;
    $ticket->subject = $request->subject;
    $ticket->message = $request->message;
    $ticket->image = $fileName;
    $ticket->save();

    return response()->json([
      'success' => true,
      'message' => 'Support ticket created successfully!',
      'data' => $ticket,
    ]);
  }

  // Post Chat User
  public function postChatUser(Request $request)
  {
    $user_id = Auth::guard('sanctum')->user()->id;
    $validator = Validator::make($request->all(), [
      'message' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'The message field is required.',
      ]);
    }

    if ($request->file('image')) {
      $fileName = time() . '.' . $request->image->getClientOriginalName();
      $request->image->move(public_path() . '/assets/images/support_images/', $fileName);
    }

    $data = new TicketChat();
    $data->ticket_id = $request->ticket_id ?? '';
    $data->message = $request->message ?? '';
    $data->image = $fileName ?? '';
    // $data->order_id = $request->order_id;
    $data->save();
    return response()->json([
      'success' => true,
      'message' => 'Success',
    ]);
  }

  // Chat User
  public function chatUser(Request $request)
  {
    $user = Auth::guard('sanctum')->user();

    if ($user) {
      $ticketId = $request->input('ticket_id');

      // Fetch the chat list for the given ticket_id and authenticated user
      $chatList = TicketChat::whereHas('ticket', function ($query) use ($ticketId, $user) {
        $query->where('id', $ticketId)->where('user_id', $user->id);
      })
        ->with([
          'ticket' => function ($query) {
            $query->select('id', 'order_id', 'user_id', 'status');
          },
          'ticket.user:id,first_name,last_name',
        ])
        ->get();

      if ($chatList->isEmpty()) {
        return response()->json(
          [
            'success' => false,
            'message' => 'No chats found for the specified ticket or you are not authorized.',
          ],
          404
        );
      }

      // Get the order_id and status from the first chat's ticket
      $firstChat = $chatList->first();
      $order_id = $firstChat->ticket->order_id;
      $status = $firstChat->ticket->status;

      // Format the chat list
      $formattedChatList = $chatList->map(function ($chat) {
        $chatData = [
          'id' => $chat->id,
          'ticket_id' => $chat->ticket_id,
          'admin_message' => $chat->admin_message,
          'message' => $chat->message,
          'created_at' => $chat->created_at,
          'updated_at' => $chat->updated_at,
          'first_name' => $chat->ticket->user->first_name,
          'last_name' => $chat->ticket->user->last_name,
          'user_id' => $chat->ticket->user_id,
        ];

        // Check if image exists
        if ($chat->image) {
          $chatData['image'] = url('assets/images/support_images/' . $chat->image);
        } else {
          $chatData['image'] = '';
        }

        return $chatData;
      });

      return response()->json(
        [
          'success' => true,
          'order_id' => $order_id,
          'status' => $status,
          'data' => $formattedChatList,
        ],
        200
      );
    } else {
      return response()->json(
        [
          'success' => false,
          'message' => 'Unauthorized',
        ],
        401
      );
    }
  }

  // Show Wallet Balance
  public function showWalletBalance()
  {
    // Get the authenticated user's ID
    $user_id = auth('sanctum')->user()->id;

    // Fetch the user's wallet balance from the database
    $user = User::findOrFail($user_id);
    $walletBalance = $user->wallet_balance ?? 0;

    // Return the user ID and wallet balance as JSON
    return response()->json([
      'user_id' => $user_id,
      'wallet_balance' => $walletBalance,
    ]);
  }

  // Wallet Data
  public function WalletData()
  {
    $user_id = Auth::guard('sanctum')->user()->id;
    $wallets = Wallet::where('user_id', $user_id)->get();

    if ($wallets->isEmpty()) {
      return response()->json(
        [
          'message' => 'No wallet data found for this user.',
        ],
        404
      );
    }

    return response()->json(
      [
        'wallets' => $wallets,
        'message' => 'Wallet data retrieved successfully.',
      ],
      200
    );
  }

  // Wallet Success
  public function WalletSuccess(Request $request)
  {
    // Check if the user is authenticated
    $user = Auth::guard('sanctum')->user();
    if (!$user) {
      return response()->json(['error' => 'authentication_error', 'message' => 'User is not authenticated.'], 401);
    }

    // Validate the input data
    $validatedData = $request->validate([
      'amount' => 'required|numeric|min:0',
      'payment_method' => 'required|string',
    ]);

    // Extract validated data
    $amount = $validatedData['amount'];
    $payment_method = $validatedData['payment_method'];

    // Check if the user has a wallet
    if (!$user->wallet) {
      // If the user doesn't have a wallet, create one
      $wallet = new Wallet();
      $wallet->user_id = $user->id;
      $wallet->amount = $amount;
      $wallet->payment_method = $payment_method;
      $wallet->status = 1; // Assuming status 1 indicates an active wallet
      $wallet->save();
    } else {
      // If the user already has a wallet, update the balance
      $user->wallet->amount += $amount;
      $user->wallet->payment_method = $payment_method; // Update the payment method if necessary
      $user->wallet->save();
    }

    // Update the wallet_balance column in the users table
    $user->wallet_balance += $amount;
    $user->save();

    // Return a JSON response with the updated wallet balance
    return response()->json(['success' => true, 'wallet_balance' => $user->wallet_balance]);
  }

  // public function WalletCheckout(Request $request)
  // {
  //   $user = Auth::guard('sanctum')->user();
  //   if ($user) {
  //     $user_id = $user->id;
  //     $wallet_balance = $user->wallet_balance;

  //     // Fetch order details from cart
  //     $orderDetail = CartItem::select(
  //       'cart_items.user_id',
  //       'cart_items.product_id',
  //       'cart_items.product_color',
  //       'cart_items.product_size',
  //       'cart_items.quantity',
  //       'cart_items.coupon_id',
  //       'cart_items.address_id',
  //       'products.id',
  //       'products.vendor_id', // Added vendor_id selection
  //       'products.product_price',
  //       'products.product_sale_price'
  //     )
  //     ->leftJoin('products', 'products.id', '=', 'cart_items.product_id')
  //     ->where('user_id', $user_id)
  //     ->get();

  //     $total_amount = 0;

  //     // Calculate total amount of the order
  //     foreach ($orderDetail as $value) {
  //       if ($value->product_sale_price != null || $value->product_sale_price > 0) {
  //         $value->product_sale_price = $value->product_sale_price * $value->quantity;
  //         $total_amount += $value->product_sale_price;
  //       } else {
  //         $value->product_sale_price = $value->product_price * $value->quantity;
  //         $total_amount += $value->product_sale_price;
  //       }

  //       // Deduct coupon amount if applicable
  //       $CouponDetail = Coupon::where('id', $value->coupon_id)->first();
  //       if ($CouponDetail) {
  //         $total_amount -= $CouponDetail->discount_amount;
  //       }
  //     }

  //     // Check if user has sufficient balance
  //     if ($wallet_balance >= $total_amount) {
  //       // Sufficient balance, proceed with order placement
  //       $order = new Order();
  //       // Populate order details
  //       $orderId = '';
  //       $coupon_id = 0;
  //       $address_id = 0;
  //       $lastOrder = Order::orderBy('id', 'desc')->first();
  //       if ($lastOrder) {
  //         $lastOrderId = $lastOrder->order_id;
  //         $orderId = str_pad($lastOrderId + 1, 4, '0', STR_PAD_LEFT);
  //       } else {
  //         $orderId = '0001';
  //       }

  //       // Populate order details
  //       $order->user_id = $user_id;
  //       $order->order_id = $orderId;
  //       $order->total_item = $orderDetail->count();
  //       $order->address_id = $address_id;
  //       $order->coupon_id = $coupon_id;
  //       $order->payment_mode = $request->payment_mode ?? ""; // Storing payment_mode in Order model
  //       $order->order_status = 1;
  //       $order->total_amount = $total_amount;
  //       $order->save();

  //       // Deduct amount from user's wallet
  //       $user->wallet_balance -= $total_amount;
  //       $user->save();

  //       // Create order items
  //       foreach ($orderDetail as $value) {
  //         $data = [
  //           'user_id' => $user_id,
  //           'order_id' => $order->id, // Use the order ID from the previously created order
  //           'address_id' => $value->address_id,
  //           'product_id' => $value->product_id,
  //           'vendor_id' => $value->vendor_id, // Added vendor_id
  //           'product_color' => $value->product_color,
  //           'product_size' => $value->product_size,
  //           'coupon_id' => $coupon_id,
  //           'quantity' => $value->quantity,
  //         ];
  //         OrderItem::create($data);
  //       }

  //       // Clear cart items
  //       CartItem::where('user_id', $user_id)->delete();

  //       // Mark coupon as used if applicable
  //       if ($coupon_id) {
  //         Coupon::where('id', $coupon_id)->update(['status' => '1']);
  //       }

  //       // Send notification to user
  //       $notification = new Notifications([
  //         'title' => 'Order',
  //         'message' => 'Your order has been created successfully',
  //         'sender_id' => Admin::first()->id,
  //       ]);
  //       $FcmToken = $user->device_token;
  //       $data = [
  //         "registration_ids" => array($FcmToken),
  //         "notification" => [
  //           "title" => 'Order created ',
  //           "message" => 'Your order has been created successfully',
  //           "type" => 'verified',
  //           "sender_id" => Admin::first()->id,
  //         ],
  //         "data" => [
  //           "title" => 'Order created ',
  //           'sender_id' =>  Admin::first()->id,
  //           'message' => 'Your order has been created successfully',
  //           'user_id' => $user_id,
  //           "type" => "verified"
  //         ]
  //       ];

  //       $this->sendNotification($data);
  //       $user->notifications()->save($notification);

  //       return response()->json([
  //         'status' => true,
  //         'message' => 'Order Placed Successfully...!',
  //       ], 201);
  //     } else {
  //       // Insufficient balance, return error response
  //       return response()->json([
  //         'status' => false,
  //         'message' => 'Insufficient balance in wallet...!',
  //       ], 422);
  //     }
  //   } else {
  //     return response()->json([
  //       'status' => false,
  //       'message' => 'Unauthorized...!',
  //     ], 401);
  //   }
  // }

  public function WalletCheckout(Request $request)
  {
    // Validate the input data
    $validatedData = $request->validate([
      'user_id' => 'required|exists:users,id',
      'payment_mode' => 'required|string',
    ]);

    // Extract validated data
    $user_id = $validatedData['user_id'];
    $payment_mode = $validatedData['payment_mode'];

    // Find the user by ID
    $user = User::find($user_id);
    if (!$user) {
      return response()->json([
        'status' => false,
        'message' => 'User not found...!',
      ], 401);
    }

    $orderDetail = CartItem::select(
      'cart_items.user_id',
      'cart_items.product_id',
      'cart_items.product_color',
      'cart_items.product_size',
      'cart_items.quantity',
      'cart_items.coupon_id',
      'cart_items.address_id',
      'cart_items.variant_id', // Assuming you have a variant_id in your cart_items table
      'products.id',
      'products.product_price',
      'products.product_sale_price',
      'products.store_id',
      'products.vendor_id'
    )
      ->leftJoin('products', 'products.id', '=', 'cart_items.product_id')
      ->where('user_id', $user_id)
      ->get();

    $orderId = '';
    $coupon_id = 0;
    $address_id = 0;
    $lastOrder = Order::orderBy('id', 'desc')->first();
    if ($lastOrder) {
      $lastOrderId = $lastOrder->order_id;
      $orderId = str_pad($lastOrderId + 1, 4, '0', STR_PAD_LEFT);
    } else {
      $orderId = '0001';
    }

    $total_amount = 0;
    $store_id = 0;
    $vendor_id = 0;

    foreach ($orderDetail as $value) {
      $productDetail = Product::where('id', $value->product_id)->first();
      $address_id = $value->address_id ?? 0;
      $coupon_id = $value->coupon_id ?? 0;
      $store_id = $value->store_id ?? 0;
      $vendor_id = $value->vendor_id ?? 0;

      // Fetch variant details if variant_id exists
      if ($value->variant_id) {
        $variantDetail = Variant::where('id', $value->variant_id)->first();
        if ($variantDetail) {
          $variant_price = $variantDetail->price * $value->quantity;
          $total_amount += $variant_price;
        }
      } else {
        if ($value->product_sale_price != null && $value->product_sale_price > 0) {
          $value->product_sale_price = $value->product_sale_price * $value->quantity;
          $total_amount += $value->product_sale_price;
        } else {
          $value->product_sale_price = $value->product_price * $value->quantity;
          $total_amount += $value->product_sale_price;
        }
      }
    }

    // Apply coupon discount to the total amount
    if ($coupon_id) {
      $CouponDetail = Coupon::where('id', $coupon_id)->first();
      if ($CouponDetail) {
        $total_amount -= $CouponDetail->discount_amount;
      }
    }

    // Check wallet balance
    if ($user->wallet_balance < $total_amount) {
      return response()->json([
        'status' => false,
        'message' => 'Insufficient wallet balance...!',
      ], 402);
    }

    // Deduct the total amount from user's wallet balance
    $user->wallet_balance -= $total_amount;
    $user->save();

    $order = new Order();
    $order->user_id = $user_id;
    $order->order_id = $orderId;
    $order->total_item = $orderDetail->count();
    $order->address_id = $address_id;
    $order->coupon_id = $coupon_id;
    $order->payment_mode = $payment_mode;
    $order->order_status = 0;
    $order->total_amount = $total_amount;
    $order->store_id = $store_id;
    $order->vendor_id = $vendor_id;
    $order->save();

    foreach ($orderDetail as $value) {
      $data = [
        'user_id' => $user_id,
        'order_id' => $order->id,
        'address_id' => $value->address_id,
        'product_id' => $value->product_id,
        'product_color' => $value->product_color,
        'product_size' => $value->product_size,
        'variant_id' => $value->variant_id, // Save variant_id
        'coupon_id' => $coupon_id,
        'quantity' => $value->quantity,
        'vendor_id' => $value->vendor_id,
      ];
      OrderItem::create($data);
    }

    CartItem::where('user_id', $user_id)->delete();

    if ($coupon_id) {
      Coupon::where('id', $coupon_id)->update(['status' => '1']);
    }

    $notification = new Notifications([
      'title' => 'Order',
      'message' => 'Your order has been created successfully',
      'sender_id' => Admin::first()->id,
    ]);

    $FcmToken = $user->device_token;
    $data = [
      'registration_ids' => [$FcmToken],
      'notification' => [
        'title' => 'Order created',
        'message' => 'Your order has been created successfully',
        'type' => 'verified',
        'sender_id' => Admin::first()->id,
      ],
      'data' => [
        'title' => 'Order created',
        'sender_id' => Admin::first()->id,
        'message' => 'Your order has been created successfully',
        'user_id' => $user_id,
        'type' => 'verified',
      ],
    ];

    $this->sendNotification($data);
    $user->notifications()->save($notification);

    return response()->json([
      'status' => true,
      'message' => 'Order Placed Successfully...!',
    ], 201);
  }



  // Get Module
  public function getReferCode(Request $request)
  {
    // Get the authenticated user's ID
    $user_id = Auth::guard('sanctum')->user()->id;

    // Retrieve the refer_code for the authenticated user
    $user = User::select('id', 'refer_code')->where('id', $user_id)->first();

    // Check if user data is found
    if ($user) {
      // Replace null refer_code with empty string
      if (is_null($user->refer_code)) {
        $user->refer_code = '';
      }

      return response()->json([
        'success' => true,
        'message' => 'Refer code found',
        'data' => [
          'id' => $user->id,
          'refer_code' => $user->refer_code,
        ],
      ]);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'No refer code found for this user',
      ]);
    }
  }


  public function banner()
  {
    $data = Banner::select('banner_image')->get();
    foreach ($data as $value) {
      $value->banner_image = url('images/banner_images/' . $value->banner_image);
    }
    if ($data) {
      return response()->json([
        'message' => 'Image found',
        'data' => $data,
      ]);
    } else {
      return response()->json([
        'message' => 'Image not found',
      ]);
    }
  }
  public function category(Request $request)
  {
    $data = Category::select('id', 'category_name', 'category_image')->get();
    foreach ($data as $value) {
      $value->category_image = url('public/assets/images/category_images/' . $value->category_image);
    }
    if ($data) {
      return response()->json([
        'message' => 'Image found',
        'data' => $data,
      ]);
    } else {
      return response()->json([
        'message' => 'Image not found',
      ]);
    }
  }

  public function productTrending(Request $request)
  {
    $product = OrderItem::select(
      'order_items.product_id',
      // 'order_items.user_id',
      'products.id',
      'products.product_name',
      'products.product_sale_price',
      'products.product_price',
      'products.product_image',
      'products.sub_category_id',
      'sub_categories.sub_category_name'
    )
      ->join('products', 'products.id', '=', 'order_items.product_id')
      ->leftjoin('sub_categories', 'sub_category_id', '=', 'sub_categories.id')
      ->groupBy(
        'products.id',
        'order_items.product_id',
        'products.product_name',
        'products.product_sale_price',
        'products.product_price',
        'products.product_image',
        'products.sub_category_id',
        'sub_categories.sub_category_name'
      )

      // ->distinct()
      ->get();

    //   dd($product);
    foreach ($product as $value) {
      $totalProductImages = [];
      $productImages = ProductImages::select('product_image')
        ->where('product_id', $value->id)
        ->get();
      $totalProductReview = 0;
      $totalReviewStar = 0;
      foreach ($productImages as $val) {
        if ($val->product_image) {
          $totalProductImages[] = url('assets/images/product_images/' . $val->product_image);
        } else {
          $totalProductImages = [];
        }
      }
      $value->product_image = $totalProductImages;
      $totalProductReview = 0;
      $totalReviewStar = 0;
      $totalProductReview = ProductReview::select('product_id', 'review_star')
        ->where('product_id', $value->id)
        ->get();
      $totalReviewStar = 0;
      $totalAvgReview = 0;
      foreach ($totalProductReview as $val) {
        $reviewStar = floatval($val->review_star);
        $totalReviewStar = $totalReviewStar + $reviewStar;
      }
      $totalReviewCount = $totalProductReview->count();

      if ($totalReviewCount) {
        (string) ($value->totalReviewCount = (string) $totalReviewCount);
      } else {
        $value->totalReviewCount = '';
      }
      if ($totalReviewCount > 0) {
        $totalAvgReview = round($totalReviewStar / $totalReviewCount, 1);
      }
      (string) ($value->totalAvgReview = (string) $totalAvgReview);

      $value->sub_category_name = $value->sub_category_name ?? '';

      if ($value->product_sale_price != '' && $value->product_sale_price > 0) {
        $value->product_sale_price = $value->product_sale_price;
      } else {
        $value->product_sale_price = $value->product_price;
      }

      $value->sub_category_name = $value->sub_category_name ?? '';
      $productLike = ProductLike::select('id', 'user_id', 'product_id')
        ->where('user_id', $request->user_id)
        ->where('product_id', $value->product_id)
        ->exists()
        ? 1
        : 0;
      if ($request->user_id != '') {
        $value->is_Like = $productLike;
      } else {
        $value->is_Like = 0;
      }
    }

    return response()->json([
      'message' => 'Trending Product...!',
      'product' => $product,
    ]);
  }
  public function sortProduct(Request $request)
  {
    $desc = $request->desc;
    $asc = $request->asc;
    if ($asc) {
      $data = Product::orderBy('product_price', $asc)->get();
    }
    if ($desc) {
      $data = Product::orderBy('product_price', $desc)->get();
    }
    return response()->json([
      'message' => $data,
    ]);
  }
  // public function home(Request $request)
  // {
  //   $notificationCount = 0;
  //   if ($request->user_id) {
  //     $cartCount = CartItem::where('user_id', $request->user_id)->count();
  //     $notificationCount = Notifications::where('user_id', $request->user_id)->where('is_seen', '0')->count();
  //     $banner = Banner::select('banner_image')->get();
  //     foreach ($banner as $value) {
  //       $value->banner_image = url('assets/images/banner_images/' . $value->banner_image);
  //     }
  //     // dd($banner);
  //     $category = Category::select('id', 'category_name', 'category_icon')->get();
  //     foreach ($category as $value) {
  //       $value->category_image = url('assets/images/category_images/' . $value->category_icon);
  //     }
  //     $product = Product::select(
  //       'products.id',
  //       'product_name',
  //       'product_sale_price',
  //       'product_price',
  //       'product_image',
  //       'sub_category_id',
  //       'sub_categories.sub_category_name'
  //     )
  //       ->leftjoin('sub_categories', 'sub_category_id', '=', 'sub_categories.id')
  //       ->get();
  //     foreach ($product as $value) {
  //       $totalProductImages = [];
  //       $productImages = ProductImages::select('product_image')
  //         ->where('product_id', $value->id)
  //         ->get();
  //       // dd($productImages);
  //       foreach ($productImages as $val) {
  //         if ($val->product_image) {
  //           $totalProductImages[] = url('assets/images/product_images/' . $val->product_image);
  //         } else {
  //           $totalProductImages = [];
  //         }
  //       }

  //       // $value->product_image = url('public/assets/images/product_images/' . $value->product_image);
  //       $productUserLike = ProductLike::where('user_id', $request->user_id)
  //         ->where('product_id', $value->id)
  //         ->first();
  //       if ($productUserLike) {
  //         $value->is_Like = 1;
  //       } else {
  //         $value->is_Like = 0;
  //       }
  //       $value->product_image = $totalProductImages;

  //       $totalProductReview = 0;
  //       $totalReviewStar = 0;
  //       $totalProductReview = ProductReview::select('product_id', 'review_star')
  //         ->where('product_id', $value->id)
  //         ->get();
  //       $totalReviewStar = 0;
  //       $totalAvgReview = 0;
  //       foreach ($totalProductReview as $val) {
  //         $reviewStar = floatval($val->review_star);
  //         $totalReviewStar = $totalReviewStar + $reviewStar;
  //       }
  //       $totalReviewCount = $totalProductReview->count();

  //       if ($totalReviewCount) {
  //         (string) ($value->totalReviewCount = (string) $totalReviewCount);
  //       } else {
  //         $value->totalReviewCount = '';
  //       }
  //       if ($totalReviewCount > 0) {
  //         $totalAvgReview = round($totalReviewStar / $totalReviewCount, 1);
  //       }
  //       (string) ($value->totalAvgReview = (string) $totalAvgReview);

  //       $value->sub_category_name = $value->sub_category_name ?? '';

  //       if ($value->product_sale_price != '' && $value->product_sale_price > 0) {
  //         $value->product_sale_price = $value->product_sale_price;
  //       } else {
  //         $value->product_sale_price = $value->product_price;
  //       }
  //     }
  //   } else {
  //     $banner = Banner::select('banner_image')->get();
  //     foreach ($banner as $value) {
  //       $value->banner_image = url('assets/images/banner_images/' . $value->banner_image);
  //     }

  //     $category = Category::select('id', 'category_name', 'category_icon')->get();
  //     foreach ($category as $value) {
  //       $value->category_image = url('/assets/images/category_images/' . $value->category_icon);
  //     }
  //     $product = OrderItem::select(
  //       'order_items.product_id',
  //       'products.id',
  //       'product_name',
  //       'product_sale_price',
  //       'product_price',
  //       'product_image',
  //       'products.sub_category_id',
  //       'sub_categories.sub_category_name'
  //     )
  //       ->leftjoin('products', 'products.id', '=', 'order_items.product_id')
  //       ->leftjoin('sub_categories', 'sub_category_id', '=', 'sub_categories.id')
  //       ->get();
  //     foreach ($product as $value) {
  //       // dd($value->id);
  //       $totalProductImages = [];
  //       $productImages = ProductImages::select('product_image')
  //         ->where('product_id', $value->id)
  //         ->get();
  //       // dd($productImages);
  //       foreach ($productImages as $val) {
  //         if ($val->product_image) {
  //           $totalProductImages[] = url('assets/images/product_images/' . $val->product_image);
  //         } else {
  //           $totalProductImages = [];
  //         }
  //       }
  //       $value->product_image = $totalProductImages;
  //       $totalProductReview = 0;
  //       $totalReviewStar = 0;
  //       $totalProductReview = ProductReview::select('product_id', 'review_star')
  //         ->where('product_id', $value->id)
  //         ->get();
  //       $totalReviewStar = 0;
  //       $totalAvgReview = 0;
  //       foreach ($totalProductReview as $val) {
  //         $reviewStar = floatval($val->review_star);
  //         $totalReviewStar = $totalReviewStar + $reviewStar;
  //       }
  //       $totalReviewCount = $totalProductReview->count();

  //       if ($totalReviewCount) {
  //         (string) ($value->totalReviewCount = (string) $totalReviewCount);
  //       } else {
  //         $value->totalReviewCount = '';
  //       }
  //       if ($totalReviewCount > 0) {
  //         $totalAvgReview = round($totalReviewStar / $totalReviewCount, 1);
  //       }
  //       (string) ($value->totalAvgReview = (string) $totalAvgReview);
  //       $value->sub_category_name = $value->sub_category_name ?? '';

  //       if ($value->product_sale_price != '' && $value->product_sale_price > 0) {
  //         $value->product_sale_price = $value->product_sale_price;
  //       } else {
  //         $value->product_sale_price = $value->product_price;
  //       }
  //       $value->is_Like = 0;
  //     }
  //   }
  //   // $bestSeller = OrderItem::leftjoin('products', 'products.id', '=', 'order_items.product_id')
  //   //   ->groupBy('order_items.product_id')
  //   //   ->get();

  //   return response()->json([
  //     'message' => 'product found',
  //     'product_Banner' => $banner,
  //     'category' => $category,
  //     'product' => $product,
  //     'cartCount' => $cartCount ?? 0,
  //     'notificationCount' => $notificationCount,
  //   ]);
  // }

  public function productDetail(Request $request)
  {
    //  dd($request->all());
    if (!Product::where('id', $request->product_id)->first()) {
      return response()->json([
        'status' => 'False',
        'Message' => 'This Product Not Available',
      ]);
    }
    $product = Product::select(
      'products.id',
      'products.category_id',
      'sub_category_id',
      'product_name',
      'product_image',
      'product_price',
      'product_sale_price',
      'product_color',
      'product_size',
      'product_about',
      'sub_categories.sub_category_name'
    )
      ->leftjoin('sub_categories', 'sub_category_id', '=', 'sub_categories.id')
      ->where('products.id', $request->product_id)
      ->first();
    $product->product_review = (string) $product->product_review;
    $product->product_sale_price = $product->product_sale_price ?? 0;
    $productPrice =
      (string) ((($product->product_sale_price - $product->product_price) * 100) / $product->product_price);
    $productColor = explode(',', $product->product_color);
    $productSize = explode(',', $product->product_size);
    $productImages = explode(',', $product->product_image);
    // $totalProductReview = ProductReview::where('product_id', $request->product_id)->count();
    $totalProductColor = [];
    $totalProductSize = [];
    $totalProductImages = [];
    $productImages = ProductImages::select('product_image')
      ->where('product_id', $product->id)
      ->get();
    // dd($productImages);
    foreach ($productImages as $val) {
      if ($val->product_image) {
        $totalProductImages[] = url('/assets/images/product_images/' . $val->product_image);
      } else {
        $totalProductImages = [];
      }
    }

    foreach ($productColor as $val) {
      if ($val) {
        $totalProductColor[] = $val;
      } else {
        $totalProductColor = [];
      }
    }
    foreach ($productSize as $val) {
      if ($val) {
        $totalProductSize[] = $val;
      } else {
        $totalProductSize = [];
      }
    }
    $product->product_image = $totalProductImages;
    $product->product_color = $totalProductColor;
    $product->product_size = $totalProductSize;
    // $product->totalProductReview = $totalProductReview;
    $product->producDiscount = $productPrice;
    $productDetails = ProductReview::select(
      'product_reviews.id',
      'product_reviews.product_id',
      'product_reviews.user_id',
      'product_reviews.review_star',
      'product_reviews.review_message',
      'product_reviews.created_at',
      'users.first_name',
      'users.last_name',
      'users.image'
    )
      ->leftjoin('users', 'product_reviews.user_id', '=', 'users.id')
      ->where('product_id', $request->product_id)
      ->orderBy('id', 'desc')
      ->get();
    //  dd($productDetails);
    $totalReviewCount = $productDetails->count();
    $totalReviewStar = 0;
    $totalAvgReview = 0;
    $is_user_review = false;
    foreach ($productDetails as $value) {
      $value->first_name = $value->first_name ?? '';
      $value->last_name = $value->last_name ?? '';
      if ($value->review_message == null && $value->review_message == '') {
        $value->review_message = '';
      }
      if ($value->image == '' || $value->image == null) {
        $value->image = url('/assets/images/users_images/default.png');
      } else {
        $value->image = url('/assets/images/users_images/' . $value->image);
      }
      if ($value->review_star) {
        $is_user_review = true;
      } else {
        $is_user_review = false;
      }
      $reviewStar = floatval($value->review_star);
      $totalReviewStar = $totalReviewStar + $reviewStar;
      if ($value->created_at) {
        $value->created_at = $value->created_at;
      } else {
        $value->created_at = '';
      }
      $value->createdAts = $value->created_at ? $value->created_at->diffForHumans() : '';
    }
    if ($totalReviewCount > 0) {
      $totalAvgReview = round($totalReviewStar / $totalReviewCount, 1);
    }
    $productDetails->totalReviewCount = $totalReviewCount;

    $productLike = ProductLike::select('id', 'user_id', 'product_id')
      ->where('user_id', $request->user_id)
      ->where('product_id', $request->product_id)
      ->exists()
      ? 1
      : 0;
    if (
      OrderItem::where('product_id', $request->product_id)
      ->where('user_id', $request->user_id)
      ->exists()
    ) {
      $is_purchased = true;
    } else {
      $is_purchased = false;
    }
    $cartCount = CartItem::where('user_id', $request->user_id)->count();
    return response()->json([
      'message' => 'Product Found',
      'totalAvgReviewCount' => (string) $totalAvgReview,
      'totalUserCount' => $totalReviewCount,
      'product' => $product,
      'productReviews' => $productDetails,
      'totalAvgReview' => (string) $totalAvgReview,
      'productLike' => $productLike,
      'is_purchased' => $is_purchased,
      'is_user_review' => $is_user_review,
      'cartCount' => $cartCount,
    ]);
  }
  public function productFilter(Request $request)
  {
    if ($request->category_id != '') {
      $query = Product::select('product_color', 'product_size')
        ->where('products.category_id', $request->category_id)
        ->get();
      //   dd($query);
      $productColors = [];
      $productSize = [];
      foreach ($query as $value) {
        if ($value->product_color) {
          $productColors[] = explode(',', $value->product_color);
        }
      }
      foreach ($query as $value) {
        if ($value->product_size) {
          $productSize[] = explode(',', $value->product_size);
        }
      }
      $uniqueColorsValues = [];
      foreach ($productColors as $colors) {
        foreach ($colors as $color) {
          if (!in_array($color, $uniqueColorsValues)) {
            $uniqueColorsValues[] = $color;
          }
        }
      }
      $uniqueSizeValues = [];
      foreach ($productSize as $sizes) {
        foreach ($sizes as $size) {
          if (!in_array($size, $uniqueSizeValues)) {
            $uniqueSizeValues[] = $size;
          }
        }
      }
      $productFilter = [];
      $productFilter = [
        'size' => 'Size',
        'product size' => $uniqueSizeValues,
        'product color' => 'Color',
        'color' => $uniqueColorsValues,
        'rating' => 'Rating',
      ];
      return response()->json([
        'message' => true,
        'filter' => 'product filter',
        'productFilter' => $productFilter,
        //   'product colors' => $uniqueColorsValues,
        //   'product size' => $uniqueSizeValues,
      ]);
    } else {
      return response()->json([
        'message' => false,
      ]);
    }
  }

  public function productSearch(Request $request)
  {
    dd('sadf');
    // $data = Product::select('id', 'category_id', 'sub_category_id', 'product_name', 'product_image', 'product_price', 'product_review', 'product_size', 'product_color', 'product_about')->where('product_name', 'LIKE', "%$request->product_name%")->get()
    $data = Product::select('id', 'product_name', 'product_image')
      ->where('product_name', 'LIKE', "%$request->product_name%")
      ->get()

      ->transform(function ($tsr) {
        $productImages = explode(',', $tsr->product_image);
        foreach ($productImages as $val) {
          if ($val) {
            $totalProductImages[] = url('images/product_images/' . $val);
            $tsr->product_image = $totalProductImages;
          } else {
            $totalProductImages = [];
            $tsr->product_image = $totalProductImages;
          }
        }

        if ($tsr->product_sale_price != null || $tsr->product_sale_price > 0) {
          $tsr->product_sale_price = $tsr->product_sale_price;
        } else {
          $tsr->product_sale_price = $tsr->product_price;
        }

        $totalProductReview = 0;
        $totalReviewStar = 0;
        $totalProductReview = ProductReview::select('product_id', 'review_star')
          ->where('product_id', $tsr->id)
          ->get();
        $totalReviewStar = 0;
        $totalAvgReview = 0;
        foreach ($totalProductReview as $val) {
          $reviewStar = floatval($val->review_star);
          $totalReviewStar = $totalReviewStar + $reviewStar;
        }
        $totalReviewCount = $totalProductReview->count();

        if ($totalReviewCount) {
          (string) ($tsr->totalReviewCount = (string) $totalReviewCount);
        } else {
          $tsr->totalReviewCount = '';
        }
        if ($totalReviewCount > 0) {
          $totalAvgReview = round($totalReviewStar / $totalReviewCount, 1);
        }
        (string) ($tsr->totalAvgReview = (string) $totalAvgReview);
        return $tsr;
      });
    return response()->json([
      'message' => 'Product search successfully ...!',
      'data' => $data,
    ]);
  }

  // public function add_cart(Request $request)
  // {
  //   // dd($request->all());
  //   $user_id = Auth::guard('sanctum')->user()->id;
  //   if (!User::find($user_id)) {
  //     return response()->json(
  //       [
  //         'message' => 'User not found ...!',
  //       ],
  //       500
  //     );
  //   }
  //   if (!Product::find($request->product_id)) {
  //     return response()->json(
  //       [
  //         'message' => 'Product not found ...!',
  //       ],
  //       500
  //     );
  //   }
  //   if ($request->product_id != '' && $request->product_color != '' && $request->product_size != '') {
  //     if (
  //       CartItem::where('user_id', $user_id)
  //       ->where('product_id', $request->product_id)
  //       ->where('product_color', $request->product_color)
  //       ->where('product_size', $request->product_size)
  //       ->exists()
  //     ) {
  //       $exitCartItem = CartItem::where('user_id', $user_id)
  //         ->where('product_id', $request->product_id)
  //         ->where('product_color', $request->product_color)
  //         ->where('product_size', $request->product_size)
  //         ->first();
  //       // dd($exitCartItem);
  //       $quantity = $exitCartItem->quantity ?? '';
  //       // dd($quantity);
  //       if ($quantity >= 5) {
  //         return response()->json(
  //           [
  //             'message' => 'you cannot add more quantity ...!',
  //           ],
  //           401
  //         );
  //       }
  //       if ($quantity < 5) {
  //         $cartItem = [
  //           'quantity' => $quantity + 1,
  //         ];
  //         //   UserAddress::where('user_id', $user_id)->update(['address_flag' => 'Y']);
  //         $data = CartItem::where('user_id', $user_id)
  //           ->where('product_id', $request->product_id)
  //           ->where('product_color', $request->product_color)
  //           ->where('product_size', $request->product_size)
  //           ->update($cartItem);
  //         return response()->json(
  //           [
  //             'message' => 'Product add to cart successfully ...!',
  //           ],
  //           200
  //         );
  //       }
  //     } else {
  //       $cartItem = [
  //         'user_id' => $user_id,
  //         'product_id' => $request->product_id,
  //         'product_color' => $request->product_color ?? '',
  //         'product_size' => $request->product_size ?? '',
  //         'quantity' => $request->quantity,
  //         'address_id' =>
  //         UserAddress::select('id')
  //           ->where('user_id', $user_id)
  //           ->where('default_address', 1)
  //           ->first()->id ?? null,
  //         'coupon_id' => CartItem::where('user_id', $user_id)->first()->coupon_id ?? null,
  //       ];
  //       // UserAddress::where('user_id', $user_id)->update(['address_flag' => 'Y']);
  //       $data = CartItem::create($cartItem);
  //       return response()->json(
  //         [
  //           'message' => 'Product add to cart successfully ...!',
  //         ],
  //         200
  //       );
  //     }
  //   }
  //   if ($request->product_id != '' && $request->product_color != '') {
  //     if (
  //       CartItem::where('user_id', $user_id)
  //       ->where('product_id', $request->product_id)
  //       ->where('product_color', $request->product_color)
  //       ->exists()
  //     ) {
  //       $exitCartItem = CartItem::where('user_id', $user_id)
  //         ->where('product_id', $request->product_id)
  //         ->where('product_color', $request->product_color)
  //         ->first();
  //       $quantity = $exitCartItem->quantity ?? '';
  //       if ($quantity >= 5) {
  //         return response()->json(
  //           [
  //             'message' => 'you cannot add more quantity ...!',
  //           ],
  //           200
  //         );
  //       } elseif ($quantity < 5) {
  //         $cartItem = [
  //           'quantity' => $quantity + 1,
  //         ];
  //         //   UserAddress::where('user_id', $user_id)->update(['address_flag' => 'Y']);
  //         $data = CartItem::where('user_id', $user_id)
  //           ->where('product_id', $request->product_id)
  //           ->where('product_color', $request->product_color)
  //           ->update($cartItem);
  //         return response()->json(
  //           [
  //             'message' => 'Product add to cart successfully ...!',
  //           ],
  //           200
  //         );
  //       }
  //     } else {
  //       $cartItem = [
  //         'user_id' => $user_id,
  //         'product_id' => $request->product_id,
  //         'product_color' => $request->product_color ?? '',
  //         'product_size' => $request->product_size ?? '',
  //         'quantity' => $request->quantity,
  //         'address_id' =>
  //         UserAddress::select('id')
  //           ->where('user_id', $user_id)
  //           ->where('default_address', 1)
  //           ->first()->id ?? null,
  //         'coupon_id' => CartItem::where('user_id', $user_id)->first()->coupon_id ?? null,
  //       ];
  //       // UserAddress::where('user_id', $user_id)->update(['address_flag' => 'Y']);
  //       $data = CartItem::create($cartItem);
  //       return response()->json(
  //         [
  //           'message' => 'Product add to cart successfully ...!',
  //         ],
  //         200
  //       );
  //     }
  //   }
  //   if ($request->product_id != '' && $request->product_size != '') {
  //     if (
  //       CartItem::where('user_id', $user_id)
  //       ->where('product_id', $request->product_id)
  //       ->where('product_size', $request->product_size)
  //       ->exists()
  //     ) {
  //       $exitCartItem = CartItem::where('user_id', $user_id)
  //         ->where('product_id', $request->product_id)
  //         ->where('product_size', $request->product_size)
  //         ->first();
  //       $quantity = $exitCartItem->quantity ?? '';
  //       if ($quantity >= 5) {
  //         return response()->json(
  //           [
  //             'message' => 'you cannot add more quantity ...!',
  //           ],
  //           200
  //         );
  //       } elseif ($quantity < 5) {
  //         $cartItem = [
  //           'quantity' => $quantity + 1,
  //         ];
  //         UserAddress::where('user_id', $user_id)->update(['address_flag' => 'Y']);
  //         $data = CartItem::where('user_id', $user_id)
  //           ->where('product_id', $request->product_id)
  //           ->where('product_size', $request->product_size)
  //           ->update($cartItem);
  //         return response()->json(
  //           [
  //             'message' => 'Product add to cart successfully ...!',
  //           ],
  //           200
  //         );
  //       }
  //     } else {
  //       $cartItem = [
  //         'user_id' => $user_id,
  //         'product_id' => $request->product_id,
  //         'product_color' => $request->product_color ?? '',
  //         'product_size' => $request->product_size ?? '',
  //         'quantity' => $request->quantity,
  //         'address_id' =>
  //         UserAddress::select('id')
  //           ->where('user_id', $user_id)
  //           ->where('default_address', 'true')
  //           ->first()->id ?? null,
  //         'coupon_id' => CartItem::where('user_id', $user_id)->first()->coupon_id ?? null,
  //       ];
  //       UserAddress::where('user_id', $user_id)->update(['address_flag' => 'Y']);
  //       $data = CartItem::create($cartItem);
  //       return response()->json(
  //         [
  //           'message' => 'Product add to cart successfully ...!',
  //         ],
  //         200
  //       );
  //     }
  //   }
  //   if ($request->product_id != '') {
  //     if (
  //       CartItem::where('user_id', $user_id)
  //       ->where('product_id', $request->product_id)
  //       ->exists()
  //     ) {
  //       $exitCartItem = CartItem::where('user_id', $user_id)
  //         ->where('product_id', $request->product_id)
  //         ->first();
  //       $quantity = $exitCartItem->quantity ?? '';
  //       if ($quantity >= 5) {
  //         return response()->json(
  //           [
  //             'message' => 'you cannot add more quantity ...!',
  //           ],
  //           200
  //         );
  //       } elseif ($quantity < 5) {
  //         $cartItem = [
  //           'quantity' => $quantity + 1,
  //         ];
  //         UserAddress::where('user_id', $user_id)->update(['address_flag' => 'Y']);
  //         $data = CartItem::where('user_id', $user_id)
  //           ->where('product_id', $request->product_id)
  //           ->update($cartItem);
  //         return response()->json(
  //           [
  //             'message' => 'Product add to cart successfully ...!',
  //           ],
  //           200
  //         );
  //       }
  //     } else {
  //       $cartItem = [
  //         'user_id' => $user_id,
  //         'product_id' => $request->product_id,
  //         'product_color' => $request->product_color ?? '',
  //         'product_size' => $request->product_size ?? '',
  //         'quantity' => $request->quantity,
  //         'address_id' =>
  //         UserAddress::select('id')
  //           ->where('user_id', $user_id)
  //           ->where('default_address', 'true')
  //           ->first()->id ?? null,
  //         'coupon_id' => CartItem::where('user_id', $user_id)->first()->coupon_id ?? null,
  //       ];
  //       UserAddress::where('user_id', $user_id)->update(['address_flag' => 'Y']);
  //       $data = CartItem::create($cartItem);
  //       return response()->json(
  //         [
  //           'message' => 'Product add to cart successfully ...!',
  //         ],
  //         200
  //       );
  //     }
  //   }
  // }
  public function listCart(Request $request)
  {
    $user_id = Auth::guard('sanctum')->user()->id;
    if ($request->product_id != '') {
      CartItem::where('product_id', $request->product_id)->update(['quantity' => $request->quantity]);
    }
    $data = CartItem::with('productImages')
      ->select(
        'cart_items.id',
        'cart_items.address_id',
        'cart_items.user_id',
        'cart_items.product_id',
        'cart_items.product_color',
        'cart_items.product_size',
        'cart_items.quantity',
        'products.product_name',
        'products.product_price',
        'products.product_sale_price',
        'sub_categories.sub_category_name',
        'coupon_id'
      )
      ->leftjoin('products', 'cart_items.product_id', '=', 'products.id')
      ->leftjoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
      ->where('cart_items.user_id', $user_id) // ->where('user_address.user_id', $request->user_id)
      ->get()
      ->transform(function ($tsr) {
        $tsr->coupon_id = (string) $tsr->coupon_id;
        return $tsr;
      });
    $bagTotal = 0;
    $coupon_id = '';
    $addressId = '';
    foreach ($data as $value) {
      $addressId = $value->address_id;
      $productLike = ProductLike::where('user_id', $user_id)
        ->where('product_id', $value->product_id)
        ->first();
      $productImage = [];
      foreach ($value->productImages as $image) {
        $productImage[] = url('/assets/images/product_images/' . $image->product_image);
      }
      $value->productImage = $productImage;
      if ($productLike) {
        $value->is_Like = 1;
      } else {
        $value->is_Like = 0;
      }
      if ($value->product_sale_price != null || $value->product_sale_price > 0) {
        $value->product_sale_price = $value->product_sale_price * $value->quantity;
        $bagTotal += $value->product_sale_price;
      } else {
        $value->product_sale_price = $value->product_price * $value->quantity;
        $bagTotal += $value->product_sale_price;
      }
      $coupon_id = $value->coupon_id;
    }
    $totalAmount = 0;
    $couopnAmt = 0;
    $couoponCode = '';
    if ($coupon_id) {
      $couopnDetail = Coupon::where('id', $coupon_id)->first();
      $totalAmount = $bagTotal - $couopnDetail->discount_amount;
      $couopnAmt = $couopnDetail->discount_amount;
      $couoponCode = $couopnDetail->coupon_code;
    } else {
      $totalAmount = $bagTotal - 0;
    }

    if ($addressId) {
      $address = UserAddress::select(
        'id',
        'first_name',
        'last_name',
        'mobile',
        'pincode',
        'address',
        'locality',
        'city',
        'state',
        'type'
      )
        ->where('id', $addressId)
        ->where('address_flag', 'Y')
        ->first();
    } elseif (
      UserAddress::where('user_id', $user_id)
      ->where('address_flag', 'Y')
      ->where('last_address_status', '1')
      ->exists()
    ) {
      $address = UserAddress::select(
        'id',
        'first_name',
        'last_name',
        'mobile',
        'pincode',
        'address',
        'locality',
        'city',
        'state',
        'type'
      )
        ->where('user_id', $user_id)
        ->where('address_flag', 'Y')
        ->where('last_address_status', '1')
        ->first();
    } else {
      $address = [
        'id' => 0,
        'first_name' => '',
        'last_name' => '',
        'mobile' => '',
        'pincode' => '',
        'address' => '',
        'locality' => '',
        'city' => '',
        'state' => '',
        'type' => '',
      ];
    }
    return response()->json([
      'message' => 'Success ...!',
      'Address' => $address,
      'data' => $data,
      'bagTotal' => $bagTotal,
      'coupons' => (int) $couopnAmt,
      'couponCode' => $couoponCode,
      'totalAmount' => $totalAmount,
    ]);
  }



  function sendNotification($data)
  {
    $url = 'https://fcm.googleapis.com/fcm/send';

    // $serverKey = getenv('FIREBSE_SERVERKEY');
    $serverKey =
      'AAAAX26WaPo:APA91bFF8vpxBlzyLUzetmH_ytImj3iJ-9cASib10YymuwuMF_SmDvEo4kcZHQzlRYBLEE5_8ud-K1rdrusQe7gSess0bcDGJuKAVNNeB9ls3NmJAoQ3BMSIfNVsAb4yJdqbVV9ngSAk';
    $encodedData = json_encode($data);
    $headers = ['Authorization:key=' . $serverKey, 'Content-Type: application/json'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    // Disabling SSL Certificate support temporarly
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
    // Execute post
    $result = curl_exec($ch);
    if ($result === false) {
      die('Curl failed: ' . curl_error($ch));
    }
    // Close connection
    curl_close($ch);
    return true;

    // // FCM response
    // echo "--";
    // print_r($result);
    // echo "--";
  }

  public function get_vendor_orders2()
  {
    try {
      // $myId = $request->user()->token()->user_id;
      $user_id = Auth::guard('sanctum')->user()->id;

      $orders = OrderItem::where('vendor_id', $user_id)
        ->distinct('order_id')
        ->orderBy('order_id', 'desc')
        ->get();
      // ->pluck('order_id');

      $orderList = [];

      // print_r($orders);
      // die;

      foreach ($orders as $orderId) {
        $orderDetails = [];
        $orderDetails['order_id'] = (string) $orderId->order_id;

        $items = OrderItem::where('order_id', $orderId->order_id)
          ->where('vendor_id', $user_id)
          ->get();

        $order_details_done = Order::where('id', $orderId->order_id)->first();

        if ($items->isNotEmpty()) {
          $firstItem = $items->first();
          $orderDetails['order_id'] = (string) $orderId->order_id;
          $orderDetails['id'] = (string) $order_details_done->order_id;
          // $orderDetails['order_date'] = (string)$firstItem->created_at;
          // $orderDetails['order_date'] = $firstItem->created_at->format('j M\'y, g:i A');
          $orderDetails['order_status'] = (string) $firstItem->is_status;
          // $orderDetails['total'] = "0";
          // $total = $firstItem->price * $firstItem->quantity;
          // $orderDetails['total'] = (string)$total;
          $sub_total = 0;

          foreach ($items as $item) {
            // Calculate total for each item
            $total = $item->price * $item->quantity;
            $sub_total += $total;
          }

          // Assign total to the orderDetails array
          $orderDetails['total'] = (string) $sub_total;
        }

        $itemList = [];

        foreach ($items as $item) {
          // Build details for each item
          $product_details_done = ProductImages::where('product_id', $item->product_id)->get();

          $pro_img = [];
          foreach ($product_details_done as $image) {
            $pro_img[] = url('assets/images/product_images/' . $image->product_image);
          }

          $product_details = Product::where('id', $item->product_id)->first();

          //     $product_image = $product_details->product_image;
          //     if($product_image){
          //     $imgs = explode("::::", $product_image);
          //     $pro_img = array();
          //     foreach ($imgs as $img) {
          //         if (strpos($img, 'products_img/') !== false) {
          //             $image = new BaseController;
          //             $pro_img[] = $image->s3FetchFile($img);
          //         } else {
          //             $pro_img[] = url('/products_img/' . $img);
          //         }
          //     }
          // }else{
          //     $pro_img[] = [];
          // }
          $itemDetails = [
            // 'order_id' => (string)$item->order_id,
            'user_id' => (string) $item->user_id,
            'product_id' => (string) $item->product_id,
            'quantity' => $item->quantity ? (string) $item->quantity : '',
            'price' => $item->price ? (string) $item->price : '',
            'product_color' => $item->product_color ? $item->product_color : '',
            'product_size' => $item->product_size ? (string) $item->product_size : '',
            'is_status' => $item->is_status ? (string) $item->is_status : '',
            'order_date' => $item->created_at->format('j M\'y'),
            // 'coupon_code' => $item->coupon_code ? $item->coupon_code : "",
            // 'checked' => $item->checked ? (string)$item->checked : "",
            // ... add other item details as needed

            //  if ($product_details) {
            // 'product_id' => $product_details->product_id ?  (string)$product_details->product_id : "",
            'category_id' => $product_details->category_id ? (string) $product_details->category_id : '',
            'sub_category_id' => $product_details->sub_category_id ? (string) $product_details->sub_category_id : '',
            'product_name' => $product_details->product_name ? $product_details->product_name : '',
            'product_price' => $product_details->product_price ? $product_details->product_price : '',
            'product_sale_price' => $product_details->product_sale_price
              ? (string) $product_details->product_sale_price
              : '',
            'pro_img' => $pro_img,
          ];

          $itemList[] = $itemDetails;
        }

        $orderDetails['items'] = $itemList;
        $orderList[] = $orderDetails;
      }

      // print_r($items);
      // die;

      $temp = [
        'response_code' => '1',
        'message' => 'User Found',
        'status' => 'success',
        'orders' => $orderList,
      ];

      return response()->json($temp);
    } catch (\Throwable $th) {
      // return $this->sendError("Otp not send", $th->getMessage());
      $temp = [
        'response_code' => '0',
        'message' => 'User Not Found',
        'status' => 'success',
        'orders' => [],
      ];
    }
  }

  public function get_vendor_orders()
  {
    try {
      // $myId = $request->user()->token()->user_id;
      $user_id = Auth::guard('sanctum')->user()->id;

      $orders = OrderItem::where('vendor_id', $user_id)
        ->where('is_status', '=', null)
        ->distinct('order_id')
        ->orderBy('order_id', 'desc')
        ->pluck('order_id');

      $orderList = [];

      foreach ($orders as $orderId) {
        $orderDetails = [];
        $orderDetails['order_id'] = (string) $orderId;

        $items = OrderItem::where('order_id', $orderId)
          ->where('vendor_id', $user_id)
          ->where('is_status', '=', null)
          ->get();

        $order_details_done = Order::where('id', $orderId)->first();

        if ($items->isNotEmpty()) {
          $firstItem = $items->first();
          $orderDetails['order_id'] = (string) $orderId;
          $orderDetails['id'] = (string) $order_details_done->order_id;
          $orderDetails['order_status'] = (string) $firstItem->is_status;
          $sub_total = 0;

          foreach ($items as $item) {
            // Calculate total for each item
            $total = $item->price * $item->quantity;
            $sub_total += $total;
          }

          // Assign total to the orderDetails array
          $orderDetails['total'] = (string) $sub_total;
        }

        $itemList = [];

        foreach ($items as $item) {
          // Build details for each item
          $product_details_done = ProductImages::where('product_id', $item->product_id)->get();

          $pro_img = [];
          foreach ($product_details_done as $image) {
            $pro_img[] = url('assets/images/product_images/' . $image->product_image);
          }

          $product_details = Product::where('id', $item->product_id)->first();

          $itemDetails = [
            // 'order_id' => (string)$item->order_id,
            'user_id' => (string) $item->user_id,
            'product_id' => (string) $item->product_id,
            'quantity' => $item->quantity ? (string) $item->quantity : '',
            'price' => $item->price ? (string) $item->price : '',
            'product_color' => $item->product_color ? $item->product_color : '',
            'product_size' => $item->product_size ? (string) $item->product_size : '',
            'is_status' => $item->is_status ? (string) $item->is_status : '',
            'order_date' => $item->created_at->format('j M\'y'),
            'category_id' => $product_details->category_id ? (string) $product_details->category_id : '',
            'sub_category_id' => $product_details->sub_category_id ? (string) $product_details->sub_category_id : '',
            'product_name' => $product_details->product_name ? $product_details->product_name : '',
            'product_price' => $product_details->product_price ? $product_details->product_price : '',
            'product_sale_price' => $product_details->product_sale_price
              ? (string) $product_details->product_sale_price
              : '',
            'pro_img' => $pro_img,
          ];

          $itemList[] = $itemDetails;
        }

        $orderDetails['items'] = $itemList;
        $orderList[] = $orderDetails;
      }

      // print_r($items);
      // die;

      $temp = [
        'response_code' => '1',
        'message' => 'User Found',
        'status' => 'success',
        'orders' => $orderList,
      ];

      return response()->json($temp);
    } catch (\Throwable $th) {
      // return $this->sendError("Otp not send", $th->getMessage());
      $temp = [
        'response_code' => '0',
        'message' => 'User Not Found',
        'status' => 'success',
        'orders' => [],
      ];
      return response()->json($temp);
    }
  }

  public function get_vendor_cancel_orders()
  {
    try {
      // $myId = $request->user()->token()->user_id;
      $user_id = Auth::guard('sanctum')->user()->id;

      $orders = OrderItem::where('vendor_id', $user_id)
        ->where('is_status', '=', 'Cancle')
        ->distinct('order_id')
        ->orderBy('order_id', 'desc')
        ->pluck('order_id');

      $orderList = [];

      // print_r($orders);
      // die;

      foreach ($orders as $orderId) {
        $orderDetails = [];
        $orderDetails['order_id'] = (string) $orderId;

        $items = OrderItem::where('order_id', $orderId)
          ->where('vendor_id', $user_id)
          ->where('is_status', '=', 'Cancle')

          ->get();

        $order_details_done = Order::where('id', $orderId)->first();

        if ($items->isNotEmpty()) {
          $firstItem = $items->first();
          $orderDetails['order_id'] = (string) $orderId;
          $orderDetails['id'] = (string) $order_details_done->order_id;
          // $orderDetails['order_date'] = (string)$firstItem->created_at;
          // $orderDetails['order_date'] = $firstItem->created_at->format('j M\'y, g:i A');
          $orderDetails['order_status'] = (string) $firstItem->is_status;
          // $orderDetails['total'] = "0";
          // $total = $firstItem->price * $firstItem->quantity;
          // $orderDetails['total'] = (string)$total;
          $sub_total = 0;

          foreach ($items as $item) {
            // Calculate total for each item
            $total = $item->price * $item->quantity;
            $sub_total += $total;
          }

          // Assign total to the orderDetails array
          $orderDetails['total'] = (string) $sub_total;
        }

        $itemList = [];

        foreach ($items as $item) {
          // Build details for each item
          $product_details_done = ProductImages::where('product_id', $item->product_id)->get();

          $pro_img = [];
          foreach ($product_details_done as $image) {
            $pro_img[] = url('assets/images/product_images/' . $image->product_image);
          }

          $product_details = Product::where('id', $item->product_id)->first();

          //     $product_image = $product_details->product_image;
          //     if($product_image){
          //     $imgs = explode("::::", $product_image);
          //     $pro_img = array();
          //     foreach ($imgs as $img) {
          //         if (strpos($img, 'products_img/') !== false) {
          //             $image = new BaseController;
          //             $pro_img[] = $image->s3FetchFile($img);
          //         } else {
          //             $pro_img[] = url('/products_img/' . $img);
          //         }
          //     }
          // }else{
          //     $pro_img[] = [];
          // }
          $itemDetails = [
            // 'order_id' => (string)$item->order_id,
            'user_id' => (string) $item->user_id,
            'product_id' => (string) $item->product_id,
            'quantity' => $item->quantity ? (string) $item->quantity : '',
            'price' => $item->price ? (string) $item->price : '',
            'product_color' => $item->product_color ? $item->product_color : '',
            'product_size' => $item->product_size ? (string) $item->product_size : '',
            'is_status' => $item->is_status ? (string) $item->is_status : '',
            'order_date' => $item->created_at->format('j M\'y'),
            // 'coupon_code' => $item->coupon_code ? $item->coupon_code : "",
            // 'checked' => $item->checked ? (string)$item->checked : "",
            // ... add other item details as needed

            //  if ($product_details) {
            // 'product_id' => $product_details->product_id ?  (string)$product_details->product_id : "",
            'category_id' => $product_details->category_id ? (string) $product_details->category_id : '',
            'sub_category_id' => $product_details->sub_category_id ? (string) $product_details->sub_category_id : '',
            'product_name' => $product_details->product_name ? $product_details->product_name : '',
            'product_price' => $product_details->product_price ? $product_details->product_price : '',
            'product_sale_price' => $product_details->product_sale_price
              ? (string) $product_details->product_sale_price
              : '',
            'pro_img' => $pro_img,
          ];

          $itemList[] = $itemDetails;
        }

        $orderDetails['items'] = $itemList;
        $orderList[] = $orderDetails;
      }

      // print_r($items);
      // die;

      $temp = [
        'response_code' => '1',
        'message' => 'User Found',
        'status' => 'success',
        'orders' => $orderList,
      ];

      return response()->json($temp);
    } catch (\Throwable $th) {
      // return $this->sendError("Otp not send", $th->getMessage());
      $temp = [
        'response_code' => '0',
        'message' => 'User Not Found',
        'status' => 'success',
        'orders' => [],
      ];

      return response()->json($temp);
    }
  }

  public function get_vendor_diliver_orders()
  {
    try {
      // $myId = $request->user()->token()->user_id;
      $user_id = Auth::guard('sanctum')->user()->id;

      $orders = OrderItem::where('vendor_id', $user_id)
        ->where('is_status', '=', 'Deliver')
        ->distinct('order_id')
        ->orderBy('order_id', 'desc')
        ->pluck('order_id');

      $orderList = [];

      // print_r($orders);
      // die;

      foreach ($orders as $orderId) {
        $orderDetails = [];
        $orderDetails['order_id'] = (string) $orderId;

        $items = OrderItem::where('order_id', $orderId)
          ->where('vendor_id', $user_id)
          ->where('is_status', '=', 'Deliver')

          ->get();
        //         print_r($items);
        // die;

        $order_details_done = Order::where('id', $orderId)->first();

        if ($items->isNotEmpty()) {
          $firstItem = $items->first();
          $orderDetails['order_id'] = (string) $orderId;
          $orderDetails['id'] = (string) $order_details_done->order_id;
          // $orderDetails['order_date'] = (string)$firstItem->created_at;
          // $orderDetails['order_date'] = $firstItem->created_at->format('j M\'y, g:i A');
          $orderDetails['order_status'] = (string) $firstItem->is_status;
          // $orderDetails['total'] = "0";
          // $total = $firstItem->price * $firstItem->quantity;
          // $orderDetails['total'] = (string)$total;
          $sub_total = 0;

          foreach ($items as $item) {
            // Calculate total for each item
            $total = $item->price * $item->quantity;
            $sub_total += $total;
          }

          // Assign total to the orderDetails array
          $orderDetails['total'] = (string) $sub_total;
        }

        $itemList = [];

        foreach ($items as $item) {
          // Build details for each item
          $product_details_done = ProductImages::where('product_id', $item->product_id)->get();

          $pro_img = [];
          foreach ($product_details_done as $image) {
            $pro_img[] = url('assets/images/product_images/' . $image->product_image);
          }

          $product_details = Product::where('id', $item->product_id)->first();

          //     $product_image = $product_details->product_image;
          //     if($product_image){
          //     $imgs = explode("::::", $product_image);
          //     $pro_img = array();
          //     foreach ($imgs as $img) {
          //         if (strpos($img, 'products_img/') !== false) {
          //             $image = new BaseController;
          //             $pro_img[] = $image->s3FetchFile($img);
          //         } else {
          //             $pro_img[] = url('/products_img/' . $img);
          //         }
          //     }
          // }else{
          //     $pro_img[] = [];
          // }
          $itemDetails = [
            // 'order_id' => (string)$item->order_id,
            'user_id' => (string) $item->user_id,
            'product_id' => (string) $item->product_id,
            'quantity' => $item->quantity ? (string) $item->quantity : '',
            'price' => $item->price ? (string) $item->price : '',
            'product_color' => $item->product_color ? $item->product_color : '',
            'product_size' => $item->product_size ? (string) $item->product_size : '',
            'is_status' => $item->is_status ? (string) $item->is_status : '',
            'order_date' => $item->created_at->format('j M\'y'),
            // 'coupon_code' => $item->coupon_code ? $item->coupon_code : "",
            // 'checked' => $item->checked ? (string)$item->checked : "",
            // ... add other item details as needed

            //  if ($product_details) {
            // 'product_id' => $product_details->product_id ?  (string)$product_details->product_id : "",
            'category_id' => $product_details->category_id ? (string) $product_details->category_id : '',
            'sub_category_id' => $product_details->sub_category_id ? (string) $product_details->sub_category_id : '',
            'product_name' => $product_details->product_name ? $product_details->product_name : '',
            'product_price' => $product_details->product_price ? $product_details->product_price : '',
            'product_sale_price' => $product_details->product_sale_price
              ? (string) $product_details->product_sale_price
              : '',
            'pro_img' => $pro_img,
          ];

          $itemList[] = $itemDetails;
        }

        $orderDetails['items'] = $itemList;
        $orderList[] = $orderDetails;
      }

      // print_r($itemList);
      // die;

      $temp = [
        'response_code' => '1',
        'message' => 'User Found',
        'status' => 'success',
        'orders' => $orderList,
      ];

      return response()->json($temp);
    } catch (\Throwable $th) {
      // return $this->sendError("Otp not send", $th->getMessage());
      $temp = [
        'response_code' => '0',
        'message' => 'User Not Found',
        'status' => 'success',
        'orders' => [],
      ];

      return response()->json($temp);
    }
  }

  public function reviewRating(Request $request)
  {
    $user_id = Auth::guard('sanctum')->user()->id;
    if ($user_id) {
      if (
        !ReviewRating::where('user_id', $user_id)
          ->where('product_id', $request->product_id)
          ->exists()
      ) {
        if (
          Order::where('user_id', $user_id)
          ->where('product_id', $request->product_id)
          ->exists()
        ) {
          $data = [
            'user_id' => $user_id,
            'product_id' => $request->product_id,
            'review_star' => $request->review_star,
            'review_message' => $request->review_message,
          ];
          ReviewRating::create($data);
          return response()->json(
            [
              'message' => 'Review and Rating Successfully ...!',
              'data' => $data,
            ],
            200
          );
        } else {
          return response()->json(
            [
              'message' => 'You are not purchase this product ...!',
            ],
            200
          );
        }
      } else {
        return response()->json(
          [
            'message' => 'You are already given review and rating ...!',
          ],
          200
        );
      }
    }
  }

  public function diliver_details(Request $request)
  {
    $user_id = Auth::guard('sanctum')->user()->id;
    if (!OrderItem::where('order_id', $request->order_id)->first()) {
      return response()->json(
        [
          'message' => 'Order not found ...!',
        ],
        200
      );
    }
    $datas = OrderItem::with('productImages')
      ->select(
        'order_items.id as order_items_id',
        'order_items.order_id as orderID',
        'order_items.product_id',
        'order_items.delivery_date',
        'order_items.created_at',
        'order_items.user_id',
        'order_items.coupon_id',
        'order_items.payment_mode',
        'order_items.is_status',
        'order_items.address_id',
        'order_items.product_size',
        'order_items.product_color',
        'order_items.quantity',
        'orders.id',
        'orders.order_id',
        'products.id',
        'products.product_name',
        'products.product_price',
        'products.product_sale_price'
      )
      ->leftjoin('products', 'order_items.product_id', '=', 'products.id')
      ->leftjoin('orders', 'order_items.order_id', '=', 'orders.id')
      ->where('order_items.order_id', $request->order_id)
      //   ->where('order_items.user_id', $user_id)
      ->first();
    //   dd($datas->address_id);
    $bagTotalDatas = 0;
    // dd($datas->productImages);
    $productImage = [];
    foreach ($datas->productImages as $value) {
      $productImage[] = url('assets/images/product_images/' . $value->product_image);
    }
    //   dd($datas->delivery_date);
    $datas->delivery_date = Carbon::parse($datas->delivery_date)->format('l, j M');
    $createdAts = Carbon::parse($datas->created_at)->format('l, j M');
    $datas->createdAts = $createdAts;
    $datas->productImage = $productImage;
    if ($datas->address_id == null) {
      $datas->address_id = 0;
    }
    if ($datas->product_sale_price != null || $datas->product_sale_price > 0) {
      $datas->product_sale_price = $datas->product_sale_price * $datas->quantity;
      $bagTotalDatas += $datas->product_sale_price;
    } else {
      $datas->product_sale_price = $datas->product_price * $datas->quantity;
      $bagTotalDatas += $datas->product_sale_price;
    }
    $couponPrice = 0;
    if ($datas->coupon_id) {
      $couponPrice = Coupon::where('id', $datas->coupon_id)->first()->discount_amount ?? 0;
    }
    if ($datas->payment_mode) {
      $datas->payment_mode = $datas->payment_mode;
    } else {
      $datas->payment_mode = '';
    }
    $ratings = ProductReview::select('review_star')
      ->where('user_id', $user_id)
      ->where('product_id', $datas->product_id)
      ->first();
    $datas->ratings = $ratings->review_star ?? '';

    if ($datas->address_id) {
      $addresss = UserAddress::select(
        'id',
        'first_name',
        'last_name',
        'mobile',
        'pincode',
        'address',
        'locality',
        'city',
        'state',
        'type'
      )
        ->where('id', $datas->address_id)
        ->where('address_flag', 'Y')
        ->first();
    } elseif ($datas->address_id == 0 || $datas->address_id == '') {
      $addresss = UserAddress::select(
        'id',
        'first_name',
        'last_name',
        'mobile',
        'pincode',
        'address',
        'locality',
        'city',
        'state',
        'type'
      )
        ->where('user_id', $user_id)
        ->where('address_flag', 'Y')
        ->where('last_address_status', '1')
        ->first();
    } else {
      $addresss = [
        'id' => 0,
        'first_name' => '',
        'last_name' => '',
        'mobile' => '',
        'pincode' => '',
        'address' => '',
        'locality' => '',
        'city' => '',
        'state' => '',
        'type' => '',
      ];
    }
    // }

    $data = OrderItem::select(
      'order_items.id as order_items_id',
      'order_items.order_id as main_order_id',
      'order_items.product_id',
      'order_items.delivery_date',
      'order_items.created_at',
      'order_items.user_id',
      'order_items.coupon_id',
      'order_items.payment_mode',
      'order_items.is_status',
      'order_items.address_id',
      'order_items.product_size',
      'order_items.product_color',
      'order_items.quantity',
      'orders.id',
      'orders.order_id',
      'products.id',
      'products.product_name',
      'products.product_price',
      'products.product_sale_price'
    )
      ->leftjoin('products', 'order_items.product_id', '=', 'products.id')
      ->leftjoin('orders', 'order_items.order_id', '=', 'orders.id')
      ->where('order_items.order_id', $request->order_id)
      //   ->where('order_items.is_status', "Cancle")
      //  ->where('order_items.vendor_id', $user_id)
      //   ->whereNotIn('order_items.id', $request->order_id)
      //   ->whereNotIn('order_items.id', [$request->order_id])
      ->get();
    //   dd($data);

    // $totalBagTotal
    $bagTotalData = 0;
    $couponPrice = 0;
    foreach ($data as $value) {
      if ($value->address_id == null) {
        $value->address_id = 0;
      }
      $productImage = [];
      //   foreach ($value->productImages as $val) {
      //     $productImage[] = url('assets/images/product_images/' . $val->product_image);
      //   }
      $value->delivery_date = Carbon::parse($value->delivery_date);
      $value->delivery_date = $value->delivery_date->format('l, j M');
      $createdAts = Carbon::parse($value->created_at)->format('l, j M');
      $value->createdAts = $createdAts;
      //   $value->productImage = $productImage;

      if ($value->product_sale_price != null || $value->product_sale_price > 0) {
        $bagTotalData += $value->product_sale_price * $value->quantity;
        // $bagTotalData = $product_sale_price;
      } else {
        $bagTotalData += $value->product_price * $value->quantity;
        // $bagTotalData = $product_sale_price;
      }
      $couponPrice = 0;
      if ($value->coupon_id) {
        $couponPrice = Coupon::where('id', $value->coupon_id)->first()->discount_amount ?? 0;
      }
      if ($value->payment_mode) {
        $value->payment_mode = $value->payment_mode;
      } else {
        $value->payment_mode = '';
      }
      $ratings = ProductReview::select('review_star')
        ->where('user_id', $user_id)
        ->where('product_id', $value->product_id)
        ->first();
      $value->ratings = $ratings->review_star ?? '';

      if ($value->address_id) {
        $address = UserAddress::select(
          'id',
          'first_name',
          'last_name',
          'mobile',
          'pincode',
          'address',
          'locality',
          'city',
          'state',
          'type'
        )
          ->where('id', $value->address_id)
          ->first();
      } elseif ($value->address_id == 0 || $value->address_id == null) {
        $address = UserAddress::select(
          'id',
          'first_name',
          'last_name',
          'mobile',
          'pincode',
          'address',
          'locality',
          'city',
          'state',
          'type'
        )
          ->where('user_id', $user_id)
          ->where('last_address_status', '1')
          ->first();
      } else {
        $address = [
          'id' => 0,
          'first_name' => '',
          'last_name' => '',
          'mobile' => '',
          'pincode' => '',
          'address' => '',
          'locality' => '',
          'city' => '',
          'state' => '',
          'type' => '',
        ];
      }
      //   if($value->address_id == null){
      //   $value->address_id = '';
      //   }
    }
    $totalBagTotal = $bagTotalData;
    //  $totalBagTotal = $bagTotalData + $bagTotalDatas;
    $orderDetail = [
      'bagTotal' => $bagTotalData,
      'couponPrice' => $couponPrice,
      'totalAmount' => $totalBagTotal - $couponPrice,
    ];

    return response()->json(
      [
        'status' => 'true',
        'message' => 'Order Detail ...!',
        // 'datas' => [$datas],
        'orderDetail' => $orderDetail,
        'address' => $addresss,
        'datas' => $data,
      ],
      200
    );
  }

  public function deleteAccount()
  {
    $user_id = Auth::guard('sanctum')->user()->id;
    User::where('id', $user_id)->delete();
    return response()->json([
      'success' => true,
      'message' => 'Your account deleted successfully',
    ]);
  }

  public function productReviewList(Request $request)
  {
    if (ProductReview::where('product_id', $request->product_id)->exists()) {
      $productDetails = ProductReview::select(
        'product_reviews.id',
        'product_reviews.product_id',
        'product_reviews.user_id',
        'product_reviews.review_star',
        'product_reviews.review_message',
        'product_reviews.created_at',
        'users.first_name',
        'users.last_name',
        'users.image'
      )
        ->leftjoin('users', 'product_reviews.user_id', '=', 'users.id')
        ->where('product_id', $request->product_id)
        ->orderBy('id', 'desc')
        ->get();
      foreach ($productDetails as $value) {
        if ($value->review_message == null && $value->review_message == '') {
          $value->review_message = '';
        }
        if ($value->image == '' || $value->image == null) {
          $value->image = url('assets/images/users_images/default.png');
        } else {
          $value->image = url('assets/images/users_images/' . $value->image);
        }
        if ($value->first_name || $value->last_name) {
          $value->first_name = $value->first_name;
          $value->last_name = $value->last_name;
        } else {
          $value->first_name = '';
          $value->last_name = '';
        }
        $value->createdAts = $value->created_at ? $value->created_at->diffForHumans() : '';
        $totalProductReview = ProductReview::where('product_id', $request->product_id)->count();
      }
      return response()->json([
        'Success' => true,
        'productReviews' => $productDetails,
      ]);
    } else {
      return response()->json([
        'Success' => false,

        'message' => 'product not found',
      ]);
    }
  }

  public function product_slist(Request $request)
  {
    $product = Product::select(
      'products.id',
      'products.category_id',
      'products.sub_category_id',
      'products.product_name',
      'products.product_sale_price',
      'products.product_price',
      'sub_categories.sub_category_name',
      'products.product_color',
      'products.product_size'
    )
      ->leftjoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
      ->where('products.category_id', $request->category_id);
    if ($request->category_id != '') {
      if ($request->desc != '') {
        $product->orderBy('product_price', $request->desc);
      } elseif ($request->asc != '') {
        $product->orderBy('product_price', $request->asc);
      } elseif ($request->product_size != '') {
        $sizes = explode(',', $request->product_size);
        $product->where(function ($product) use ($sizes) {
          foreach ($sizes as $size) {
            $product->where('product_size', 'LIKE', "%$size%");
          }
        });
      } elseif ($request->product_colors != '') {
        // dd($request->product_colors);
        $colors = explode(',', $request->product_colors);
        $product->where(function ($product) use ($colors) {
          foreach ($colors as $color) {
            $product->where('product_color', 'LIKE', "%$color%");
          }
        });
      } elseif ($request->rating != '') {
        $product->where('product_reviews.review_star', 'LIKE', "%$request->rating%");
      }
      $product = $product->get();
      $productFilter = [];
      foreach ($product as $value) {
        $productFilter = [];
        $productColor = explode(',', $value->product_color);
        $productSize = explode(',', $value->product_size);

        foreach ($productColor as $val) {
          if ($val) {
            $totalProductColor[] = $val;
          } else {
            $totalProductColor = [];
          }
        }
        foreach ($productSize as $val) {
          if ($val) {
            $totalproductSize[] = $val;
          } else {
            $totalproductSize = [];
          }
        }
        $productFilter[] = [
          'totalProductColor' => $totalProductColor,
          'totalproductSize' => $totalproductSize,
        ];
        if ($value->product_sale_price != '' && $value->product_sale_price > 0) {
          $value->product_sale_price = $value->product_sale_price;
        } else {
          $value->product_sale_price = $value->product_price;
        }
        $value->product_size = '';
        // dd($value->product_size);
        $value->product_color = '';
        $totalProductImages = [];
        $productImages = ProductImages::select('product_image')
          ->where('product_id', $value->id)
          ->get();
        foreach ($productImages as $val) {
          if ($val->product_image) {
            $totalProductImages[] = url('assets/images/product_images/' . $val->product_image);
          } else {
            $totalProductImages = [];
          }
        }
        $totalProductReview = 0;
        $totalReviewStar = 0;
        $totalProductReview = ProductReview::select('product_id', 'review_star')
          ->where('product_id', $value->id)
          ->get();
        $totalReviewStar = 0;
        $totalAvgReview = 0;
        foreach ($totalProductReview as $val) {
          $reviewStar = floatval($val->review_star);
          $totalReviewStar = $totalReviewStar + $reviewStar;
        }
        $value->product_image = $totalProductImages;
        $totalReviewCount = $totalProductReview->count();

        if ($totalReviewCount) {
          (string) ($value->totalReviewCount = (string) $totalReviewCount);
        } else {
          $value->totalReviewCount = '';
        }
        if ($totalReviewCount > 0) {
          $totalAvgReview = round($totalReviewStar / $totalReviewCount, 1);
        }
        (string) ($value->totalAvgReview = (string) $totalAvgReview);

        $value->product_image = $totalProductImages;
        $productUserLike = ProductLike::where('user_id', $request->user_id)
          ->where('product_id', $value->id)
          ->first();
        if ($productUserLike) {
          $value->is_Like = 1;
        } else {
          $value->is_Like = 0;
        }
      }
      return response()->json([
        'message' => 'product found',
        'productFilter' => $productFilter,
        'product' => $product,
      ]);
    } else {
      return response()->json([
        'message' => 'Category not found',
      ]);
    }
  }
  public function contactUs(Request $request)
  {
    $user_id = Auth::guard('sanctum')->user()->id;
    if (
      Order::where('user_id', $user_id)
      ->where('order_id', $request->order_id)
      ->exists()
    ) {
      if (
        User::where('id', $user_id)
        ->where('first_name', $request->first_name)
        ->where('last_name', $request->last_name)
        ->exists()
      ) {
        $user = User::where('id', $user_id)->first();
        if (
          !CreateTicket::where('order_id', $request->order_id)
            ->where('status', 1)
            ->exists()
        ) {
          $ticketID = rand(100000, 999999);
          $ticket = new CreateTicket();
          $ticket->ticket_id = $ticketID;
          $ticket->user_id = $user_id;
          $ticket->message = $request->message ?? '';
          $ticket->order_id = $request->order_id;
          $ticket->save();
          $data = new ContactUs();
          $data->user_id = $user_id;
          $data->first_name = $user->first_name;
          $data->last_name = $user->last_name;
          $data->email = $user->email ?? '';
          $data->mobile = $user->mobile ?? '';
          $data->order_id = $request->order_id ?? '';
          $data->from_user = $user_id;
          $data->to_user = Admin::first()->id;
          $data->message = $request->message ?? '';
          $data->ticket_id = $ticket->id;
          $data->save();
          return response()->json([
            'success' => true,
            'message' => 'Success',
          ]);
        } elseif (
          CreateTicket::where('order_id', $request->order_id)
          ->where('status', '1')
          ->exists()
        ) {
          $ticket = CreateTicket::where('order_id', $request->order_id)->first();
          $data = new ContactUs();
          $data->user_id = $user_id;
          $data->first_name = $user->first_name;
          $data->last_name = $user->last_name;
          $data->email = $user->email ?? '';
          $data->mobile = $user->mobile ?? '';
          $data->order_id = $request->order_id ?? '';
          $data->from_user = $user_id;
          $data->to_user = Admin::first()->id;
          $data->message = $request->message ?? '';
          $data->ticket_id = $ticket->id;
          $data->save();
        }
        return response()->json([
          'success' => true,
          'message' => 'Success',
        ]);
      } else {
        $user = User::where('id', $user_id)->first();
        if (User::where('id', $user_id)->exists()) {
          $user = User::where('id', $user_id)->first();
          if (!CreateTicket::where('order_id', $request->order_id)->exists()) {
            $ticketID = rand(100000, 999999);
            $ticket = new CreateTicket();
            $ticket->ticket_id = $ticketID;
            $ticket->user_id = $user_id;
            $ticket->message = $request->message ?? '';
            $ticket->order_id = $request->order_id;
            $ticket->save();

            $data = new ContactUs();
            $data->user_id = $user_id;
            $data->first_name = $request->first_name;
            $data->last_name = $request->last_name;
            $data->email = $user->email ?? '';
            $data->mobile = $user->mobile ?? '';
            $data->order_id = $request->order_id ?? '';
            $data->from_user = $user_id;
            $data->to_user = Admin::first()->id;
            $data->message = $request->message ?? '';
            $data->ticket_id = $ticket->id;
            $data->save();
            $user = User::where('id', $user_id)->update([
              'first_name' => $request->first_name,
              'last_name' => $request->last_name,
            ]);
            return response()->json([
              'success' => true,
              'message' => 'Success',
            ]);
          } elseif (CreateTicket::where('order_id', $request->order_id)->exists()) {
            $ticket = CreateTicket::where('order_id', $request->order_id)->first();
            $data = new ContactUs();
            $data->user_id = $user_id;
            $data->first_name = $request->first_name;
            $data->last_name = $request->last_name;
            $data->email = $user->email ?? '';
            $data->mobile = $user->mobile ?? '';
            $data->order_id = $request->order_id ?? '';
            $data->from_user = $user_id;
            $data->to_user = Admin::first()->id;
            $data->message = $request->message ?? '';
            $data->ticket_id = $ticket->id;
            $data->save();
            $user = User::where('id', $user_id)->update([
              'first_name' => $request->first_name,
              'last_name' => $request->last_name,
            ]);
            return response()->json([
              'success' => true,
              'message' => 'Success',
            ]);
          }
        }
      }
    } else {
      return response()->json([
        'success' => false,
        'message' => 'Order ID does not exists',
      ]);
    }
  }
  public function getContactUs(Request $request)
  {
    $user_id = Auth::guard('sanctum')->user()->id;
    $user = User::where('id', $user_id)->first();
    $data = [
      'user_id' => $user_id,
      'first_name' => $user->first_name ?? '',
      'last_name' => $user->last_name ?? '',
      'email' => $user->email ?? '',
      'mobile' => $user->mobile ?? '',
    ];
    return response()->json([
      'success' => true,
      'data' => $data,
    ]);
  }
  public function getReviewRating(Request $request)
  {
    $user_id = Auth::guard('sanctum')->user()->id;
    $user = User::where('id', $user_id)->first();
    $data = [
      'user_id' => $user_id,
      'first_name' => $user->first_name ?? '',
      'last_name' => $user->last_name ?? '',
    ];
    return response()->json([
      'success' => true,
      'data' => $data,
    ]);
  }

  public function getMultipleAddress(Request $request)
  {
    $user_id = Auth::guard('sanctum')->user()->id;
    if ($user_id) {
      $address = UserAddress::where('user_id', $user_id)
        ->where('user_address.id', $request->address_id)
        ->get();
      dd($address);
      return response()->json(
        [
          'message' => 'Address Get Successfully ...!',
          'address' => $address,
        ],
        200
      );
    }
  }
  // public function couponList(Request $request)
  // {
  //   $user = Auth::guard('sanctum')->user()->id;
  //   if ($user) {
  //     $couponList = Coupon::get();
  //     return response()->json([
  //       'message' => 'Success...!',
  //       'data' => $couponList
  //     ], 201);
  //   }
  // }

  //   public function updateProfile(Request $request)
  //   {
  //     // dd($request->all());
  //     $user_id = Auth::guard('sanctum')->user()->id;
  //     // dd($user_id);
  //     $dateOfbirth = date('Y-m-d', strtotime($request->dob));
  //     $data = [
  //       'first_name' => $request->first_name,
  //       'last_name' => $request->last_name,
  //       'username' => $request->username,
  //       'dob' => $dateOfbirth,
  //       'gender' => $request->gender,
  //     ];
  //     $data = User::where('id', $user_id)->update($data);
  //     return response([
  //       'success' => true,
  //       'message' => 'Profile updated successfully ...!',
  //     ]);
  //   }

  public function privacyPolicy()
  {
    $setting = Setting::get();
    return response([
      'success' => true,
      'message' => 'Success...!',
      'data' => $setting,
    ]);
  }

  public function setting()
  {
    $stripe = Settings::where('id', 1)->first();
    $rozerpay = Settings::where('id', 2)->first();
    $flutterwave = Settings::where('id', 3)->first();
    return response()->json([
      'success' => true,
      'datas' => ['stripes' => $stripe, 'rozerpay' => $rozerpay, 'flutterwave' => $flutterwave],
    ]);
  }
  public function getNotification(Request $request)
  {
    $user_id = Auth::guard('sanctum')->user()->id;
    Notifications::where('user_id', $user_id)->update(['is_seen' => $request->is_seen]);
    $notification = Notifications::where('user_id', $user_id)->get();
    return response()->json([
      'success' => true,
      'datas' => $notification,
    ]);
  }

  public function getSubCategory(Request $request)
  {
    if (!empty($request->category_id)) {
      if (SubCategory::where('category_id', $request->category_id)->exists()) {
        $subCategory = SubCategory::select('id', 'category_id', 'sub_category_name')
          ->where('category_id', $request->category_id)
          ->get();
        return response()->json([
          'success' => true,
          'message' => 'Subcategory list ....!',
          'data' => $subCategory,
        ]);
      } else {
        return response()->json([
          'success' => false,
          'message' => 'category not found ....!',
          'data' => '',
        ]);
      }
    } else {
      return response()->json([
        'success' => false,
        'message' => 'Please select category',
        'data' => '',
      ]);
    }
  }

  public function forgotPassword2(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|exists:users',
    ]);

    if ($validator->fails()) {
      return $this->sendError('Error validation', $validator->errors());
    }

    if ($request->email != '') {
      if (!User::where('email', $request->email)->first()) {
        return response()->json([
          'success' => false,
          'message' => 'Email not found...!',
        ]);
      } else {
        $email = $request->email;
        // $otp = Str::random(8);
        $otp = random_int(10000000, 99999999);
        User::where('email', $request->email)->update(['password' => bcrypt($otp)]);
        $userDetails = User::where('email', $request->email)->first();
        $messageData = ['email' => $userDetails->email, 'otp' => $otp];

        try {
          Mail::send('otp', $messageData, function ($message) use ($email) {
            $message->to($email)->subject('Your OTP');
          });

          return response()->json(
            [
              'success' => true,
              'user_id' => $userDetails->id,
              'email' => $request->email,
              'message' => 'OTP sent successfully',
            ],
            200
          );
        } catch (\Exception $e) {
          return response()->json(
            [
              'success' => false,
              'message' => 'Failed to send OTP',
              'error' => $e->getMessage(),
            ],
            400
          );
        }
      }
    }
  }

  // Website
  // Dashboard product list
  // public function dashboardproductList(Request $request)
  // {
  //   // dd($request->all());
  //   $product = Product::select(
  //     'products.id',
  //     'products.category_id',
  //     'products.sub_category_id',
  //     'products.product_name',
  //     'products.product_about',
  //     'products.product_sale_price',
  //     'products.product_price',
  //     'sub_categories.sub_category_name',
  //     'products.product_color',
  //     'products.product_size'
  //   )
  //     ->leftjoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id');

  //   if ($request->has('category_id')) {
  //     $product->where('products.category_id', $request->category_id);
  //   }

  //   if ($request->has('desc')) {
  //     $product->orderBy('products.created_at', 'desc'); // Sort by products' created_at timestamp in descending order
  //   } elseif ($request->has('asc')) {
  //     $product->orderBy('products.created_at', 'asc'); // Sort by products' created_at timestamp in ascending order
  //   } else {
  //     $product->orderBy('products.created_at', 'desc'); // Default sorting by products' created_at timestamp in descending order
  //   }

  //   if ($request->has('product_size')) {
  //     $sizes = explode(',', $request->product_size);
  //     $product->where(function ($query) use ($sizes) {
  //       foreach ($sizes as $size) {
  //         $query->where('product_size', 'LIKE', "%$size%");
  //       }
  //     });
  //   } elseif ($request->has('product_colors')) {
  //     $colors = explode(',', $request->product_colors);
  //     $product->where(function ($query) use ($colors) {
  //       foreach ($colors as $color) {
  //         $query->where('product_color', 'LIKE', "%$color%");
  //       }
  //     });
  //   }

  //   $product = $product->get();
  //   $productFilter = [];

  //   $uniqueColor = [];
  //   $uniqueSize = [];
  //   $totalProductColor = [];
  //   $totalproductSize = [];
  //   foreach ($product as $value) {
  //     $productColor = explode(',', $value->product_color);
  //     $productSize = explode(',', $value->product_size);

  //     foreach ($productColor as $val) {
  //       if ($val) {
  //         $totalProductColor[] = $val;
  //       }
  //     }
  //     foreach ($productSize as $val) {
  //       if ($val) {
  //         $totalproductSize[] = $val;
  //       }
  //     }

  //     foreach ($totalProductColor as $colors) {
  //       if (!in_array($colors, $uniqueColor)) {
  //         $uniqueColor[] = $colors;
  //       }
  //     }
  //     foreach ($totalproductSize as $size) {
  //       if (!in_array($size, $uniqueSize)) {
  //         $uniqueSize[] = $size;
  //       }
  //     }

  //     $productFilter = [
  //       'totalProductColor' => $uniqueColor,
  //       'totalproductSize' => $uniqueSize
  //     ];
  //     if ($value->product_sale_price != '' && $value->product_sale_price > 0) {
  //       $value->product_sale_price = $value->product_sale_price;
  //     } else {
  //       $value->product_sale_price = $value->product_price;
  //     }
  //     $totalProductImages = [];
  //     $productImages = ProductImages::select('product_image')
  //       ->where('product_id', $value->id)
  //       ->get();
  //     foreach ($productImages as $val) {
  //       if ($val->product_image) {
  //         $totalProductImages = url('/assets/images/product_images/' . $val->product_image);
  //       } else {
  //         $totalProductImages = [];
  //       }
  //     }
  //     $totalProductReview = 0;
  //     $totalReviewStar = 0;
  //     $totalProductReview = ProductReview::select('product_id', 'review_star')
  //       ->where('product_id', $value->id)
  //       ->get();

  //     $totalReviewStar = 0;
  //     $totalAvgReview = 0;
  //     foreach ($totalProductReview as $val) {
  //       $reviewStar = floatval($val->review_star);
  //       $totalReviewStar = $totalReviewStar + $reviewStar;
  //     }
  //     $value->product_image = $totalProductImages;
  //     $totalReviewCount = $totalProductReview->count();

  //     if ($totalReviewCount) {
  //       (string) ($value->totalReviewCount = (string) $totalReviewCount);
  //     } else {
  //       $value->totalReviewCount = '';
  //     }
  //     if ($totalReviewCount > 0) {
  //       $totalAvgReview = round($totalReviewStar / $totalReviewCount, 1);
  //     }
  //     (string) ($value->totalAvgReview = (string) $totalAvgReview);

  //     $value->product_image = $totalProductImages;
  //     $productUserLike = ProductLike::where('user_id', $request->user_id)
  //       ->where('product_id', $value->id)
  //       ->first();
  //     if ($productUserLike) {
  //       $value->is_Like = 1;
  //     } else {
  //       $value->is_Like = 0;
  //     }
  //   }
  //   //   $product=$product->where('totalAvgReview',$request->rating);
  //   //   $product = $product->where('totalAvgReview', 'LIKE', '%' . $request->rating . '%');
  //   //   dd($product);

  //   return response()->json([
  //     'message' => 'product found',
  //     'productFilter' => $productFilter,
  //     'product' => $product,
  //   ]);
  // }

  // Product List
  // public function productList2(Request $request)
  // {
  //   $perPage = $request->input('per_page', 12); // Number of items per page, default to 12
  //   $page = $request->input('page', 1); // Current page, default to 1

  //   $productQuery = Product::select(
  //     'products.id',
  //     'products.category_id',
  //     'products.sub_category_id',
  //     'products.product_name',
  //     'products.product_about',
  //     'products.product_sale_price',
  //     'products.product_price',
  //     'sub_categories.sub_category_name',
  //     'products.product_color',
  //     'products.product_size'
  //   )->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id');

  //   if ($request->has('category_id')) {
  //     $productQuery->where('products.category_id', $request->category_id);
  //   }

  //   if ($request->has('desc')) {
  //     $productQuery->orderBy('products.created_at', 'desc'); // Sort by products' created_at timestamp in descending order
  //   } elseif ($request->has('asc')) {
  //     $productQuery->orderBy('products.created_at', 'asc'); // Sort by products' created_at timestamp in ascending order
  //   } else {
  //     $productQuery->orderBy('products.created_at', 'desc'); // Default sorting by products' created_at timestamp in descending order
  //   }

  //   if ($request->has('product_size')) {
  //     $sizes = explode(',', $request->product_size);
  //     $productQuery->where(function ($query) use ($sizes) {
  //       foreach ($sizes as $size) {
  //         $query->where('product_size', 'LIKE', "%$size%");
  //       }
  //     });
  //   } elseif ($request->has('product_colors')) {
  //     $colors = explode(',', $request->product_colors);
  //     $productQuery->where(function ($query) use ($colors) {
  //       foreach ($colors as $color) {
  //         $query->where('product_color', 'LIKE', "%$color%");
  //       }
  //     });
  //   }

  //   $products = $productQuery->paginate($perPage, ['*'], 'page', $page);

  //   $productFilter = [
  //     'totalProductColor' => $products->pluck('product_color')->flatten()->unique()->values()->all(),
  //     'totalproductSize' => $products->pluck('product_size')->flatten()->unique()->values()->all()
  //   ];

  //   foreach ($products as $product) {
  //     if ($product->product_sale_price != '' && $product->product_sale_price > 0) {
  //       $product->product_sale_price = $product->product_sale_price;
  //     } else {
  //       $product->product_sale_price = $product->product_price;
  //     }

  //     $productImages = ProductImages::select('product_image')
  //       ->where('product_id', $product->id)
  //       ->get();
  //     $totalProductImages = [];
  //     foreach ($productImages as $val) {
  //       if ($val->product_image) {
  //         $totalProductImages = url('/assets/images/product_images/' . $val->product_image);
  //       } else {
  //         $totalProductImages = [];
  //       }
  //     }
  //     $product->product_image = $totalProductImages;

  //     $totalProductReview = ProductReview::select('product_id', 'review_star')
  //       ->where('product_id', $product->id)
  //       ->get();

  //     $totalReviewStar = 0;
  //     $totalAvgReview = 0;
  //     foreach ($totalProductReview as $val) {
  //       $reviewStar = floatval($val->review_star);
  //       $totalReviewStar = $totalReviewStar + $reviewStar;
  //     }

  //     $totalReviewCount = $totalProductReview->count();

  //     $product->totalReviewCount = $totalReviewCount ? (string) $totalReviewCount : '';
  //     if ($totalReviewCount > 0) {
  //       $totalAvgReview = round($totalReviewStar / $totalReviewCount, 1);
  //     }
  //     $product->totalAvgReview = (string) $totalAvgReview;

  //     $productUserLike = ProductLike::where('user_id', $request->user_id)
  //       ->where('product_id', $product->id)
  //       ->first();
  //     $product->is_Like = $productUserLike ? 1 : 0;
  //   }

  //   return response()->json([
  //     'message' => 'Products found',
  //     'productFilter' => $productFilter,
  //     'products' => $products,
  //   ]);
  // }

  public function WalletPaymentSuccess(Request $request)
  {
    $user_id = Auth::guard('sanctum')->user()->id;
    $validator = Validator::make($request->all(), [
      'user_id' => 'required',
      'payment_method' => 'required',
      'amount' => 'required',
      'status' => 'required|in:add,remove', // Ensure status is either 'add' or 'remove'
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          'message' => 'Validation failed',
          'errors' => $validator->errors(),
        ],
        JsonResponse::HTTP_UNPROCESSABLE_ENTITY
      );
    }

    $data = new Wallet();
    $data->user_id = $request->user_id;
    $data->payment_method = $request->payment_method;
    $data->amount = $request->amount;
    $data->status = $request->status;

    $data->save();

    // Update user wallet balance based on status
    if ($request->status === 'add') {
      User::where('id', $request->user_id)->increment('wallet_balance', $request->amount);
      $message = 'Amount added to wallet successfully.';
    } elseif ($request->status === 'remove') {
      User::where('id', $request->user_id)->decrement('wallet_balance', $request->amount);
      $message = 'Amount removed from wallet successfully.';
    }

    return response([
      'message' => $message,
      'data' => $data,
    ]);
  }

  public function WalletCheckout2(Request $request)
  {
    $user = Auth::guard('sanctum')->user();
    if ($user) {
      $user_id = $user->id;
      $wallet_balance = $user->wallet_balance;

      // Fetch order details from cart
      $orderDetail = CartItem::select(
        'cart_items.user_id',
        'cart_items.product_id',
        'cart_items.product_color',
        'cart_items.product_size',
        'cart_items.quantity',
        'cart_items.coupon_id',
        'cart_items.address_id',
        'products.id',
        'products.product_price',
        'products.product_sale_price'
      )
        ->leftJoin('products', 'products.id', '=', 'cart_items.product_id')
        ->where('user_id', $user_id)
        ->get();

      $total_amount = 0;

      // Calculate total amount of the order
      foreach ($orderDetail as $value) {
        if ($value->product_sale_price != null || $value->product_sale_price > 0) {
          $value->product_sale_price = $value->product_sale_price * $value->quantity;
          $total_amount += $value->product_sale_price;
        } else {
          $value->product_sale_price = $value->product_price * $value->quantity;
          $total_amount += $value->product_sale_price;
        }

        // Deduct coupon amount if applicable
        $CouponDetail = Coupon::where('id', $value->coupon_id)->first();
        if ($CouponDetail) {
          $total_amount -= $CouponDetail->discount_amount;
        }
      }

      // Check if user has sufficient balance
      if ($wallet_balance >= $total_amount) {
        // Sufficient balance, proceed with order placement
        $order = new Order();
        // Populate order details
        $orderId = '';
        $coupon_id = 0;
        $address_id = 0;
        $lastOrder = Order::orderBy('id', 'desc')->first();
        if ($lastOrder) {
          $lastOrderId = $lastOrder->order_id;
          $orderId = str_pad($lastOrderId + 1, 4, '0', STR_PAD_LEFT);
        } else {
          $orderId = '0001';
        }

        // Populate order details
        $order->user_id = $user_id;
        $order->order_id = $orderId;
        $order->total_item = $orderDetail->count();
        $order->address_id = $address_id;
        $order->coupon_id = $coupon_id;
        $order->payment_mode = $request->payment_mode ?? '';
        $order->order_status = 1;
        $order->total_amount = $total_amount;
        $order->save();

        // Deduct amount from user's wallet
        $user->wallet_balance -= $total_amount;
        $user->save();

        // Create order items
        foreach ($orderDetail as $value) {
          $data = [
            'user_id' => $user_id,
            'order_id' => $order->id, // Use the order ID from the previously created order
            'address_id' => $value->address_id,
            'product_id' => $value->product_id,
            'product_color' => $value->product_color,
            'product_size' => $value->product_size,
            'coupon_id' => $coupon_id,
            'quantity' => $value->quantity,
          ];
          OrderItem::create($data);
        }

        // Clear cart items
        CartItem::where('user_id', $user_id)->delete();

        // Mark coupon as used if applicable
        if ($coupon_id) {
          Coupon::where('id', $coupon_id)->update(['status' => '1']);
        }

        // Send notification to user
        $notification = new Notifications([
          'title' => 'Order',
          'message' => 'Your order has been created successfully',
          'sender_id' => Admin::first()->id,
        ]);
        $FcmToken = $user->device_token;
        $data = [
          'registration_ids' => [$FcmToken],
          'notification' => [
            'title' => 'Order created ',
            'message' => 'Your order has been created successfully',
            'type' => 'verified',
            'sender_id' => Admin::first()->id,
          ],
          'data' => [
            'title' => 'Order created ',
            'sender_id' => Admin::first()->id,
            'message' => 'Your order has been created successfully',
            'user_id' => $user_id,
            'type' => 'verified',
          ],
        ];

        $this->sendNotification($data);
        $user->notifications()->save($notification);

        return response()->json(
          [
            'status' => true,
            'message' => 'Order Placed Successfully...!',
          ],
          201
        );
      } else {
        // Insufficient balance, return error response
        return response()->json(
          [
            'status' => false,
            'message' => 'Insufficient balance in wallet...!',
          ],
          422
        );
      }
    } else {
      return response()->json(
        [
          'status' => false,
          'message' => 'Unauthorized...!',
        ],
        401
      );
    }
  }
}
