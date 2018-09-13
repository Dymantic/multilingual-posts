<?php


namespace Dymantic\MultilingualPosts;


class CategoryAttributes
{
    private $attributes;

    public function __construct($attributes)
    {
        $this->attributes = $attributes;
    }

    public function isMissingTitle()
    {
        return ! array_key_exists('title', $this->attributes);
    }

    public function hasEmptyTitle()
    {
        if($this->isMissingTitle()) {
            return false;
        }

        $valid_titles = collect($this->attributes['title'])->filter(function($title) {
            return !empty($title);
        });

        return $valid_titles->count() === 0;
    }

    public function translated()
    {
        return collect($this->attributes)->flatMap(function($value, $field) {
            if (is_string($value)) {
                return [$field => [app()->getLocale() => $value]];
            }

            return [$field => $value];
        })->all();
    }
}