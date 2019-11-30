<?php


namespace Dymantic\MultilingualPosts;


class PostDataPresenter
{
    public static function dataArray($post)
    {
        $locale = app()->getLocale();
        $data = [
            'id'                   => $post->id,
            'title'                => $post->getTranslations('title') ?: [$locale => ""],
            'slug'                 => $post->slug,
            'intro'                => $post->getTranslations('intro') ?: [$locale => ""],
            'description'          => $post->getTranslations('description') ?: [$locale => ""],
            'body'                 => $post->getTranslations('body') ?: [$locale => ""],
            'created_at'           => $post->created_at->format('d M Y'),
            'updated_at'           => $post->updated_at->format('d M Y'),
            'is_draft'             => $post->is_draft ?? true,
            'is_live'              => $post->isLive(),
            'publish_date_string'  => $post->publish_date ? $post->publish_date->format('d F Y') : null,
            'publish_date_year'    => $post->publish_date ? $post->publish_date->year : null,
            'publish_date_month'    => $post->publish_date ? $post->publish_date->month - 1 : null,
            'publish_date_day'    => $post->publish_date ? $post->publish_date->day : null,
            'author'               => null,
            'first_published_on'   => $post->first_published_on ? $post->first_published_on->format('d M Y') : null,
            'title_image_original' => $post->titleImage(),
        ];

        return array_merge($data, static::titleImageConversions($post));
    }

    public static function dataArrayFor($lang, $post)
    {
        $data = [
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
            'publish_date_string'  => $post->publish_date ? $post->publish_date->format('d F Y') : null,
            'publish_date_year'    => $post->publish_date ? $post->publish_date->year : null,
            'publish_date_month'    => $post->publish_date ? $post->publish_date->month - 1 : null,
            'publish_date_day'    => $post->publish_date ? $post->publish_date->day : null,
            'author'               => null,
            'first_published_on'   => $post->first_published_on ? $post->first_published_on->format('d M Y') : null,
            'title_image_original' => $post->titleImage(),
        ];

        return array_merge($data, static::titleImageConversions($post));
    }

    private static function titleImageConversions($post)
    {
        $image = $post->getFirstMedia(Post::TITLE_IMAGES);

        if(!$image) {
            return [];
        }

        return collect($image->getMediaConversionNames())
            ->flatMap(function($name) use ($post) {
                return ["title_image_{$name}" => $post->titleImage($name)];
            })->all();
    }
}