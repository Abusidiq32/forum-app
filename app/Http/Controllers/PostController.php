<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Post::class);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia('Posts/Index', [
            'posts' => PostResource::collection(Post::with('user')->latest()->latest('id')->paginate()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia('Posts/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data= $request->validate([
            'title' => ['required', 'string', 'min:10', 'max:255'],
            'body' => ['required', 'string', 'min:10', 'max:10000'],
        ]);

        $post = Post::make($data);
        $post->user()->associate($request->user())->save();
        return redirect($post->showRoute())
            ->banner('Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Post $post)
    {
        // dd($post->showRoute(), $request->path());
        if(! Str::contains($post->showRoute(), $request->path()))
        {
            return redirect($post->showRoute($request->query()), status: 301);
        }

        $post->load('user');
        return Inertia('Posts/Show', [
            'post' => PostResource::make($post),
            'comments' => CommentResource::collection($post->comments()->with('user')->latest()->latest('id')->paginate(10)),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
