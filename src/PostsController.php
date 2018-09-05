<?php


namespace Dymantic\MultilingualPosts;


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
            'title' => ['required', 'array', function($attribute, $value, $fail) {
                $uni = collect($value)->filter(function($v, $k) { return is_numeric($k); })->count() > 0;
                if($uni) {
                    return $fail("{$attribute} should have at least one language entry");
                }
            }]
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