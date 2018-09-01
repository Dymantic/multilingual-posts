<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\Tests\MakesPosts;
use Dymantic\MultilingualPosts\Tests\TestCase;

class PostPublishingTest extends TestCase
{
    use MakesPosts;

    /**
     *@test
     */
    public function a_post_may_be_published()
    {
        $post = $this->makePost();
        $this->assertNull($post->published_on);

        $post->publish();

        $this->assertTrue($post->fresh()->published_on->isToday());
        $this->assertFalse($post->fresh()->is_draft);
    }
}