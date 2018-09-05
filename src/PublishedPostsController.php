<?php


namespace Dymantic\MultilingualPosts;


use Illuminate\Routing\Controller;

class PublishedPostsController extends Controller
{
    public function store()
    {
        $post = Post::findOrFail(request('post_id'));
        $post->publish();
    }

    public function destroy($postId)
    {
        $post = Post::findOrFail($postId);
        $post->retract();
    }
}