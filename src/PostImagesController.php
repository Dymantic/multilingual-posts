<?php


namespace Dymantic\MultilingualPosts;



use Illuminate\Routing\Controller;

class PostImagesController extends Controller
{
    public function store($postId)
    {
        request()->validate([
            'image' => ['required', 'image']
        ]);

        Post::findOrFail($postId)->attachImage(request('image'));

        return response(null, 201);
    }
}