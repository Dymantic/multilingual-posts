<?php


namespace Dymantic\MultilingualPosts;



use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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