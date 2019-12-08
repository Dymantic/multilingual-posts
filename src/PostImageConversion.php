<?php


namespace Dymantic\MultilingualPosts;



class PostImageConversion
{
    public $name;
    public $width;
    public $height;
    public $manipulation;
    public $collections;

    public function __construct($attributes)
    {
        $this->name = $attributes['name'];
        $this->width = $attributes['width'];
        $this->height = $attributes['height'];
        $this->manipulation = $this->getManipulation($attributes['manipulation']);
        $this->collections = $this->getCollections($attributes['title'], $attributes['post']);
        $this->optimize = $attributes['optimize'] ?? false;
    }

    private function getManipulation($strategy)
    {
        $fits = [
            'crop' => 'crop',
            'fit'  => 'max',
        ];

        return $fits[$strategy] ?? 'max';
    }

    private function getCollections($title, $body)
    {
        return collect([
            $title ? Post::TITLE_IMAGES : null,
            $body ? Post::BODY_IMAGES : null,
        ])
            ->filter(function ($collection) {
                return $collection;
            })
            ->values()
            ->all();
    }
}