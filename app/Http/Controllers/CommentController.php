<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return true;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        Comment::make($request->validate([
            'body' => 'required|string|max:1000',
        ]))
            ->user()->associate($request->user())
            ->post()->associate($post)
            ->save();

        return redirect($post->showRoute())
            ->banner('Comment added successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);
        $comment->update($request->validate([
            'body' => 'required|string|max:1000',
        ]));
        return redirect($comment->post->showRoute(['page' => $request->query('page')]))
            ->banner('Comment updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();
        return redirect($comment->post->showRoute(['page' => $request->query('page')]))
            ->dangerBanner('Comment deleted successfully!');
    }
}
