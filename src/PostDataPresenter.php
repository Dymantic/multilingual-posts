<?php


namespace Dymantic\MultilingualPosts;


class PostDataPresenter
{
    public static function dataArray($post)
    {
        return [
            'id'                   => $post->id,
            'title'                => $post->getTranslations('title'),
            'slug'                 => $post->slug,
            'intro'                => $post->getTranslations('intro'),
            'description'          => $post->getTranslations('description'),
            'body'                 => $post->getTranslations('body'),
            'created_at'           => $post->created_at->format('d M Y'),
            'updated_at'           => $post->updated_at->format('d M Y'),
            'is_draft'             => $post->is_draft ?? true,
            'is_live'              => $post->isLive(),
            'publish_date_string'  => $post->publish_date ? $post->publish_date->format('d M Y') : null,
            'publish_date_year'    => $post->publish_date ? $post->publish_date->year : null,
            'publish_date_month'    => $post->publish_date ? $post->publish_date->month - 1 : null,
            'publish_date_day'    => $post->publish_date ? $post->publish_date->day : null,
            'author'               => null,
            'first_published_on'   => $post->first_published_on ? $post->first_published_on->format('d M Y') : null,
            'title_image_original' => $post->titleImage(),
            'title_image_banner'   => $post->titleImage('banner'),
            'title_image_web'      => $post->titleImage('web'),
            'title_image_thumb'    => $post->titleImage('thumb'),
        ];
    }

    public static function dataArrayFor($lang, $post)
    {
        return [
            'id'                   => $post->id,
            'title'                => $post->getTranslation('title', $lang),
            'slug'                 => $post->slug,
            'intro'                => $post->getTranslation('intro', $lang),
            'description'          => $post->getTranslation('description', $lang),
            'body'                 => $post->getTranslation('body', $lang),
            'created_at'           => $post->created_at->format('d M Y'),
            'updated_at'           => $post->updated_at->format('d M Y'),
            'is_draft'             => $post->is_draft ?? true,
            'is_live'              => $post->isLive(),
            'publish_date_string'  => $post->publish_date ? $post->publish_date->format('d M Y') : null,
            'publish_date_year'    => $post->publish_date ? $post->publish_date->year : null,
            'publish_date_month'    => $post->publish_date ? $post->publish_date->month - 1 : null,
            'publish_date_day'    => $post->publish_date ? $post->publish_date->day : null,
            'author'               => null,
            'first_published_on'   => $post->first_published_on ? $post->first_published_on->format('d M Y') : null,
            'title_image_original' => $post->titleImage(),
            'title_image_banner'   => $post->titleImage('banner'),
            'title_image_web'      => $post->titleImage('web'),
            'title_image_thumb'    => $post->titleImage('thumb'),
        ];
    }
}