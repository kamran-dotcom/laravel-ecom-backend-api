<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
class CategoryController extends Controller
{
    //

    public function index()
    {
        $category = Category::all();

        return response()->json([
            'status'=>200,
            'category'=>$category
        ]);
    }

    public function edit($id)
    {
        $category = Category::find($id);

        if($category)
        {

            return response()->json([
                'status' => 200,
                'category' => $category
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=> 'No Record found against ID'
            ]);
        }
    }

    public function update($id,Request $request)
    {
        $category = Category::find($id);


        if($category)
        {
            // return response()->json($request->all());
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->description = $request->description;
            $category->meta_title = $request->meta_title;
            $category->meta_tag = $request->meta_tag;
            $category->meta_description = $request->meta_description;

            $category->update();

            return response()->json([
                'status'=>200,
                'message'=>'Data Updated Successfully'
            ]);
        }
        else
        {
            return response()->json([
                'status'=>400,
                'message'=> "Data cannot Update"
            ]);

        }

    }

    public function storeCategory(Request $request)
    {
        // return response($request->all());
        $validator = Validator::make($request->all(),[
            'name'=>'required|max:191',
            'slug'=>'required',
            // 'status'=>'required',
            'description'=>'required',
            'meta_title'=>'required',
            'meta_tags'=>'required',
            'meta_description'=>'required'
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
            // return response($request->name);
            $category = Category::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'meta_title' => $request->meta_title,
                'meta_tag' => $request->meta_tags,
                'meta_description' => $request->meta_description,
                'status' => 0
            ]);

            return response()->json([
                'status'=> 200,
                'message' => 'Category Added'
            ]);
        }
        
    }

    public function delete($id)
    {
        $category = Category::find($id);

        if($category)
        {
            $category = $category->delete();

            // return response()->json($category);

            if($category)
            {

                return response()->json([
                    'status' => 200,
                    'message' => "Record Deleted"
                ]);
            }
            else
            {

                return response()->json([
                    'status' => 400,
                    'message' => "Operation Failed"
                ]);
            }

        }
        else
        {
            return response()->json([
                'status' => 400,
                'message' => "Record not Found"
            ]);

        }
    }

    public function getCategories()
    {
        $category = Category::all();

        return response()->json([
            'status' => 200,
            'category' => $category
        ]);
    }

    public function frontendCategories()
    {
        $categories = Category::limit(4)->get();

        return response()->json([
            'status' => 200,
            'categories' => $categories
        ]);
    }
}
