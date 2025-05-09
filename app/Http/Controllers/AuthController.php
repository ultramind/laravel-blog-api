<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;

class AuthController extends Controller
{
    //create a new user
    public function registerUser(Request $request)
    {
        // validating the user fields
        // $validate = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|email|unique:user|max:255',
        //     'password' => 'required|string|min:6|confirmed'
        // ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 403);
        }


        try {

            // create a new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            // return the user
            return response()->json([
                'message' => 'User created successfully',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // login user
    public function loginUser(Request $request)
    {
        // validating the request fields
        $validate = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6'
        ]);

        // error handling
        if ($validate->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validate->errors(),
                403
            ]);
        }

        //get the request credentials
        $credentials = ['email' => $request->email, 'password' => $request->password];
        // checking if the auth is valid
        try {
            if (!auth()->attempt($credentials)) {
                return response()->json([
                    'message' => 'Invalid Credentials',
                    'errors' => 'Invalid Credentials'
                ], 401);
            }

            // check if the user exists
            $user = User::where('email', $request->email)->firstOrFail();

            // create a token for the user
            $token = $user->createToken('auth_token')->plainTextToken;

            // return the user and token
            return response()->json([
                'message' => 'User logged in successfully',
                'user' => $user,
                'access_token' => $token
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error logging in',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // logout user
    public function logoutUser(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        // return response
        return response()->json([
            'message' => 'User logged out successfully'
        ], 200);
    }
}