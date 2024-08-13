<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Modulebanner;
use App\Models\Notifications;
use App\Models\ProductImages;
use App\Models\ProductReview;
use App\Models\ProductLike;
use App\Models\SubCategory;
use App\Models\StoreReview;
use App\Models\VendorStore;
use App\Models\StoreLike;
use App\Models\User;
use App\Models\UserReferal;
use App\Models\Variant;
use Illuminate\Support\Facades\DB;

class WebApiController extends Controller
{
  // Product List
  // public function productList(Request $request)
  // {

  //   $perPage = $request->input('per_page', 12);
  //   $page = $request->input('page', 1);
  //   $price = $request->input('price');
  //   $categoryIds = $request->input('category_id');
  //   $subCategoryIds = $request->input('sub_category_id'); // New parameter
  //   $moduleId = $request->input('module_id'); // New parameter

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
  //     'products.product_size',
  //     'products.store_id',
  //     'categories.category_name',
  //     'vendor_stores.store_name'
  //   )->leftJoin(
  //     'sub_categories',
  //     'products.sub_category_id',
  //     '=',
  //     'sub_categories.id'
  //   )
  //     ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
  //     ->leftJoin('vendor_stores', 'products.store_id', '=', 'vendor_stores.id');

  //   if ($categoryIds) {
  //     $categoryIdsArray = explode(',', $categoryIds);
  //     $productQuery->whereIn('products.category_id', $categoryIdsArray);
  //   }

  //   if ($subCategoryIds) {
  //     $subcategoryIds = explode(',', $subCategoryIds);
  //     $productQuery->whereIn('products.sub_category_id', $subcategoryIds);
  //   }

  //   // Filter by module_id (assuming module_id corresponds to some attribute in products)
  //   if ($moduleId) {
  //     $productQuery->where('products.module_id', $moduleId);
  //   }

  //   if ($price) {
  //     $priceRange = explode(',', $price);
  //     if (count($priceRange) == 1) {
  //       $productQuery->where('products.product_sale_price', '>', $priceRange[0]);
  //     } else {
  //       $productQuery->whereBetween('products.product_sale_price', $priceRange);
  //     }
  //   }

  //   if ($request->has('desc')) {
  //     $productQuery->orderBy('products.created_at', 'desc');
  //   } elseif ($request->has('asc')) {
  //     $productQuery->orderBy('products.created_at', 'asc');
  //   } else {
  //     $productQuery->orderBy('products.created_at', 'desc');
  //   }

  //   if ($request->has('product_size')) {
  //     $sizes = explode(',', $request->product_size);
  //     $productQuery->where(function ($query) use ($sizes) {
  //       foreach ($sizes as $size) {
  //         $query->orWhere('product_size', 'LIKE', "%$size%");
  //       }
  //     });
  //   }

  //   if ($request->has('product_colors')) {
  //     $colors = explode(',', $request->product_colors);
  //     $productQuery->where(function ($query) use ($colors) {
  //       foreach ($colors as $color) {
  //         $query->orWhere('product_color', 'LIKE', "%$color%");
  //       }
  //     });
  //   }

  //   $products = $productQuery->paginate($perPage, ['*'], 'page', $page);

  //   $productFilter = [
  //     'totalProductColor' => collect($products->pluck('product_color')->flatten())->map(function ($item) {
  //       return array_map('trim', explode(',', $item));
  //     })->flatten()->unique()->values()->all(),
  //     'totalproductSize' => collect($products->pluck('product_size')->flatten())->map(function ($item) {
  //       return array_map('trim', explode(',', $item));
  //     })->flatten()->unique()->values()->all(),
  //   ];

  //   $categories = Category::pluck('category_name', 'id')->map(function ($name, $id) {
  //     return [
  //       'id' => $id,
  //       'category_name' => $name
  //     ];
  //   })->values()->all();

  //   foreach ($products as $product) {
  //     $product->product_sale_price = $product->product_sale_price != '' && $product->product_sale_price > 0
  //       ? $product->product_sale_price
  //       : $product->product_price;

  //     $productImages = ProductImages::select('product_image')
  //       ->where('product_id', $product->id)
  //       ->get();

  //     $totalProductImages = [];
  //     foreach ($productImages as $val) {
  //       if ($val->product_image) {
  //         $totalProductImages = url('/assets/images/product_images/' . $val->product_image);
  //       }
  //     }
  //     $product->product_image = $totalProductImages;

  //     $totalReviewCount = ProductReview::where('product_id', $product->id)->count();
  //     $product->totalReviewCount = $totalReviewCount;

  //     $totalProductReview = ProductReview::select('product_id', 'review_star')
  //       ->where('product_id', $product->id)
  //       ->get();

  //     $totalReviewStar = 0;
  //     $totalAvgReview = 0;
  //     foreach ($totalProductReview as $val) {
  //       $reviewStar = floatval($val->review_star);
  //       $totalReviewStar += $reviewStar;
  //     }

  //     $totalReviewCount = $totalProductReview->count();
  //     $product->totalReviewCount = $totalReviewCount ? $totalReviewCount : 0;
  //     if ($totalReviewCount > 0) {
  //       $totalAvgReview = round($totalReviewStar / $totalReviewCount, 1);
  //     }
  //     $product->totalAvgReview = (string) $totalAvgReview;

  //     $productUserLike = ProductLike::where('user_id', $request->user_id)
  //       ->where('product_id', $product->id)
  //       ->first();
  //     $product->is_Like = $productUserLike ? 1 : 0;

  //     $product->product_color = $product->product_color ?? '';
  //     $product->product_size = $product->product_size ?? '';
  //   }

  //   $totalProductPrices = $products->pluck('product_price')->unique()->values()->all();

  //   return response()->json([
  //     'message' => 'Products found',
  //     'productFilter' => [
  //       'totalProductColor' => $productFilter['totalProductColor'],
  //       'totalproductSize' => $productFilter['totalproductSize'],
  //       'totalProductPrices' => $totalProductPrices,
  //       'categories' => $categories,
  //     ],
  //     'products' => $products,
  //   ]);
  // }

