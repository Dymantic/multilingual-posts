<?php


namespace Dymantic\MultilingualPosts;


class TranslatableAttributes
{
    private $attributes;

    public function __construct($attributes)
    {
        $this->attributes = $attributes;
    }

    public function isMissingTitle()
    {
        return !array_key_exists('title', $this->attributes);
    }

    public function hasEmptyTitle()
    {
        if ($this->isMissingTitle()) {
            return false;
        }

        $valid_titles = collect($this->attributes['title'])->filter(function ($title) {
            return !empty($title);
        });

        return $valid_titles->count() === 0;
    }

    public function translated($translatable_fields, $require_fields = false)
    {
        $attributes = collect($this->attributes);
        if ($require_fields) {
            $attributes = collect(array_merge($translatable_fields, array_keys($this->attributes)))
                ->flatMap(function ($field) {
                    if (array_key_exists($field, $this->attributes)) {
                        return [$field => $this->attributes[$field]];
                    }

                    return [$field => ""];
                });
        }

        return $attributes->flatMap(function ($value, $field) use ($translatable_fields) {
            if (in_array($field, $translatable_fields) && is_string($value)) {
                return [$field => [app()->getLocale() => $value]];
            }

            return [$field => $value];
        })->all();
    }

    public function has($field)
    {
        return array_key_exists($field, $this->attributes);
    }

    public function __get($field)
    {
        if(!$this->has($field)) {
            throw new \Exception('attributes does not contain ' . $field);
        }

        return $this->attributes[$field];
    }
}