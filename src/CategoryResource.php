<?php


namespace Dymantic\MultilingualPosts;


use Illuminate\Http\Resources\Json\Resource;

class CategoryResource extends Resource
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
        ];
    }
}