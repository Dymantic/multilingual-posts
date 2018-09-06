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

        $this->assertTrue($post->fresh()->first_published_on->isToday());
        $this->assertTrue($post->fresh()->publish_date->isToday());
        $this->assertFalse($post->fresh()->is_draft);
    }

    /**
     *@test
     */
    public function the_publish_date_can_be_set_when_publishing()
    {
        $post = $this->makePost();
        $this->assertNull($post->published_on);

        $post->publish(Carbon::now()->addWeek());

        $this->assertTrue($post->fresh()->publish_date->isNextWeek());
        $this->assertFalse($post->fresh()->is_draft);
    }

    /**
     *@test
     */
    public function scheduling_a_previously_published_post_has_correct_first_published_on_date()
    {
        $post = $this->makePost();
        $post->publish(Carbon::now()->addWeek());

        $this->assertTrue($post->fresh()->first_published_on->isNextWeek());
        $this->assertTrue($post->fresh()->publish_date->isNextWeek());
        $this->assertFalse($post->fresh()->is_draft);
    }

    /**
     *@test
     */
    public function publishing_a_post_with_a_first_published_on_state_does_not_have_it_changed()
    {
        $time = Carbon::now()->subWeek();
        $post = $this->makePost(['first_published_on' => $time]);
        $this->assertNotNull($post->first_published_on);

        $post->publish();

        $this->assertFalse($post->fresh()->is_draft);
        $this->assertEquals($post->fresh()->first_published_on->format('d m Y'), $time->format('d m Y'));
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

        $this->assertTrue($post->fresh()->first_published_on->isToday());
        $this->assertNull($post->fresh()->publish_date);
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

        $this->assertTrue($post->fresh()->first_published_on->isToday());
        $this->assertTrue($post->fresh()->is_draft);
        $this->assertTrue($post->fresh()->hasBeenPublished());
    }

    /**
     *@test
     */
    public function a_post_with_a_future_first_published_on_date_has_not_been_published()
    {
        $post = $this->makePost();

        $post->publish(Carbon::today()->addWeek());
        $this->assertTrue($post->fresh()->first_published_on->startOfDay()->isFuture());
        $this->assertFalse($post->fresh()->hasBeenPublished());
    }

    /**
     *@test
     */
    public function retracting_a_future_first_published_date_clears_the_first_pubished_on_date()
    {
        $post = $this->makePost();
        $post->publish(Carbon::today()->addWeek());

        $post->retract();

        $this->assertNull($post->fresh()->first_published_on);
    }

    /**
     *@test
     */
    public function a_post_that_is_not_a_draft_but_has_a_future_publish_date_is_not_live()
    {
        $post = $this->makePost();
        $this->assertNull($post->published_on);

        $post->publish(Carbon::now()->addWeek());

        $this->assertFalse($post->fresh()->isLive());

        $post->retract();
        $post->publish();

        $this->assertTrue($post->fresh()->isLive());

    }
}