  public function productList(Request $request)
  {
    $perPage = $request->input('per_page', 12);
    $page = $request->input('page', 1);
    $price = $request->input('price');
    $categoryIds = $request->input('category_id');
    $subCategoryIds = $request->input('sub_category_id');
    $moduleId = $request->input('module_id');

    $productQuery = Product::select(
      'products.id',
      'products.category_id',
      'products.sub_category_id',
      'products.product_name',
      'products.product_about',
      'products.product_sale_price',
      'products.product_price',
      'products.module_id',
      'sub_categories.sub_category_name',
      'products.store_id',
      'categories.category_name',
      'vendor_stores.store_name',
      DB::raw('GROUP_CONCAT(DISTINCT variants.color) as product_color'),
      DB::raw('GROUP_CONCAT(DISTINCT variants.size) as product_size')
    )
      ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
      ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
      ->leftJoin('vendor_stores', 'products.store_id', '=', 'vendor_stores.id');

    // Join variants table to get product_color and product_size
    $productQuery->leftJoin('variants', 'products.id', '=', 'variants.product_id');

    if ($categoryIds) {
      $categoryIdsArray = explode(',', $categoryIds);
      $productQuery->whereIn('products.category_id', $categoryIdsArray);
    }

    if ($subCategoryIds) {
      $subcategoryIds = explode(',', $subCategoryIds);
      $productQuery->whereIn('products.sub_category_id', $subcategoryIds);
    }

    // Filter by module_id (assuming module_id corresponds to some attribute in products)
    if ($moduleId) {
      $productQuery->where('products.module_id', $moduleId);
    }

    if ($price) {
      $priceRange = explode(',', $price);
      if (count($priceRange) == 1) {
        $productQuery->where('products.product_sale_price', '>', $priceRange[0]);
      } else {
        $productQuery->whereBetween('products.product_sale_price', $priceRange);
      }
    }

    // Group by necessary columns
    $productQuery->groupBy(
      'products.id',
      'products.category_id',
      'products.sub_category_id',
      'products.product_name',
      'products.product_about',
      'products.product_sale_price',
      'products.module_id',
      'products.product_price',
      'sub_categories.sub_category_name',
      'products.store_id',
      'categories.category_name',
      'vendor_stores.store_name'
    );

    if ($request->has('desc')) {
      $productQuery->orderBy('products.created_at', 'desc');
    } elseif ($request->has('asc')) {
      $productQuery->orderBy('products.created_at', 'asc');
    } else {
      $productQuery->orderBy('products.created_at', 'desc');
    }

    $products = $productQuery->paginate($perPage, ['products.*'], 'page', $page);

    // Process each product to include variants data
    foreach ($products as $product) {
      // Adjust sale price if needed
      $product->product_sale_price =
        $product->product_sale_price != '' && $product->product_sale_price > 0
        ? $product->product_sale_price
        : $product->product_price;

      // Fetch product images
      $productImages = ProductImages::select('product_image')
        ->where('product_id', $product->id)
        ->get();

      $product->product_image = $productImages->isNotEmpty()
        ? url('/assets/images/product_images/' . $productImages->first()->product_image)
        : null;

      // Fetch total review count and average review
      $totalProductReview = ProductReview::select('product_id', 'review_star')
        ->where('product_id', $product->id)
        ->get();

      $totalReviewStar = $totalProductReview->sum('review_star');
      $totalReviewCount = $totalProductReview->count();
      $product->totalReviewCount = $totalReviewCount;
      $product->totalAvgReview = $totalReviewCount > 0 ? round($totalReviewStar / $totalReviewCount, 1) : 0;

      // Check if the user has liked this product
      $product->is_Like = ProductLike::where('user_id', $request->user_id)
        ->where('product_id', $product->id)
        ->exists();

      // Handle product colors and sizes
      $product->product_color = $product->product_color ? explode(',', $product->product_color) : [];
      $product->product_size = $product->product_size ? explode(',', $product->product_size) : [];

      // Fetch variants data
      $variants = Variant::where('product_id', $product->id)->get();
      $product->variants = $variants;
    }

    // Prepare response data
    $productFilter = [
      'totalProductColor' => collect($products->pluck('product_color')->flatten())
        ->map(function ($item) {
          return array_map('trim', explode(',', $item));
        })
        ->flatten()
        ->unique()
        ->values()
        ->all(),
      'totalproductSize' => collect($products->pluck('product_size')->flatten())
        ->map(function ($item) {
          return array_map('trim', explode(',', $item));
        })
        ->flatten()
        ->unique()
        ->values()
        ->all(),
    ];

    $categories = Category::pluck('category_name', 'id')
      ->map(function ($name, $id) {
        return [
          'id' => $id,
          'category_name' => $name,
        ];
      })
      ->values()
      ->all();

    return response()->json([
      'message' => 'Products found',
      'productFilter' => [
        'totalProductColor' => $productFilter['totalProductColor'],
        'totalproductSize' => $productFilter['totalproductSize'],
        'categories' => $categories,
      ],
      'products' => $products,
    ]);
  }

  // public function productList(Request $request)
  // {
  //   $perPage = $request->input('per_page', 12);
  //   $page = $request->input('page', 1);
  //   $price = $request->input('price');
  //   $categoryIds = $request->input('category_id');
  //   $subCategoryIds = $request->input('sub_category_id'); // New parameter
  //   $moduleId = $request->input('module_id'); // New parameter

  //   $productQuery = Product::select(
  //     'products.id',
  //     'products.category_id',
  //     'products.sub_category_id',
  //     'products.product_name',
  //     'products.product_about',
  //     'products.product_sale_price',
  //     'products.product_price',
  //     'sub_categories.sub_category_name',
  //     'products.store_id',
  //     'categories.category_name',
  //     'vendor_stores.store_name'
  //   )
  //   ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
  //   ->leftJoin('categories', 'products.category_id', '=',
  //     'categories.id'
  //   )
  //   ->leftJoin('vendor_stores', 'products.store_id', '=', 'vendor_stores.id')
  //   ->leftJoin('variants', 'products.id', '=', 'variants.product_id'); // Join the variants table

  //   if ($categoryIds) {
  //     $categoryIdsArray = explode(',', $categoryIds);
  //     $productQuery->whereIn('products.category_id', $categoryIdsArray);
  //   }

  //   if ($subCategoryIds) {
  //     $subcategoryIds = explode(',', $subCategoryIds);
  //     $productQuery->whereIn('products.sub_category_id', $subcategoryIds);
  //   }

  //   if ($moduleId) {
  //     $productQuery->where('products.module_id', $moduleId);
  //   }

  //   if ($price) {
  //     $priceRange = explode(',', $price);
  //     if (count($priceRange) == 1) {
  //       $productQuery->where('products.product_sale_price', '>', $priceRange[0]);
  //     } else {
  //       $productQuery->whereBetween('products.product_sale_price', $priceRange);
  //     }
  //   }

  //   if ($request->has('desc')) {
  //     $productQuery->orderBy('products.created_at', 'desc');
  //   } elseif ($request->has('asc')) {
  //     $productQuery->orderBy('products.created_at', 'asc');
  //   } else {
  //     $productQuery->orderBy('products.created_at', 'desc');
  //   }

