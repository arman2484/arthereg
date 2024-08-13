<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\VendorStore;
use App\Models\Notifications;
use App\Models\OrderItem;
use App\Http\Resources\UserResource;
use App\Http\Resources\VendorResource;
use App\Models\Banner;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\Coupon;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\ProductReview;
use App\Models\Variant;

class VendorController extends BaseController
{
  // Vendor Register
  // public function vendorRegister(Request $request)
  // {
  //   $rules = [
  //     'first_name' => 'required',
  //     'last_name' => 'required',
  //     'email' => 'required|email|unique:vendors',
  //     'password' => 'required|min:6',
  //     'mobile' => 'required',
  //   ];
  //   $customMessages = [
  //     'first_name.required' => 'Please enter your first_name.',
  //     'last_name.required' => 'Please enter your last_name.',
  //     'email.required' => 'Please enter your email.',
  //     'email.unique' => 'This email is already taken.',
  //     'password.required' => 'Please enter your password.',
  //     'mobile.required' => 'Please enter your phone number.',
  //   ];
  //   $validator = Validator::make($request->all(), $rules, $customMessages);
  //   if ($validator->fails()) {
  //     $errors = $validator->errors();

  //     $responseErrors = [];
  //     foreach ($errors->all() as $error) {
  //       $responseErrors = $error;
  //     }

  //     return response([
  //       'success' => false,
  //       'email' => $request->email ?? "",
  //       'message' => $responseErrors,
  //     ], 422);
  //   }

  //   $input = $request->all();
  //   if (Vendor::where('email', $input['email'])->exists()) {
  //     return response([
  //       'email' => (string) $request->email,
  //       'message' => 'Email Already Register..!',
  //     ]);
  //   }
  //   $input['password'] = bcrypt($input['password']);
  //   $vendor = Vendor::create($input);
  //   $success['token'] =  $vendor->createToken('vendorApp')->accessToken;
  //   $success['first_name'] =  $vendor->first_name;
  //   $success['last_name'] =  $vendor->last_name;
  //   $success['mobile'] =  $vendor->mobile;

  //   if (Auth::guard('vendor')->attempt(['email' => $request->email, 'password' => $request->password])) {
  //     $authVendor = Auth::guard('vendor')->user();
  //     $token = $authVendor->createToken('vendorApp')->plainTextToken;
  //     return response([
  //       'success' => true,
  //       'email' => (string) $request->email,
  //       'message' => 'Vender registered successfully..!',
  //       'token' => $token,
  //     ]);
  //   }
  // }

  public function vendorRegister(Request $request)
  {
    $rules = [
      'first_name' => 'required',
      'last_name' => 'required',
      'email' => 'required|email|unique:vendors',
      'password' => 'required|min:6',
      'mobile' => 'required',
    ];
    $customMessages = [
      'first_name.required' => 'Please enter your first name.',
      'last_name.required' => 'Please enter your last name.',
      'email.required' => 'Please enter your email.',
      'email.unique' => 'This email is already taken.',
      'password.required' => 'Please enter your password.',
      'mobile.required' => 'Please enter your phone number.',
    ];
    $validator = Validator::make($request->all(), $rules, $customMessages);
    if ($validator->fails()) {
      $errors = $validator->errors();
      $responseErrors = [];
      foreach ($errors->all() as $error) {
        $responseErrors[] = $error;
      }

      return response(
        [
          'success' => false,
          'email' => $request->email ?? '',
          'Message' => implode(' ', $responseErrors),
        ],
        422
      );
    }

    $input = $request->all();
    if (Vendor::where('email', $input['email'])->exists()) {
      return response(
        [
          'success' => false,
          'email' => (string) $request->email,
          'Message' => 'This email is already taken.',
        ],
        422
      );
    }
    $input['password'] = bcrypt($input['password']);
    $vendor = Vendor::create($input);
    $success['token'] = $vendor->createToken('vendorApp')->accessToken;
    $success['id'] = $vendor->id;
    $success['first_name'] = $vendor->first_name;
    $success['last_name'] = $vendor->last_name;
    $success['mobile'] = $vendor->mobile;

    if (Auth::guard('vendor')->attempt(['email' => $request->email, 'password' => $request->password])) {
      $authVendor = Auth::guard('vendor')->user();
      $token = $authVendor->createToken('vendorApp')->plainTextToken;
      return response([
        'success' => true,
        'email' => (string) $request->email,
        'id' => (int) $vendor->id,
        'Message' => 'Vendor registered successfully..!',
        'token' => $token,
      ]);
    }
  }

  //  Vendor Login
  // public function vendorLogin(Request $request)
  // {
  //   $rules = [
  //     'email' => 'required|exists:vendors',
  //     'password' => 'required',
  //   ];
  //   $customMessages = [
  //     'email.required' => 'Please enter your email.',
  //     'email.unique' => 'This email is not exists.',
  //     'password.required' => 'Please enter your password.',
  //   ];
  //   $validator = Validator::make($request->all(), $rules, $customMessages);
  //   if ($validator->fails()) {
  //     $errors = $validator->errors();

  //     $responseErrors = [];
  //     foreach ($errors->all() as $error) {
  //       $responseErrors = $error;
  //     }

  //     return response([
  //       'success' => false,
  //       'email' => $request->email ?? "",
  //       'message' => $responseErrors,
  //     ], 422);
  //   }

  //   if (Auth::guard('vendor')->attempt(['email' => $request->email, 'password' => $request->password])) {
  //     $authVendor = Auth::guard('vendor')->user();
  //     $token = $authVendor->createToken('vendorApp')->plainTextToken;

  //     return response([
  //       'success' => true,
  //       'vendor_id' => $authVendor->id,
  //       'email' => $authVendor->email,
  //       'token' => $token,
  //       'message' => 'Vendor signed in..!',
  //     ]);
  //   } else {
  //     // Authentication failed
  //     return response([
  //       'success' => false,
  //       'message' => 'Invalid credentials',
  //     ], 401);
  //   }
  // }

  public function vendorLogin(Request $request)
  {
    $rules = [
      'email' => 'required|exists:vendors',
      'password' => 'required',
    ];

    $customMessages = [
      'email.required' => 'Please enter your email.',
      'email.exists' => 'This email does not exist.',
      'password.required' => 'Please enter your password.',
    ];

    $validator = Validator::make($request->all(), $rules, $customMessages);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return response(
        [
          'success' => false,
          'email' => $request->email ?? '',
          'message' => $errors->first(),
        ],
        422
      );
    }

