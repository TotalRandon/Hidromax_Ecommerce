<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Image;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::latest('id')->with('product_images');

        if ($request->get('keyword') != "") {
            $products = $products->where('title', 'like', '%' . $request->keyword . '%');
        }

        $products = $products->paginate();
        $data['products'] = $products;
        return view('admin.products.list', $data);
    }

    public function create()
    {
        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();

        $data['brands'] = $brands;
        $data['categories'] = $categories;
        return view('admin.products.create', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:yes,no',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $product = new Product;
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->shipping_returns = $request->shipping_returns;
            $product->short_description = $request->short_description;
            $product->related_products = (!empty($request->related_products)) ? implode(',', $request->related_products) : '';
            $product->save();

            // Salvar fotos de galeria

            if (!empty($request->image_array)) {
                foreach ($request->image_array as $temp_images_id) {

                    $tempImageInfo = TempImage::find($temp_images_id);
                    $extArray = explode('.', $tempImageInfo->name);
                    $ext = last($extArray); // tipo jpeg, jpg, png ...

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = 'NULL';
                    $productImage->save();

                    $imageName = $product->id.'-'.$productImage->id . '-' . time() . '.' . $ext;
                    $productImage->image = $imageName;
                    $productImage->save();

                    // product_id => 4 ; product_image_id => 1
                    // 4-1-313213213.jpg

                    // gerador de thumbnails de produtos

                    // Imagem grande

                    // $sourcePath = public_path(). '/temp/'.$tempImageInfo->name;
                    // $destPath = public_path().'/uploads/product/large/'.$imageName;
                    // $image = Image::make($sourcePath);
                    // $image->resize(1400, null, function ($constraint) {
                    //     $constraint->aspectRadio();
                    // });
                    // $image->save($destPath);

                    // // Imagem pequena

                    // $destPath = public_path().'/uploads/product/small/'.$imageName;
                    // $image = Image::make($sourcePath);
                    // $image->fit(300, 300);
                    // $image->save($destPath);

                    // Imagem grande
                    $sourcePath = public_path() . '/temp/' . $tempImageInfo->name;
                    $largeDestPath = public_path('uploads/product/large/') . $imageName;
                    $image = Image::make($sourcePath);
                    $image->resize(1400, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image->save($largeDestPath);

                    // Imagem pequena
                    $smallDestPath = public_path('uploads/product/small/') . $imageName;
                    $image = Image::make($sourcePath);
                    $image->fit(300, 300);
                    $image->save($smallDestPath);

                }
            }

            session()->flash('success', 'Produto criado com sucesso!');

            return response()->json([
                'status' => true,
                'message' => 'Produto criado com sucesso!'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request)
    {
        $product = Product::find($id);

        if (empty($product)) {
            return redirect()->route('products.index')->with('error', 'Produto não encontrado!');
        }

        // encontrar imagens dos produtos
        $productImages = ProductImage::where('product_id', $product->id)->get();

        $subCategories = SubCategory::where('category_id', $product->category_id)->get();
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();

        $relatedProducts = [];
        // encontrar productos relacionados
        if ($product->related_products != '') {
            $productArray = explode(',', $product->related_products);
            $relatedProducts = Product::WhereIn('id', $productArray)->get();
        }

        $data = [];
        $data['subCategories'] = $subCategories;
        $data['product'] = $product;
        $data['brands'] = $brands;
        $data['categories'] = $categories;
        $data['productImages'] = $productImages;
        $data['relatedProducts'] = $relatedProducts;

        return view('admin.products.edit', $data);
    }

    public function update($id, Request $request)
    {
        $product = Product::find($id);

        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,' . $product->id . ',id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,' . $product->id . ',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:yes,no',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->shipping_returns = $request->shipping_returns;
            $product->short_description = $request->short_description;
            $product->related_products = (!empty($request->related_products)) ? implode(',', $request->related_products) : '';
            $product->save();

            session()->flash('success', 'Produto salvo com sucesso!');

            return response()->json([
                'status' => true,
                'message' => 'Produto salvo com sucesso!'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if(empty($product)){
            session()->flash('success', 'Produto não encontrado!');

            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }

        $productImages = ProductImage::where('product_id', $id)->get();

        if (!empty($productImages)) {

            foreach ($productImages as $productImage) {
                File::delete(public_path('uploads/product/large/'.$productImage->image));
                File::delete(public_path('uploads/product/small/'.$productImage->image));
            }

            ProductImage::where('product_id', $id)->delete();
        }

        $product->delete();

        session()->flash('success', 'Produto deletado com sucesso!');

        return response()->json([
            'status' => true,
            'message' => 'Produto deletado com sucesso!'
        ]);
    }

    public function getProducts(Request $request)
    {
        $tempProduct = [];
        if($request->term != "") {
            $products = Product::where('title', 'like', '%'.$request->term.'%')->get();

            if($products != null){
                foreach ($products as $product) {
                    $tempProduct[] = array('id' => $product->id, 'text' => $product->title);
                }
            }
        }

        return response()->json([
            'tags' => $tempProduct,
            'status' => true
        ]);

    }
}