  //   if ($request->has('product_size')) {
  //     $sizes = explode(',', $request->product_size);
  //     $productQuery->where(function ($query) use ($sizes) {
  //       foreach ($sizes as $size) {
  //         $query->orWhere('variants.size', 'LIKE', "%$size%");
  //       }
  //     });
  //   }

  //   if ($request->has('product_colors')) {
  //     $colors = explode(',', $request->product_colors);
  //     $productQuery->where(function ($query) use ($colors) {
  //       foreach ($colors as $color) {
  //         $query->orWhere('variants.color', 'LIKE', "%$color%");
  //       }
  //     });
  //   }

  //   $products = $productQuery->paginate($perPage, ['*'], 'page', $page);

  //   $productFilter = [
  //     'totalProductColor' => $products->pluck('variants.color')->flatten()->map(function ($item) {
  //       return array_map('trim',
  //         explode(',', $item)
  //       );
  //     })->flatten()->unique()->values()->all(),
  //     'totalproductSize' => $products->pluck('variants.size')->flatten()->map(function ($item) {
  //       return array_map('trim', explode(',', $item));
  //     })->flatten()->unique()->values()->all(),
  //   ];

  //   $categories = Category::pluck('category_name', 'id')->map(function ($name, $id) {
  //     return [
  //       'id' => $id,
  //       'category_name' => $name
  //     ];
  //   })->values()->all();

  //   foreach ($products as $product) {
  //     $product->product_sale_price = $product->product_sale_price != '' && $product->product_sale_price > 0
  //       ? $product->product_sale_price
  //       : $product->product_price;

  //     $productImages = ProductImages::select('product_image')
  //     ->where('product_id', $product->id)
  //     ->get();

  //     $totalProductImages = [];
  //     foreach ($productImages as $val) {
  //       if ($val->product_image) {
  //         $totalProductImages = url('/assets/images/product_images/' . $val->product_image);
  //       }
  //     }
  //     $product->product_image = $totalProductImages;

  //     $totalReviewCount = ProductReview::where('product_id', $product->id)->count();
  //     $product->totalReviewCount = $totalReviewCount;

  //     $totalProductReview = ProductReview::select('product_id', 'review_star')
  //     ->where('product_id', $product->id)
  //     ->get();

  //     $totalReviewStar = 0;
  //     $totalAvgReview = 0;
  //     foreach ($totalProductReview as $val) {
  //       $reviewStar = floatval($val->review_star);
  //       $totalReviewStar += $reviewStar;
  //     }

  //     $totalReviewCount = $totalProductReview->count();
  //     $product->totalReviewCount = $totalReviewCount ? $totalReviewCount : 0;
  //     if ($totalReviewCount > 0) {
  //       $totalAvgReview = round($totalReviewStar / $totalReviewCount, 1);
  //     }
  //     $product->totalAvgReview = (string) $totalAvgReview;

  //     $productUserLike = ProductLike::where('user_id', $request->user_id)
  //       ->where('product_id', $product->id)
  //       ->first();
  //     $product->is_Like = $productUserLike ? 1 : 0;

  //     $product->product_color = $product->variants->pluck('color')->unique()->implode(', ');
  //     $product->product_size = $product->variants->pluck('size')->unique()->implode(', ');
  //   }

  //   $totalProductPrices = $products->pluck('product_price')->unique()->values()->all();

  //   return response()->json([
  //     'message' => 'Products found',
  //     'productFilter' => [
  //       'totalProductColor' => $productFilter['totalProductColor'],
  //       'totalproductSize' => $productFilter['totalproductSize'],
  //       'totalProductPrices' => $totalProductPrices,
  //       'categories' => $categories,
  //     ],
  //     'products' => $products,
  //   ]);
  // }

  // Sort Product
  // public function sortProduct(Request $request)
  // {
  //   $perPage = $request->input('per_page', 12);
  //   $page = $request->input('page', 1);
  //   $sortOrder = $request->input('sort_order', 'latest'); // 'asc', 'desc', or 'latest'
  //   $moduleId = $request->input('module_id'); // New parameter

  //   $productQuery = Product::select(
  //     'products.id',
  //     'products.category_id',
  //     'products.sub_category_id',
  //     'products.product_name',
  //     'products.product_about',
  //     'products.product_sale_price',
  //     'products.product_price',
  //     'products.store_id',
  //     'sub_categories.sub_category_name',
  //     'categories.category_name',
  //     'vendor_stores.store_name', // Add store_name to the select clause

  //     DB::raw('GROUP_CONCAT(DISTINCT variants.color) as product_color'),
  //     DB::raw('GROUP_CONCAT(DISTINCT variants.size) as product_size')
  //   )
  //     ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
  //     ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
  //     ->leftJoin('variants', 'products.id', '=', 'variants.product_id') // Join with variants table
  //     ->leftJoin('vendor_stores', 'products.store_id', '=', 'vendor_stores.id') // Join with stores table
  //     ->groupBy(
  //       'products.id',
  //       'products.category_id',
  //       'products.sub_category_id',
  //       'products.product_name',
  //       'products.product_about',
  //       'products.product_sale_price',
  //       'products.product_price',
  //       'products.store_id',
  //       'sub_categories.sub_category_name',
  //       'categories.category_name',
  //       'vendor_stores.store_name' // Add this to the group by clause
  //     );

  //   // Filter by module_id if provided
  //   if ($moduleId) {
  //     $productQuery->where('products.module_id', $moduleId);
  //   }

  //   if ($sortOrder == 'asc') {
  //     $productQuery->orderBy('products.product_sale_price', 'asc');
  //   } elseif ($sortOrder == 'desc') {
  //     $productQuery->orderBy('products.product_sale_price', 'desc');
  //   } else {
  //     $productQuery->orderBy('products.created_at', 'desc');
  //   }

  //   $products = $productQuery->paginate($perPage, ['*'], 'page', $page);

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
  //       }
  //     }
  //     $product->product_image = $totalProductImages;

  //     // Handle product colors and sizes
  //     $product->product_color = $product->product_color ? explode(',', $product->product_color) : [];
  //     $product->product_size = $product->product_size ? explode(',', $product->product_size) : [];

  //     // Count total reviews
  //     $totalReviewCount = ProductReview::where('product_id', $product->id)->count();
  //     $product->totalReviewCount = $totalReviewCount;

  //     $totalProductReview = ProductReview::select('product_id', 'review_star')
  //       ->where('product_id', $product->id)
  //       ->get();

  //     $totalReviewStar = 0;
  //     $totalAvgReview = 0;
  //     foreach ($totalProductReview as $val) {
  //       $reviewStar = floatval($val->review_star);
  //       $totalReviewStar += $reviewStar;
  //     }

