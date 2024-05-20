<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return request()->user();

        $post = Post::with(['user'])->paginate(10);

        if (!$post) {
            return response()->json([
                'message' => 'Data Post Not Found',
            ], 404);
        }

        return response()->json([
            'data' => $post,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'title' => 'required|min:5',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages(),
            ], 404);
        }

        // $post = Post::create($data);
        $post = request()->user()->posts()->create($data);

        return response()->json([
            'message' => "Success Create Post Data",
            'data' => $post,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Data Post Not Found',
            ], 404);
        }

        //dengan menggunakan resource kita dapat menentukan data apa saja yang ingin ditampilkan ke
        return response()->json([
            'post' => new PostResource($post),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages(),
            ], 404);
        }

        $post->update($request->all());

        return response()->json([
            'message' => 'Success Update Post Data',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json([
            'message' => 'Success Delete Post Data',
        ], 200);
    }
}
