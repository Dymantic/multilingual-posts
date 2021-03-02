<?php


namespace Dymantic\MultilingualPosts;


use Dymantic\MultilingualPosts\Rules\RequiresLanguage;
use Illuminate\Routing\Controller;

class PostsController extends Controller
{

    public function index()
    {
        return PostResource::collection(Post::latest('updated_at')->paginate());
    }

    public function show($postId)
    {
        return new PostResource(Post::findOrFail($postId));
    }

    public function store()
    {
        request()->validate([
            'title' => ['required', 'array', new RequiresLanguage],
            'category_ids' => ['array']
        ]);

        $post =  Post::create(request()->only('title', 'intro', 'description', 'body', 'category_id'));

        return new PostResource($post);
    }

    public function update($postId)
    {
        $post = Post::findOrFail($postId);
        $post->safeUpdate(request()->only(['title', 'intro', 'description', 'body', 'category_id']));
    }

    public function destroy($postId)
    {
        Post::findOrFail($postId)->delete();
    }
}