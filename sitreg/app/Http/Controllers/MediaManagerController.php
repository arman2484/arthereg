<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\ProductMedia;
use Illuminate\Http\Request;

class MediaManagerController extends Controller
{
  public function index()
  {
    $data = Product::with('productImages')->orderBy('id', 'desc')->get();
    // dd($data);
    return view('content.apps.app-ecommerce-media-manager-list', compact('data'));
  }
  public function getProduct()
  {
    $data = ProductImages::orderBy('id', 'desc')->get();
    // dd($data);
    $productImage = [];
    foreach ($data as  $value) {
      // foreach ($value->productImages as $img) {
      $productImage[] = [
        'image_id' => $value->id,
        'product_image' => $value->product_image,
      ];
      // }
    }
    // dd($productImage);
    return response()->json($productImage);
  }
  public function getCategory()
  {
    $data = Category::orderBy('id', 'desc')->get();
    // dd($data);
    $CategoryImage = [];
    foreach ($data as  $value) {
      $CategoryImage[] = [
        'category_id' => $value->id,
        'category_image' => $value->category_image,
      ];
    }
    // dd($CategoryImage);
    return response()->json($CategoryImage);
  }
  public function fileUpload(Request $request)
  {
    // dd($request->all());
    if ($request->hasfile('files')) {
      foreach ($request->file('files') as $file) {
        $fileName = time() . '.' . $file->getClientOriginalName();
        $file->move(public_path() . '/assets/images/product_images/', $fileName);
        $data = new ProductImages();
        $data->product_media_image = $fileName;
        $data->save();
      }
    }
    return response()->json(['message' => 'Image uploaded successfully']);
  }
  public function productImageDelete($id)
  {
    $data = ProductImages::find($id)->delete();
    return response()->json(['message' => 'Image delete successfully', 'id' => $id]);
  }
  public function categoryImageDelete($id)
  {
    $data = Category::find($id)->delete();
    return response()->json(['message' => 'Image delete successfully', 'id' => $id]);
  }
}