  //     $totalReviewCount = $totalProductReview->count();

  //     $product->totalReviewCount = $totalReviewCount ? $totalReviewCount : '';
  //     if ($totalReviewCount > 0) {
  //       $totalAvgReview = round($totalReviewStar / $totalReviewCount, 1);
  //     }
  //     $product->totalAvgReview = (string) $totalAvgReview;

  //     $productUserLike = ProductLike::where('user_id', $request->user_id)
  //       ->where('product_id', $product->id)
  //       ->first();
  //     $product->is_Like = $productUserLike ? 1 : 0;

  //     $product->product_color = $product->product_color ?? '';
  //     $product->product_size = $product->product_size ?? '';
  //   }

  //   // Get all product prices
  //   $totalProductPrices = $products
  //     ->pluck('product_price')
  //     ->unique()
  //     ->values()
  //     ->all();

  //   return response()->json([
  //     'message' => 'Products sorted',
  //     'products' => $products,
  //   ]);
  // }

  // Sort Product
  // Sort Product
  public function sortProduct(Request $request)
  {
    $perPage = $request->input('per_page', 12);
    $page = $request->input('page', 1);
    $sortOrder = $request->input('sort_order', 'latest'); // 'asc', 'desc', or 'latest'
    $moduleId = $request->input('module_id'); // New parameter
    $categoryIds = $request->input('category_id'); // New parameter
    $subCategoryIds = $request->input('sub_category_id'); // New parameter

    $productQuery = Product::select(
      'products.id',
      'products.category_id',
      'products.sub_category_id',
      'products.product_name',
      'products.product_about',
      'products.product_sale_price',
      'products.product_price',
      'products.store_id',
      'sub_categories.sub_category_name',
      'categories.category_name',
      'vendor_stores.store_name', // Add store_name to the select clause

      DB::raw('GROUP_CONCAT(DISTINCT variants.color) as product_color'),
      DB::raw('GROUP_CONCAT(DISTINCT variants.size) as product_size')
    )
      ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
      ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
      ->leftJoin('variants', 'products.id', '=', 'variants.product_id') // Join with variants table
      ->leftJoin('vendor_stores', 'products.store_id', '=', 'vendor_stores.id') // Join with stores table
      ->groupBy(
        'products.id',
        'products.category_id',
        'products.sub_category_id',
        'products.product_name',
        'products.product_about',
        'products.product_sale_price',
        'products.product_price',
        'products.store_id',
        'sub_categories.sub_category_name',
        'categories.category_name',
        'vendor_stores.store_name' // Add this to the group by clause
      );

    // Filter by module_id if provided
    if ($moduleId) {
      $productQuery->where('products.module_id', $moduleId);
    }

    // Filter by category_ids if provided
    if ($categoryIds) {
      $categoryIdsArray = explode(',', $categoryIds);
      $productQuery->whereIn('products.category_id', $categoryIdsArray);
    }

    // Filter by sub_category_ids if provided
    if ($subCategoryIds) {
      $subCategoryIdsArray = explode(',', $subCategoryIds);
      $productQuery->whereIn('products.sub_category_id', $subCategoryIdsArray);
    }

    // Apply sorting order
    if ($sortOrder == 'asc') {
      $productQuery->orderBy('products.product_sale_price', 'asc');
    } elseif ($sortOrder == 'desc') {
      $productQuery->orderBy('products.product_sale_price', 'desc');
    } else {
      $productQuery->orderBy('products.created_at', 'desc');
    }

    $products = $productQuery->paginate($perPage, ['*'], 'page', $page);

    foreach ($products as $product) {
      if ($product->product_sale_price != '' && $product->product_sale_price > 0) {
        $product->product_sale_price = $product->product_sale_price;
      } else {
        $product->product_sale_price = $product->product_price;
      }

      $productImages = ProductImages::select('product_image')
        ->where('product_id', $product->id)
        ->get();
      $totalProductImages = [];
      foreach ($productImages as $val) {
        if ($val->product_image) {
          $totalProductImages = url('/assets/images/product_images/' . $val->product_image);
        }
      }
      $product->product_image = $totalProductImages;

      // Handle product colors and sizes
      $product->product_color = $product->product_color ? explode(',', $product->product_color) : [];
      $product->product_size = $product->product_size ? explode(',', $product->product_size) : [];

      // Count total reviews
      $totalReviewCount = ProductReview::where('product_id', $product->id)->count();
      $product->totalReviewCount = $totalReviewCount;

      $totalProductReview = ProductReview::select('product_id', 'review_star')
        ->where('product_id', $product->id)
        ->get();

      $totalReviewStar = 0;
      $totalAvgReview = 0;
      foreach ($totalProductReview as $val) {
        $reviewStar = floatval($val->review_star);
        $totalReviewStar += $reviewStar;
      }

      $totalReviewCount = $totalProductReview->count();

      $product->totalReviewCount = $totalReviewCount ? $totalReviewCount : '';
      if ($totalReviewCount > 0) {
        $totalAvgReview = round($totalReviewStar / $totalReviewCount, 1);
      }
      $product->totalAvgReview = (string) $totalAvgReview;

      $productUserLike = ProductLike::where('user_id', $request->user_id)
        ->where('product_id', $product->id)
        ->first();
      $product->is_Like = $productUserLike ? 1 : 0;

      $product->product_color = $product->product_color ?? '';
      $product->product_size = $product->product_size ?? '';
    }

    // Get all product prices
    $totalProductPrices = $products
      ->pluck('product_price')
      ->unique()
      ->values()
      ->all();

    return response()->json([
      'message' => 'Products sorted',
      'products' => $products,
    ]);
  }



