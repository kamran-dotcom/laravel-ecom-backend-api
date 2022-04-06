<?php

namespace App\Http\Controllers\ApI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required|max:191',
            // 'last_name'=>'required|max:191',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:8'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'validation_errors'=>$validator->messages()
            ]);
        }
        else
        {
            $user = User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password)
            ]);

            $token = $user->createToken($user->email.'_token')->plainTextToken;
            
            return response()->json([
                'status'=>200,
                'username'=>$user->name,
                'token'=>$token,
                'message'=>'Registration Successful'
            ]);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required|min:8'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'validation_errors'=>$validator->messages()
            ]);
        }
        else
        {
            $user = User::where('email',$request->email)->first();

            if(! $user || ! Hash::check($request->password,$user->password))
            {
                return response()->json([
                    'status'=> 401,
                    'message' => "Invalide credientials"
                ]);
            }
            else
            {
                $token = $user->createToken($user->email.'_token')->plainTextToken;
            
                return response()->json([
                    'status'=> 200,
                    'username'=> $user->name,
                    'token'=> $token,
                    'message'=> 'Logged in Successfully'
                ]);
            }
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => 200,
            'message' => "Logged out Successfully"
        ]);
    }
}
