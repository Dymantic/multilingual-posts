<?php


namespace Dymantic\MultilingualPosts\Tests;


use Dymantic\MultilingualPosts\Post;

trait MakesPosts
{
    protected function makePost($post_data = [])
    {
        $defaults = [
            'title'       => ['en' => 'test title', 'fr' => 'test titley'],
            'intro'       => ['en' => 'test intro', 'fr' => 'test introy'],
            'description' => ['en' => 'test description', 'fr' => 'test descriptiony'],
            'body'        => ['en' => 'test body', 'fr' => 'test bodyy'],
        ];

        return Post::forceCreate(array_merge($defaults, $post_data))->fresh();
    }
}