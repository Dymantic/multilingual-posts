<?php

namespace Dymantic\MultilingualPosts\Tests\Unit;

use Dymantic\MultilingualPosts\Post;
use Dymantic\MultilingualPosts\Tests\TestCase;

class PostsTest extends TestCase
{
    /**
     *@test
     */
    public function a_new_post_can_be_made_with_just_a_title()
    {
        $post = Post::create(['title' => ['en' => 'Test Title']]);

        $this->assertDatabaseHasWithTranslations('multilingual_posts', [
            'title' => ['en' => 'Test Title']
        ]);
    }




}