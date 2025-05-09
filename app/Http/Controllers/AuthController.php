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

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users|max:255',
                'password' => 'required|string|min:6|confirmed'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 403);
            }

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
}