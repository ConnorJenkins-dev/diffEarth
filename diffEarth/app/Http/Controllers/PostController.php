<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Events\NewPostCreated;

class PostController extends Controller
{
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {


        $request->validate([
            'postTitle' => 'required|string',
            'postContent' => 'required|string',
            'userId' => 'required|integer',
            'img' => 'image|mimes:jpeg|max:2048'
        ]);

        $postContent = $request->input('postContent');

        $userId = $request->input('userId');
        $postTitle = $request->input('postTitle');
        $imageBase64 = null;

        $imagePath = null;
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageBase64 = base64_encode(file_get_contents($request->file('img')->getRealPath()));
        }


        // Post to database
        $post = new Post();
        $post->title = $postTitle;
        $post->content = $postContent;
        $post->user_id = $userId;
        $post->image_base64 = $imageBase64;
        $post->save();

        if ($post->save()) {
            broadcast(new NewPostCreated($post))->toOthers();
        }

        return response()->json([
            'message' => 'Post created successfully.',
            'post' => $post
        ]);
    }
    public function getAllPosts()
    {
        try {
            $postAndUser = Post::with('user')
                ->orderBy('posts.created_at', 'asc')
                ->get()
            ->map(function ($fetchPost) {
                return [
                    'id' => $fetchPost->id,
                    'title' => $fetchPost->title,
                    'content' => $fetchPost->content,
                    'user' => $fetchPost->user->name,
                    'image' => $fetchPost->image_base64,
                    'created_at' => $fetchPost->created_at
                ];
            });
            return response()->json($postAndUser);
        } catch (\Exception $e) {
            Log::error('Error retrieving posts: ' . $e->getMessage());
            return response()->json(['message' => 'Error retrieving posts'], 500);
        }
    }

    public function getPostById($id)
    {
        try {
            $postAndUser = Post::with('user')
                ->where('id', $id)
                ->orderBy('posts.created_at', 'asc')
                ->get()
                ->map(function ($fetchPost) {
                    return [
                        'id' => $fetchPost->id,
                        'title' => $fetchPost->title,
                        'content' => $fetchPost->content,
                        'user' => $fetchPost->user->name,
                        'image' => $fetchPost->image_base64,
                        'created_at' => $fetchPost->created_at
                    ];
                });
            return response()->json($postAndUser);
        } catch (\Exception $e) {
            Log::error('Error retrieving posts: ' . $e->getMessage());
            return response()->json(['message' => 'Error retrieving posts'], 500);
        }
    }
}
