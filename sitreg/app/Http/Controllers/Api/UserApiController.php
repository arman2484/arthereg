<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Module;
use App\Models\Modulebanner;
use App\Models\Notifications;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\ProductLike;
use App\Models\ProductReview;
use App\Models\StoreImages;
use App\Models\StoreLike;
use App\Models\StoreReview;
use App\Models\SubCategory;
use App\Models\Units;
use App\Models\User;
use App\Models\Variant;
use App\Models\VendorStore;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;

class UserApiController extends Controller
{
  // Social Login
  public function SocialLogin2(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'login_type' => 'required',
      'email' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'data' => $validator->errors(),
      ]);
    }
    $input = $request->all();
    // dd($input);
    if (User::where('email', $request->email)->exists()) {
      $user = User::where('email', $request->email)->first();
      $user->update($input);
      // return response()->json([
      //   'success' => false,
      //   'data' => array("token" => $user->createToken('MyApp')->accessToken, "login_type" => (string)$user->login_type), "Login success.",
      // ]);
      return response([
        'user_id' => (string) $user->id,
        'email' => $user->email,
        'token' => $user->createToken('MyApp')->plainTextToken,
        'login_type' => (string) $user->login_type,
        'message' => 'Login success.',
      ]);
    }
    User::create($input);
    $user = User::where('email', $request->email)->first();
    // return response()->json([
    //   'success' => false,
    //   'data' => array("token" => $user->createToken('MyApp')->accessToken, "login_type" => (string)$user->login_type), "Signup success.",
    // ]);
    return response([
      'user_id' => (string) $user->id,
      'email' => $user->email,
      'token' => $user->createToken('MyApp')->plainTextToken,
      'login_type' => (string) $user->login_type,
      'message' => 'Signup success.',
    ]);
  }

  public function SocialLogin(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'login_type' => 'required',
      'email' => 'required|email',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'data' => $validator->errors(),
      ]);
    }

    $input = $request->all();

    // Extract the part before the @ symbol from the email
    $emailLocalPart = explode('@', $request->email)[0];

    // Split the local part by . to get first_name and last_name
    $nameParts = explode('.', $emailLocalPart);

    // Default to 'User' if there is no last_name
    $first_name = isset($nameParts[0]) ? ucwords($nameParts[0]) : 'User';
    $last_name = isset($nameParts[1]) ? ucwords($nameParts[1]) : 'User';

    // Add first_name and last_name to input
    $input['first_name'] = $first_name;
    $input['last_name'] = $last_name;

    if (User::where('email', $request->email)->exists()) {
      $user = User::where('email', $request->email)->first();
      $user->update($input);
      return response([
        'user_id' => (string) $user->id,
        'email' => $user->email,
        'token' => $user->createToken('MyApp')->plainTextToken,
        'login_type' => (string) $user->login_type,
        'message' => 'Login success.',
      ]);
    }

    User::create($input);
    $user = User::where('email', $request->email)->first();
    return response([
      'user_id' => (string) $user->id,
      'email' => $user->email,
      'token' => $user->createToken('MyApp')->plainTextToken,
      'login_type' => (string) $user->login_type,
      'message' => 'Signup success.',
    ]);
  }





  // Get Module
  public function GetModule(Request $request)
  {
    $data = Module::select('id', 'name', 'image')->get();
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

  // Get Category
  public function GetCategory(Request $request)
  {
    $moduleId = $request->module_id;

    if (empty($moduleId)) {
      return response()->json([
        'message' => 'Module ID is required',
      ]);
    }

    $data = Category::where('module_id', $moduleId)
      ->select('id', 'category_name', 'category_image')
      ->with('sub_categories:id,category_id') // Eager load subcategories
      ->get();

    foreach ($data as $value) {
      $value->category_image = $value->category_image
        ? url('assets/images/category_images/' . $value->category_image)
        : '';

      // Check if subcategories relationship is not null before plucking IDs
      $value->subcategory_ids = $value->sub_categories
        ? $value->sub_categories->pluck('id')
        : [];

      // Unset sub_categories from the response
      unset($value->sub_categories);
    }

    if ($data->isNotEmpty()) {
      return response()->json([
        'message' => 'Category found',
        'data' => $data,
      ]);
    } else {
      return response()->json([
        'message' => 'Category not found',
      ]);
    }
  }



  // Get SubCategory
  public function getSubCategory(Request $request)
  {
    if (!empty($request->category_id)) {
      if (SubCategory::where('category_id', $request->category_id)->exists()) {
        $subCategory = SubCategory::select('id', 'category_id', 'sub_category_name')
          ->where('category_id', $request->category_id)
          ->get();
        return response()->json([
          'success' => true,
          'message' => 'Subcategory list Found',
          'data' => $subCategory,
        ]);
      } else {
        return response()->json([
          'success' => false,
          'message' => 'Sub Category not found',
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

  // Get Store
  // public function getStore(Request $request)
  // {
  //   $category_id = $request->input('category_id');
  //   $sub_category_id = $request->input('sub_category_id');

  //   // Query to get store IDs based on category_id and sub_category_id if provided
  //   $productQuery = Product::query();

  //   if ($category_id) {
  //     $productQuery->where('category_id', $category_id);
  //   }

  //   if ($sub_category_id) {
  //     $productQuery->where('sub_category_id', $sub_category_id);
  //   }

  //   $storeIds = $productQuery->pluck('store_id')->unique();

  //   $stores = VendorStore::with(['storeImages', 'bannerImages'])
  //     ->whereIn('id', $storeIds)
  //     ->get();

  //   $vendorStore = [];

  //   foreach ($stores as $store) {
  //     $storeImg = [];
  //     foreach ($store->storeImages as $img) {
  //       $storeImg[] = $img->store_images ? url('/assets/images/store_images/' . $img->store_images) : '';
  //     }
  //     $bannerImg = [];
  //     foreach ($store->bannerImages as $img) {
  //       $bannerImg[] = $img->banner_image ? url('/assets/images/banner_images/' . $img->banner_image) : '';
  //     }

  //     // Check if the store is liked by the user
  //     $StoreUserLike = StoreLike::where('user_id', $request->user_id)
  //       ->where('store_id', $store->id)
  //       ->exists();

  //     $vendorStore[] = [
  //       'id' => (string)$store->id,
  //       'vendor_id' => $store->vendor_id,
  //       'store_name' => $store->store_name ?? '',
  //       'store_description' => $store->store_description ?? '',
  //       'module_id' => $store->module_id,
  //       'lat' => $store->lat ?? '',
  //       'lon' => $store->lon ?? '',
  //       'store_address' => $store->store_address ?? '',
  //       'delivery_time' => $store->delivery_time ?? '',
  //       'store_logo' => $store->store_logo ? url('/assets/images/store_logo/' . $store->store_logo) : '',
  //       'store_images' => $storeImg,
  //       'banner_images' => $bannerImg,
  //       'is_Like' => $StoreUserLike ? 1 : 0,
  //     ];
  //   }

  //   return response([
  //     'success' => true,
  //     'data' => $vendorStore,
  //   ], 200);
  // }

  // public function getStore(Request $request)
  // {
  //   // Get input parameters
  //   $category_id = $request->input('category_id');
  //   $sub_category_id = $request->input('sub_category_id');
  //   $module_id = $request->input('module_id'); // New parameter

  //   // Query to get store IDs based on category_id, sub_category_id, and module_id
  //   $productQuery = Product::query();

  //   if ($category_id) {
  //     $productQuery->where('category_id', $category_id);
  //   }

  //   if ($sub_category_id) {
  //     $productQuery->where('sub_category_id', $sub_category_id);
  //   }

  //   if ($module_id) {
  //     $productQuery->where('module_id', $module_id); // Filter by module_id
  //   }

  //   // Get unique store IDs from the products
  //   $storeIds = $productQuery->pluck('store_id')->unique();

  //   // Fetch stores with their images
  //   $stores = VendorStore::with(['storeImages', 'bannerImages'])
  //     ->whereIn('id', $storeIds)
  //     ->get();

  //   $vendorStore = [];

  //   foreach ($stores as $store) {
  //     $storeImg = [];
  //     foreach ($store->storeImages as $img) {
  //       $storeImg[] = $img->store_images ? url('/assets/images/store_images/' . $img->store_images) : '';
  //     }

  //     $bannerImg = [];
  //     foreach ($store->bannerImages as $img) {
  //       $bannerImg[] = $img->banner_image ? url('/assets/images/banner_images/' . $img->banner_image) : '';
  //     }

  //     // Check if the store is liked by the user
  //     $StoreUserLike = StoreLike::where('user_id', $request->user_id)
  //       ->where('store_id', $store->id)
  //       ->exists();

  //     $vendorStore[] = [
  //       'id' => (string)$store->id,
  //       'vendor_id' => $store->vendor_id,
  //       'store_name' => $store->store_name ?? '',
  //       'store_description' => $store->store_description ?? '',
  //       'module_id' => $store->module_id,
  //       'lat' => $store->lat ?? '',
  //       'lon' => $store->lon ?? '',
  //       'store_address' => $store->store_address ?? '',
  //       'delivery_time' => $store->delivery_time ?? '',
  //       'store_logo' => $store->store_logo ? url('/assets/images/store_logo/' . $store->store_logo) : '',
  //       'store_images' => $storeImg,
  //       'banner_images' => $bannerImg,
  //       'is_Like' => $StoreUserLike ? 1 : 0,
  //     ];
  //   }

  //   return response([
  //     'success' => true,
  //     'data' => $vendorStore,
  //   ], 200);
  // }

  public function getStore(Request $request)
  {
    // Get input parameters
    $category_id = $request->input('category_id');
    $sub_category_id = $request->input('sub_category_id');
    $module_id = $request->input('module_id'); // New parameter

    // Query to get store IDs based on category_id, sub_category_id, and module_id
    $productQuery = Product::query();

    if ($category_id) {
      $productQuery->where('category_id', $category_id);
    }

    if ($sub_category_id) {
      $productQuery->where('sub_category_id', $sub_category_id);
    }

    if ($module_id) {
      $productQuery->where('module_id', $module_id); // Filter by module_id
    }

    // Get unique store IDs from the products
    $storeIds = $productQuery->pluck('store_id')->unique();

    // Fetch stores with their images
    $stores = VendorStore::with(['storeImages', 'bannerImages'])
      ->whereIn('id', $storeIds)
      ->get();

    $vendorStore = [];

    foreach ($stores as $store) {
      $storeImg = [];
      foreach ($store->storeImages as $img) {
        $storeImg[] = $img->store_images ? url('/assets/images/store_images/' . $img->store_images) : '';
      }

      $bannerImg = [];
      foreach ($store->bannerImages as $img) {
        $bannerImg[] = $img->banner_image ? url('/assets/images/banner_images/' . $img->banner_image) : '';
      }

      // Check if the store is liked by the user
      $StoreUserLike = StoreLike::where('user_id', $request->user_id)
        ->where('store_id', $store->id)
        ->exists();

      // Fetch products related to this store
      $products = Product::with('productImages')
        ->where('store_id', $store->id)
        ->get(['id', 'product_name', 'product_sale_price']);

      $productDetails = [];

      foreach ($products as $product) {
        $productImgUrls = [];
        foreach ($product->productImages as $productImage) {
          $productImgUrls[] = $productImage->product_image
            ? url('/assets/images/product_images/' . $productImage->product_image)
            : '';
        }

        $productDetails[] = [
          'product_name' => $product->product_name,
          'product_sale_price' => $product->product_sale_price,
          'product_images' => $productImgUrls, // Add all product images
        ];
      }

      $vendorStore[] = [
        'id' => (int) $store->id,
        'vendor_id' => $store->vendor_id,
        'store_name' => $store->store_name ?? '',
        'store_description' => $store->store_description ?? '',
        'module_id' => $store->module_id,
        'lat' => $store->lat ?? '',
        'lon' => $store->lon ?? '',
        'store_address' => $store->store_address ?? '',
        'delivery_time' => $store->delivery_time ?? '',
        'store_logo' => $store->store_logo ? url('/assets/images/store_logo/' . $store->store_logo) : '',
        'store_images' => $storeImg,
        'banner_images' => $bannerImg,
        'is_Like' => $StoreUserLike ? 1 : 0,
        'products' => $productDetails, // Add products to the store details
      ];
    }

    return response(
      [
        'success' => true,
        'data' => $vendorStore,
      ],
      200
    );
  }

  // Get Product
  // public function getProduct(Request $request)
  // {
  //   // Validate the incoming request to ensure required fields are present
  //   $request->validate([
  //     'category_id' => 'nullable|integer',
  //     'sub_category_id' => 'nullable|integer',
  //     'desc' => 'nullable|string',
  //     'asc' => 'nullable|string',
  //     'product_size' => 'nullable|string',
  //     'product_colors' => 'nullable|string',
  //     'user_id' => 'nullable|integer',
  //     'store_id' => 'nullable|integer',
  //     'price' => 'nullable|string', // New price parameter
  //     'vendor_id' => 'nullable|integer', // New vendor_id parameter
  //   ]);

  //   $product = Product::select(
  //     'products.id',
  //     'products.vendor_id',
  //     'products.store_id',
  //     'products.category_id',
  //     'products.sub_category_id',
  //     'products.product_name',
  //     'products.product_about',
  //     'products.product_sale_price',
  //     'products.product_price',
  //     'products.product_color',
  //     'products.product_size',
  //     'products.product_quantity',
  //     'products.in_stock'
  //   )->leftjoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id');

  //   // Apply category_id filter if provided
  //   if ($request->has('category_id') && $request->category_id != '') {
  //     $product->where('products.category_id', $request->category_id);
  //   }

  //   // Apply store_id filter if provided
  //   if ($request->has('store_id') && $request->store_id != '') {
  //     $product->where('products.store_id', $request->store_id);
  //   }

  //   // Apply sub_category_id filter if provided
  //   if ($request->has('sub_category_id') && $request->sub_category_id != '') {
  //     $product->where('products.sub_category_id', $request->sub_category_id);
  //   }

  //   // Apply vendor_id filter if provided
  //   if ($request->has('vendor_id') && $request->vendor_id != '') {
  //     $product->where('products.vendor_id', $request->vendor_id);
  //   }

  //   if ($request->desc != '') {
  //     $product->orderBy('product_sale_price', 'desc');
  //   } elseif ($request->asc != '') {
  //     $product->orderBy('product_sale_price', 'asc');
  //   }

  //   if ($request->product_size != '') {
  //     $sizes = explode(',', $request->product_size);
  //     $product->where(function ($query) use ($sizes) {
  //       foreach ($sizes as $size) {
  //         $query->orWhere('product_size', 'LIKE', "%$size%");
  //       }
  //     });
  //   }

  //   if ($request->product_colors != '') {
  //     $colors = explode(',', $request->product_colors);
  //     $product->where(function ($query) use ($colors) {
  //       foreach ($colors as $color) {
  //         $query->orWhere('product_color', 'LIKE', "%$color%");
  //       }
  //     });
  //   }

  //   // Apply price filter if provided
  //   if ($request->has('price') && $request->price != '') {
  //     $priceRange = explode(',', $request->price);
  //     if (count($priceRange) == 1) {
  //       $product->where('products.product_sale_price', '>', $priceRange[0]);
  //     } else {
  //       $product->whereBetween('products.product_sale_price', $priceRange);
  //     }
  //   }

  //   $products = $product->get();

  //   $productFilter = [];
  //   $uniqueColors = [];
  //   $uniqueSizes = [];

  //   foreach ($products as $product) {
  //     // Aggregate unique product colors and sizes
  //     $productColors = explode(',', $product->product_color);
  //     $productSizes = explode(',', $product->product_size);

  //     foreach ($productColors as $color) {
  //       if (!in_array($color, $uniqueColors)) {
  //         $uniqueColors[] = $color;
  //       }
  //     }

  //     foreach ($productSizes as $size) {
  //       if (!in_array($size, $uniqueSizes)) {
  //         $uniqueSizes[] = $size;
  //       }
  //     }

  //     // Set sale price
  //     if ($product->product_sale_price != '' && $product->product_sale_price > 0) {
  //       $product->product_sale_price = $product->product_sale_price;
  //     } else {
  //       $product->product_sale_price = $product->product_price;
  //     }

  //     // Get product images
  //     $productImages = ProductImages::select('product_image')
  //       ->where('product_id', $product->id)
  //       ->get()
  //       ->pluck('product_image')
  //       ->map(function ($image) {
  //         return url('/assets/images/product_images/' . $image);
  //       })
  //       ->toArray();

  //     $product->product_image = $productImages;

  //     // Get product reviews and calculate average rating
  //     $productReviews = ProductReview::select('review_star')
  //       ->where('product_id', $product->id)
  //       ->get();

  //     $totalReviews = $productReviews->count();
  //     $totalReviewStars = $productReviews->sum('review_star');
  //     $averageReview = $totalReviews > 0 ? round($totalReviewStars / $totalReviews, 1) : 0;

  //     $product->totalReviewCount = (int) $totalReviews;
  //     $product->totalAvgReview = (string) $averageReview;

  //     // Check if the product is liked by the user
  //     $productUserLike = ProductLike::where('user_id', $request->user_id)
  //       ->where('product_id', $product->id)
  //       ->first();

  //     $product->is_Like = $productUserLike ? 1 : 0;
  //   }

  //   // Prepare the filter data
  //   $productFilter = [
  //     'totalProductColor' => $uniqueColors,
  //     'totalproductSize' => $uniqueSizes,
  //   ];

  //   return response()->json([
  //     'message' => 'Product found',
  //     'productFilter' => $productFilter,
  //     'product' => $products,
  //   ]);
  // }

  public function getProduct(Request $request)
  {
    // Validate the incoming request to ensure required fields are present
    $request->validate([
      'category_id' => 'nullable|integer',
      'sub_category_id' => 'nullable|integer',
      'desc' => 'nullable|string',
      'asc' => 'nullable|string',
      'product_size' => 'nullable|string',
      'product_colors' => 'nullable|string',
      'user_id' => 'nullable|integer',
      'store_id' => 'nullable|integer',
      'price' => 'nullable|string',
      'vendor_id' => 'nullable|integer',
    ]);

    $product = Product::select(
      'products.id',
      'products.vendor_id',
      'products.store_id',
      'products.category_id',
      'products.sub_category_id',
      'products.product_name',
      'products.product_about',
      'products.product_sale_price',
      'products.product_price',
      'products.product_quantity',
      'products.in_stock',
      DB::raw('GROUP_CONCAT(DISTINCT variants.color) as product_color'),
      DB::raw('GROUP_CONCAT(DISTINCT variants.size) as product_size')
    )
      ->with('variants')
      ->leftJoin('variants', 'products.id', '=', 'variants.product_id')
      ->groupBy(
        'products.id',
        'products.vendor_id',
        'products.store_id',
        'products.category_id',
        'products.sub_category_id',
        'products.product_name',
        'products.product_about',
        'products.product_sale_price',
        'products.product_price',
        'products.product_quantity',
        'products.in_stock',
        'variants.id' // Include variants.id in the GROUP BY clause
      );

    // Apply category_id filter if provided
    if ($request->has('category_id') && $request->category_id != '') {
      $product->where('products.category_id', $request->category_id);
    }

    // Apply store_id filter if provided
    if ($request->has('store_id') && $request->store_id != '') {
      $product->where('products.store_id', $request->store_id);
    }

    // Apply sub_category_id filter if provided
    if ($request->has('sub_category_id') && $request->sub_category_id != '') {
      $product->where('products.sub_category_id', $request->sub_category_id);
    }

    // Apply vendor_id filter if provided
    if ($request->has('vendor_id') && $request->vendor_id != '') {
      $product->where('products.vendor_id', $request->vendor_id);
    }

    // Group by non-aggregated columns
    $product->groupBy(
      'products.id',
      'products.vendor_id',
      'products.store_id',
      'products.category_id',
      'products.sub_category_id',
      'products.product_name',
      'products.product_about',
      'products.product_sale_price',
      'products.product_price',
      'products.product_quantity',
      'products.in_stock'
    );

    if ($request->desc != '') {
      $product->orderBy('product_sale_price', 'desc');
    } elseif ($request->asc != '') {
      $product->orderBy('product_sale_price', 'asc');
    }

    if ($request->product_size != '') {
      $sizes = explode(',', $request->product_size);
      $product->where(function ($query) use ($sizes) {
        foreach ($sizes as $size) {
          $query->orWhere('variants.size', 'LIKE', "%$size%");
        }
      });
    }

    if ($request->product_colors != '') {
      $colors = explode(',', $request->product_colors);
      $product->where(function ($query) use ($colors) {
        foreach ($colors as $color) {
          $query->orWhere('variants.color', 'LIKE', "%$color%");
        }
      });
    }

    // Apply price filter if provided
    if ($request->has('price') && $request->price != '') {
      $priceRange = explode(',', $request->price);
      if (count($priceRange) == 1) {
        $product->where('products.product_sale_price', '>', $priceRange[0]);
      } else {
        $product->whereBetween('products.product_sale_price', $priceRange);
      }
    }

    $products = $product->get();

    $productFilter = [];
    $uniqueColors = [];
    $uniqueSizes = [];

    foreach ($products as $product) {
      // Set sale price
      $product->product_sale_price =
        $product->product_sale_price > 0 ? $product->product_sale_price : $product->product_price;

      // Get product images
      $productImages = ProductImages::select('product_image')
        ->where('product_id', $product->id)
        ->get()
        ->pluck('product_image')
        ->map(function ($image) {
          return url('/assets/images/product_images/' . $image);
        })
        ->toArray();

      $product->product_image = $productImages;

      $product->product_color = $product->product_color ? explode(',', $product->product_color) : [];
      $product->product_size = $product->product_size ? explode(',', $product->product_size) : [];

      // Get product reviews and calculate average rating
      $productReviews = ProductReview::select('review_star')
        ->where('product_id', $product->id)
        ->get();

      $totalReviews = $productReviews->count();
      $totalReviewStars = $productReviews->sum('review_star');
      $averageReview = $totalReviews > 0 ? round($totalReviewStars / $totalReviews, 1) : 0;

      $product->totalReviewCount = $totalReviews;
      $product->totalAvgReview = (string) $averageReview;

      // Check if the product is liked by the user
      $productUserLike = ProductLike::where('user_id', $request->user_id)
        ->where('product_id', $product->id)
        ->first();

      $product->is_Like = $productUserLike ? 1 : 0;
    }

    return response()->json([
      'message' => 'Products found',
      'productFilter' => $productFilter,
      'products' => $products,
    ]);
  }

  public function getProductonStore(Request $request)
  {
    // Validate the incoming request to ensure required fields are present
    $request->validate([
      'store_id' => 'required|integer', // Store ID is now required
      'category_id' => 'nullable|integer',
      'sub_category_id' => 'nullable|integer',
      'desc' => 'nullable|string',
      'asc' => 'nullable|string',
      'product_size' => 'nullable|string',
      'product_colors' => 'nullable|string',
      'user_id' => 'nullable|integer',
    ]);

    $productQuery = Product::select(
      'products.id',
      'products.vendor_id',
      'products.store_id',
      'products.category_id',
      'products.sub_category_id',
      'products.product_name',
      'products.product_about',
      'products.product_sale_price',
      'products.product_price',
      'sub_categories.sub_category_name',
      'products.product_color',
      'products.product_size',
      'products.product_quantity',
      'products.in_stock'
    )
      ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
      ->where('products.store_id', $request->store_id); // Filtering by store_id

    // Fetch all unique subcategory names and ids for the given store
    $subcategories = SubCategory::select('id as sub_category_id', 'sub_category_name')
      ->whereHas('products', function ($query) use ($request) {
        $query->where('store_id', $request->store_id);
      })
      ->distinct()
      ->get()
      ->toArray();

    // Apply filters if provided
    if ($request->filled('category_id')) {
      $productQuery->where('products.category_id', $request->category_id);
    }

    if ($request->filled('sub_category_id')) {
      $productQuery->where('products.sub_category_id', $request->sub_category_id);
    }

    // Apply sorting if provided
    if ($request->filled('desc')) {
      $productQuery->orderBy('product_sale_price', 'desc');
    } elseif ($request->filled('asc')) {
      $productQuery->orderBy('product_sale_price', 'asc');
    }

    // Filter by product size if provided
    if ($request->filled('product_size')) {
      $sizes = explode(',', $request->product_size);
      $productQuery->where(function ($query) use ($sizes) {
        foreach ($sizes as $size) {
          $query->orWhere('product_size', 'LIKE', "%$size%");
        }
      });
    }

    // Filter by product colors if provided
    if ($request->filled('product_colors')) {
      $colors = explode(',', $request->product_colors);
      $productQuery->where(function ($query) use ($colors) {
        foreach ($colors as $color) {
          $query->orWhere('product_color', 'LIKE', "%$color%");
        }
      });
    }

    $products = $productQuery->get();

    $productFilter = [];
    $uniqueColors = [];
    $uniqueSizes = [];

    foreach ($products as $product) {
      // Aggregate unique product colors and sizes
      $productColors = explode(',', $product->product_color);
      $productSizes = explode(',', $product->product_size);

      foreach ($productColors as $color) {
        if (!in_array($color, $uniqueColors)) {
          $uniqueColors[] = $color;
        }
      }

      foreach ($productSizes as $size) {
        if (!in_array($size, $uniqueSizes)) {
          $uniqueSizes[] = $size;
        }
      }

      // Set sale price
      $product->product_sale_price =
        $product->product_sale_price > 0 ? $product->product_sale_price : $product->product_price;

      // Get product images
      $productImages = ProductImages::select('product_image')
        ->where('product_id', $product->id)
        ->get()
        ->pluck('product_image')
        ->map(function ($image) {
          return url('/assets/images/product_images/' . $image);
        })
        ->toArray();

      $product->product_image = $productImages;

      // Get product reviews and calculate average rating
      $productReviews = ProductReview::select('review_star')
        ->where('product_id', $product->id)
        ->get();

      $totalReviews = $productReviews->count();
      $totalReviewStars = $productReviews->sum('review_star');
      $averageReview = $totalReviews > 0 ? round($totalReviewStars / $totalReviews, 1) : 0;

      $product->totalReviewCount = (string) $totalReviews;
      $product->totalAvgReview = (string) $averageReview;

      // Check if the product is liked by the user
      $productUserLike = ProductLike::where('user_id', $request->user_id)
        ->where('product_id', $product->id)
        ->first();

      $product->is_Like = $productUserLike ? 1 : 0;
    }

    // Prepare the filter data
    $productFilter = [
      'totalProductColor' => $uniqueColors,
      'totalproductSize' => $uniqueSizes,
    ];

    return response()->json([
      'message' => 'Product found',
      'productFilter' => $productFilter,
      'subCategoryNames' => $subcategories,
      'product' => $products,
    ]);
  }

  // Product detail
  // Perfect Code
  // public function productDetail(Request $request)
  // {
  //   // Check if the product exists
  //   if (!Product::where('id', $request->product_id)->first()) {
  //     return response()->json([
  //       'status' => 'False',
  //       'Message' => 'This Product Not Available',
  //     ]);
  //   }

  //   // Fetch product details along with store name
  //   $product = Product::select(
  //     'products.id',
  //     'products.category_id',
  //     'sub_category_id',
  //     'product_name',
  //     'product_image',
  //     'product_price',
  //     'product_sale_price',
  //     'product_color',
  //     'product_size',
  //     'product_about',
  //     'products.store_id', // Add store_id
  //     'products.in_stock', // Add in_stock
  //     'sub_categories.sub_category_name',
  //     'vendor_stores.store_name' // Fetch store_name through join
  //   )
  //     ->leftJoin('sub_categories', 'sub_category_id', '=', 'sub_categories.id')
  //     ->leftJoin('vendor_stores', 'products.store_id', '=', 'vendor_stores.id') // Join vendor_stores table
  //     ->where('products.id', $request->product_id)
  //     ->first();

  //   // If product review is null, set it to string
  //   $product->product_review = (string) $product->product_review;
  //   // Calculate discount percentage
  //   $product->product_sale_price = $product->product_sale_price ?? 0;
  //   $productPrice = (string) ((($product->product_sale_price - $product->product_price) * 100) / $product->product_price);
  //   // Split product colors and sizes into arrays
  //   $productColor = explode(',', $product->product_color);
  //   $productSize = explode(',', $product->product_size);

  //   // Fetch product images
  //   $productImages = ProductImages::select('product_image')
  //     ->where('product_id', $product->id)
  //     ->get();

  //   // Prepare arrays for colors, sizes, and images
  //   $totalProductColor = [];
  //   $totalProductSize = [];
  //   $totalProductImages = [];
  //   foreach ($productImages as $val) {
  //     $totalProductImages[] = $val->product_image ? url('/assets/images/product_images/' . $val->product_image) : "";
  //   }
  //   foreach ($productColor as $value) {
  //     $totalProductColor[] = $value;
  //   }

  //   foreach ($productSize as $vals) {
  //     $totalProductSize[] = $vals;
  //   }

  //   $product->product_image = $totalProductImages;
  //   $product->product_color = $productColor[0] == "" ? [] : $totalProductColor;
  //   $product->product_size = $productSize[0] == "" ? [] : $totalProductSize;
  //   $product->producDiscount = $productPrice;

  //   // Fetch product reviews
  //   $productDetails = ProductReview::select(
  //     'product_reviews.id',
  //     'product_reviews.product_id',
  //     'product_reviews.user_id',
  //     'product_reviews.review_star',
  //     'product_reviews.review_message',
  //     'product_reviews.created_at',
  //     'users.first_name',
  //     'users.last_name',
  //     'users.image'
  //   )
  //     ->leftJoin('users', 'product_reviews.user_id', '=', 'users.id')
  //     ->where('product_id', $request->product_id)
  //     ->orderBy('id', 'desc')
  //     ->get();

  //   // Calculate total reviews and average rating
  //   $totalReviewCount = $productDetails->count();
  //   $totalReviewStar = 0;
  //   $totalAvgReview = 0;
  //   $is_user_review = false;
  //   foreach ($productDetails as $value) {
  //     $value->first_name = $value->first_name ?? "";
  //     $value->last_name = $value->last_name ?? "";
  //     $value->review_message = $value->review_message ?? '';
  //     $value->image = $value->image ? url('/assets/images/users_images/' . $value->image) : "";
  //     $is_user_review = $value->review_star ? true : false;
  //     $totalReviewStar += floatval($value->review_star);
  //     $value->createdAts = $value->created_at ? $value->created_at->diffForHumans() : '';
  //   }
  //   if ($totalReviewCount > 0) {
  //     $totalAvgReview = round($totalReviewStar / $totalReviewCount, 1);
  //   }
  //   $productDetails->totalReviewCount = $totalReviewCount;

  //   // Check if product is liked by user
  //   $productLike = ProductLike::where('user_id', $request->user_id)
  //     ->where('product_id', $request->product_id)
  //     ->exists() ? 1 : 0;

  //   // Check if product is purchased by user
  //   $is_purchased = OrderItem::where('product_id', $request->product_id)
  //     ->where('user_id', $request->user_id)
  //     ->exists() ? true : false;

  //   // Get cart count for user
  //   $cartCount = CartItem::where('user_id', $request->user_id)->count();

  //   // Return the response
  //   return response()->json([
  //     'message' => 'Product Found',
  //     'totalAvgReviewCount' => (string) $totalAvgReview,
  //     'totalUserCount' => $totalReviewCount,
  //     'product' => $product,
  //     'productReviews' => $productDetails,
  //     'totalAvgReview' => (string) $totalAvgReview,
  //     'productLike' => $productLike,
  //     'is_purchased' => $is_purchased,
  //     'is_user_review' => $is_user_review,
  //     'cartCount' => $cartCount
  //   ]);
  // }

  public function GetUnits(Request $request)
  {
    $data = Units::select('id', 'name')->get();

    if ($data->isNotEmpty()) {
      return response()->json([
        'success' => true,
        'message' => 'Units found',
        'UnitData' => $data,
      ]);
    } else {
      return response()->json([
        'success' => true,
        'message' => 'No units found',
      ]);
    }
  }

  public function Showvariants(Request $request)
  {
    $productId = $request->input('product_id');
    $productSize = $request->input('product_size');
    $productColor = $request->input('product_color');

    if (empty($productId)) {
      return response()->json([
        'message' => 'Product ID is required',
      ]);
    }

    $query = Variant::where('product_id', $productId);

    if (!empty($productSize)) {
      $query->where('size', $productSize);
    }

    if (!empty($productColor)) {
      $query->where('color', $productColor);
    }

    $data = $query->select('id', 'product_id', 'color', 'size', 'price', 'total_stock', 'type')->get();

    if ($data->isNotEmpty()) {
      $formattedData = $data->map(function ($variant) {
        return [
          'id' => $variant->id,
          'product_id' => $variant->product_id,
          'color' => $variant->color ?? '',
          'size' => $variant->size ?? '',
          'price' => $variant->price ?? 0,
          'total_stock' => $variant->total_stock ?? 0,
          'type' => $variant->type ?? '',
        ];
      });

      return response()->json([
        'success' => true,
        'data' => $formattedData,
      ]);
    } else {
      return response()->json([
        'success' => false,
        'data' => [],
      ]);
    }
  }

  public function productDetail(Request $request)
  {
    // Check if the product exists
    if (!Product::where('id', $request->product_id)->first()) {
      return response()->json([
        'status' => 'False',
        'Message' => 'This Product Not Available',
      ]);
    }

    // Fetch product details along with store name
    $product = Product::select(
      'products.id',
      'products.category_id',
      'sub_category_id',
      'product_name',
      'product_image',
      'product_price',
      'product_sku',
      'tag',
      'product_sale_price',
      'product_color', // Removed from original logic
      'product_size', // Removed from original logic
      'product_about',
      'products.store_id',
      'products.in_stock',
      'vendor_stores.store_name'
    )
      ->leftJoin('sub_categories', 'sub_category_id', '=', 'sub_categories.id')
      ->leftJoin('vendor_stores', 'products.store_id', '=', 'vendor_stores.id')
      ->where('products.id', $request->product_id)
      ->first();

    // Check if sub_category_id is null, then set it to 0
    $product->sub_category_id = $product->sub_category_id ?? 0;

    // If product review is null, set it to string
    $product->product_review = (string) $product->product_review;
    // Calculate discount percentage
    $product->product_sale_price = $product->product_sale_price ?? 0;
    $productPrice =
      (string) ((($product->product_sale_price - $product->product_price) * 100) / $product->product_price);

    // Fetch product variants for colors and sizes
    $variants = Variant::select('color', 'size')
      ->where('product_id', $product->id)
      ->get();

    $totalProductColor = [];
    $totalProductSize = [];
    foreach ($variants as $variant) {
      if ($variant->color && !in_array($variant->color, $totalProductColor)) {
        $totalProductColor[] = $variant->color;
      }
      if ($variant->size && !in_array($variant->size, $totalProductSize)) {
        $totalProductSize[] = $variant->size;
      }
    }

    // Fetch product images
    $productImages = ProductImages::select('product_image')
      ->where('product_id', $product->id)
      ->get();

    $totalProductImages = [];
    foreach ($productImages as $val) {
      $totalProductImages[] = $val->product_image ? url('/assets/images/product_images/' . $val->product_image) : '';
    }

    $product->product_image = $totalProductImages;
    $product->product_color = $totalProductColor;
    $product->product_size = $totalProductSize;
    $product->producDiscount = $productPrice;

    // Fetch product reviews
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
      ->leftJoin('users', 'product_reviews.user_id', '=', 'users.id')
      ->where('product_id', $request->product_id)
      ->orderBy('id', 'desc')
      ->get();

    // Calculate total reviews and average rating
    $totalReviewCount = $productDetails->count();
    $totalReviewStar = 0;
    $totalAvgReview = 0;
    $is_user_review = false;
    foreach ($productDetails as $value) {
      $value->first_name = $value->first_name ?? '';
      $value->last_name = $value->last_name ?? '';
      $value->review_message = $value->review_message ?? '';
      $value->image = $value->image ? url('/assets/images/users_images/' . $value->image) : '';
      $is_user_review = $value->review_star ? true : false;
      $totalReviewStar += floatval($value->review_star);
      $value->createdAts = $value->created_at ? $value->created_at->diffForHumans() : '';
    }
    if ($totalReviewCount > 0) {
      $totalAvgReview = round($totalReviewStar / $totalReviewCount, 1);
    }
    $productDetails->totalReviewCount = $totalReviewCount;

    // Fetch product variants
    $variants = Variant::select('color', 'size', 'type', 'price', 'total_stock')
      ->where('product_id', $product->id)
      ->get();

    // Extract only variants
    $productVariants = [];
    foreach ($variants as $variant) {
      $productVariants[] = [
        'color' => $variant->color ?? '',
        'size' => $variant->size ?? '',
        'type' => $variant->type ?? '',
        'price' => $variant->price ?? 0,
        'total_stock' => $variant->total_stock ?? 0,
      ];
    }

    // Check if product is liked by user
    $productLike = ProductLike::where('user_id', $request->user_id)
      ->where('product_id', $request->product_id)
      ->exists()
      ? 1
      : 0;

    // Check if product is purchased by user
    $is_purchased = OrderItem::where('product_id', $request->product_id)
      ->where('user_id', $request->user_id)
      ->exists()
      ? true
      : false;

    // Get cart count for user
    $cartCount = CartItem::where('user_id', $request->user_id)->count();

    // Return the response
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
      'productVariants' => $productVariants,
    ]);
  }

  //  Store detail
  // public function Storedetail(Request $request)
  // {
  //   // Check if the store exists
  //   $store = VendorStore::find($request->store_id);
  //   if (!$store) {
  //     return response()->json([
  //       'status' => 'False',
  //       'Message' => 'This Store Not Available',
  //     ]);
  //   }

  //   // Fetch store details along with banner image
  //   $store = VendorStore::select(
  //     'vendor_stores.id',
  //     'vendor_stores.store_name',
  //     'vendor_stores.store_description',
  //     'vendor_stores.lat',
  //     'vendor_stores.lon',
  //     'vendor_stores.store_address',
  //     'vendor_stores.store_logo',
  //     'vendor_stores.min_time',
  //     'vendor_stores.max_time',
  //     'vendor_stores.time_type',
  //     'banners.banner_image'
  //   )
  //     ->leftJoin('banners', 'banners.store_id', '=', 'vendor_stores.id')
  //     ->where('vendor_stores.id', $request->store_id)
  //     ->first();

  //   // Replace null values with empty strings
  //   $store->min_time = $store->min_time ?? '';
  //   $store->max_time = $store->max_time ?? '';
  //   $store->time_type = $store->time_type ?? '';
  //   $store->store_logo = $store->store_logo ? url('/assets/images/store_logo/' . $store->store_logo) : '';

  //   // Fetch banner images
  //   $bannerImages = Banner::select('banner_image')
  //     ->where('store_id', $store->id)
  //     ->get();

  //   $totalbannerImages = [];
  //   foreach ($bannerImages as $val) {
  //     $totalbannerImages[] = $val->banner_image ? url('/assets/images/banner_images/' . $val->banner_image) : '';
  //   }
  //   $store->banner_image = $totalbannerImages;

  //   // Fetch store images
  //   $storeImages = StoreImages::select('store_images')
  //     ->where('store_id', $store->id)
  //     ->get();

  //   $totalStoreImages = [];
  //   foreach ($storeImages as $val) {
  //     $totalStoreImages[] = $val->store_images ? url('/assets/images/store_images/' . $val->store_images) : '';
  //   }
  //   $store->store_images = $totalStoreImages;

  //   // Fetch store reviews
  //   $storeReviews = StoreReview::select(
  //     'store_reviews.id',
  //     'store_reviews.store_id',
  //     'store_reviews.user_id',
  //     'store_reviews.review_star',
  //     'store_reviews.review_message',
  //     'users.first_name',
  //     'users.last_name',
  //     'users.image'
  //   )
  //     ->leftJoin('users', 'store_reviews.user_id', '=', 'users.id')
  //     ->where('store_id', $request->store_id)
  //     ->orderBy('id', 'desc')
  //     ->get();

  //   // Calculate total reviews and average rating
  //   $totalReviewCount = $storeReviews->count();
  //   $totalReviewStar = 0;
  //   $totalAvgReview = 0;
  //   $is_user_review = false;
  //   foreach ($storeReviews as $review) {
  //     $review->first_name = $review->first_name ?? "";
  //     $review->last_name = $review->last_name ?? "";
  //     $review->review_message = $review->review_message ?? '';
  //     $review->image = $review->image ? url('/assets/images/users_images/' . $review->image) : "";
  //     $totalReviewStar += floatval($review->review_star);
  //     $review->createdAt = $review->created_at ? $review->created_at->format('Y-m-d H:i:s') : '';
  //   }
  //   if ($totalReviewCount > 0) {
  //     $totalAvgReview = round($totalReviewStar / $totalReviewCount, 1);
  //   }

  //   // Get cart count for user
  //   $cartCount = CartItem::where('user_id', $request->user_id)->count();

  //   // Return the response
  //   return response()->json([
  //     'message' => 'Store Found',
  //     'totalAvgReviewCount' => (string) $totalAvgReview,
  //     'totalUserCount' => $totalReviewCount,
  //     'store' => $store,
  //     'storeReviews' => $storeReviews,
  //     'totalAvgReview' => (string) $totalAvgReview,
  //     'is_user_review' => $is_user_review,
  //     'cartCount' => $cartCount
  //   ]);
  // }

  public function Storedetail(Request $request)
  {
    // Check if the store exists
    $store = VendorStore::find($request->store_id);
    if (!$store) {
      return response()->json([
        'status' => 'False',
        'Message' => 'This Store Not Available',
      ]);
    }

    // Fetch store details along with banner image
    $store = VendorStore::select(
      'vendor_stores.id',
      'vendor_stores.store_name',
      'vendor_stores.store_description',
      'vendor_stores.lat',
      'vendor_stores.lon',
      'vendor_stores.store_address',
      'vendor_stores.store_logo',
      'vendor_stores.min_time',
      'vendor_stores.max_time',
      'vendor_stores.time_type',
      'banners.banner_image'
    )
      ->leftJoin('banners', 'banners.store_id', '=', 'vendor_stores.id')
      ->where('vendor_stores.id', $request->store_id)
      ->first();

    // Replace null values with empty strings
    $store->min_time = $store->min_time ?? '';
    $store->max_time = $store->max_time ?? '';
    $store->time_type = $store->time_type ?? '';
    $store->store_logo = $store->store_logo ? url('/assets/images/store_logo/' . $store->store_logo) : '';

    // Fetch banner images
    $bannerImages = Banner::select('banner_image')
      ->where('store_id', $store->id)
      ->get();

    $totalbannerImages = [];
    foreach ($bannerImages as $val) {
      $totalbannerImages[] = $val->banner_image ? url('/assets/images/banner_images/' . $val->banner_image) : '';
    }
    $store->banner_image = $totalbannerImages;

    // Fetch store images
    $storeImages = StoreImages::select('store_images')
      ->where('store_id', $store->id)
      ->get();

    $totalStoreImages = [];
    foreach ($storeImages as $val) {
      $totalStoreImages[] = $val->store_images ? url('/assets/images/store_images/' . $val->store_images) : '';
    }
    $store->store_images = $totalStoreImages;

    // Fetch store reviews
    $storeReviews = StoreReview::select(
      'store_reviews.id',
      'store_reviews.store_id',
      'store_reviews.user_id',
      'store_reviews.review_star',
      'store_reviews.review_message',
      'store_reviews.created_at',
      'users.first_name',
      'users.last_name',
      'users.image'
    )
      ->leftJoin('users', 'store_reviews.user_id', '=', 'users.id')
      ->where('store_id', $request->store_id)
      ->orderBy('id', 'desc')
      ->get();

    // Calculate total reviews and average rating
    $totalReviewCount = $storeReviews->count();
    $totalReviewStar = 0;
    $totalAvgReview = 0;
    $is_user_review = false;
    foreach ($storeReviews as $review) {
      $review->first_name = $review->first_name ?? '';
      $review->last_name = $review->last_name ?? '';
      $review->review_message = $review->review_message ?? '';
      $review->image = $review->image ? url('/assets/images/users_images/' . $review->image) : '';
      $review->createdAt = $review->created_at ? $review->created_at->format('Y-m-d H:i:s') : '';
      $totalReviewStar += floatval($review->review_star);
    }
    if ($totalReviewCount > 0) {
      $totalAvgReview = round($totalReviewStar / $totalReviewCount, 1);
    }

    // Get cart count for user
    $cartCount = CartItem::where('user_id', $request->user_id)->count();

    // Return the response
    return response()->json([
      'message' => 'Store Found',
      'totalAvgReviewCount' => (string) $totalAvgReview,
      'totalUserCount' => $totalReviewCount,
      'store' => $store,
      'storeReviews' => $storeReviews,
      'totalAvgReview' => (string) $totalAvgReview,
      'is_user_review' => $is_user_review,
      'cartCount' => $cartCount,
    ]);
  }

  //  Home
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
  //     $banner = Modulebanner::select('image')->get();
  //     foreach ($banner as $value) {
  //       $value->image = url('assets/images/module_banner/' . $value->image);
  //     }

  //     $category = Category::select('id', 'category_name', 'category_image')->get();
  //     foreach ($category as $value) {
  //       $value->category_image = url('/assets/images/category_images/' . $value->category_image);
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
  // public function home(Request $request)
  // {
  //   $notificationCount = 0;
  //   $module_id = $request->module_id;

  //   // Check if the module_id is provided
  //   if (!$module_id) {
  //     return response()->json([
  //       'message' => 'module_id is required',
  //     ], 400);
  //   }

  //   if ($request->user_id) {
  //     $cartCount = CartItem::where('user_id', $request->user_id)->count();
  //     $notificationCount = Notifications::where('user_id', $request->user_id)->where('is_seen', '0')->count();

  //     $banner = Modulebanner::where('module_id', $module_id)->select('image')->get();
  //     foreach ($banner as $value) {
  //       $value->image = url('assets/images/module_banner/' . $value->image);
  //     }

  //     $category = Category::where('module_id', $module_id)->select('id', 'category_name', 'category_icon')->get();
  //     foreach ($category as $value) {
  //       $value->category_image = url('assets/images/category_images/' . $value->category_icon);
  //     }

  //     // Popular products based on orders and likes
  //     $popularProducts = OrderItem::where('products.module_id', $module_id)
  //       ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
  //       ->leftJoin('product_likes', 'products.id', '=', 'product_likes.product_id')
  //       ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
  //       ->select(
  //         'products.id',
  //         'products.product_name',
  //         'products.product_sale_price',
  //         'products.product_price',
  //         'products.sub_category_id',
  //         'products.product_image',
  //         DB::raw('COUNT(order_items.id) as order_count'),
  //         DB::raw('COUNT(product_likes.id) as like_count'),
  //         'sub_categories.sub_category_name'
  //       )
  //       ->groupBy('products.id', 'products.product_name', 'products.product_sale_price', 'products.product_price', 'products.sub_category_id', 'products.product_image', 'sub_categories.sub_category_name')
  //       ->orderBy('order_count', 'desc')
  //       ->orderBy('like_count', 'desc')
  //       ->get();

  //     foreach ($popularProducts as $value) {
  //       $totalProductImages = [];
  //       $productImages = ProductImages::select('product_image')
  //         ->where('product_id', $value->id)
  //         ->get();

  //       foreach ($productImages as $val) {
  //         if ($val->product_image) {
  //           $totalProductImages[] = url('assets/images/product_images/' . $val->product_image);
  //         } else {
  //           $totalProductImages = [];
  //         }
  //       }

  //       $productUserLike = ProductLike::where('user_id', $request->user_id)
  //         ->where('product_id', $value->id)
  //         ->first();
  //       $value->is_Like = $productUserLike ? 1 : 0;
  //       $value->product_image = $totalProductImages;

  //       $totalProductReview = ProductReview::where('product_id', $value->id)->get();
  //       $totalReviewStar = $totalProductReview->sum('review_star');
  //       $totalReviewCount = $totalProductReview->count();

  //       $value->totalReviewCount = $totalReviewCount ? (string) $totalReviewCount : '';
  //       $value->totalAvgReview = $totalReviewCount > 0 ? (string) round($totalReviewStar / $totalReviewCount, 1) : '';
  //       $value->sub_category_name = $value->sub_category_name ?? '';
  //       $value->product_sale_price = $value->product_sale_price && $value->product_sale_price > 0 ? $value->product_sale_price : $value->product_price;
  //     }
  //   } else {
  //     $banner = Modulebanner::where('module_id', $module_id)->select('image')->get();
  //     foreach ($banner as $value) {
  //       $value->image = url('assets/images/module_banner/' . $value->image);
  //     }

  //     $category = Category::where('module_id', $module_id)->select('id', 'category_name', 'category_image')->get();
  //     foreach ($category as $value) {
  //       $value->category_image = url('/assets/images/category_images/' . $value->category_image);
  //     }

  //     // Popular products based on orders and likes
  //     $popularProducts = Product::where('products.module_id', $module_id)
  //       ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
  //       ->leftJoin('product_likes', 'products.id', '=', 'product_likes.product_id')
  //       ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
  //       ->select(
  //         'products.id',
  //         'products.product_name',
  //         'products.product_sale_price',
  //         'products.product_price',
  //         'products.sub_category_id',
  //         'products.product_image',
  //         DB::raw('COUNT(order_items.id) as order_count'),
  //         DB::raw('COUNT(product_likes.id) as like_count'),
  //         'sub_categories.sub_category_name'
  //       )
  //       ->groupBy('products.id', 'products.product_name', 'products.product_sale_price', 'products.product_price', 'products.sub_category_id', 'products.product_image', 'sub_categories.sub_category_name')
  //       ->orderBy('order_count', 'desc')
  //       ->orderBy('like_count', 'desc')
  //       ->get();

  //     foreach ($popularProducts as $value) {
  //       $totalProductImages = [];
  //       $productImages = ProductImages::select('product_image')
  //         ->where('product_id', $value->id)
  //         ->get();

  //       foreach ($productImages as $val) {
  //         if ($val->product_image) {
  //           $totalProductImages[] = url('assets/images/product_images/' . $val->product_image);
  //         } else {
  //           $totalProductImages = [];
  //         }
  //       }

  //       $value->product_image = $totalProductImages;

  //       $totalProductReview = ProductReview::where('product_id', $value->id)->get();
  //       $totalReviewStar = $totalProductReview->sum('review_star');
  //       $totalReviewCount = $totalProductReview->count();

  //       $value->totalReviewCount = $totalReviewCount ? (string) $totalReviewCount : '';
  //       $value->totalAvgReview = $totalReviewCount > 0 ? (string) round($totalReviewStar / $totalReviewCount, 1) : '';
  //       $value->sub_category_name = $value->sub_category_name ?? '';
  //       $value->product_sale_price = $value->product_sale_price && $value->product_sale_price > 0 ? $value->product_sale_price : $value->product_price;
  //       $value->is_Like = 0;
  //     }
  //   }

  //   return response()->json([
  //     'message' => 'product found',
  //     'category' => $category,
  //     'popular_product' => $popularProducts,
  //     'product_banner' => $banner,
  //     'cartCount' => $cartCount ?? 0,
  //     'notificationCount' => $notificationCount,
  //   ]);
  // }

  // public function home(Request $request)
  // {
  //   $notificationCount = 0;
  //   $module_id = $request->module_id;

  //   // Check if the module_id is provided
  //   if (!$module_id) {
  //     return response()->json([
  //       'message' => 'module_id is required',
  //     ], 400);
  //   }

  //   if ($request->user_id) {
  //     $cartCount = CartItem::where('user_id', $request->user_id)->count();
  //     $notificationCount = Notifications::where('user_id', $request->user_id)->where('is_seen', '0')->count();

  //     $banner = Modulebanner::where('module_id', $module_id)->select('image')->get();
  //     foreach ($banner as $value) {
  //       $value->image = url('assets/images/module_banner/' . $value->image);
  //     }

  //     $category = Category::where('module_id', $module_id)->select('id', 'category_name', 'category_icon')->get();
  //     foreach ($category as $value) {
  //       $value->category_image = url('assets/images/category_images/' . $value->category_icon);
  //     }

  //     // Popular products based on orders and likes
  //     $popularProducts = Product::where('products.module_id', $module_id)
  //       ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
  //       ->leftJoin('product_likes', 'products.id', '=', 'product_likes.product_id')
  //       ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
  //       ->select(
  //         'products.id',
  //         'products.product_name',
  //         'products.product_sale_price',
  //         'products.product_price',
  //         'products.sub_category_id',
  //         'products.product_image',
  //         DB::raw('COUNT(order_items.id) as order_count'),
  //         DB::raw('COUNT(product_likes.id) as like_count'),
  //         'sub_categories.sub_category_name'
  //       )
  //       ->groupBy(
  //         'products.id',
  //         'products.product_name',
  //         'products.product_sale_price',
  //         'products.product_price',
  //         'products.sub_category_id',
  //         'products.product_image',
  //         'sub_categories.sub_category_name'
  //       )
  //       ->orderBy('order_count', 'desc')
  //       ->orderBy('like_count', 'desc')
  //       ->get();

  //     foreach ($popularProducts as $value) {
  //       $totalProductImages = [];
  //       $productImages = ProductImages::select('product_image')
  //         ->where('product_id', $value->id)
  //         ->get();

  //       foreach ($productImages as $val) {
  //         if ($val->product_image) {
  //           $totalProductImages[] = url('assets/images/product_images/' . $val->product_image);
  //         } else {
  //           $totalProductImages = [];
  //         }
  //       }

  //       $productUserLike = ProductLike::where('user_id', $request->user_id)
  //         ->where('product_id', $value->id)
  //         ->first();
  //       $value->is_Like = $productUserLike ? 1 : 0;
  //       $value->product_image = $totalProductImages;

  //       $totalProductReview = ProductReview::where('product_id', $value->id)->get();
  //       $totalReviewStar = $totalProductReview->sum('review_star');
  //       $totalReviewCount = $totalProductReview->count();

  //       $value->totalReviewCount = $totalReviewCount ? (string)$totalReviewCount : '';
  //       $value->totalAvgReview = $totalReviewCount > 0 ? (string)round($totalReviewStar / $totalReviewCount, 1) : '';
  //       $value->sub_category_name = $value->sub_category_name ?? '';
  //       $value->product_sale_price = $value->product_sale_price && $value->product_sale_price > 0 ? $value->product_sale_price : $value->product_price;
  //     }

  //     // Nearby stores based on module_id
  //     $nearbyStores = VendorStore::where('module_id', $module_id)->get();
  //     $vendorStore = [];
  //     foreach ($nearbyStores as $store) {
  //       $storeImg = [];
  //       foreach ($store->storeImages as $img) {
  //         $storeImg[] = $img->store_images ? url('/assets/images/store_images/' . $img->store_images) : '';
  //       }

  //       // Check if the store is liked by the user
  //       $StoreUserLike = StoreLike::where('user_id', $request->user_id)
  //         ->where('store_id', $store->id)
  //         ->exists();

  //       $vendorStore[] = [
  //         'id' => (string)$store->id,
  //         'vendor_id' => $store->vendor_id,
  //         'store_name' => $store->store_name ?? '',
  //         'lat' => $store->lat ?? '',
  //         'lon' => $store->lon ?? '',
  //         'store_address' => $store->store_address ?? '',
  //         'status' => $store->status ?? '',
  //         'store_logo' => $store->store_logo ? url('/assets/images/store_logo/' . $store->store_logo) : '',
  //         'store_images' => $storeImg,
  //         'is_Like' => $StoreUserLike ? 1 : 0,
  //       ];
  //     }
  //   } else {
  //     $banner = Modulebanner::where('module_id', $module_id)->select('image')->get();
  //     foreach ($banner as $value) {
  //       $value->image = url('assets/images/module_banner/' . $value->image);
  //     }

  //     $category = Category::where('module_id', $module_id)->select('id', 'category_name', 'category_image')->get();
  //     foreach ($category as $value) {
  //       $value->category_image = url('/assets/images/category_images/' . $value->category_image);
  //     }

  //     // Popular products based on orders and likes
  //     $popularProducts = Product::where('products.module_id', $module_id)
  //       ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
  //       ->leftJoin('product_likes', 'products.id', '=', 'product_likes.product_id')
  //       ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
  //       ->select(
  //         'products.id',
  //         'products.product_name',
  //         'products.product_sale_price',
  //         'products.product_price',
  //         'products.sub_category_id',
  //         'products.product_image',
  //         DB::raw('COUNT(order_items.id) as order_count'),
  //         DB::raw('COUNT(product_likes.id) as like_count'),
  //         'sub_categories.sub_category_name'
  //       )
  //       ->groupBy(
  //         'products.id',
  //         'products.product_name',
  //         'products.product_sale_price',
  //         'products.product_price',
  //         'products.sub_category_id',
  //         'products.product_image',
  //         'sub_categories.sub_category_name'
  //       )
  //       ->orderBy('order_count', 'desc')
  //       ->orderBy('like_count', 'desc')
  //       ->get();

  //     foreach ($popularProducts as $value) {
  //       $totalProductImages = [];
  //       $productImages = ProductImages::select('product_image')
  //         ->where('product_id', $value->id)
  //         ->get();

  //       foreach ($productImages as $val) {
  //         if ($val->product_image) {
  //           $totalProductImages[] = url('assets/images/product_images/' . $val->product_image);
  //         } else {
  //           $totalProductImages = [];
  //         }
  //       }

  //       $value->product_image = $totalProductImages;

  //       $totalProductReview = ProductReview::where('product_id', $value->id)->get();
  //       $totalReviewStar = $totalProductReview->sum('review_star');
  //       $totalReviewCount = $totalProductReview->count();

  //       $value->totalReviewCount = $totalReviewCount ? (string)$totalReviewCount : '';
  //       $value->totalAvgReview = $totalReviewCount > 0 ? (string)round($totalReviewStar / $totalReviewCount, 1) : '';
  //       $value->sub_category_name = $value->sub_category_name ?? '';
  //       $value->product_sale_price = $value->product_sale_price && $value->product_sale_price > 0 ? $value->product_sale_price : $value->product_price;
  //       $value->is_Like = 0;
  //     }

  //     // Fetch nearby stores without user_id context
  //     $nearbyStores = VendorStore::where('module_id', $module_id)->get();
  //     $vendorStore = [];
  //     foreach ($nearbyStores as $store) {
  //       $storeImg = [];
  //       foreach ($store->storeImages as $img) {
  //         $storeImg[] = $img->store_images ? url('/assets/images/store_images/' . $img->store_images) : '';
  //       }

  //       $vendorStore[] = [
  //         'id' => (string)$store->id,
  //         'vendor_id' => $store->vendor_id,
  //         'store_name' => $store->store_name ?? '',
  //         'lat' => $store->lat ?? '',
  //         'lon' => $store->lon ?? '',
  //         'store_address' => $store->store_address ?? '',
  //         'status' => $store->status ?? '',
  //         'store_logo' => $store->store_logo ? url('/assets/images/store_logo/' . $store->store_logo) : '',
  //         'store_images' => $storeImg,
  //         'is_Like' => 0, // Since there's no user_id, we assume no likes
  //       ];
  //     }
  //   }

  //   return response()->json([
  //     'message' => 'product found',
  //     'category' => $category,
  //     'popular_product' => $popularProducts,
  //     'product_banner' => $banner,
  //     'nearby_store' => $vendorStore,
  //     'cartCount' => $cartCount ?? 0,
  //     'notificationCount' => $notificationCount,
  //   ]);
  // }

  public function home(Request $request)
  {
    $notificationCount = 0;
    $module_id = $request->module_id;
    $lat = $request->lat;
    $lon = $request->lon;

    // Check if the module_id is provided
    if (!$module_id) {
      return response()->json(
        [
          'message' => 'module_id is required',
        ],
        400
      );
    }

    // Function to calculate distance between two points using Haversine formula
    function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
      $earthRadius = 6371; // Radius of the earth in kilometers
      $deltaLat = deg2rad($lat2 - $lat1);
      $deltaLon = deg2rad($lon2 - $lon1);
      $a =
        sin($deltaLat / 2) * sin($deltaLat / 2) +
        cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($deltaLon / 2) * sin($deltaLon / 2);
      $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
      $distance = $earthRadius * $c; // Distance in kilometers
      return $distance;
    }

    if ($request->user_id) {
      $cartCount = CartItem::where('user_id', $request->user_id)->count();
      $notificationCount = Notifications::where('user_id', $request->user_id)
        ->where('is_seen', '0')
        ->count();

      $banner = Modulebanner::where('module_id', $module_id)
        ->select('image')
        ->get();
      $totalbannerImage = [];
      foreach ($banner as $value) {
        $totalbannerImage[] = url('assets/images/module_banner/' . $value->image);
      }

      $category = Category::where('module_id', $module_id)
        ->select('id', 'category_name', 'category_image')
        ->get();
      foreach ($category as $value) {
        $value->category_image = url('/assets/images/category_images/' . $value->category_image);
      }

      $subcategory = SubCategory::where('module_id', $module_id)
        ->select('id', 'sub_category_name', 'image')
        ->get();
      foreach ($subcategory as $value) {
        $value->image = url('/assets/images/subcategory_image/' . $value->image);
      }

      // Popular products based on orders and likes
      $popularProducts = Product::where('products.module_id', $module_id)
        ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
        ->leftJoin('product_likes', 'products.id', '=', 'product_likes.product_id')
        ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
        ->select(
          'products.id',
          'products.product_name',
          'products.product_sale_price',
          'products.product_price',
          'products.sub_category_id',
          'products.product_image',
          DB::raw('COUNT(order_items.id) as order_count'),
          DB::raw('COUNT(product_likes.id) as like_count'),
          'sub_categories.sub_category_name'
        )
        ->groupBy(
          'products.id',
          'products.product_name',
          'products.product_sale_price',
          'products.product_price',
          'products.sub_category_id',
          'products.product_image',
          'sub_categories.sub_category_name'
        )
        ->orderBy('order_count', 'desc')
        ->orderBy('like_count', 'desc')
        ->get();

      foreach ($popularProducts as $value) {
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

        $productUserLike = ProductLike::where('user_id', $request->user_id)
          ->where('product_id', $value->id)
          ->first();
        $value->is_Like = $productUserLike ? 1 : 0;
        $value->product_image = $totalProductImages;

        $totalProductReview = ProductReview::where('product_id', $value->id)->get();
        $totalReviewStar = $totalProductReview->sum('review_star');
        $totalReviewCount = $totalProductReview->count();

        $value->totalReviewCount = $totalReviewCount ? (string) $totalReviewCount : '';
        $value->totalAvgReview = $totalReviewCount > 0 ? (string) round($totalReviewStar / $totalReviewCount, 1) : '';
        $value->sub_category_name = $value->sub_category_name ?? '';
        $value->product_sale_price =
          $value->product_sale_price && $value->product_sale_price > 0
          ? $value->product_sale_price
          : $value->product_price;
      }

      // Nearby stores based on module_id
      $nearbyStores = VendorStore::where('module_id', $module_id)->get();
      $vendorStore = [];
      foreach ($nearbyStores as $store) {
        $distance = haversineDistance($lat, $lon, $store->lat, $store->lon);
        // You can set a distance threshold, for example, 10 kilometers
        if ($distance <= 10) {
          // Adjust the distance as needed
          $storeImg = [];
          foreach ($store->storeImages as $img) {
            $storeImg[] = $img->store_images ? url('/assets/images/store_images/' . $img->store_images) : '';
          }

          // Check if the store is liked by the user
          $StoreUserLike = StoreLike::where('user_id', $request->user_id)
            ->where('store_id', $store->id)
            ->exists();

          // Calculate the average review star for the store
          $totalStoreReview = StoreReview::where('store_id', $store->id)->get();
          $totalReviewStar = $totalStoreReview->sum('review_star');
          $totalReviewCount = $totalStoreReview->count();
          $totalAvgReview = $totalReviewCount > 0 ? round($totalReviewStar / $totalReviewCount, 1) : '';

          $vendorStore[] = [
            'id' => (string) $store->id,
            'vendor_id' => $store->vendor_id,
            'store_name' => $store->store_name ?? '',
            'lat' => $store->lat ?? '',
            'lon' => $store->lon ?? '',
            'store_address' => $store->store_address ?? '',
            'status' => $store->status ?? '',
            'store_logo' => $store->store_logo ? url('/assets/images/store_logo/' . $store->store_logo) : '',
            'store_images' => $storeImg,
            'is_Like' => $StoreUserLike ? 1 : 0,
            'totalAvgReview' => $totalAvgReview,
            'distance' => round($distance, 2), // Distance in kilometers rounded to 2 decimal places
          ];
        }
      }

      //  Latest products based on module_id
      $latestProducts = Product::select(
        'products.id',
        'product_name',
        'product_sale_price',
        'product_price',
        'product_image',
        'sub_category_id',
        'sub_categories.sub_category_name'
      )
        ->leftJoin('sub_categories', 'sub_category_id', '=', 'sub_categories.id')
        ->where('products.module_id', $module_id)
        ->orderBy('products.created_at', 'desc') // Order by product creation date in descending order
        ->get();

      foreach ($latestProducts as $value) {
        $totalProductImages = [];
        $productImages = ProductImages::select('product_image')
          ->where('product_id', $value->id)
          ->get();
        // dd($productImages);
        foreach ($productImages as $val) {
          if ($val->product_image) {
            $totalProductImages[] = url('assets/images/product_images/' . $val->product_image);
          } else {
            $totalProductImages = [];
          }
        }

        // $value->product_image = url('public/assets/images/product_images/' . $value->product_image);
        $productUserLike = ProductLike::where('user_id', $request->user_id)
          ->where('product_id', $value->id)
          ->first();
        if ($productUserLike) {
          $value->is_Like = 1;
        } else {
          $value->is_Like = 0;
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
      }

      // Trending products based on orders and likes
      $trendingProducts = Product::where('products.module_id', $module_id)
        ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
        ->leftJoin('product_likes', 'products.id', '=', 'product_likes.product_id')
        ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
        ->select(
          'products.id',
          'products.product_name',
          'products.product_sale_price',
          'products.product_price',
          'products.sub_category_id',
          'products.product_image',
          DB::raw('COUNT(order_items.id) as order_count'),
          DB::raw('COUNT(product_likes.id) as like_count'),
          'sub_categories.sub_category_name'
        )
        ->groupBy(
          'products.id',
          'products.product_name',
          'products.product_sale_price',
          'products.product_price',
          'products.sub_category_id',
          'products.product_image',
          'sub_categories.sub_category_name'
        )
        ->orderBy('order_count', 'desc')
        ->orderBy('like_count', 'desc')
        ->get();

      foreach ($trendingProducts as $value) {
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

        $productUserLike = ProductLike::where('user_id', $request->user_id)
          ->where('product_id', $value->id)
          ->first();
        $value->is_Like = $productUserLike ? 1 : 0;
        $value->product_image = $totalProductImages;

        $totalProductReview = ProductReview::where('product_id', $value->id)->get();
        $totalReviewStar = $totalProductReview->sum('review_star');
        $totalReviewCount = $totalProductReview->count();

        $value->totalReviewCount = $totalReviewCount ? (string) $totalReviewCount : '';
        $value->totalAvgReview = $totalReviewCount > 0 ? (string) round($totalReviewStar / $totalReviewCount, 1) : '';
        $value->sub_category_name = $value->sub_category_name ?? '';
        $value->product_sale_price =
          $value->product_sale_price && $value->product_sale_price > 0
          ? $value->product_sale_price
          : $value->product_price;
      }
    } else {
      $banner = Modulebanner::where('module_id', $module_id)
        ->select('image')
        ->get();
      $totalbannerImage = [];
      foreach ($banner as $value) {
        $totalbannerImage[] = url('assets/images/module_banner/' . $value->image);
      }

      $category = Category::where('module_id', $module_id)
        ->select('id', 'category_name', 'category_image')
        ->get();
      foreach ($category as $value) {
        $value->category_image = url('/assets/images/category_images/' . $value->category_image);
      }

      $subcategory = SubCategory::where('module_id', $module_id)
        ->select('id', 'sub_category_name', 'image')
        ->get();
      foreach ($subcategory as $value) {
        $value->image = url('/assets/images/subcategory_image/' . $value->image);
      }

      // Popular products based on orders and likes
      $popularProducts = Product::where('products.module_id', $module_id)
        ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
        ->leftJoin('product_likes', 'products.id', '=', 'product_likes.product_id')
        ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
        ->select(
          'products.id',
          'products.product_name',
          'products.product_sale_price',
          'products.product_price',
          'products.sub_category_id',
          'products.product_image',
          DB::raw('COUNT(order_items.id) as order_count'),
          DB::raw('COUNT(product_likes.id) as like_count'),
          'sub_categories.sub_category_name'
        )
        ->groupBy(
          'products.id',
          'products.product_name',
          'products.product_sale_price',
          'products.product_price',
          'products.sub_category_id',
          'products.product_image',
          'sub_categories.sub_category_name'
        )
        ->orderBy('order_count', 'desc')
        ->orderBy('like_count', 'desc')
        ->get();

      foreach ($popularProducts as $value) {
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

        $value->product_image = $totalProductImages;

        $totalProductReview = ProductReview::where('product_id', $value->id)->get();
        $totalReviewStar = $totalProductReview->sum('review_star');
        $totalReviewCount = $totalProductReview->count();

        $value->totalReviewCount = $totalReviewCount ? (string) $totalReviewCount : '';
        $value->totalAvgReview = $totalReviewCount > 0 ? (string) round($totalReviewStar / $totalReviewCount, 1) : '';
        $value->sub_category_name = $value->sub_category_name ?? '';
        $value->product_sale_price =
          $value->product_sale_price && $value->product_sale_price > 0
          ? $value->product_sale_price
          : $value->product_price;
        $value->is_Like = 0;
      }

      // Fetch nearby stores without user_id context
      $nearbyStores = VendorStore::where('module_id', $module_id)->get();
      $vendorStore = [];
      foreach ($nearbyStores as $store) {
        $distance = haversineDistance($lat, $lon, $store->lat, $store->lon);
        if ($distance <= 10) {
          // Adjust the distance as needed
          $storeImg = [];
          foreach ($store->storeImages as $img) {
            $storeImg[] = $img->store_images ? url('/assets/images/store_images/' . $img->store_images) : '';
          }

          // Calculate the average review star for the store
          $totalStoreReview = StoreReview::where('store_id', $store->id)->get();
          $totalReviewStar = $totalStoreReview->sum('review_star');
          $totalReviewCount = $totalStoreReview->count();
          $totalAvgReview = $totalReviewCount > 0 ? round($totalReviewStar / $totalReviewCount, 1) : '';

          $vendorStore[] = [
            'id' => (string) $store->id,
            'vendor_id' => $store->vendor_id,
            'store_name' => $store->store_name ?? '',
            'lat' => $store->lat ?? '',
            'lon' => $store->lon ?? '',
            'store_address' => $store->store_address ?? '',
            'status' => $store->status ?? '',
            'store_logo' => $store->store_logo ? url('/assets/images/store_logo/' . $store->store_logo) : '',
            'store_images' => $storeImg,
            'is_Like' => 0, // Since there's no user_id, we assume no likes
            'totalAvgReview' => $totalAvgReview,
            'distance' => round($distance, 2), // Distance in kilometers rounded to 2 decimal places
          ];
        }
      }

      // latest products on basis of module_id
      $latestProducts = Product::select(
        'products.id',
        'product_name',
        'product_sale_price',
        'product_price',
        'product_image',
        'sub_category_id',
        'sub_categories.sub_category_name'
      )
        ->leftJoin('sub_categories', 'sub_category_id', '=', 'sub_categories.id')
        ->where('products.module_id', $module_id)
        ->orderBy('products.created_at', 'desc') // Order by product creation date in descending order
        ->get();

      foreach ($latestProducts as $value) {
        // dd($value->id);
        $totalProductImages = [];
        $productImages = ProductImages::select('product_image')
          ->where('product_id', $value->id)
          ->get();
        // dd($productImages);
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
        $value->is_Like = 0;
      }

      // Popular products based on orders and likes
      $trendingProducts = Product::where('products.module_id', $module_id)
        ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
        ->leftJoin('product_likes', 'products.id', '=', 'product_likes.product_id')
        ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
        ->select(
          'products.id',
          'products.product_name',
          'products.product_sale_price',
          'products.product_price',
          'products.sub_category_id',
          'products.product_image',
          DB::raw('COUNT(order_items.id) as order_count'),
          DB::raw('COUNT(product_likes.id) as like_count'),
          'sub_categories.sub_category_name'
        )
        ->groupBy(
          'products.id',
          'products.product_name',
          'products.product_sale_price',
          'products.product_price',
          'products.sub_category_id',
          'products.product_image',
          'sub_categories.sub_category_name'
        )
        ->orderBy('order_count', 'desc')
        ->orderBy('like_count', 'desc')
        ->get();

      foreach ($trendingProducts as $value) {
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

        $value->product_image = $totalProductImages;

        $totalProductReview = ProductReview::where('product_id', $value->id)->get();
        $totalReviewStar = $totalProductReview->sum('review_star');
        $totalReviewCount = $totalProductReview->count();

        $value->totalReviewCount = $totalReviewCount ? (string) $totalReviewCount : '';
        $value->totalAvgReview = $totalReviewCount > 0 ? (string) round($totalReviewStar / $totalReviewCount, 1) : '';
        $value->sub_category_name = $value->sub_category_name ?? '';
        $value->product_sale_price =
          $value->product_sale_price && $value->product_sale_price > 0
          ? $value->product_sale_price
          : $value->product_price;
        $value->is_Like = 0;
      }
    }

    return response()->json([
      'message' => 'product found',
      'category' => $category,
      'popular_product' => $popularProducts,
      'nearby_store' => $vendorStore,
      'product_banner' => $totalbannerImage,
      'daily_essentials' => $latestProducts,
      'household_favourites' => $subcategory,
      'trending_product' => $trendingProducts,
      'cartCount' => $cartCount ?? 0,
      'notificationCount' => $notificationCount,
    ]);
  }

  // public function addVariants(Request $request)
  // {
  //   $data = new Variant();
  //   $data->product_id = $request->product_id;
  //   $data->color = $request->color;
  //   $data->size = $request->size;
  //   $data->type = $request->type;
  //   $data->price = $request->price;
  //   $data->total_stock = $request->total_stock;
  //   $data->save();

  //   return response(
  //     [
  //       'success' => true,
  //       'message' => 'Variants added successfully...!',
  //     ],
  //     200
  //   );
  // }

  public function addVariants(Request $request)
  {
    // Validate request data
    $price = $request->price;
    $request->validate([
      'product_id' => 'required|exists:products,id',
      // 'price' => [
      //     'required',
      //     function ($attribute, $value, $fail) use ($request) {
      //         $product = Product::findOrFail($request->product_id);

      //         // Check if price exceeds product_sale_price
      //         // if ($price >= $product->product_sale_price) {
      //         //     $fail('The price cannot be greater than the product sale price.');
      //         // }
      //     },
      // ],
    ]);

    if ($price) {
      $all_product = Product::where('id', $request->product_id)->first();

      $all_product_price = $all_product->product_sale_price;

      if ($price > $all_product_price) {
        return response()->json([
          'status' => 'False',
          'Message' => 'This Store Not Available',
        ]);
      }
    }

    // Create and save the variant
    $variant = new Variant();
    $variant->product_id = $request->product_id;
    $variant->color = $request->color ?? null;
    $variant->size = $request->size ?? null;
    $variant->type = $request->type ?? null;
    $variant->price = $request->price;
    $variant->total_stock = $request->total_stock;
    $variant->save();

    return response()->json(
      [
        'success' => true,
        'message' => 'Variant added successfully.',
      ],
      200
    );
  }
}
