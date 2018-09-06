<?php


namespace Dymantic\MultilingualPosts;


use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;

class PublishedPostsController extends Controller
{
    public function store()
    {
        request()->validate(['publish_date' => ['date', 'nullable']]);
        $date = Carbon::parse(request('publish_date', Carbon::today()->format('Y-m-d')));
        $post = Post::findOrFail(request('post_id'));
        $post->publish($date);
    }

    public function destroy($postId)
    {
        $post = Post::findOrFail($postId);
        $post->retract();
    }
}