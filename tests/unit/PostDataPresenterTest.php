<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\Post;
use Dymantic\MultilingualPosts\PostDataPresenter;
use Dymantic\MultilingualPosts\Tests\TestCase;
use Dymantic\SmlMediaBroker\SmlMediaBroker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;

class PostDataPresenterTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        config(['multilingual-posts.media-broker' => SmlMediaBroker::class]);
    }
    /**
     *@test
     */
    public function it_can_present_the_data_for_a_given_language()
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
            'title'                => 'test titley',
            'intro'                => 'test introy',
            'description'          => 'test descriptiony',
            'body'                 => 'test bodyy',
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

        $this->assertEquals($expected, PostDataPresenter::dataArrayFor('fr', $post));
    }

    /**
     *@test
     */
    public function it_has_correct_conversions_from_config()
    {
        config(['multilingual-posts.conversions' => [
            ['name' => 'web', 'manipulation' => 'fit', 'width' => 1600, 'height' => 1000, 'title' => true, 'post' => false],
            ['name' => 'banner', 'manipulation' => 'crop', 'width' => 2000, 'height' => 1000, 'title' => true, 'post' => false],
        ]]);

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
            'title'                => 'test titley',
            'intro'                => 'test introy',
            'description'          => 'test descriptiony',
            'body'                 => 'test bodyy',
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
        ];

        $this->assertEquals($expected, PostDataPresenter::dataArrayFor('fr', $post));
    }
}