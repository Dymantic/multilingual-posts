<?php


namespace Dymantic\MultilingualPosts;



use Illuminate\Routing\Controller;

class PostImagesController extends Controller
{
    public function store($postId)
    {
        $post = Post::findOrFail($postId);

        $post->attachImage(request('image'));

        return response(null, 201);
    }
}