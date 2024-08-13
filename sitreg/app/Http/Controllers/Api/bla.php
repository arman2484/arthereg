
<?php

namespace App\Http\Controllers\Api;
public function home(Request $request)
    {
        if ($request->user_id) {
            $banner = Banner::select('banner_image')->get();
            foreach ($banner as  $value) {
                $value->banner_image = url('public/images/banner_images/' . $value->banner_image);
            }
            $category = Category::select('id', 'category_name', 'category_icon')->get();
            foreach ($category as  $value) {
                $value->category_image = url('public/images/category_images/' . $value->category_icon);
            }
            $product = Product::select('products.id as productID', 'product_name','product_price', 'product_image', 'product_review', 'product_id','sub_category_id','sub_categories.sub_category_name')
            ->leftjoin('sub_categories', 'sub_category_id', '=', 'sub_categories.id')
            ->get();
            foreach ($product as $value) {
                $totalProductImages = [];
                $productImages = explode(',', $value->product_image);
                foreach ($productImages as $val) {
                    if ($val) {
                        $totalProductImages[] = url('public/images/product_images/' . $val);
                    } else {
                        $totalProductImages = [];
                    }
                }
                $value->product_image = url('public/images/product_images/' . $value->product_image);
                $productUserLike = ProductLike::where('user_id', $request->user_id)->where('product_id',$value->productID) ->first(); 
                if($productUserLike){
                    $value->is_Like = 1;
                }
                else{
                    $value->is_Like = 0;

                }
                $value->product_image = $totalProductImages;
                if($totalProductReview){
                    $value->product_review = $value->product_review ;
                }
                else{
                    $value->product_review = "";
                }
            }
        
    }
    else{
        $banner = Banner::select('banner_image')->get();
        foreach ($banner as  $value) {
            $value->banner_image = url('public/images/banner_images/' . $value->banner_image);
        }

        $category = Category::select('id', 'category_name', 'category_icon')->get();
        foreach ($category as  $value) {
            $value->category_image = url('public/images/category_images/' . $value->category_icon);
        }
        $product = Product::select('products.id as productID', 'product_name','product_price', 'product_image', 'product_review', 'product_id','sub_category_id','sub_categories.sub_category_name')
        ->leftjoin('sub_categories', 'sub_category_id', '=', 'sub_categories.id')
        ->get();
        foreach ($product as  $value) {
            // dd($value->id);
            $totalProductImages = [];
            $productImages = explode(',', $value->product_image);
            foreach ($productImages as $val) {
                if ($val) {
                    $totalProductImages[] = url('public/images/product_images/' . $val);
                } else {
                    $totalProductImages = [];
                }
            }
            
            $totalProductReview = ProductReview::where('product_id', $value->id)->count();
            // $productLike = ProductLike::where('product_id', $value->id)->first(); 
            $value->product_image = $totalProductImages;
            $value->totalProductReview = $totalProductReview;
            $value->subCategoryName = $subCategory->sub_category_name ?? "";
            $value->is_Like = 0;
        }
    }
        return response()->json([
            'message' => 'product found',
            'product_Banner' => $banner,
            'category' => $category,
            'product' => $product,
        ]);
    }
    ?>