  // Product List on Store
  public function productListonStore(Request $request)
  {
    $price = $request->input('price');
    $categoryId = $request->input('category_id');
    $storeId = $request->input('store_id'); // Get store_id from the request

    $productQuery = Product::select(
      'products.id',
      'products.category_id',
      'products.store_id',
      'products.sub_category_id',
      'products.product_name',
      'products.product_about',
      'products.product_sale_price',
      'products.module_id',
      'products.product_price',
      'sub_categories.sub_category_name',
      'categories.category_name',
      'vendor_stores.store_name',
      DB::raw('GROUP_CONCAT(DISTINCT variants.color) as product_color'),
      DB::raw('GROUP_CONCAT(DISTINCT variants.size) as product_size')
    )
      ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
      ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
      ->leftJoin('vendor_stores', 'products.store_id', '=', 'vendor_stores.id')
      ->leftJoin('variants', 'products.id', '=', 'variants.product_id')
      ->groupBy(
        'products.id',
        'products.category_id',
        'products.store_id',
        'products.sub_category_id',
        'products.product_name',
        'products.product_about',
        'products.product_sale_price',
        'products.product_price',
        'products.module_id',
        'sub_categories.sub_category_name',
        'categories.category_name',
        'vendor_stores.store_name'
      );

    if ($categoryId) {
      $productQuery->where('products.category_id', $categoryId);
    }

    if ($price) {
      $priceRange = explode(',', $price);
      $productQuery->whereBetween('products.product_price', $priceRange);
    }

    if ($storeId) {
      $productQuery->where('products.store_id', $storeId);
    }

    if ($request->has('desc')) {
      $productQuery->orderBy('products.created_at', 'desc');
    } elseif ($request->has('asc')) {
      $productQuery->orderBy('products.created_at', 'asc');
    } else {
      $productQuery->orderBy('products.created_at', 'desc');
    }

    if ($request->has('product_size')) {
      $sizes = explode(',', $request->product_size);
      $productQuery->where(function ($query) use ($sizes) {
        foreach ($sizes as $size) {
          $query->orWhere('variants.size', 'LIKE', "%$size%");
        }
      });
    } elseif ($request->has('product_colors')) {
      $colors = explode(',', $request->product_colors);
      $productQuery->where(function ($query) use ($colors) {
        foreach ($colors as $color) {
          $query->orWhere('variants.color', 'LIKE', "%$color%");
        }
      });
    }

    $products = $productQuery->get();

    foreach ($products as $product) {
      if ($product->product_sale_price != '' && $product->product_sale_price > 0) {
        $product->product_sale_price = $product->product_sale_price;
      } else {
        $product->product_sale_price = $product->product_price;
      }

      $productImages = ProductImages::select('product_image')
        ->where('product_id', $product->id)
        ->get();
      $totalProductImages = [];
      foreach ($productImages as $val) {
        if ($val->product_image) {
          $totalProductImages[] = url('/assets/images/product_images/' . $val->product_image);
        }
      }
      $product->product_image = $totalProductImages;

      $totalReviewCount = ProductReview::where('product_id', $product->id)->count();
      $product->totalReviewCount = $totalReviewCount;

      $totalProductReview = ProductReview::select('product_id', 'review_star')
        ->where('product_id', $product->id)
        ->get();

      $totalReviewStar = 0;
      $totalAvgReview = 0;
      foreach ($totalProductReview as $val) {
        $reviewStar = floatval($val->review_star);
        $totalReviewStar += $reviewStar;
      }

      $totalReviewCount = $totalProductReview->count();

      $product->totalReviewCount = $totalReviewCount ? $totalReviewCount : 0;
      if ($totalReviewCount > 0) {
        $totalAvgReview = round($totalReviewStar / $totalReviewCount, 1);
      }
      $product->totalAvgReview = (int) $totalAvgReview;

      $productUserLike = ProductLike::where('user_id', $request->user_id)
        ->where('product_id', $product->id)
        ->first();
      $product->is_Like = $productUserLike ? 1 : 0;

      $product->product_color = $product->product_color ? explode(',', $product->product_color) : [];
      $product->product_size = $product->product_size ? explode(',', $product->product_size) : [];
    }

    return response()->json([
      'message' => 'Products found',
      'products' => $products,
    ]);
  }

