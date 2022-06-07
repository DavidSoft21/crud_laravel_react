<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request  $request){
        $validatedDate = $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:8'
        ]);

        $user = User::create([
            'name' => $validatedDate['name'],
            'email' => $validatedDate['email'],
            'password' => Hash::make($validatedDate['password'])
        ]);
        
        $user->tokens()->delete();
        $token = $user->createToken('aut_token')->plainTextToken;

        $data = [
            'user' => $user,
            'acces_token' => $token,
            'token_type' => 'Bearer'
        ];

        return response()->json($data);

    }

    public function login(Request $request){

        $validatedDate = $request->validate([
            'email' => 'required|string|email|max:191',
            'password' => 'required|string|min:8'
        ]);

        $user = User::where('email', '=', $validatedDate['email'])->first();

        if(Empty($user) == true){

            $data = [
                "message" => "Invalid Access Login Details!",
                "status" => 404
            ];

            return response()->json($data);

        }else{

            if (Hash::check($validatedDate['password'], $user->password)) {
                $user->tokens()->delete();
                $token = $user->createToken('auth_token')->plainTextToken;

                $data = [
                    "token" => $token,
                    "token_type" => "Bearer"
                ];

                return response()->json($data);
            } else {
                $data = [
                    "message" => "Invalid Access Login Details!",
                    "status" => 404
                ];

                return response()->json($data);
            }
        }
        
    }

    public function userinfo(Request $request){
        return $request->user();
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message'=>'Log out!', 'status' =>200]);
    }
}
