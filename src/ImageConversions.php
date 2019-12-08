<?php


namespace Dymantic\MultilingualPosts;


class ImageConversions
{
    public static function configured()
    {
        $conversions = collect(config('multilingual-posts.conversions') ?? static::defaultConversions());
        return $conversions
            ->map(function ($conversion) {
                return new PostImageConversion($conversion);
            });
    }

    private static function defaultConversions()
    {
        return [
            ['name'         => 'thumb',
             'manipulation' => 'crop',
             'width'        => 400,
             'height'       => 300,
             'title'        => true,
             'post'         => true
            ],
            ['name'         => 'web',
             'manipulation' => 'fit',
             'width'        => 800,
             'height'       => 1800,
             'title'        => true,
             'post'         => true
            ],
            ['name'         => 'banner',
             'manipulation' => 'fit',
             'width'        => 1400,
             'height'       => 1000,
             'title'        => true,
             'post'         => false
            ],
        ];
    }
}