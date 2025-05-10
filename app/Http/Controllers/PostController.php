<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    // create post
    public function addNewPost(Request $request)
    {
        // validating the request
        $validate = Validator::make($request->all(), [
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

    // fetching all posts
    public function getAllPosts()
    {
        try {
            $posts = Post::all();
            //return response
            return response()->json([
                'message' => 'Posts fetched successfully',
                'posts' => $posts
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Error fetching posts',
                'error' => $error->getMessage()
            ]);
        }
    }

    //get single post
    public function singlePost($post_id)
    {
        try {
            $post = Post::find($post_id);
            if (!$post) {
                return response()->json([
                    'message' => 'Post not found'
                ], 404);
            }
            return response()->json([
                'message' => 'Post fetched successfully',
                'post' => $post
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Error fetching post',
                'error' => $error->getMessage()
            ]);
        }
    }


    // update post
    public function editPost(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|string|max:2555',
            'content' => 'required|string',
            'post_id' => 'required|integer'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => 'Validating Error ',
                'errors' => $validate->errors()

            ]);
        }

        try {
            $post_data = Post::find($request->post_id);
            $updated_post = $post_data->update([
                'title' => $request->title,
                'content' => $request->content
            ]);

            //return return response
            return response()->json([
                'message' => 'Post updated successfully',
                'updated_post' => $updated_post
            ], 200);
        } catch (\Exception $err) {
            return response()->json([
                'updated_post' => $err->getMessage()
            ], 200);
        }
    }
}