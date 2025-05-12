<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LikeController extends Controller
{
    // like post

    public function likePost(Request $request){
        $validate = Validator::make($request->all(), [
            'post_id' => 'required|integer'
        ]);

        if ($validate->fails())  {
            return response()->json([
                'message' => 'Validation error',
                'error' => $validate->errors()
            ]);
        }

        try {
            $hasLikedBefore = Like::where('user_id', auth()->user()->id)->where('post_id', $request->post_id)->first();
            if ($hasLikedBefore) {
               return response()->json([
                'message'=> 'Post cant be likes more than once'
               ],403);
            }else{
                $like = new Like();
                $like->post_id = $request->post_id;
                $like->user_id = auth()->user()->id;

                $like->save();
                return response()->json([
                    'message' => 'Post liked successfully',
                    'like' => $like
                ], 200); 
            }
        } catch (\Exception $err) {
            return response()->json([
                'error' => $err->getMessage()
            ], 403);
        }
    }
}