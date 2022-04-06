<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\Cart;

class CheckoutController extends Controller
{
    //

    public function placeOrder(Request $request)
    {
        if(auth('sanctum')->check())
        {
            $validator = Validator::make($request->all(),[
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'city'  => 'required',
                'state' => 'required',
                'address' => 'required'
            ]);
            
            if($validator->fails())
            {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->messages(),
                ]);
            }
            else
            {
                $user_id = auth('sanctum')->user()->id;

                $order = new Order;

                $order->user_id = $user_id;
                $order->first_name = $request->first_name;
                $order->last_name = $request->last_name;
                $order->email = $request->email;
                $order->phone = $request->phone;
                $order->city = $request->city;
                $order->state = $request->state;
                $order->zipcode = $request->zipcode;
                $order->address = $request->address;

                $order->payment_mode = 'COD';
                $order->tracking_number = "ecomlaravel".rand(1111,9999);
                $order->save();

                $cart = Cart::where('user_id',$user_id)->get();

                $orderitems = [];
                foreach($cart as $item)
                {
                    $orderitems[]=[
                        'product_id' => $item->product_id,
                        'qty' => $item->product_id,
                        'price' => $item->product->selling_price
                    ];

                    $item->product->update([
                        'qty' => $item->product->qty - $item->product_qty
                    ]);
                }

                $order->orderitems()->createMany($orderitems);
                Cart::destroy($cart);
                return response()->json([
                    'status' => 200,
                    'message' => $request->first_name
                ]);
            }
        }
        else
        {
            return response()->json([
                'status' => 401,
                'message' => "login first ..."
            ]);
        }
    }

    public function getOrders()
    {
        if(auth('sanctum')->check())
        {

            $order = Order::all();
    
            return response()->json([
                'status' => 200,
                'order' => $order
            ]);
        }
        else
        {

        }
    }
}
