<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\Cart;
use Stripe;

class CheckoutController extends Controller
{
    //

    public function placeOrder(Request $request)
    {
        // return response()->json(['data'=> $request->all()]);
        if(auth('sanctum')->check())
        {
            $validator = Validator::make($request->all(),[
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'city'  => 'required',
                'state' => 'required',
                'zipcode' => 'required',
                'address' => 'required',
                'card_no' => 'required',
                'cvc' => 'required|numeric',
                'expiry_month' => 'required|numeric',
                'expiry_year' => 'required|numeric'
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

                // $order->payment_mode = 'STRIP';
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


                // strip code

                // $stripe = Stripe\Stripe::setApiKey(env('STRIPE_KEY'));
                // // $stripe = Stripe::make(env('STRIPE_KEY'));

                // $amount = 500;

                // try{
                //     $token = $stripe->tokens()->create([
                //         "card"=>[
                //             "number" => $request->card_no,
                //             "exp_month" => $request->expiry_month,
                //             "exp_year" => $request->expiry_year,
                //             "cvc" => $request->cvc
                //         ]
                //     ]);

                //     if(!isset($token['id']))
                //     {
                //         dd("payment not created");
                //     }
                //     else
                //     {
                //         $customer = $stripe->customers()->create([
                //             'name' => $request->first_name ." ". $request->last_name,
                //             'email' => $request->email,
                //             'phone' => $request->phone,
                //             'address'=>[
                //                 'line1' => $request->address,
                //                 'city' => $request->city,
                //                 'postal_code' => $request->zipcode,
                //                 'state' => $request->province,
                //                 'country' => $request->country
                //             ],
                //             'shipping'=>[
                //                 'name' => $request->first_name ." ". $request->last_name,
                //                 'phone' => $request->phone,
                //                 'address'=>[
                //                     'line1' => $request->address,
                //                     'city' => $request->city,
                //                     'postal_code' => $request->zipcode,
                //                     'state' => $request->province,
                //                     'country' => $request->country
                //                     ]
                //                 ],
                //                 'source'=> $token['id']
                //         ]);

                //         $charge = $stripe->charges()->create([
                //             'customer' => $customer['id'],
                //             'currency' => 'USD',
                //             'amount'   => $amount,
                //         ]);

                //         if($charge['status'] == "successed")
                //         {
                //             dd("transection aproved");
                //         }
                //     }
                // } catch(Exception $e)
                // {
                //     dd("error");
                // } 


                ////////////////////////////////////////////////// stripe 2  ///////////////////////////////////////////////////////////////// 
        //         $amount = 500;

        //         $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        // try {
        //     $response = \Stripe\Token::create(array(
        //         "card" => array(
        //             "number"    => $request->input('card_no'),
        //             "exp_month" => $request->input('expiry_month'),
        //             "exp_year"  => $request->input('expiry_year'),
        //             "cvc"       => $request->input('cvc')
        //         )));
        //     if (!isset($response['id'])) {
        //         return redirect()->route('addmoney.paymentstripe');
        //     }
        //     $charge = \Stripe\Charge::create([
        //         'card' => $response['id'],
        //         'currency' => 'USD',
        //         'amount' =>  $amount,
        //         'description' => 'wallet',
        //     ]);
 
        //     if($charge['status'] == 'succeeded') {
        //         return redirect('stripe')->with('success', 'Payment Success!');
 
        //     } else {
        //         return redirect('stripe')->with('error', 'something went to wrong.');
        //     }
 
        // }
        // catch (Exception $e) {
        //     return $e->getMessage();
        // }
 


                return response()->json([
                    'status' => 200,
                    'message' => $request->first_name.' '.$request->lastname
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
