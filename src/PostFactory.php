<?php


namespace Dymantic\MultilingualPosts;


class PostFactory
{
    public static function make($post_attributes)
    {
        static::guardAgainstMissingTitle($post_attributes);
        $model = new Post();

        $translations = static::formatTranslatableFields($post_attributes, $model->translatable);

        $post =  $model->query()->create(array_merge($post_attributes, $translations));

        if(array_key_exists('category_id', $post_attributes)) {
            $post->setCategories(Category::find($post_attributes['category_id']));
        }

        return $post;
    }

    private static function guardAgainstMissingTitle($post_attributes)
    {
        if (!array_key_exists('title', $post_attributes) || empty($post_attributes['title'])) {
            throw new BadPostDataException('a title is required to create a post');
        }
    }

    private static function formatTranslatableFields($post_attributes, $translatable_fields)
    {
        return collect($translatable_fields)
            ->flatMap(function ($field) use ($post_attributes) {
                return array_key_exists($field, $post_attributes) ?
                    [$field => $post_attributes[$field]] :
                    [$field => [app()->getLocale() => ""]];
            })->flatMap(function ($value, $field) {
                return is_string($value) ? [$field => [app()->getLocale() => $value]] : [$field => $value];
            })->all();
    }
}