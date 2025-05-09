<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    // create post
    public function addNewPost(Request $request){
        // validating the request
        $validate = Validator::make($request->all(),[
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // checking for validation errors
        if ($validate->fails()) {
            return response()->json([
                'message' => 'Validating error',
                'errors' => $validate->errors()
            ], 402);
        }

        // creating the post
        try {
            $post = new Post();
            $post->title = $request->title;
            $post->content = $request->content;
            $post->user_id = auth()->user()->id;
            $post->save();

            //return response
            return response()->json([
                'message' => 'Post created successfully',
                'post' => $post
            ], 201);
    
        } catch (\Exception $err) {
            return response()->json([
                'message' => 'Error creating post',
                'error' => $err->getMessage()
            ], 500);
        }
        
    }
}