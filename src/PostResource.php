<?php


namespace Dymantic\MultilingualPosts;


use Illuminate\Http\Resources\Json\Resource;

class PostResource extends Resource
{
    public static $wrap = null;

    public function toArray($request)
    {
        return [
            'id'                   => $this->id,
            'title'                => $this->getTranslations('title'),
            'slug'                 => $this->slug,
            'intro'                => $this->getTranslations('intro'),
            'description'          => $this->getTranslations('description'),
            'body'                 => $this->getTranslations('body'),
            'created_at'           => $this->created_at->format('d M Y'),
            'updated_at'           => $this->updated_at->format('d M Y'),
            'is_draft'             => $this->is_draft ?? true,
            'author'               => null,
            'published_on'         => $this->published_on ? $this->published_on->format('d M Y') : null,
            'title_image_original' => null,
            'title_image_thumb'    => null
        ];
    }
}