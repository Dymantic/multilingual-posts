<?php


namespace Dymantic\MultilingualPosts;


use Illuminate\Routing\Controller;

class PostTitleImageController extends Controller
{
    public function store($postId)
    {
        request()->validate([
            'image' => ['required', 'image']
        ]);
        $post = Post::findOrFail($postId);

        $post->setTitleImage(request('image'));
    }
}