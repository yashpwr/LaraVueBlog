<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Validator;

class PostController extends Controller {
    /**
    * Display a listing of the resource.
    */
    public function index(){
        $posts = Post::all();
        return response()->json([
            "success" => true,
            "message" => "Post List",
            "data" => $posts
        ]);
    }
    /**
    * Store a newly created resource in storage.
    */
    public function store(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'content' => 'required'
        ]);
        if($validator->fails()){
            
            return response()->json([
                "success" => false,
                "message" => "Validation Error.",
                "data" => $validator->errors()
            ]);
        }

        $post = Post::create($input);
            return response()->json([
            "success" => true,
            "message" => "Post created successfully.",
            "data" => $post
        ]);
    } 
    /**
    * Display the specified resource.
    */
    public function show($id) {
        $post = Post::find($id);
        if (is_null($post)) {
            return response()->json([
                "success" => false,
                "message" => "Post not found.",
                "data" => []
            ]);
        }
        return response()->json([
            "success" => true,
            "message" => "Post retrieved successfully.",
            "data" => $post
        ]);
    }
    /**
    * Update the specified resource in storage.
    */
    public function update(Request $request, Post $post){
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'content' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                "success" => false,
                "message" => "Validation Error.",
                "data" => $validator->errors()
            ]);
        }
        $post->title = $input['title'];
        $post->content = $input['content'];
        $post->save();

        return response()->json([
            "success" => true,
            "message" => "Post updated successfully.",
            "data" => $post
        ]);
    }
    /**
    * Remove the specified resource from storage.
    */
    public function destroy(Post $post) {
        $post->delete();
        return response()->json([
            "success" => true,
            "message" => "Post deleted successfully.",
            "data" => $post
        ]);
    }
}