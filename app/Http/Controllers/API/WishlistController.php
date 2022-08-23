<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    //
    public function addToWish(Request $request)
    {
        if(auth('sanctum')->check())
        {
            $user_id = auth('sanctum')->user()->id;
            $product_id = $request->product_id;
            $product_qty = $request->product_qty;

            
            $productCheck = Product::find($product_id);
            

            if($productCheck)
            {
                if(Wishlist::where('user_id',$user_id)->where('product_id',$product_id)->exists())
                {

                    return response()->json([
                        'status' => 409,
                        'message' => $productCheck->name.' already in wishlist'
                    ]);
                }
                else
                {
                    $wishlistProducts = new Wishlist();

                    $wishlistProducts->user_id = $user_id;
                    $wishlistProducts->product_id = $product_id;
                    // $wishlistProducts->product_qty = $product_qty;

                    $wishlistProducts->save();

                    return response()->json([
                        'status' => 201,
                        'message' => 'Product Added into Wishlist'
                    ]);
                }

            }
            else
            {
                return response()->json([
                    'status' => 404,
                    'message' => 'Product not found'
                ]);

            }
        }
        else
        {
            return response()->json([
                'status' => 401,
                'message' => 'please login for add to wishlist'
            ]);
        }
    }

    // view wishlist

    public function viewWishlist()
    {
       
        if(auth('sanctum')->check())
        {
            $user_id = auth('sanctum')->user()->id;

            $wishItems = Wishlist::where('user_id',$user_id)->get();

            return response()->json([
                'status' => 200,
                'wishlist'=> $wishItems
            ]);
        }
        else
        {
            return response()->json([
                'status' => 401,
                'message' => "Login to View Wishlist Products"
            ]);
        }
    }

    // delete wishlist

    public function deleteWishlist($id)
    {
        if(auth('sanctum')->check())
        {
            $wishlist = Wishlist::find($id);
    
            if($wishlist)
            {
                $wishlist->delete();

                return response()->json([
                    'status' => 200,
                    'message' => "Item removed from Wishlist"
                ]);
            }
            else
            {
                return response()->json([
                    'status' => 404,
                    'message' => "Item not found"
                ]);
            }
        }
        else
        {
            return response()->json([
                'status' => 401,
                'message' => "Please login first ..."
            ]);
        }
    }
}
