<?php


namespace Dymantic\MultilingualPosts;


use Dymantic\MultilingualPosts\Rules\RequiresLanguage;
use Illuminate\Routing\Controller;

class PostsController extends Controller
{

    public function index()
    {
        return PostResource::collection(Post::paginate());
    }

    public function show($postId)
    {
        return new PostResource(Post::findOrFail($postId));
    }

    public function store()
    {
        request()->validate([
            'title' => ['required', 'array', new RequiresLanguage]
        ]);

        $post =  Post::create(request()->all('title', 'intro', 'description', 'body'));

        return new PostResource($post);
    }

    public function update($postId)
    {
        $post = Post::findOrFail($postId);
        $post->update(request()->only(['title', 'intro', 'description', 'body']));
    }

    public function destroy($postId)
    {
        Post::findOrFail($postId)->delete();
    }
}