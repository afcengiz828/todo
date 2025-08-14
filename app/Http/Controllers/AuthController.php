<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

use function Laravel\Prompts\error;
use function Laravel\Prompts\password;

class AuthController extends Controller
{
    public function register (Request $request) {
        $validator = Validator::make($request->all(), [
             "name" => [ "required", "string", "min:3", "max:20"],
             "email"=> ["required", "string", "max:255", "email", "unique:users"],
             "role"=> ["required", "string"],
             "password"=> ["required", "min:8"],
         ]);

         if($validator->fails()){
            return response()->json([
                "status" => "error",
                "message" => "Validation error",
            ],422);
         }

         try{

             $user = User::create([
                 "name" => $request->name,
                 "email" => $request->email,
                 "password" => $request->password,
                 "role" => $request->role,
                ]);
                
                return response()->json([
                    "status" => "succes",
                    "message" => "User created succesfully",
                ],200);
            }catch(Exception $error){
                return response()->json([
                    "status" => "error",
                    "message" => "An error occured while add a new user",
                    "data" => $error
                ],500);
            }


    }

    public function login(Request $request)   {
        // request içinde email ve şifre var.

        // İlk olarak kullanıcıyı bul.
        $user = User::where("email", $request->email)->first();


        // Kullanıcı var mı kontrol et.
        if($user and Hash::check($request->password, $user->password)){
            // Kullanıcı varsa jwt token ını return et.
            //Jwt token oluştur
            $token = JWTAuth::fromUser($user);

            return response()->json([
            'status' => 'success',
            'token' => $token,
        ]);
            

        }else{
            return response()->json([
                "status" => "error",
                "message" => "Wrong email or password",
            ],401);
        }
    }

    public function logout()
    {
        Auth::guard('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