  //  Web Home
  public function Webhome(Request $request)
  {
    $notificationCount = 0;
    $module_id = $request->module_id;
    $lat = $request->lat;
    $lon = $request->lon;
    $category_id = $request->category_id; // Get category_id from the request

    // Check if the module_id is provided
    if (!$module_id) {
      return response()->json(
        [
          'message' => 'module_id is required',
        ],
        400
      );
    }

    if ($request->user_id) {
      $cartCount = CartItem::where('user_id', $request->user_id)->count();
      $notificationCount = Notifications::where('user_id', $request->user_id)
        ->where('is_seen', '0')
        ->count();

      $slider = Modulebanner::where('module_id', $module_id)
        ->where('type', 0)
        ->select('image')
        ->get();
      $totalsliderImage = [];
      foreach ($slider as $value) {
        $totalsliderImage[] = url('assets/images/module_banner/' . $value->image);
      }

      $banner = Modulebanner::where('module_id', $module_id)
        ->where('type', 1)
        ->select('image')
        ->get();
      $totalbannerImage = [];
      foreach ($banner as $value) {
        $totalbannerImage[] = url('assets/images/module_banner/' . $value->image);
      }

      $category = Category::where('module_id', $module_id)
        ->select('id', 'category_name', 'category_image')
        ->with('sub_categories:id,category_id') // Eager load subcategories
        ->get();
      foreach ($category as $value) {
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


      // Fetch categories with subcategories
      // $categories = Category::where('module_id', $module_id)
      //   ->select('id', 'category_name', 'category_image')
      //   ->get();

      // foreach ($categories as $category) {
      //   $category->category_image = url('/assets/images/category_images/' . $category->category_image);

      //   // Fetch subcategories for each category
      //   $subcategories = SubCategory::where('category_id', $category->id)
      //     ->select('id', 'sub_category_name')
      //     ->get();

      //   $category->subcategories = $subcategories;
      // }

      // Popular products based on orders and likes
      // Popular products based on orders and likes, with optional category filter
      $popularProducts = Product::where('products.module_id', $module_id)
        ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
        ->leftJoin('product_likes', 'products.id', '=', 'product_likes.product_id')
        ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
        ->leftJoin('vendor_stores', 'products.store_id', '=', 'vendor_stores.id')
        ->leftJoin('variants', 'products.id', '=', 'variants.product_id') // Join with variants table
        ->select(
          'products.id',
          'products.product_name',
          'products.product_sale_price',
          'products.product_price',
          'products.sub_category_id',
          'products.category_id', // Include category_id in the select statement
          'products.store_id',
          'products.module_id',
          'products.product_image',
          'vendor_stores.store_name',

          DB::raw('COUNT(order_items.id) as order_count'),
          DB::raw('COUNT(product_likes.id) as like_count'),

          DB::raw('GROUP_CONCAT(DISTINCT variants.color) as product_color'),
          DB::raw('GROUP_CONCAT(DISTINCT variants.size) as product_size'),
          'sub_categories.sub_category_name'
        )
        ->groupBy(
          'products.id',
          'products.product_name',
          'products.product_sale_price',
          'products.product_price',
          'products.sub_category_id',
          'products.category_id', // Include category_id in the select statement
          'products.product_image',
          'products.store_id',
          'products.sub_category_id',
          'products.module_id',
          'vendor_stores.store_name',
          'sub_categories.sub_category_name'
        )
        ->orderBy('order_count', 'desc')
        ->orderBy('like_count', 'desc');

      if ($category_id) {
        $popularProducts->where('products.category_id', $category_id);
      }

      $popularProducts = $popularProducts->get();

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

        // Handle product colors and sizes
        $value->product_color = $value->product_color ? explode(',', $value->product_color) : [];
        $value->product_size = $value->product_size ? explode(',', $value->product_size) : [];

        // // Fetch variants data
        // $variants = Variant::where('product_id', $value->id)->get();
        // $value->variants = $variants;

        $productUserLike = ProductLike::where('user_id', $request->user_id)
          ->where('product_id', $value->id)
          ->first();
        $value->is_Like = $productUserLike ? 1 : 0;
        $value->product_image = $totalProductImages;

        $totalProductReview = ProductReview::where('product_id', $value->id)->get();
        $totalReviewStar = $totalProductReview->sum('review_star');
        $totalReviewCount = $totalProductReview->count();

        $value->totalReviewCount = $totalReviewCount ? (string) $totalReviewCount : 0;
        $value->totalAvgReview = $totalReviewCount > 0 ? (string) round($totalReviewStar / $totalReviewCount, 1) : '';
        $value->sub_category_name = $value->sub_category_name ?? '';
        $value->product_sale_price =
          $value->product_sale_price && $value->product_sale_price > 0
          ? $value->product_sale_price
          : $value->product_price;
      }
      // Nearby stores based on module_id
      // Nearby stores based on module_id
      $nearbyStores = VendorStore::where('module_id', $module_id)->orderBy('created_at', 'desc')->get();
      $vendorStore = [];
      foreach ($nearbyStores as $store) {
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
          'totalReviewCount' => $totalReviewCount,
        ];
      }

      //  Latest products based on module_id
      $latestProducts = Product::select(
        'products.id',
        'product_name',
        'product_sale_price',
        'product_price',
        'product_image',
        'product_about',
        'store_id',
        'sub_category_id',
        'vendor_stores.store_name',
        'sub_categories.sub_category_name',
        'products.module_id',
        DB::raw('GROUP_CONCAT(DISTINCT variants.color) as product_color'),
        DB::raw('GROUP_CONCAT(DISTINCT variants.size) as product_size')
      )
        ->leftJoin('sub_categories', 'sub_category_id', '=', 'sub_categories.id')
        ->leftJoin('variants', 'products.id', '=', 'variants.product_id') // Join with variants table
        ->leftJoin('vendor_stores', 'products.store_id', '=', 'vendor_stores.id')
        ->where('products.module_id', $module_id)
        ->orderBy('products.created_at', 'desc') // Order by product creation date in descending order
        ->groupBy(
          'products.id',
          'product_name',
          'product_sale_price',
          'product_price',
          'product_about',
          'product_image',
          'store_id',
          'sub_category_id',
          'vendor_stores.store_name',
          'sub_categories.sub_category_name',
          'products.module_id',
        )
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

        // Handle product colors and sizes
        $value->product_color = $value->product_color ? explode(',', $value->product_color) : [];
        $value->product_size = $value->product_size ? explode(',', $value->product_size) : [];

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
          $value->totalReviewCount = 0;
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

      //  Latest products based on module_id
      $latestWebProducts = Product::select(
        'products.id',
        'product_name',
        'product_sale_price',
        'product_price',
        'product_image',
        'product_about',
        'sub_category_id',
        'sub_categories.sub_category_name',
        'store_id',
        'vendor_stores.store_name',
        'products.module_id',
        DB::raw('GROUP_CONCAT(DISTINCT variants.color) as product_color'),
        DB::raw('GROUP_CONCAT(DISTINCT variants.size) as product_size')
      )
        ->leftJoin('variants', 'products.id', '=', 'variants.product_id')
        ->leftJoin('sub_categories', 'sub_category_id', '=', 'sub_categories.id')
        ->leftJoin('vendor_stores', 'products.store_id', '=', 'vendor_stores.id')
        ->where('products.module_id', $module_id)
        ->orderBy('products.created_at', 'desc')
        ->groupBy(
          'products.id',
          'products.product_name',
          'products.product_sale_price',
          'products.product_price',
          'product_about',
          'products.sub_category_id',
          'products.category_id',
          'products.product_image',
          'products.store_id',
          'products.sub_category_id',
          'sub_categories.sub_category_name',
          'vendor_stores.store_name',
          'products.module_id', // Add this to the group by clause
        )
        ->get();

      foreach ($latestWebProducts as $value) {
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

        // Handle product colors and sizes
        $value->product_color = $value->product_color ? explode(',', $value->product_color) : [];
        $value->product_size = $value->product_size ? explode(',', $value->product_size) : [];

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
          $value->totalReviewCount = 0;
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
          'products.product_about',
          'products.sub_category_id',
          'products.store_id',
          'products.module_id',
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
          'products.product_about',
          'products.sub_category_id',
          'products.module_id',
          'products.product_image',
          'products.store_id',
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

        $value->totalReviewCount = $totalReviewCount ? (string) $totalReviewCount : 0;
        $value->totalAvgReview = $totalReviewCount > 0 ? (string) round($totalReviewStar / $totalReviewCount, 1) : '';
        $value->sub_category_name = $value->sub_category_name ?? '';
        $value->product_sale_price =
          $value->product_sale_price && $value->product_sale_price > 0
          ? $value->product_sale_price
          : $value->product_price;
      }
    } else {
      $slider = Modulebanner::where('module_id', $module_id)
        ->where('type', 0)
        ->select('image')
        ->get();
      $totalsliderImage = [];
      foreach ($slider as $value) {
        $totalsliderImage[] = url('assets/images/module_banner/' . $value->image);
      }

      $banner = Modulebanner::where('module_id', $module_id)
        ->where('type', 1)
        ->select('image')
        ->get();
      $totalbannerImage = [];
      foreach ($banner as $value) {
        $totalbannerImage[] = url('assets/images/module_banner/' . $value->image);
      }

      $category = Category::where('module_id', $module_id)
        ->select('id', 'category_name', 'category_image')
        ->with('sub_categories:id,category_id') // Eager load subcategories
        ->get();
      foreach ($category as $value) {
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

      // $subcategory = SubCategory::where('module_id', $module_id)->select('id', 'sub_category_name', 'image')->get();
      // foreach ($subcategory as $value) {
      //   $value->image = url('/assets/images/subcategory_image/' . $value->image);
      // }

      // Popular products based on orders and likes
      $popularProducts = Product::where('products.module_id', $module_id)
        ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
        ->leftJoin('product_likes', 'products.id', '=', 'product_likes.product_id')
        ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
        ->leftJoin('vendor_stores', 'products.store_id', '=', 'vendor_stores.id')
        ->leftJoin('variants', 'products.id', '=', 'variants.product_id') // Join with variants table
        ->select(
          'products.id',
          'products.product_name',
          'products.product_sale_price',
          'products.product_price',
          'products.sub_category_id',
          'products.category_id',
          'products.product_image',
          'products.store_id',
          'vendor_stores.store_name',
          'products.sub_category_id',
          'products.module_id',
          DB::raw('COUNT(order_items.id) as order_count'),
          DB::raw('COUNT(product_likes.id) as like_count'),
          DB::raw('GROUP_CONCAT(DISTINCT variants.color) as product_color'),
          DB::raw('GROUP_CONCAT(DISTINCT variants.size) as product_size'),
          'sub_categories.sub_category_name'
        )
        ->groupBy(
          'products.id',
          'products.product_name',
          'products.product_sale_price',
          'products.category_id',
          'products.product_price',
          'products.sub_category_id',
          'products.product_image',
          'products.store_id',
          'vendor_stores.store_name',
          'sub_categories.sub_category_name',
          'products.module_id',
        )
        ->orderBy('order_count', 'desc')
        ->orderBy('like_count', 'desc');

      if ($category_id) {
        $popularProducts->where('products.category_id', $category_id);
      }

      $popularProducts = $popularProducts->get();

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

        // Handle product colors and sizes
        $value->product_color = $value->product_color ? explode(',', $value->product_color) : [];
        $value->product_size = $value->product_size ? explode(',', $value->product_size) : [];

        $value->product_image = $totalProductImages;

        $totalProductReview = ProductReview::where('product_id', $value->id)->get();
        $totalReviewStar = $totalProductReview->sum('review_star');
        $totalReviewCount = $totalProductReview->count();

        $value->totalReviewCount = $totalReviewCount ? (string) $totalReviewCount : 0;
        $value->totalAvgReview = $totalReviewCount > 0 ? (string) round($totalReviewStar / $totalReviewCount, 1) : '';
        $value->sub_category_name = $value->sub_category_name ?? '';
        $value->product_sale_price =
          $value->product_sale_price && $value->product_sale_price > 0
          ? $value->product_sale_price
          : $value->product_price;
        $value->is_Like = 0;
      }

      // Fetch nearby stores without user_id context
      $nearbyStores = VendorStore::where('module_id', $module_id)->orderBy('created_at', 'desc')->get();
      $vendorStore = [];
      foreach ($nearbyStores as $store) {
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
          'totalReviewCount' => $totalReviewCount,
        ];
      }

