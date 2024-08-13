<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Module;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\SubCategory;
use App\Models\Units;
use App\Models\Variant;
use App\Models\Vendor;
use App\Models\VendorStore;
use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;

class EcommerceProductAdd extends Controller
{
  public function index()
  {
    $category = Category::all();
    $store = VendorStore::all();
    $module = Module::all();
    $subcategory = SubCategory::all();
    $units = Units::all();
    $vendor = Vendor::all();
    return view('content.apps.app-ecommerce-product-add', compact('category', 'subcategory', 'module', 'store', 'units','vendor'));
  }

  public function store(Request $request)
  {
    // dd($request->all());
    // Validate the request
    $rules = [
      'product_name' => 'required',
      'product_about' => 'required',
      'module_id' => 'required',
      'store_id' => 'required',
      'category_id' => 'required',
      'unit_id' => 'required',
      'vendor_id' => 'required',
      'product_price' => 'required',
      'product_sale_price' => 'sometimes|nullable|lt:product_price',
      'product_images' => 'required',
    ];

    $customMessages = [
      'product_name.required' => 'Please enter product name.',
      'product_about.required' => 'Please enter product description.',
      'module_id.required' => 'Please select module.',
      'store_id.required' => 'Please select store.',
      'category_id.required' => 'Please select category.',
      'unit_id.required' => 'Please select unit.',
      'vendor_id.required' => 'Please select vendor.',
      'product_price.required' => 'Please enter product price.',
      'product_sale_price.numeric' => 'Please enter product sale price.',
      'product_sale_price.lt' => 'The sale price must be less than the main price.',
      'product_images.required' => 'Please select product image.',
    ];

    $this->validate($request, $rules, $customMessages);

    // Calculate total stock from variants
    $totalStock = 0;
    if ($request->has('variants')) {
      foreach ($request->variants['total_stock'] as $stock) {
        $totalStock += (int) $stock;
      }
    } else {
      $totalStock = (int) $request->product_quantity;
    }


    $data = new Product();
    $data->product_name = $request->product_name;
    $data->product_about = $request->product_about;
    $data->module_id = $request->module_id;
    $data->store_id = $request->store_id;
    $data->category_id = $request->category_id;
    $data->sub_category_id = $request->sub_category_id;
    $data->unit_id = $request->unit_id;
    $data->vendor_id = $request->vendor_id;
    $data->product_price = $request->product_price;
    $data->product_sale_price = $request->product_sale_price ?? 0;
    $data->product_quantity = $totalStock;


    // Check if in_stock checkbox is checked
    $data->in_stock = $request->has('in_stock') ? 1 : 0;
    // Process tags

    if ($request->tag) {
      $tagArray = json_decode($request->tag);
      $values = [];
      foreach ($tagArray as $item) {
        $values[] = $item->value;
      }
      $tagArray = implode(',', $values);
    }

    $data->tag = $tagArray;


    $data->save();
    if ($request->hasfile('product_images')) {
      foreach ($request->file('product_images') as $file) {
        $fileName = time() . '.' . $file->getClientOriginalName();
        $file->move(public_path() . '/assets/images/product_images/', $fileName);

        $data->productImages()->create([
          'product_id' => $data->id,
          'product_image' => $fileName
        ]);
      }
    }


    if ($request->has('variants')) {
      foreach ($request->variants['color'] as $index => $color) {
        $variant = new Variant();
        $variant->color = $color;
        $variant->size = $request->variants['size'][$index];
        $variant->type = $request->variants['type'][$index];
        $variant->price = $request->variants['price'][$index];
        $variant->total_stock = $request->variants['total_stock'][$index];
        $variant->product_id = $data->id;
        $variant->save();
      }
    }

    if ($request->filled('deleted_variants')) {
      $deletedVariantIds = json_decode($request->deleted_variants, true);
      Variant::whereIn('id', $deletedVariantIds)->delete();
    }

    return redirect()->route('app-ecommerce-product-list')->with('message', 'Product added successfully');;
  }



  public function edit($id)
  {
    $data = Product::with('productImages')->find($id);

    $product = Product::find($id);
    $variants = Variant::where('product_id', $id)->get();

    $category = Category::all();
    $store = VendorStore::all();
    $module = Module::all();
    $subcategory = SubCategory::all();
    $units = Units::all();
    $vendor = Vendor::all();

    return view('content.apps.app-ecommerce-product-edit', compact('category', 'data', 'subcategory', 'module', 'store', 'units', 'variants','vendor'));
  }


  public function update($id, Request $request)
  {

    // Initialize $tagArray
    $tagArray = '';
    // Handle tags
    if ($request->tag) {
      $tagArray = json_decode($request->tag);
      $values = [];
      foreach ($tagArray as $item) {
        $values[] = $item->value;
      }
      $tagArray = implode(',', $values);
    }


    // Calculate total stock from variants or use single product quantity
    $totalStock = 0;
    if ($request->has('variants')) {
      foreach ($request->variants['total_stock'] as $stock) {
        $totalStock += (int) $stock;
      }
    } else {
      $totalStock = (int) $request->product_quantity;
    }

    // Find the product by ID
    $data = Product::find($id);
    $data->product_name = $request->product_name;
    $data->product_about = $request->product_about;
    $data->module_id = $request->module_id;
    $data->store_id = $request->store_id;
    $data->category_id = $request->category_id;
    $data->sub_category_id = $request->sub_category_id;
    $data->unit_id = $request->unit_id;
    $data->vendor_id = $request->vendor_id;
    $data->product_price = $request->product_price;
    $data->product_sale_price = $request->product_sale_price ?? 0;
    $data->product_quantity = $totalStock;

    // Ensure 'in_stock' checkbox state is correctly set
    $data->in_stock = $request->input('in_stock') ? 1 : 0;
    $data->tag = $tagArray;
    $data->save();

    // Handle product images upload
    if ($request->hasfile('product_images')) {
      foreach ($request->file('product_images') as $file) {
        $fileName = time() . '.' . $file->getClientOriginalName();
        $file->move(public_path() . '/assets/images/product_images/', $fileName);

        $data->productImages()->create([
          'product_id' => $data->id,
          'product_image' => $fileName
        ]);
      }
    }

    // Handle variants update
    if ($request->has('variants')) {
      foreach ($request->variants['color'] as $index => $color) {
        $variantId = $request->variants['id'][$index];

        $variant = $variantId ? Variant::find($variantId) : new Variant();
        $variant->color = $color;
        $variant->size = $request->variants['size'][$index];
        $variant->type = $request->variants['type'][$index];
        $variant->price = $request->variants['price'][$index];
        $variant->total_stock = $request->variants['total_stock'][$index];
        $variant->product_id = $data->id;
        $variant->save();
      }
    }

    // Redirect after successful update
    return redirect()->route('app-ecommerce-product-list')->with('message', 'Product updated successfully');
  }


  public function productImageDelete($id)
  {
    $data = ProductImages::find($id);
    $image_path = public_path('assets/images/product_images/' . $data->product_image);
    if (file_exists($image_path)) {
      unlink($image_path);
    }
    $data->delete();
    return response()->json(['success' => 'sucess']);
  }



  public function Deletevariant($id)
  {
    $variant = Variant::find($id);
    if (!$variant) {
      return response()->json(['error' => 'Variant not found'], 404);
    }

    $variant->delete();

    return response()->json(['success' => 'Variant deleted successfully']);
  }



  public function delete($id)
  {
    $data = Product::where('id', $id)->delete();
    return redirect()->back()->with('message', 'Product deleted successfully');
  }
}
