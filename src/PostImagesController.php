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

        $image = Post::findOrFail($postId)->attachImage(request('image'));

        return response(['src' => $image->getUrl(ImageConversions::useForPostImageUploadResponse())], 201);
    }
}