      // latest products on basis of module_id
      $latestProducts = Product::select(
        'products.id',
        'product_name',
        'product_sale_price',
        'product_price',
        'product_image',
        'product_about',
        'store_id',
        'sub_category_id',
        'vendor_stores.store_name',
        'sub_categories.sub_category_name',
        'products.module_id',
        DB::raw('GROUP_CONCAT(DISTINCT variants.color) as product_color'),
        DB::raw('GROUP_CONCAT(DISTINCT variants.size) as product_size')
      )
        ->leftJoin('variants', 'products.id', '=', 'variants.product_id') // Join with variants table
        ->leftJoin('sub_categories', 'sub_category_id', '=', 'sub_categories.id')
        ->leftJoin('vendor_stores', 'products.store_id', '=', 'vendor_stores.id')
        ->where('products.module_id', $module_id)
        ->orderBy('products.created_at', 'desc') // Order by product creation date in descending order
        ->groupBy(
          'products.id',
          'product_name',
          'product_sale_price',
          'product_about',
          'product_price',
          'product_image',
          'sub_category_id',
          'store_id',
          'vendor_stores.store_name',
          'sub_categories.sub_category_name',
          'products.module_id',
        )
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

        // Handle product colors and sizes
        $value->product_color = $value->product_color ? explode(',', $value->product_color) : [];
        $value->product_size = $value->product_size ? explode(',', $value->product_size) : [];

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
          $value->totalReviewCount = 0;
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

      //  Latest products based on module_id
      $latestWebProducts = Product::select(
        'products.id',
        'product_name',
        'product_sale_price',
        'product_price',
        'product_about',
        'product_image',
        'sub_category_id',
        'sub_categories.sub_category_name',
        'store_id',
        'vendor_stores.store_name',
        'products.module_id',
        DB::raw('GROUP_CONCAT(DISTINCT variants.color) as product_color'),
        DB::raw('GROUP_CONCAT(DISTINCT variants.size) as product_size')
      )
        ->leftJoin('variants', 'products.id', '=', 'variants.product_id')
        ->leftJoin('sub_categories', 'sub_category_id', '=', 'sub_categories.id')
        ->leftJoin('vendor_stores', 'products.store_id', '=', 'vendor_stores.id')
        ->where('products.module_id', $module_id)
        ->orderBy('products.created_at', 'desc')
        ->groupBy(
          'products.id',
          'products.product_name',
          'products.product_sale_price',
          'products.product_price',
          'products.sub_category_id',
          'products.product_about',
          'products.category_id',
          'products.product_image',
          'products.store_id',
          'products.sub_category_id',
          'sub_categories.sub_category_name',
          'vendor_stores.store_name',
          'products.module_id', // Add this to the group by clause
        )
        ->get();

