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
        $image = Post::findOrFail($postId)->setTitleImage(request('image'));

        return ['image_src' => $image->getUrl(ImageConversions::useForTitleImageUploadResponse())];
    }
}