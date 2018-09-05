<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\Tests\MakesPosts;
use Dymantic\MultilingualPosts\Tests\TestCase;
use Illuminate\Support\Carbon;

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

    /**
     *@test
     */
    public function publishing_a_post_with_a_published_on_state_does_not_have_it_changed()
    {
        $time = Carbon::now()->subWeek();
        $post = $this->makePost(['published_on' => $time]);
        $this->assertNotNull($post->published_on);

        $post->publish();

        $this->assertFalse($post->fresh()->is_draft);
        $this->assertEquals($post->fresh()->published_on->format('d m Y'), $time->format('d m Y'));
    }

    /**
     *@test
     */
    public function a_published_post_may_be_retracted()
    {
        $post = $this->makePost();
        $post->publish();
        $this->assertFalse($post->fresh()->is_draft);

        $post->retract();

        $this->assertTrue($post->fresh()->published_on->isToday());
        $this->assertTrue($post->fresh()->is_draft);
    }

    /**
     *@test
     */
    public function a_post_can_tell_if_it_has_been_published_before()
    {
        $post = $this->makePost();
        $this->assertFalse($post->fresh()->hasBeenPublished());
        $post->publish();
        $this->assertFalse($post->fresh()->is_draft);

        $post->retract();

        $this->assertTrue($post->fresh()->published_on->isToday());
        $this->assertTrue($post->fresh()->is_draft);
        $this->assertTrue($post->fresh()->hasBeenPublished());
    }
}