      foreach ($latestWebProducts as $value) {
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

        // Handle product colors and sizes
        $value->product_color = $value->product_color ? explode(',', $value->product_color) : [];
        $value->product_size = $value->product_size ? explode(',', $value->product_size) : [];
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
          'products.product_about',
          'products.product_image',
          'products.store_id',
          'products.module_id',
          DB::raw('COUNT(order_items.id) as order_count'),
          DB::raw('COUNT(product_likes.id) as like_count'),
          'sub_categories.sub_category_name'
        )
        ->groupBy(
          'products.id',
          'products.product_name',
          'products.product_sale_price',
          'products.product_price',
          'products.product_about',
          'products.sub_category_id',
          'products.product_image',
          'products.store_id',
          'sub_categories.sub_category_name',
          'products.module_id',
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
      'slider' => $totalsliderImage,
      'category' => $category,
      'popular_product' => $popularProducts,
      'special_offer' => $latestProducts,
      'product_banner' => $totalbannerImage,
      'stores' => $vendorStore,
      'latest_product' => $latestWebProducts,
      'trending_product' => $trendingProducts,
      'cartCount' => $cartCount ?? 0,
      'notificationCount' => $notificationCount,
    ]);
  }

  // Get Store on web
  public function getStoreonweb(Request $request)
  {
    $perPage = 12; // Number of stores per page
    $page = $request->input('page', 1); // Current page, default is 1
    $moduleId = $request->module_id;

    if (empty($moduleId)) {
      return response()->json([
        'message' => 'Module ID is required',
      ]);
    }

    $stores = VendorStore::where('module_id', $moduleId)
      ->where('vendor_request', 1) // Filter by vendor_request column
      ->paginate($perPage, ['*'], 'page', $page);

    $data = [];

    foreach ($stores as $store) {
      $storeImg = [];
      foreach ($store->storeImages as $img) {
        $storeImg[] = $img->store_images ? url('/assets/images/store_images/' . $img->store_images) : '';
      }

      $bannerImg = [];
      foreach ($store->bannerImages as $img) {
        $bannerImg[] = $img->banner_image ? url('/assets/images/banner_images/' . $img->banner_image) : '';
      }

      // Calculate the average review star for the store
      $totalStoreReview = StoreReview::where('store_id', $store->id)->get();
      $totalReviewStar = $totalStoreReview->sum('review_star');
      $totalReviewCount = $totalStoreReview->count();
      $totalAvgReview = $totalReviewCount > 0 ? round($totalReviewStar / $totalReviewCount, 1) : '';

      $data[] = [
        'id' => $store->id,
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
        'totalReviewCount' => $totalReviewCount,
        'totalAvgReview' => $totalAvgReview,
      ];
    }

    if (!empty($data)) {
      return response()->json([
        'message' => 'Store found',
        'data' => $data,
        'current_page' => $stores->currentPage(),
        'last_page' => $stores->lastPage(),
        'total' => $stores->total(),
      ]);
    } else {
      return response()->json([
        'message' => 'Store not found',
      ]);
    }
  }

  // public function WebHomeTrendingProducts(Request $request)
  // {
  //   $module_id = $request->module_id;

  //   // Check if the module_id is provided
  //   if (!$module_id) {
  //     return response()->json([
  //       'message' => 'module_id is required',
  //     ], 400);
  //   }

  //   $store_ids = VendorStore::where('module_id', $module_id)->pluck('id');

  //   if ($request->user_id) {
  //     // Trending products based on orders and likes
  //     $trendingProducts = Product::whereIn('products.store_id', $store_ids)
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

  //     foreach ($trendingProducts as $value) {
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
  //   } else {
  //     // Popular products based on orders and likes
  //     $trendingProducts = Product::whereIn('products.store_id', $store_ids)
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

  //     foreach ($trendingProducts as $value) {
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
  //   }

  //   return response()->json([
  //     'message' => 'product found',
  //     'trending_product' => $trendingProducts,
  //   ]);
  // }

  public function FetchCategoriesFilter(Request $request)
  {
    $moduleId = $request->module_id;

    if (empty($moduleId)) {
      return response()->json([
        'message' => 'Module ID is required',
      ]);
    }

    $data = Category::where('module_id', $moduleId)
      ->with('sub_categories:id,category_id,sub_category_name') // Eager load subcategories
      ->select('id', 'category_name')
      ->get();

    if ($data->isNotEmpty()) {
      return response()->json([
        'message' => 'Categories found',
        'data' => $data,
      ]);
    } else {
      return response()->json([
        'message' => 'Categories not found',
      ]);
    }
  }

  public function showVariantsPrice(Request $request)
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
        'message' => 'Variant prices found',
        'data' => $formattedData,
      ]);
    } else {
      return response()->json([
        'message' => 'Variant prices not found for the given criteria',
      ]);
    }
  }




  public function namesRegister(Request $request)
  {
    // Define validation rules
    $rules = [
      'id' => 'required|integer|exists:users,id', // Ensure the ID exists in the users table
      'first_name' => 'required',
      'last_name' => 'nullable', // Allow last_name to be optional
    ];

    // Define custom validation messages
    $customMessages = [
      'id.required' => 'Please enter your user ID.',
      'id.exists' => 'The specified user ID does not exist.',
      'first_name.required' => 'Please enter your first name.',
    ];

    // Validate the request
    $validatedData = $request->validate($rules, $customMessages);

    // Find the user by ID
    $user = User::find($validatedData['id']);

    if (!$user) {
      return response()->json([
        'success' => false,
        'message' => 'User not found.',
      ], 404);
    }




    // Update the user details
    $user->first_name = $validatedData['first_name'];
    $user->last_name = $request->input('last_name', $user->last_name); // Use existing last_name if not provided
    // $user->email = $request->input('email');
    $user->mobile = $request->input('mobile');
    $user->user_refer_code = $request->input('user_refer_code');

    if (
      $request->user_refer_code != ''
    ) {
      $use_refer_user = User::where('refer_code', $request->user_refer_code)->first();

      //   $users = User::where('refer_code', $request->use_refer_code)->first();


      if ($use_refer_user) {

        $userReferal = [
          'user_id' => $user->id,
          // 'refer_code' => $refer_code,
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



    // Save the updated user
    $user->save();

    // Return a success response
    return response()->json([
      'success' => true,
      'message' => 'Profile updated successfully!',
      'id' => $user->id,
      'first_name' => $user->first_name, // Include first_name in the response
    ], 200);
  }

  public function namesRegisterEmail(Request $request)
  {
    // Define validation rules
    $rules = [
      'id' => 'required|integer|exists:users,id', // Ensure the ID exists in the users table
      'first_name' => 'required',
      'last_name' => 'nullable', // Allow last_name to be optional
    ];

    // Define custom validation messages
    $customMessages = [
      'id.required' => 'Please enter your user ID.',
      'id.exists' => 'The specified user ID does not exist.',
      'first_name.required' => 'Please enter your first name.',
    ];

    // Validate the request
    $validatedData = $request->validate($rules, $customMessages);

    // Find the user by ID
    $user = User::find($validatedData['id']);

    if (!$user) {
      return response()->json([
        'success' => false,
        'message' => 'User not found.',
      ], 404);
    }




    // Update the user details
    $user->first_name = $validatedData['first_name'];
    $user->last_name = $request->input('last_name', $user->last_name); // Use existing last_name if not provided
    $user->email = $request->input('email');
    $user->user_refer_code = $request->input('user_refer_code');

    if (
      $request->user_refer_code != ''
    ) {
      $use_refer_user = User::where('refer_code', $request->user_refer_code)->first();

      //   $users = User::where('refer_code', $request->use_refer_code)->first();


      if ($use_refer_user) {

        $userReferal = [
          'user_id' => $user->id,
          // 'refer_code' => $refer_code,
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



    // Save the updated user
    $user->save();

    // Return a success response
    return response()->json([
      'success' => true,
      'message' => 'Profile updated successfully!',
      'id' => $user->id,
      'first_name' => $user->first_name, // Include first_name in the response
    ], 200);
  }
}
