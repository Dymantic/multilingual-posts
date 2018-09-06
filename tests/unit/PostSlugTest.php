<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\Post;
use Dymantic\MultilingualPosts\Tests\TestCase;
use Illuminate\Support\Carbon;

class PostSlugTest extends TestCase
{
    /**
     *@test
     */
    public function a_newly_created_post_has_a_slug()
    {
        $post = Post::create(['title' => ['en' => 'Test Title']]);

        $this->assertEquals('test-title', $post->fresh()->slug);
    }

    /**
     *@test
     */
    public function a_post_that_has_no_published_on_date_will_have_its_slug_updated()
    {
        $post = Post::create(['title' => ['en' => 'Test Title']]);
        $this->assertNull($post->published_on);
        $this->assertEquals('test-title', $post->fresh()->slug);

//        $post->published_on = Carbon::now();
//        $post->save();

        $post->title = ['en' => 'A new title', 'fr' => 'A new titley'];
        $post->save();

        $this->assertEquals('a-new-title', $post->fresh()->slug);
    }

    /**
     *@test
     */
    public function the_slug_wont_be_updated_if_post_has_been_published()
    {
        $post = Post::create(['title' => ['en' => 'Original Title']]);
        $this->assertNull($post->published_on);
        $this->assertEquals('original-title', $post->fresh()->slug);

        $post->first_published_on = Carbon::now();
        $post->save();

        $post->title = ['en' => 'A new title', 'fr' => 'A new titley'];
        $post->save();

        $this->assertEquals('original-title', $post->fresh()->slug);
    }
}