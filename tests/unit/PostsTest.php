<?php

namespace Dymantic\MultilingualPosts\Tests\Unit;

use Dymantic\MultilingualPosts\MediaLibraryMediaBroker;
use Dymantic\MultilingualPosts\Post;
use Dymantic\MultilingualPosts\TestMediaBroker;
use Dymantic\MultilingualPosts\Tests\TestCase;
use Dymantic\SmlMediaBroker\SmlMediaBroker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;

class PostsTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        config(['multilingual-posts.media-broker' => TestMediaBroker::class]);
    }
    
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

    /**
     *@test
     */
    public function previous_post_and_next_post()
    {
        $one = $this->makePost(Carbon::today()->subDays(5), true);
        $two = $this->makePost(Carbon::today()->subDays(4), false);
        $three = $this->makePost(Carbon::today()->subDays(3), true);
        $four = $this->makePost(Carbon::today()->subDays(2), true);
        $five = $this->makePost(Carbon::today()->subDays(1), false);

        $this->assertTrue($one->next()->is($three));
        $this->assertNull($one->prev());

        //retracted posts have no next/prev
        $this->assertNull($two->next());
        $this->assertNull($two->prev());

        $this->assertTrue($three->next()->is($four));
        $this->assertTrue($three->prev()->is($one));

        $this->assertNull($four->next());
        $this->assertTrue($four->prev()->is($three));

        $this->assertNull($five->next());
        $this->assertNull($five->prev());

    }


    /**
     *@test
     */
    public function a_post_can_be_presented_as_a_data_array()
    {
        $post = Post::create([
            'title'       => ['en' => 'test title', 'fr' => 'test titley'],
            'intro'       => ['en' => 'test intro', 'fr' => 'test introy'],
            'description' => ['en' => 'test description', 'fr' => 'test descriptiony'],
            'body'        => ['en' => 'test body', 'fr' => 'test bodyy']
        ]);

        $image = $post->setTitleImage(UploadedFile::fake()->image('testpic.png'));

        $post->publish();
        $post->publish(Carbon::today()->addWeek());

        $expected = [
            'id'                   => $post->id,
            'slug'                 => 'test-title',
            'title'                => ['en' => 'test title', 'fr' => 'test titley'],
            'intro'                => ['en' => 'test intro', 'fr' => 'test introy'],
            'description'          => ['en' => 'test description', 'fr' => 'test descriptiony'],
            'body'                 => ['en' => 'test body', 'fr' => 'test bodyy'],
            'created_at'           => $post->created_at->format('d M Y'),
            'updated_at'           => $post->updated_at->format('d M Y'),
            'is_draft'             => false,
            'is_live'              => false,
            'publish_date_string'  => Carbon::today()->addWeek()->format('d F Y'),
            'publish_date_year'    => Carbon::today()->addWeek()->year,
            'publish_date_month'   => Carbon::today()->addWeek()->month - 1,
            'publish_date_day'     => Carbon::today()->addWeek()->day,
            'author'               => null,
            'first_published_on'   => Carbon::today()->format('d M Y'),
            'title_image_original' => $image->getUrl(),
            'title_image_banner'   => $image->getUrl('banner'),
            'title_image_web'      => $image->getUrl('web'),
            'title_image_thumb'    => $image->getUrl('thumb'),
        ];

        $this->assertEquals($expected, $post->asDataArray());
    }

    /**
     *@test
     */
    public function a_post_can_present_itself_for_a_given_language()
    {
        $post = Post::create([
            'title'       => ['en' => 'test title', 'fr' => 'test titley'],
            'intro'       => ['en' => 'test intro', 'fr' => 'test introy'],
            'description' => ['en' => 'test description', 'fr' => 'test descriptiony'],
            'body'        => ['en' => 'test body', 'fr' => 'test bodyy']
        ]);

        $image = $post->setTitleImage(UploadedFile::fake()->image('testpic.png'));

        $post->publish();
        $post->publish(Carbon::today()->addWeek());

        $expected = [
            'id'                   => $post->id,
            'slug'                 => 'test-title',
            'title'                => 'test title',
            'intro'                => 'test intro',
            'description'          => 'test description',
            'body'                 => 'test body',
            'created_at'           => $post->created_at->format('d M Y'),
            'updated_at'           => $post->updated_at->format('d M Y'),
            'is_draft'             => false,
            'is_live'              => false,
            'publish_date_string'  => Carbon::today()->addWeek()->format('d F Y'),
            'publish_date_year'    => Carbon::today()->addWeek()->year,
            'publish_date_month'   => Carbon::today()->addWeek()->month - 1,
            'publish_date_day'     => Carbon::today()->addWeek()->day,
            'author'               => null,
            'first_published_on'   => Carbon::today()->format('d M Y'),
            'title_image_original' => $image->getUrl(),
            'title_image_banner'   => $image->getUrl('banner'),
            'title_image_web'      => $image->getUrl('web'),
            'title_image_thumb'    => $image->getUrl('thumb'),
        ];

        $this->assertEquals($expected, $post->asDataArrayFor('en'));
    }

    private function makePost($published, $is_live, $overrides = [])
    {
        $default = [
            'title'       => ['en' => 'test title', 'fr' => 'test titley'],
            'intro'       => ['en' => 'test intro', 'fr' => 'test introy'],
            'description' => ['en' => 'test description', 'fr' => 'test descriptiony'],
            'body'        => ['en' => 'test body', 'fr' => 'test bodyy']
        ];
        $post = Post::create(array_merge($default, $overrides));

        if($published) {
            $post->publish($published);
        }

        if(!$is_live) {
            $post->retract();
        }

        return $post;
    }




}