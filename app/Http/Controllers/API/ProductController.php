<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    //

    public function index()
    {
        $products = Product::all();

        return response()->json([
            'status' => 200,
            'products' => $products
        ]);
    }

    public function storeProduct(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'category'=>'required',
            'name'=>'required',
            'slug'=>'required',
            // 'description'=>'required',
            'meta_title'=>'required',
            'meta_keyword'=>'required',
            // 'meta_description'=>'required',
            'selling_price'=>'required',
            'original_price'=>'required',
            'qty'=>'required',
            'brand'=>'required',
            // 'image'=>'required',
            // 'feature'=>'required',
            // 'popular'=>'required',
            // 'status'=>'required'
        ]);

        if($validator->fails())
        {
            
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages()
            ]);
        }
        else
        {
            $product = new Product();
            
            $product->category_id = $request->category;
            $product->name = $request->name;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->meta_title = $request->meta_title;
            $product->meta_keyword = $request->meta_keyword;
            $product->meta_description = $request->meta_description;
            $product->selling_price = $request->selling_price;
            $product->original_price = $request->original_price;
            $product->qty = $request->qty;
            $product->brand = $request->brand;
            // $product->image = $request->image;
            $product->feature = $request->feature == true ? '1' : '0';
            $product->popular = $request->popular == true ? '1' : '0';
            $product->status = $request->status == true ? '1' : '0';

            if($request->hasFile('image'))
            {
                $file = $request->file('image');

                $extension = $file->getClientOriginalExtension();

                $filename = time().'.'.$extension;

                $file->move('uploads/products/',$filename);

                $product->image = 'uploads/products/'.$filename;
            }
            

            $product->save();

            return response()->json([
                'status'=> 200,
                'message' => 'Product Added'
            ]);
        }
    }

    public function delete($id)
    {
        $product = Product::find($id);

        if($product)
        {
            $product = $product->delete();

            if($product)
            {
                return response()->json([
                    'status' => 200,
                    'message' => "Product Deleted Succesfully",
                ]);
            }
            else
            {
                return response()->json([
                    'status'=> 400,
                    'message' => "Operation Faild"
                ]);
            }
        }
    }

    public function edit($id)
    {
        $product = Product::find($id);

        if($product)
        {
            // return response()->json("success");
            return response()->json([
                'status' => 200,
                'products' => $product
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => "No Record Found Against ID"
            ]);
        }
    }

    public function update($id, Request $request)
    {

        $validator = Validator::make($request->all(),[
            'category_id'=>'required',
            'name'=>'required',
            'slug'=>'required',
            // 'description'=>'required',
            'meta_title'=>'required',
            'meta_keyword'=>'required',
            // 'meta_description'=>'required',
            'selling_price'=>'required',
            'original_price'=>'required',
            'qty'=>'required',
            'brand'=>'required',
            // 'image'=>'required',
            // 'feature'=>'required',
            // 'popular'=>'required',
            // 'status'=>'required'
        ]);

            if($validator->fails())
            {
                return response()->json([
                    'error' => $validator->messages()
                ]);
                return response()->json([
                    'status' => 400,
                    'message' => "all fields are manadatory to fill"
                ]);
            }
            else
            {

            
            $product = Product::find($id);

            if($product)
            {
                $product->category_id = $request->category_id;
                $product->name = $request->name;
                $product->slug = $request->slug;
                $product->description = $request->description;
                $product->meta_title = $request->meta_title;
                $product->meta_keyword = $request->meta_keyword;
                $product->meta_description = $request->meta_description;
                $product->selling_price = $request->selling_price;
                $product->original_price = $request->original_price;
                $product->qty = $request->qty;
                $product->brand = $request->brand;
                // $product->image = $request->image;
                $product->feature = $request->feature ;
                $product->popular = $request->popular ;
                $product->status = $request->status ;

                if($request->hasFile('image'))
                {
                    $path = $product->image;

                    if(File::exists($path))
                    {
                        File::delete($path);
                    }

                    $file = $request->file('image');

                    $extension = $file->getClientOriginalExtension();

                    $filename = time().'.'.$extension;

                    $file->move('uploads/products/',$filename);

                    $product->image = 'uploads/products/'.$filename;
                }
                

                $product->update();

                return response()->json([
                    'status'=> 200,
                    'message' => 'Product Updated Successfuly'
                ]);
            }
        }
    }

    public function frontendProducts()
    {
        $products = Product::where('status',0)->limit(6)->get();

        return response()->json([
            'status' => 200,
            'products' => $products
        ]);
    }

    public function categoryProducts($slug)
    {
        $category = Category::where('slug',$slug)->first();

        if($category)
        {
            $products = Product::where('category_id',$category->id)->where('status',0)->get();
            return response()->json([
                'status' => 200,
                'product_data'=>[
                    'products'=>$products,
                    'category'=>$category
                ]
            ]);

        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => "Category not Found"
            ]);
        }
    }

    public function singleProduct($slug)
    {
        $product = Product::where('slug',$slug)->where('status',0)->first();

        if($product)
        {
            return response()->json([
                'status' => 200,
                'product' => $product 
            ]);
        }
    }
}
