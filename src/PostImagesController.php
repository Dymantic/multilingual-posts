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

        $conversion = ImageConversions::useForPostImageUploadResponse();
        $url = config('multilingual-posts.use_full_url_for_body_images', false) ?
        $image->getFullUrl($conversion) : $image->getUrl($conversion);

        return response(['src' => $url], 201);
    }
}