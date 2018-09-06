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
            'is_live'              => $this->isLive(),
            'publish_date'         => $this->publish_date ? $this->publish_date->format('d M Y') : null,
            'author'               => null,
            'first_published_on'   => $this->first_published_on ? $this->first_published_on->format('d M Y') : null,
            'title_image_original' => $this->titleImage(),
            'title_image_banner'   => $this->titleImage('banner'),
            'title_image_web'      => $this->titleImage('web'),
            'title_image_thumb'    => $this->titleImage('thumb'),
        ];
    }
}