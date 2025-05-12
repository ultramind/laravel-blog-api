<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    //Post comment
    public function postComment(Request $request)
    {
        // validating fields
        $validate = Validator::make($request->all(), [
            'post_id' => 'required|integer',
            'comment' => 'required|string|max:255'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => 'validating error',
                'errors' => $validate->errors()
            ]);
            # code...
        }

        // add to db
        try {

            
            $comment = new Comment();
            $comment->post_id = $request->post_id;
            $comment->comment = $request->comment;
            $comment->user_id = auth()->user()->id;
            $comment->save();

            // return response
            return response()->json([
                'message' => 'Comment added successful',

            ], 200);
        } catch (\Exception $err) {
            return response()->json([
                'error' => $err->getMessage()
            ]);
        }
    }
}