    // Attempt to authenticate the vendor
    if (Auth::guard('vendor')->attempt(['email' => $request->email, 'password' => $request->password])) {
      $authVendor = Auth::guard('vendor')->user();

      // Load vendor store details
      $vendorStore = VendorStore::where('vendor_id', $authVendor->id)->first();

      if (!$vendorStore) {
        return response(
          [
            'success' => false,
            'message' => 'Vendor store not found',
          ],
          404
        );
      }

      // Fetch module details
      $module = Module::find($vendorStore->module_id);

      if (!$module) {
        return response(
          [
            'success' => false,
            'message' => 'Module not found',
          ],
          404
        );
      }

      // Generate token for API authentication
      $token = $authVendor->createToken('vendorApp')->plainTextToken;

      return response([
        'success' => true,
        'vendor_id' => $authVendor->id,
        'email' => $authVendor->email,
        'store_id' => $vendorStore->id,
        'store_name' => $vendorStore->store_name,
        'module_id' => $vendorStore->module_id,
        'module_name' => $module->name, // Assuming 'module_name' is the column in the 'modules' table
        'token' => $token,
        'message' => 'Vendor signed in successfully',
      ]);
    } else {
      // Authentication failed
      return response(
        [
          'success' => false,
          'message' => 'Invalid credentials',
        ],
        401
      );
    }
  }

  // Add Store
  public function addStore(Request $request)
  {
    $vendor_id = Auth::guard('sanctum')->user()->id;
    $rules = [
      'store_name' => 'required',
      'store_description' => 'required',
      'module_id' => 'required|exists:module,id',
      'lat' => 'required',
      'lon' => 'required',
      'store_address' => 'required',
      'min_time' => 'required',
      'max_time' => 'required',
      'time_type' => 'required',
      'store_logo' => 'required',
      'store_images' => 'required',
      'banner_image' => 'required',
    ];

    $customMessages = [
      'store_name.required' => 'Please enter store name.',
      'store_description.required' => 'Please enter store description.',
      'module_id.required' => 'Please select a module.',
      'lat.required' => 'Please enter latitude.',
      'lon.required' => 'Please enter longitude.',
      'store_address.required' => 'Please enter store address.',
      'min_time.required' => 'Please enter minium time.',
      'max_time.required' => 'Please enter maximum time.',
      'time_type.required' => 'Please select delivery time type.',
      'store_logo.required' => 'Please upload store logo.',
      'store_images.required' => 'Please select store image.',
      'banner_image.required' => 'Please select banner image.',
    ];

    $validator = Validator::make($request->all(), $rules, $customMessages);
    if ($validator->fails()) {
      $errors = $validator->errors();

      $responseErrors = [];
      foreach ($errors->all() as $error) {
        $responseErrors = $error;
      }

      return response(
        [
          'success' => false,
          'message' => $responseErrors,
        ],
        422
      );
    }
    if ($request->hasfile('store_logo')) {
      $storeLogo = time() . '.' . $request->store_logo->getClientOriginalName();
      $request->store_logo->move(public_path() . '/assets/images/store_logo/', $storeLogo);
    }
    $data = new VendorStore();
    $data->vendor_id = $vendor_id;
    $data->store_name = $request->store_name;
    $data->store_description = $request->store_description;
    $data->module_id = $request->module_id;
    $data->lat = $request->lat;
    $data->lon = $request->lon;
    $data->store_address = $request->store_address;
    $data->min_time = $request->min_time;
    $data->max_time = $request->max_time;
    $data->time_type = $request->time_type;
    $data->store_logo = $storeLogo;
    $data->save();
    if ($request->hasfile('store_images')) {
      foreach ($request->file('store_images') as $file) {
        $fileName = time() . '.' . $file->getClientOriginalName();
        $file->move(public_path() . '/assets/images/store_images/', $fileName);
        $data->storeImages()->create([
          'store_id' => $data->id,
          'store_images' => $fileName,
        ]);
      }
    }
    if ($request->hasfile('banner_image')) {
      foreach ($request->file('banner_image') as $file) {
        $fileName = time() . '.' . $file->getClientOriginalName();
        $file->move(public_path() . '/assets/images/banner_images/', $fileName);
        $data->bannerImages()->create([
          'store_id' => $data->id,
          'banner_image' => $fileName,
        ]);
      }
    }

    // Fetch the module name
    $module = Module::find($data->module_id);
    return response(
      [
        'success' => true,
        'message' => 'Store added successfully...!',
        'store_id' => $data->id,
        'store_name' => $data->store_name,
        'module_id' => $module->id,
        'module_name' => $module->name,
      ],
      200
    );
  }

  // Add Product
  // public function addProduct(Request $request)
  // {
  //   $vendor_id = Auth::guard('sanctum')->user()->id;
  //   $rules = [
  //     'product_name' => 'required',
  //     'product_about' => 'required',
  //     'product_price' => 'required',
  //     'product_sale_price' => 'sometimes|nullable|lt:product_price',
  //     'category_id' => 'required',
  //     'module_id' => 'required',
  //     'store_id' => 'required',
  //     'unit_id' => 'required',
  //     'product_quantity' => 'required',
  //     'in_stock' => 'required',
  //   ];

  //   $customMessages = [
  //     'product_name.required' => 'Please enter product name.',
  //     'product_about.required' => 'Please enter product description.',
  //     'product_images.required' => 'Please select product image.',
  //     'product_price.required' => 'Please enter product price.',
  //     'product_sale_price.numeric' => 'Please enter product sale price.',
  //     'product_sale_price.lt' => 'The sale price must be less than the main price.',
  //     'category_id.required' => 'Please select category.',
  //     'module_id.required' => 'Please select module id.',
  //     'store_id.required' => 'Please select store id.',
  //     'unit_id.required' => 'Please select unit id.',
  //     'product_quantity.required' => 'Please enter product quantity.',
  //     'in_stock.required' => 'Please select in stock.',
  //   ];

  //   $validator = Validator::make($request->all(), $rules, $customMessages);
  //   if ($validator->fails()) {
  //     $errors = $validator->errors();

  //     $responseErrors = [];
  //     foreach ($errors->all() as $error) {
  //       $responseErrors = $error;
  //     }

  //     return response(
  //       [
  //         'success' => false,
  //         'message' => $responseErrors,
  //       ],
  //       422
  //     );
  //   }

  //   $data = new Product();
  //   $data->vendor_id = $vendor_id;
  //   $data->store_id = $request->store_id;
  //   $data->category_id = $request->category_id;
  //   $data->sub_category_id = $request->sub_category_id;
  //   $data->module_id = $request->module_id;
  //   $data->unit_id = $request->unit_id;
  //   $data->in_stock = $request->in_stock;
  //   $data->product_name = $request->product_name;
  //   $data->product_about = $request->product_about;
  //   $data->product_price = $request->product_price;
  //   $data->product_sale_price = $request->product_sale_price ?? 0;
  //   $data->product_quantity = $request->product_quantity;
  //   $data->tag = $request->tag;
  //   $data->save();

  //   if ($request->hasfile('product_images')) {
  //     foreach ($request->file('product_images') as $file) {
  //       $fileName = time() . '.' . $file->getClientOriginalName();
  //       $file->move(public_path() . '/assets/images/product_images/', $fileName);
  //       $data->productImages()->create([
  //         'product_id' => $data->id,
  //         'product_image' => $fileName,
  //       ]);
  //     }
  //   }
  //   return response(
  //     [
  //       'success' => true,
  //       'message' => 'Product added successfully...!',
  //       'product_id' => $data->id,
  //     ],
  //     200
  //   );
  // }
  public function addProduct(Request $request)
  {
    $vendor_id = Auth::guard('sanctum')->user()->id;
    $rules = [
      'product_name' => 'required',
      'product_about' => 'required',
      'product_price' => 'required',
      'product_sale_price' => 'sometimes|nullable|lt:product_price',
      'category_id' => 'required',
      'module_id' => 'required',
      'store_id' => 'required',
      'unit_id' => 'required',
      'product_quantity' => 'required',
      'in_stock' => 'required',
    ];

    $customMessages = [
      'product_name.required' => 'Please enter product name.',
      'product_about.required' => 'Please enter product description.',
      'product_images.required' => 'Please select product image.',
      'product_price.required' => 'Please enter product price.',
      'product_sale_price.numeric' => 'Please enter product sale price.',
      'product_sale_price.lt' => 'The sale price must be less than the main price.',
      'category_id.required' => 'Please select category.',
      'module_id.required' => 'Please select module id.',
      'store_id.required' => 'Please select store id.',
      'unit_id.required' => 'Please select unit id.',
      'product_quantity.required' => 'Please enter product quantity.',
      'in_stock.required' => 'Please select in stock.',
    ];

    $validator = Validator::make($request->all(), $rules, $customMessages);
    if ($validator->fails()) {
      $errors = $validator->errors();
      $responseErrors = [];
      foreach ($errors->all() as $error) {
        $responseErrors[] = $error;
      }
      return response(
        [
          'success' => false,
          'message' => $responseErrors,
        ],
        422
      );
    }

    $data = new Product();
    $data->vendor_id = $vendor_id;
    $data->store_id = $request->store_id;
    $data->category_id = $request->category_id;
    $data->sub_category_id = $request->sub_category_id;
    $data->module_id = $request->module_id;
    $data->unit_id = $request->unit_id;
    $data->in_stock = $request->in_stock;
    $data->product_name = $request->product_name;
    $data->product_about = $request->product_about;
    $data->product_price = $request->product_price;
    $data->product_sale_price = $request->product_sale_price ?? 0;
    $data->product_quantity = $request->product_quantity;
    $data->tag = $request->tag;
    $data->save();

    if ($request->hasfile('product_images')) {
      foreach ($request->file('product_images') as $file) {
        $fileName = time() . '.' . $file->getClientOriginalName();
        $file->move(public_path() . '/assets/images/product_images/', $fileName);
        $data->productImages()->create([
          'product_id' => $data->id,
          'product_image' => $fileName,
        ]);
      }
    }

    $variants = json_decode($request->variants, true); // Decode JSON string to array
    if (is_array($variants)) {
      foreach ($variants as $variantData) {
        $variant = new Variant();
        $variant->product_id = $data->id;
        $variant->color = $variantData['color'] ?? null;
        $variant->size = $variantData['size'] ?? null;
        $variant->type = $variantData['type'] ?? null;
        $variant->price = $variantData['price'] ?? null;
        $variant->total_stock = $variantData['total_stock'];
        $variant->save();
      }
    }

    return response(
      [
        'success' => true,
        'message' => 'Product added successfully...!',
        'product_id' => $data->id,
      ],
      200
    );
  }

  public function addStoreBanner(Request $request)
  {
    $rules = [
      'store_id' => 'required',
      'banner_image' => 'required',
    ];

    $customMessages = [
      'store_id.required' => 'Please enter store.',
      'banner_image.required' => 'Please upload banner images.',
    ];

    $validator = Validator::make($request->all(), $rules, $customMessages);
    if ($validator->fails()) {
      $errors = $validator->errors();
      $responseErrors = [];
      foreach ($errors->all() as $error) {
        $responseErrors[] = $error;
      }
      return response([
        'success' => false,
        'message' => $responseErrors,
      ], 422);
    }

    $data = new Banner();
    $data->store_id = $request->store_id;
    // $data->save();

    if ($request->hasfile('banner_image')) {
      foreach ($request->file('banner_image') as $file) {
        $fileName = time() . '.' . $file->getClientOriginalName();
        $file->move(public_path() . '/assets/images/banner_images/', $fileName);
        $data->bannerImagesData()->create([
          'store_id' => $data->store_id,
          'banner_image' => $fileName,
        ]);
      }
    }

    return response([
      'success' => true,
      'message' => 'Banner added successfully...!',
      'store_id' => $data->store_id,
    ], 200);
  }





  // updateProduct
  public function updateProduct(Request $request)
  {
    // Retrieve the authenticated vendor's ID
    $vendor_id = Auth::guard('sanctum')->user()->id;

    // Extract the product ID from the request
    $product_id = $request->product_id;

    // Define the data to be updated
    $data = [
      'product_quantity' => $request->input('product_quantity'),
    ];

    $updatedProduct = Product::where('id', $product_id)
      ->where('vendor_id', $vendor_id)
      ->update($data);

    // Check if the product was updated successfully
    if ($updatedProduct) {
      return response()->json([
        'success' => true,
        'message' => 'Product updated successfully.',
        'data' => $data,
      ]);
    } else {
      return response()->json(
        [
          'success' => false,
          'message' => 'Failed to update product. Make sure the product exists and you have permission to update it.',
        ],
        400
      );
    }
  }

  // Update Store
  // Update Store
  public function updateStore(Request $request)
  {
    // Retrieve the authenticated vendor's ID
    $vendor_id = Auth::guard('sanctum')->user()->id;

    // Extract the store ID from the request
    $id = $request->id;

    // Define the data to be updated
    $data = $request->only([
      'store_name',
      'mobile',
      'lat',
      'lon',
      'store_address',
      'min_time',
      'max_time',
      'time_type',
      'store_description',
      'open_time',
      'close_time',
      'mfo',
      'delivery',
      'take_away',
    ]);

    // Handle store logo upload
    if ($request->hasFile('store_logo')) {
      $storeLogo = time() . '.' . $request->store_logo->getClientOriginalExtension();
      $request->store_logo->move(public_path('/assets/images/store_logo/'), $storeLogo);
      $data['store_logo'] = $storeLogo;
    }

    // Handle banner image uploads
    if ($request->hasFile('banner_image')) {
      $bannerImages = [];
      foreach ($request->file('banner_image') as $file) {
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('/assets/images/banner_images/'), $fileName);
        $bannerImages[] = ['banner_image' => $fileName, 'store_id' => $id];
      }
      Banner::insert($bannerImages);
    }

    try {
      // Attempt to update the store
      $updatedStore = VendorStore::where('id', $id)
        ->where('vendor_id', $vendor_id)
        ->update($data);

      // Check if the store was updated successfully
      if ($updatedStore) {
        return response()->json([
          'success' => true,
          'message' => 'Store updated successfully.',
        ]);
      } else {
        return response()->json(
          [
            'success' => false,
            'message' => 'Failed to update store. Make sure the store exists and you have permission to update it.',
          ],
          400
        );
      }
    } catch (\Exception $e) {
      // Handle any exceptions that occur during the update
      return response()->json(
        [
          'success' => false,
          'message' => 'An error occurred while updating the store: ' . $e->getMessage(),
        ],
        500
      );
    }
  }

  public function updateProductVendor(Request $request)
  {
    try {
      $vendor_id = Auth::guard('sanctum')->user()->id;
      $product_id = $request->product_id;
      $variant = $request->variant_id;
      $data = Product::find($product_id);

      if (!$data || $data->vendor_id != $vendor_id) {
        return response(
          [
            'success' => false,
            'message' => 'Product not found or not authorized.',
          ],
          404
        );
      }

      $data->store_id = $request->store_id ?? $data->store_id;
      $data->category_id = $request->category_id ?? $data->category_id;
      $data->sub_category_id = $request->sub_category_id ?? $data->sub_category_id;
      $data->module_id = $request->module_id ?? $data->module_id;
      $data->unit_id = $request->unit_id ?? $data->unit_id;
      $data->in_stock = $request->in_stock ?? $data->in_stock;
      $data->product_name = $request->product_name ?? $data->product_name;
      $data->product_about = $request->product_about ?? $data->product_about;
      $data->product_price = $request->product_price ?? $data->product_price;
      $data->product_sale_price = $request->product_sale_price ?? $data->product_sale_price;
      $data->product_quantity = $request->product_quantity ?? $data->product_quantity;
      $data->tag = $request->tag ?? $data->tag;
      $data->save();

      // Handle product images
      if ($request->hasfile('product_images')) {
        foreach ($data->productImages as $oldImage) {
          if (file_exists(public_path('/assets/images/product_images/' . $oldImage->product_image))) {
            unlink(public_path('/assets/images/product_images/' . $oldImage->product_image));
          }
          $oldImage->delete();
        }

        foreach ($request->file('product_images') as $file) {
          $fileName = time() . '.' . $file->getClientOriginalName();
          $file->move(public_path() . '/assets/images/product_images/', $fileName);
          $data->productImages()->create([
            'product_id' => $data->id,
            'product_image' => $fileName,
          ]);
        }
      }

      $variants = json_decode($request->variants, true);
      if (is_array($variants)) {
        foreach ($variants as $variantData) {
          Log::info('Processing variant data: ', $variantData);

          // Find the existing variant
          $variant = Variant::where('product_id', $data->id)
            ->where('id', $variant)
            ->first();

          if ($variant) {
            Log::info('Updating variant: ', $variant->toArray());
            $variant->color = $variantData['color'] ?? $variant->color;
            $variant->size = $variantData['size'] ?? $variant->size;
            $variant->type = $variantData['type'] ?? $variant->type;
            $variant->price = $variantData['price'] ?? $variant->price;
            $variant->total_stock = $variantData['total_stock'] ?? $variant->total_stock;
            $variant->save();
          }
        }
      }

      return response(
        [
          'success' => true,
          'message' => 'Product updated successfully...!',
          'product_id' => $data->id,
        ],
        200
      );
    } catch (\Exception $e) {
      Log::error('Error updating product: ' . $e->getMessage());
      return response(
        [
          'success' => false,
          'message' => 'An error occurred while updating the product.',
        ],
        500
      );
    }
  }

  // Forgot Password
  public function forgotPassword(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|exists:vendors',
    ]);

    if ($validator->fails()) {
      return $this->sendError('Error validation', $validator->errors());
    }

    if ($request->email != '') {
      if (!Vendor::where('email', $request->email)->first()) {
        return response()->json([
          'success' => false,
          'message' => 'Email not found...!',
        ]);
      } else {
        $email = $request->email;
        // $otp = Str::random(8);
        $otp = random_int(1000, 9999);
        // Vendor::where('email', $request->email)->update(['password' => bcrypt($otp)]);
        Vendor::where('email', $request->email)->update(['otp' => $otp]);
        $userDetails = Vendor::where('email', $request->email)->first();
        $messageData = ['email' => $userDetails->email, 'otp' => $otp];

        try {
          // Mail::send('otp', $messageData, function ($message) use ($email) {
          //   $message->to($email)->subject('Your OTP');
          // });

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

  // Change Password
  public function ChangePassword(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
    ]);

    if ($validator->fails()) {
      return $this->sendError($validator->errors());
    }

    if (!Vendor::where('email', $request->email)->exists()) {
      return response()->json(['error' => 'Invalid Email id..!']);
    }

    if (!empty($request->otp)) {
      $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'otp' => 'required',
        // 'password' => 'required',
        // 'cnf_pass' => 'required|same:password',
      ]);
      if ($validator->fails()) {
        return $this->sendError($validator->errors());
      }

      if (
        Vendor::where('email', $request->email)
        ->where('otp', $request->otp)
        ->exists()
      ) {
        Vendor::where('email', $request->email)
          ->where('otp', $request->otp)
          ->update(['email_verified_at' => now()]);

        // return $this->sendResponse('Password reset success.');
        return response([
          'success' => true,
          'message' => 'Otp successfully..!',
        ]);
      } else {
        // return $this->sendError('Invelid otp.');

        return response([
          'success' => false,
          'message' => 'Otp Not successfully..!',
        ]);
      }
    }
  }

  // Reset Password
  public function ResetPassword(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
    ]);

    if ($validator->fails()) {
      return $this->sendError($validator->errors());
    }

    if (!Vendor::where('email', $request->email)->exists()) {
      return response()->json(['error' => 'Invalid Email id..!']);
    }

    if (!empty($request->email)) {
      $validator = Validator::make($request->all(), [
        // 'email' => 'required|email',
        'password' => 'required',
        'confirm_password' => 'required|same:password',
      ]);
      // if ($validator->fails()) {
      //     return $this->sendError($validator->errors());
      // }

      if ($validator->fails()) {
        // return $this->sendResponse('Password reset success.');
        return response([
          'success' => false,
          'message' => 'Password and confirm password is not same',
        ]);
      }

      if (Vendor::where('email', $request->email)->exists()) {
        Vendor::where('email', $request->email)->update([
          'email_verified_at' => now(),
          'password' => bcrypt($request->password),
        ]);

        // return $this->sendResponse('Password reset success.');
        return response([
          'success' => true,
          'message' => 'Password reset successfully..!',
        ]);
      } else {
        return $this->sendError('Invelid otp.');
      }
    }
  }

  // getStoreonVendor
  public function getStoreonVendor(Request $request)
  {
    // Get the authenticated vendor's ID
    $vendor_id = Auth::guard('sanctum')->user()->id;
    $id = $request->input('id');

    // Fetch vendor stores with related images
    $stores = VendorStore::with(['storeImages', 'bannerImages'])
      ->where('vendor_id', $vendor_id) // Ensure the stores belong to the authenticated vendor
      ->whereIn('id', $id ? [$id] : []) // Filter by provided id if available
      ->get();

    $vendorStore = $stores->map(function ($store) {
      // Map store images
      $storeImages = $store->storeImages
        ->map(function ($img) {
          return $img->store_images ? url('/assets/images/store_images/' . $img->store_images) : '';
        })
        ->toArray();

      // Get the first banner image
      $firstBannerImage = $store->bannerImages->first();
      $bannerImageUrl =
        $firstBannerImage && $firstBannerImage->banner_image
        ? url('/assets/images/banner_images/' . $firstBannerImage->banner_image)
        : '';

      // Return structured store data
      return [
        'id' => (string) $store->id,
        'vendor_id' => $store->vendor_id,
        'store_name' => $store->store_name ?? '',
        'store_description' => $store->store_description ?? '',
        'lat' => $store->lat ?? '',
        'lon' => $store->lon ?? '',
        'store_address' => $store->store_address ?? '',
        'min_time' => $store->min_time ?? '',
        'max_time' => $store->max_time ?? '',
        'time_type' => $store->time_type ?? '',
        'store_logo' => $store->store_logo ? url('/assets/images/store_logo/' . $store->store_logo) : '',
        'store_images' => $storeImages,
        'banner_image' => $bannerImageUrl, // Only the first banner image
      ];
    });

    return response(
      [
        'success' => true,
        'data' => $vendorStore,
      ],
      200
    );
  }

  // public function getOrderListofVendor()
  // {
  //   $vendor_id = Auth::guard('sanctum')->user()->id;

  //   $mainOrderData = Order::select(
  //     'orders.order_id',
  //     'orders.id',
  //     'orders.order_status',
  //     'orders.store_id',
  //     'orders.created_at As order_date',
  //     DB::raw("IFNULL(vendor_stores.store_name, '') as store_name"),
  //     DB::raw("IFNULL(vendor_stores.store_address, '') as store_address"),
  //     'vendor_stores.store_logo',
  //     'orders.payment_mode'
  //   )
  //     ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
  //     ->leftJoin('vendor_stores', 'order_items.vendor_id', '=', 'vendor_stores.vendor_id')
  //     ->where('orders.vendor_id', $vendor_id)  // Filter by vendor_id
  //     ->where('orders.order_status', '1')
  //     ->orderBy('orders.id', 'desc')
  //     ->groupBy(
  //       'orders.order_id',
  //       'orders.id',
  //       'orders.order_status',
  //       'orders.store_id',
  //       'orders.created_at',
  //       'vendor_stores.store_name',
  //       'vendor_stores.store_address',
  //       'vendor_stores.store_logo',
  //       'orders.payment_mode'
  //     )
  //     ->get()
  //     ->transform(function ($tsr) use ($vendor_id) {
  //       $tsr->order_date = \Carbon\Carbon::parse($tsr->order_date)->format('l, j M g:i A');

  //       // Fetch store details based on store_id
  //       $store = VendorStore::select('store_name', 'store_address', 'store_logo')
  //         ->where('id', $tsr->store_id)
  //         ->first();

  //       if ($store) {
  //         $tsr->store_name = $store->store_name;
  //         $tsr->store_address = $store->store_address;
  //         $tsr->store_logo = $store->store_logo ? url('assets/images/store_logo/' . $store->store_logo) : "";
  //       } else {
  //         $tsr->store_logo = "";
  //       }

  //       $totalAmount = 0;
  //       $totalDiscountAmount = 0;
  //       $order = OrderItem::with('productImages')
  //         ->select(
  //           'products.product_name',
  //           'products.product_price',
  //           'products.product_sale_price',
  //           'order_items.id',
  //           'order_items.vendor_id',
  //           'order_items.product_id',
  //           'order_items.coupon_id',
  //           'order_items.payment_id',
  //           'order_items.product_color',
  //           'order_items.product_size',
  //           'order_items.delivery_date',
  //           'order_items.is_status',
  //           'order_items.quantity'
  //         )
  //         ->where('order_items.vendor_id', $vendor_id)  // Filter by vendor_id
  //         ->where('order_items.order_id', $tsr->id)
  //         ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
  //         ->get()
  //         ->transform(function ($tr) use ($vendor_id, &$totalAmount, &$totalDiscountAmount) {
  //           $tr->is_status = (int)$tr->is_status;
  //           $tr->product_color = $tr->product_color ?? '';
  //           $tr->product_size = $tr->product_size ?? '';
  //           $tr->coupon_id = $tr->coupon_id ?? 0;
  //           $tr->payment_id = $tr->payment_id ?? '';

  //           if ($tr->product_sale_price != null || $tr->product_sale_price > 0) {
  //             $tr->product_sale_price = $tr->product_sale_price * $tr->quantity;
  //           } else {
  //             $tr->product_sale_price = $tr->product_price * $tr->quantity;
  //           }

  //           $totalAmount += $tr->product_sale_price;

  //           if ($tr->coupon_id != 0) {
  //             $coupon = Coupon::select('discount_amount')->where('id', $tr->coupon_id)->first();
  //             $discountAmount = $coupon ? $coupon->discount_amount : 0;
  //             $tr->discount_amount = $discountAmount;
  //             $totalDiscountAmount += $discountAmount;
  //           } else {
  //             $tr->discount_amount = 0;
  //           }

  //           return $tr;
  //         });

  //       $totalProductPrice = $order->sum(function ($item) {
  //         return $item->product_price * $item->quantity;
  //       });

  //       $totalSaleProductPrice = $order->sum(function ($item) {
  //         return $item->product_sale_price;
  //       });

  //       $totalFinalAmount = $totalSaleProductPrice - $totalDiscountAmount;

  //       foreach ($order as $value) {
  //         $productImage = [];
  //         foreach ($value->productImages as $image) {
  //           $productImage[] = url('assets/images/product_images/' . $image->product_image);
  //         }
  //         $value->productImage = $productImage;
  //         $value->delivery_date = \Carbon\Carbon::parse($value->delivery_date)->format('l, j M');
  //       }
  //       $tsr->itemsList = $order;
  //       $tsr->totalProductPrice = $totalProductPrice;
  //       $tsr->totalSaleProductPrice = $totalSaleProductPrice;
  //       $tsr->totalAmount = $totalAmount;
  //       $tsr->totalDiscountAmount = $totalDiscountAmount;
  //       $tsr->totalFinalAmount = $totalFinalAmount;
  //       return $tsr;
  //     });

  //   $totalOrders = $mainOrderData->count();

  //   return response()->json(
  //     [
  //       'status' => "success",
  //       'message' => 'Order List Found...!',
  //       'data' => $mainOrderData,
  //       'totalOrders' => $totalOrders
  //     ],
  //     200
  //   );
  // }

  // public function getOrderListofVendor()
  // {
  //   // Get the vendor ID from the authenticated user
  //   $vendor_id = Auth::guard('sanctum')->user()->id;

  //   // Query to fetch orders based on vendor ID
  //   $mainOrderData = Order::select(
  //     'orders.order_id',
  //     'orders.id',
  //     'orders.order_status',
  //     'orders.store_id',
  //     'orders.created_at AS order_date',
  //     'vendor_stores.store_name',
  //     'vendor_stores.store_address',
  //     'vendor_stores.store_logo',
  //     'orders.payment_mode'
  //   )
  //   ->leftJoin('vendor_stores', 'orders.store_id', '=', 'vendor_stores.id')
  //   ->where('orders.vendor_id', $vendor_id) // Filter by vendor ID
  //     ->where('orders.order_status', '1') // Filter by order status (adjust as needed)
  //     ->orderBy('orders.id', 'desc')
  //     ->get()
  //     ->transform(function ($order) {
  //       // Format order date
  //       $order->order_date = \Carbon\Carbon::parse($order->order_date)->format('l, j M g:i A');

  //       // Format store logo URL if available
  //       $order->store_logo = $order->store_logo ? url('assets/images/store_logo/' . $order->store_logo) : "";

  //       return $order;
  //     });

  //   // Count total orders
  //   $totalOrders = $mainOrderData->count();

  //   // Return JSON response
  //   return response()->json([
  //     'status' => "success",
  //     'message' => 'Order List Found...!',
  //     'data' => $mainOrderData,
  //     'totalOrders' => $totalOrders
  //   ], 200);
  // }

  public function getOrderListofVendor(Request $request)
  {
    $vendor_id = Auth::guard('sanctum')->user()->id;

    $orders = Order::select('id', 'order_id', 'vendor_id', 'order_status', 'created_at', 'total_item')
      ->where('vendor_id', $vendor_id)
      ->get();

    $pending = [];
    $confirmed = [];
    $delivered = [];
    $cancelled = [];

    foreach ($orders as $order) {
      switch ($order->order_status) {
        case 0:
          $pending[] = $order;
          break;
        case 1:
          $confirmed[] = $order;
          break;
        case 2:
          $delivered[] = $order;
          break;
        case 3:
          $cancelled[] = $order;
          break;
        default:
          // Handle unexpected order_status values if needed
          break;
      }
    }

    $response = [
      'success' => true,
      'message' => 'Orders found',
      'data' => [
        'pending' => $pending,
        'confirmed' => $confirmed,
        'delivered' => $delivered,
        'cancelled' => $cancelled,
      ],
    ];

    return response()->json($response);
  }

  public function removeProduct(Request $request)
  {
    // Get the authenticated vendor's ID
    $vendor_id = Auth::guard('sanctum')->user()->id;

    // Check if the authenticated vendor ID is available
    if ($vendor_id) {
      // Validate the request to ensure product_id is provided
      $request->validate([
        'product_id' => 'required|integer|exists:products,id',
      ]);

      // Find the product by ID and vendor ID
      $product = Product::where('id', $request->product_id)
        ->where('vendor_id', $vendor_id)
        ->first();

      // Check if the product exists and belongs to the authenticated vendor
      if ($product) {
        // Remove the product
        $product->delete();

        return response()->json(
          [
            'success' => true,
            'message' => 'Product removed successfully!',
          ],
          200
        );
      } else {
        return response()->json(
          [
            'success' => false,
            'message' => 'Product not found or you do not have permission to delete this product.',
          ],
          404
        );
      }
    } else {
      return response()->json(
        [
          'success' => false,
          'message' => 'Vendor not authenticated.',
        ],
        401
      );
    }
  }

  public function addCouponByVendor(Request $request)
  {
    $vendor_id = Auth::guard('sanctum')->user()->id;

    $rules = [
      'description' => 'required',
      'coupon_code' => 'required',
      // 'status' => 'required',
      'discount_amount' => 'required',
    ];

    $customMessages = [
      'description.required' => 'Please enter a description.',
      'coupon_code.required' => 'Please enter a coupon code.',
      // 'status.required' => 'Please enter the status.',
      'discount_amount.required' => 'Please enter the discount amount.',
    ];

    $validator = Validator::make($request->all(), $rules, $customMessages);

    if ($validator->fails()) {
      return response(
        [
          'success' => false,
          'message' => $validator->errors()->first(),
        ],
        422
      );
    }

    $coupon = new Coupon();
    $coupon->vendor_id = $vendor_id;
    $coupon->description = $request->description;
    $coupon->coupon_code = $request->coupon_code;
    $coupon->discount_amount = $request->discount_amount;

    $coupon->save();

    return response(
      [
        'success' => true,
        'message' => 'Coupon added successfully!',
        'coupon_id' => $coupon->id,
      ],
      200
    );
  }

  public function couponListofVendor(Request $request)
  {
    $vendor_id = Auth::guard('sanctum')->user()->id;
    if ($vendor_id) {
      // Retrieve coupons that belong to the authenticated vendor
      $couponList = Coupon::where('vendor_id', $vendor_id)
        ->orderBy('created_at', 'desc')
        ->get();

      // Modify the coupon list to set 'type' to an empty string if it's null
      $couponList = $couponList->map(function ($coupon) {
        if (is_null($coupon->type)) {
          $coupon->type = '';
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

  public function removeCouponbyvendor(Request $request)
  {
    $vendor_id = Auth::guard('sanctum')->user()->id;
    if ($vendor_id) {
      // Find the coupon by ID and vendor ID
      $coupon = Coupon::where('id', $request->id)
        ->where('vendor_id', $vendor_id)
        ->first();

      if ($coupon) {
        // Delete the coupon
        $coupon->delete();

        return response()->json(
          [
            'success' => true,
            'message' => 'Coupon removed Successfully ...!',
          ],
          201
        );
      } else {
        return response()->json(
          [
            'success' => false,
            'message' => 'Coupon not found or unauthorized',
          ],
          404
        );
      }
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

  // Add Module
  public function addModule(Request $request)
  {
    $vendor_id = Auth::guard('sanctum')->user()->id;

    $rules = [
      'name' => 'required',
      'image' => 'required|image',
    ];

    $customMessages = [
      'name.required' => 'Please enter module name.',
      'image.required' => 'Please select module image.',
    ];

    $validator = Validator::make($request->all(), $rules, $customMessages);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return response(
        [
          'success' => false,
          'message' => $errors->first(),
        ],
        422
      );
    }

    // Handle file upload
    if ($request->hasFile('image')) {
      $moduleimage = time() . '.' . $request->image->getClientOriginalExtension();
      $request->image->move(public_path('assets/images/module/'), $moduleimage);
    } else {
      return response(
        [
          'success' => false,
          'message' => 'Image file not provided.',
        ],
        422
      );
    }

    // Create new module record
    $data = new Module();
    $data->vendor_id = $vendor_id;
    $data->name = $request->name;
    $data->image = $moduleimage;
    $data->save();

    return response(
      [
        'success' => true,
        'message' => 'Module added successfully.',
        'module_id' => $data->id,
      ],
      200
    );
  }

  // Get Module
  public function GetModuleofVendor(Request $request)
  {
    $vendor_id = Auth::guard('sanctum')->user()->id;

    // Assuming there's a vendor_id column in the modules table
    $data = Module::select('id', 'name', 'image')
      ->where('vendor_id', $vendor_id)
      ->get();

    foreach ($data as $value) {
      $value->image = url('assets/images/module/' . $value->image);
    }

    if ($data->isNotEmpty()) {
      return response()->json([
        'success' => true,
        'message' => 'Modules found',
        'data' => $data,
      ]);
    } else {
      return response()->json([
        'success' => true,
        'message' => 'No modules found',
      ]);
    }
  }

  public function orderDetailofVendor(Request $request)
  {
    $vendor_id = Auth::guard('sanctum')->user()->id;
    $product_id = $request->product_id;

    // Fetch all order items for the given vendor and order/product id
    $orderItems = OrderItem::with(['productImages'])
      ->select(
        'order_items.id as order_items_id',
        'order_items.order_id',
        'order_items.product_id',
        'order_items.created_at',
        'order_items.user_id',
        'orders.payment_mode',
        'orders.order_status',
        'order_items.product_size',
        'order_items.product_color',
        'order_items.quantity',
        'orders.id as order_id',
        'orders.coupon_id', // Include coupon_id from orders table
        'orders.total_amount', // Include total_amount from orders table
        'products.product_name',
        'products.product_price',
        'coupons.discount_amount', // Include coupon_amount from coupons table
        'variants.price as product_sale_price' // Include product_sale_price from variants
      )
      ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
      ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
      ->leftJoin('variants', 'order_items.variant_id', '=', 'variants.id') // Join variants table
      ->leftJoin('coupons', 'orders.coupon_id', '=', 'coupons.id') // Join coupons table
      ->where('orders.vendor_id', $vendor_id);

    // Filter by product_id or order_id
    if ($product_id) {
      $orderItems->where('order_items.product_id', $product_id);
    } else {
      $orderItems->where('order_items.order_id', $request->order_id);
    }

    $orderItems = $orderItems->latest()->get();

    if ($orderItems->isEmpty()) {
      return response()->json(
        [
          'status' => false,
          'message' => 'Order items not found.',
        ],
        404
      );
    }

    // Process each order item
    $orderItems->transform(function ($item) {
      // Process product images
      $item->productImages->transform(function ($image) {
        return url('/assets/images/product_images/' . $image->product_image);
      });

      // Set discount_amount to 0 if it is null
      $item->discount_amount = $item->discount_amount ?? 0;
      $item->product_color = $item->product_color ?? '';
      $item->product_size = $item->product_size ?? '';
      $item->product_sale_price = $item->product_sale_price ?? 0;

      return $item;
    });

    // Calculate total amounts
    $bagTotalData = $orderItems->sum('product_sale_price');
    $totalAmount = (int) $orderItems->first()->total_amount; // Cast to integer
    $couponAmount = $orderItems->first()->discount_amount ?? 0;
    $orderStatus = $orderItems->first()->order_status;

    // Construct order detail response
    $orderDetail = [
      'bagTotal' => $bagTotalData,
      'totalAmount' => max($totalAmount, 0),
      'couponAmount' => $couponAmount,
      'paymentMode' => $orderItems->isEmpty() ? null : $orderItems[0]->payment_mode, // Assuming payment mode is same for all items in the order
      'orderStatus' => $orderStatus,
    ];

    return response()->json(
      [
        'status' => true,
        'message' => 'Order Detail fetched successfully.',
        'orderDetail' => $orderDetail,
        'orderItems' => $orderItems,
      ],
      200
    );
  }

  public function orderConformByVendor(Request $request)
  {
    $vendor_id = Auth::guard('sanctum')->user()->id;
    $order_id = request('order_id');
    $order_status = request('order_status');

    $validator = Validator::make($request->all(), [
      'order_id' => 'required',
      'order_status' => 'required|in:0,1,2,3',
    ]);

    if ($validator->fails()) {
      return $this->sendError('Errors', $validator->errors());
    }

    // Update order status in order_items table
    $orderUpdated = OrderItem::where('vendor_id', $vendor_id)
      ->where('order_id', $order_id)
      ->update(['is_status' => $order_status]);

    if (!$orderUpdated) {
      return response()->json(['error' => 'Order not found or not updated'], 404);
    }

    // Get unique order statuses for the given order
    $orderStatuses = OrderItem::where('order_id', $order_id)
      ->pluck('is_status')
      ->unique();

    // Update the order_status in orders table if all order_items have the same status
    if ($orderStatuses->count() === 1) {
      $singleStatus = $orderStatuses->first();
      Order::where('id', $order_id)->update(['order_status' => $singleStatus]);
    }

    return response()->json([
      'success' => true,
      'message' => 'Order Status Updated',
    ]);
  }

  public function social_login_vendor(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'login_type' => 'required',
      'email' => 'required',
      'device_token' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'data' => $validator->errors(),
      ]);
    }
    $input = $request->all();
    // dd($input);
    if (Vendor::where('email', $request->email)->exists()) {
      $user = Vendor::where('email', $request->email)->first();
      $user->update($input);
      // return response()->json([
      //   'success' => false,
      //   'data' => array("token" => $user->createToken('MyApp')->accessToken, "login_type" => (string)$user->login_type), "Login success.",
      // ]);
      return response([
        'user_id' => (string) $user->id,
        'token' => $user->createToken('vendorApp')->plainTextToken,
        'login_type' => (string) $user->login_type,
        'message' => 'Login success.',
      ]);
    }
    Vendor::create($input);
    $user = Vendor::where('email', $request->email)->first();
    // return response()->json([
    //   'success' => false,
    //   'data' => array("token" => $user->createToken('MyApp')->accessToken, "login_type" => (string)$user->login_type), "Signup success.",
    // ]);
    return response([
      'user_id' => (string) $user->id,
      'token' => $user->createToken('MyApp')->plainTextToken,
      'login_type' => (string) $user->login_type,
      'message' => 'Signup success.',
    ]);
  }

  public function updateVendor(Request $request)
  {
    // dd($request->all());
    // $user_id = Auth::guard('vendor')->user()->id;
    $vendor_id = Auth::guard('sanctum')->user()->id;

    $user = Vendor::find($vendor_id);

    // dd($user_id);
    // $dateOfbirth = date('Y-m-d', strtotime($request->dob));
    if ($request->hasFile('image')) {
      $file = $request->file('image');
      $image_exts = ['tif', 'jpg', 'jpeg', 'gif', 'png'];
      $extension = $file->getClientOriginalExtension();
      if (!in_array($extension, $image_exts)) {
        $response = [
          'status' => 'failure',
          'message' => 'Image Type Error',
        ];
        return response()->json($response);
      }
      $fileName = 'image-' . uniqid() . '.' . $extension;
      $file->move(public_path() . '/assets/images/users_images/', $fileName);
      $image = $fileName;
    } else {
      // If no image file is provided, retain the existing image
      $image = $user->image;
    }
    $data = [
      'first_name' => $request->input('first_name', $user->first_name),
      'last_name' => $request->input('last_name', $user->last_name),
      'dob' => $request->input('dob', $user->dob),
      'gender' => $request->input('gender', $user->gender),
      'mobile' => $request->input('mobile', $user->mobile),
      'country_code' => $request->input('country_code', $user->country_code),
      'image' => $image,
    ];
    $data = Vendor::where('id', $vendor_id)->update($data);
    return response([
      'success' => true,
      'message' => 'Profile updated successfully ...!',
    ]);
  }

  public function getVendorProfile()
  {
    $vendor_id = Auth::guard('sanctum')->user()->id;
    $data = Vendor::where('id', $vendor_id)->first();
    if (!$data) {
      return response([
        'message' => 'User not found ...!',
      ]);
    } else {
      $userResource = new VendorResource($data);
      $userResourceData = $userResource->showWithDetails($data);
      return response([
        'success' => true,
        'user' => $userResourceData,
      ]);
    }
  }

  // public function addStore(Request $request)
  // {
  //   $vendor_id = Auth::guard('sanctum')->user()->id;
  //   $rules = [
  //     'store_name' => 'required',
  //     'business_email' => 'required',
  //     'business_email' => 'required|email|unique:vendor_stores',
  //     'mobile' => 'required',
  //     'vat_gstin_no' => 'required',
  //     'store_address' => 'required',
  //     'store_images' => 'required',

  //   ];

  //   $customMessages = [
  //     'store_name.required' => 'Please enter store name.',
  //     'business_email.required' => 'Please enter business email.',
  //     'business_email.unique' => 'This email is already taken.',
  //     'mobile.required' => 'Please enter mobile.',
  //     'vat_gstin_no.required' => 'Please enter vat gstin no.',
  //     'store_address.required' => 'Please enter store address.',
  //     'store_images.required' => 'Please select store image.',
  //   ];
  //   $validator = Validator::make($request->all(), $rules, $customMessages);
  //   if ($validator->fails()) {
  //     $errors = $validator->errors();

  //     $responseErrors = [];
  //     foreach ($errors->all() as $error) {
  //       $responseErrors = $error;
  //     }

  //     return response([
  //       'success' => false,
  //       'message' => $responseErrors,
  //     ], 422);
  //   }
  //   if ($request->hasfile('store_logo')) {
  //     $storeLogo = time() . '.' . $request->store_logo->getClientOriginalName();
  //     $request->store_logo->move(public_path() . '/assets/images/store_logo/', $storeLogo);
  //   }
  //   $data = new VendorStore();
  //   $data->vendor_id = $vendor_id;
  //   $data->store_name = $request->store_name;
  //   $data->business_email = $request->business_email;
  //   $data->country_code = $request->country_code;
  //   $data->mobile = $request->mobile;
  //   $data->vat_gstin_no = $request->vat_gstin_no;
  //   $data->store_address = $request->store_address;
  //   $data->store_logo = $storeLogo;
  //   $data->save();
  //   if ($request->hasfile('store_images')) {
  //     foreach ($request->file('store_images') as $file) {
  //       $fileName = time() . '.' . $file->getClientOriginalName();
  //       $file->move(public_path() . '/assets/images/store_images/', $fileName);
  //       $data->storeImages()->create([
  //         'store_id' => $data->id,
  //         'store_images' => $fileName
  //       ]);
  //     }
  //   }
  //   return response([
  //     'success' => true,
  //     'message' => 'Store added successfully...!',
  //   ], 200);
  // }
  public function getStore()
  {
    $data = VendorStore::with('storeImages')
      ->where('vendor_id', $vendor_id)
      ->get();
    $vendoreStore = [];
    foreach ($data as $value) {
      $storeImg = [];
      foreach ($value->storeImages as $img) {
        $storeImg[] = $img->store_images ? url('assets/images/store_images/' . $img->store_images) : '';
      }
      $vendoreStore[] = [
        'id' => (string) $value->id,
        'vendor_id' => $value->vendor_id,
        'store_name' => $value->store_name,
        'business_email' => $value->business_email,
        'country_code' => $value->country_code,
        'store_address' => $value->store_address,
        'store_logo' => $value->store_logo ? url('assets/images/store_logo/' . $value->store_logo) : '',
        'store_images' => $storeImg,
      ];
    }
    return response(
      [
        'success' => true,
        'data' => $vendoreStore,
      ],
      200
    );
  }

  // public function getProduct2()
  // {
  //   $vendor_id = Auth::guard('sanctum')->user()->id;

  //   // $products = Product::where('vendor_id', $vendor_id)
  //   //   ->with('products.productImages')
  //   //   ->get();

  //   $products = Product::with('productImages')->where('vendor_id', $vendor_id)->get();

  //   $vendorProducts = [];

  //   foreach ($products as $vendorStore) {
  //     foreach ($vendorStore->products as $product) {
  //       $productImages = [];
  //       foreach ($product->productImages as $image) {
  //         $productImages[] = $image->product_image ? url('assets/images/product_images/' . $image->product_image) : '';
  //       }

  //       $vendorProducts[] = [
  //         'id' => $product->id,
  //         'vendor_id' => $product->vendor_id,
  //         'product_name' => $product->product_name,
  //         'product_description' => $product->product_about,
  //         'product_price' => $product->product_price,
  //         'product_sale_price' => $product->product_sale_price,
  //         'product_images' => $productImages,
  //       ];
  //     }
  //   }

  //   return response([
  //     'success' => true,
  //     'data' => $vendorProducts,
  //   ], 200);
  // }
  // public function getProduct3()
  // {
  //   $vendor_id = Auth::guard('sanctum')->user()->id;

  //   $products = Product::with('productImages')->where('vendor_id', $vendor_id)->get();

  //   $vendorProducts = [];

  //   foreach ($products as $vendorStore) {
  //     if ($vendorStore->products) { // Check if $vendorStore->products is not null
  //       foreach ($vendorStore->products as $product) {
  //         $productImages = [];
  //         if ($product->productImages) { // Check if $product->productImages is not null
  //           foreach ($product->productImages as $image) {
  //             $productImages[] = $image->product_image ? url('assets/images/product_images/' . $image->product_image) : '';
  //           }
  //         }

  //         $vendorProducts[] = [
  //           'id' => $product->id,
  //           'vendor_id' => $product->vendor_id,
  //           'product_name' => $product->product_name,
  //           'product_description' => $product->product_about,
  //           'product_price' => $product->product_price,
  //           'product_sale_price' => $product->product_sale_price,
  //           'product_images' => $productImages,
  //         ];
  //       }
  //     }
  //   }

  //   return response([
  //     'success' => true,
  //     'data' => $vendorProducts,
  //   ], 200);
  // }

  // public function getProduct()
  // {
  //   $vendor_id = Auth::guard('sanctum')->user()->id;
  //   $data = Product::with('productImages')->where('vendor_id', $vendor_id)->orderBy('id', 'desc')->get();
  //   $vendoreStore = [];
  //   foreach ($data as $value) {
  //     $storeImg = [];
  //     foreach ($value->productImages as $img) {
  //       $storeImg[] = $img->product_image ? url('assets/images/product_images/' . $img->product_image) : '';
  //     }
  //     $vendoreStore[] = [
  //       'id' => (string)$value->id,
  //       'vendor_id' => $value->vendor_id,
  //       'product_name' => $value->product_name,
  //       'product_description' => $value->product_description ?? "",
  //       'product_price' => $value->product_price,
  //       'product_sale_price' => $value->product_sale_price,
  //       'product_about' => $value->product_about ?? "",
  //       // 'store_logo' => $value->store_logo ? url('assets/images/store_logo/' . $value->store_logo) : '',
  //       'product_images' => $storeImg,
  //     ];

  //     $approve = Notifications::where('user_id', $vendor_id)->where('is_seen', '0')->count();
  //     // echo $approve;
  //     if ($approve != 0) {
  //       // echo "123";
  //       // exit;
  //       $is_view = "1";
  //     } else {
  //       $is_view = "0";
  //     }
  //   }
  //   return response([
  //     'success' => true,
  //     'data' => $vendoreStore,
  //     'is_seen' => $is_view,
  //   ], 200);
  // }

  public function getProductdetails(Request $request)
  {
    $product_id = $request->input('product_id');
    $data = Product::with('productImages')
      ->where('id', $product_id)
      ->get();
    $vendoreStore = [];
    foreach ($data as $value) {
      $storeImg = [];
      foreach ($value->productImages as $img) {
        $storeImg[] = $img->product_image ? url('assets/images/product_images/' . $img->product_image) : '';
      }
      //   $user =  Vendor::where('id', $value->vendor_id)->first();
      $vendoreStore = [
        'id' => (string) $value->id,
        'vendor_id' => $value->vendor_id,
        'product_name' => $value->product_name,
        'product_description' => $value->product_description ?? '',
        'product_price' => $value->product_price,
        'product_sale_price' => $value->product_sale_price,
        'product_about' => $value->product_about ?? '',
        'category_id' => $value->category_id,
        'sub_category_id' => $value->sub_category_id,
        'product_size' => $value->product_size,
        'product_color' => $value->product_color,
        'product_quantity' => (string) $value->product_quantity,
        'product_sku' => (string) $value->product_sku,
        'key_features' => (string) $value->key_features,
        'store_id' => explode(',', $value->store_id),
        // 'store_logo' => $value->store_logo ? url('assets/images/store_logo/' . $value->store_logo) : '',
        'product_images' => $storeImg,
        // 'vendor_name' => $user->name,
      ];
    }
    return response(
      [
        'success' => true,
        'product' => $vendoreStore,
      ],
      200
    );
  }

  public function vendor_delete(Request $request)
  {
    // $user = User::findOrFail($id);
    // $user->delete();
    $validator = Validator::make($request->all(), [
      'vendor_id' => 'required',
    ]);
    if ($validator->fails()) {
      return $this->sendError('Enter this field', $validator->errors(), 422);
      //     return response()->json([
      //         'errors' => $validator->errors(),
      //     ], 422); // Return a HTTP status code 422 (Unprocessable Entity)
    }

    $user_id = $request->input('vendor_id');
    $user = Vendor::find($request->input('vendor_id'));
    VendorStore::where('vendor_id', $user->id)->delete();

    if ($user) {
      $user->delete();

      return response()->json(['message' => 'User account deleted successfully']);
    } else {
      return response()->json(['message' => 'User already deleted']);
    }
  }

  public function update_status(Request $request)
  {
    $user_id = request('user_id');
    // $myId = $request->user()->token()->user_id;
    $vendor_id = Auth::guard('sanctum')->user()->id;
    $order_id = request('order_id');
    $order_status = request('order_status');

    $validator = Validator::make($request->all(), [
      'user_id' => 'required',
      'order_id' => 'required',
    ]);

    if ($validator->fails()) {
      return $this->sendError('Errors', $validator->errors());
      // return response()->json(['error' => $validator->errors()], 401);
    }

    $order = OrderItem::where('vendor_id', $vendor_id)
      ->where('user_id', $user_id)
      ->where('order_id', $order_id)
      ->update(['is_status' => $order_status]);

    // $order_status = CartItemsModel::where('order_id', $order_id)->get();

    $orderStatuses = OrderItem::where('order_id', $order_id)
      ->pluck('is_status')
      ->unique();

    // Update corresponding orders in OrdersModel based on order_status
    foreach ($orderStatuses as $status) {
      OrderItem::where('order_id', $order_id)->update(['is_status' => $order_status]);
    }

    if ($orderStatuses) {
      $temp = [
        'response_code' => '1',
        'message' => 'User Status Update',
      ];

      return response()->json($temp);
    }
  }
}
