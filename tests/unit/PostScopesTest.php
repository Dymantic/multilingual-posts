<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\Post;
use Dymantic\MultilingualPosts\Tests\TestCase;
use Dymantic\MultilingualPosts\Tests\UsesModels;
use Illuminate\Support\Carbon;

class PostScopesTest extends TestCase
{
    use UsesModels;
    /**
     *@test
     */
    public function it_has_a_scope_for_live_posts()
    {
        $liveA = $this->makePost();
        $liveA->publish();
        $liveB = $this->makePost();
        $liveB->publish(Carbon::now()->subWeek());
        $draft = $this->makePost();
        $draft->retract();
        $postDated = $this->makePost();
        $postDated->publish(Carbon::now()->addWeek());

        $livePosts = Post::live()->get();

        $this->assertTrue($livePosts->contains($liveA));
        $this->assertTrue($livePosts->contains($liveB));
        $this->assertFalse($livePosts->contains($draft));
        $this->assertFalse($livePosts->contains($postDated));
    }
}