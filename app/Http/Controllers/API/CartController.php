<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;


class CartController extends Controller
{
    //

    public function addToCart(Request $request)
    {
        if(auth('sanctum')->check())
        {
            $user_id = auth('sanctum')->user()->id;
            $product_id = $request->product_id;
            $product_qty = $request->product_qty;

            
            $productCheck = Product::find($product_id);
            
            if($productCheck && !($productCheck->qty <= 0))
            {
                if(Cart::where('user_id',$user_id)->where('product_id',$product_id)->exists())
                {

                    return response()->json([
                        'status' => 409,
                        'message' => $productCheck->name.' already in cart'
                    ]);
                }
                else
                {
                    $cartProducts = new Cart();

                    $cartProducts->user_id = $user_id;
                    $cartProducts->product_id = $product_id;
                    $cartProducts->product_qty = $product_qty;

                    $cartProducts->save();

                    return response()->json([
                        'status' => 201,
                        'message' => 'Product Added into Cart'
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
                'message' => 'please login for add to cart'
            ]);
        }
    }

    public function viewCart()
    {
        if(auth('sanctum')->check())
        {
            $user_id = auth('sanctum')->user()->id;

            $cartItems = Cart::where('user_id',$user_id)->get();

            return response()->json([
                'status' => 200,
                'cart'=> $cartItems
            ]);
        }
        else
        {
            return response()->json([
                'status' => 401,
                'message' => "Login to View Cart Products"
            ]);
        }
    }

    public function updateCart($id,$scop)
    {
        if(auth('sanctum')->check())
        {
            $user_id = auth('sanctum')->user()->id;

            $cartItems = Cart::where('user_id',$user_id)->where('id',$id)->first();
            // 
            if($scop == 'inc')
            {
               $cartItems->product_qty = $cartItems->product_qty + 1;   
            }
            else if($scop == 'dec')
            {
                $cartItems->product_qty = $cartItems->product_qty - 1;   
                
            }
            else
            {
                $cartItems->product_qty = $cartItems->product_qty;   
              
            }

            $cartItems->update();

            return response()->json([
                "status" => 200,
                "message" => "Quantity updated"
                
            ]);
        }
    }

    public function deleteCart($id)
    {
        if(auth('sanctum')->check())
        {
            $cart = Cart::find($id);
    
            if($cart)
            {
                $cart->delete();
               
                $user_id = auth('sanctum')->user()->id;

                $cartItems = Cart::where('user_id',$user_id)->get();

                return response()->json([
                    'status' => 200,
                    'message' => "Item removed from Cart",
                    'cart' => $cartItems
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
