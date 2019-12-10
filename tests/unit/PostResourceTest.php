<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\Category;
use Dymantic\MultilingualPosts\MediaLibraryMediaBroker;
use Dymantic\MultilingualPosts\Post;
use Dymantic\MultilingualPosts\PostResource;
use Dymantic\MultilingualPosts\Tests\ComparesResources;
use Dymantic\MultilingualPosts\Tests\TestCase;
use Dymantic\SmlMediaBroker\SmlMediaBroker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;

class PostResourceTest extends TestCase
{
    use ComparesResources;

    public function setUp(): void
    {
        parent::setUp();

        config(['multilingual-posts.media-broker' => SmlMediaBroker::class]);
    }

    /**
     * @test
     */
    public function a_post_resource_has_the_expected_data_points()
    {
        $post = Post::create([
            'title'       => ['en' => 'test title', 'fr' => 'test titley'],
            'intro'       => ['en' => 'test intro', 'fr' => 'test introy'],
            'description' => ['en' => 'test description', 'fr' => 'test descriptiony'],
            'body'        => ['en' => 'test body', 'fr' => 'test bodyy']
        ]);

        $categoryA = Category::create([
            'title'       => ['en' => 'test title A', 'fr' => 'test titley A'],
            'intro'       => ['en' => 'test intro A', 'fr' => 'test introy A'],
            'description' => ['en' => 'test description A', 'fr' => 'test descriptiony A']
        ]);

        $categoryB = Category::create([
            'title'       => ['en' => 'test title B', 'fr' => 'test titley B'],
            'intro'       => ['en' => 'test intro B', 'fr' => 'test introy B'],
            'description' => ['en' => 'test description B', 'fr' => 'test descriptiony B']
        ]);

        $post->setCategories(collect([$categoryA, $categoryB]));

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
            'categories'           => [
                [
                    'id'          => $categoryA->id,
                    'slug'        => 'test-title-a',
                    'title'       => ['en' => 'test title A', 'fr' => 'test titley A'],
                    'intro'       => ['en' => 'test intro A', 'fr' => 'test introy A'],
                    'description' => ['en' => 'test description A', 'fr' => 'test descriptiony A']
                ],
                [
                    'id'          => $categoryB->id,
                    'slug'        => 'test-title-b',
                    'title'       => ['en' => 'test title B', 'fr' => 'test titley B'],
                    'intro'       => ['en' => 'test intro B', 'fr' => 'test introy B'],
                    'description' => ['en' => 'test description B', 'fr' => 'test descriptiony B']
                ]
            ]
        ];

        $this->assertEquals($expected, $this->getResourceResponseData(new PostResource($post->fresh())));
    }

    /**
     *@test
     */
    public function a_resource_has_at_least_the_default_locale_translation_evn_when_empty()
    {
        $post = Post::create([
            'title'       => ['en' => 'test title'],
        ]);

        $categoryA = Category::create([
            'title'       => ['en' => 'test title A', 'fr' => 'test titley A'],
            'intro'       => ['en' => 'test intro A', 'fr' => 'test introy A'],
            'description' => ['en' => 'test description A', 'fr' => 'test descriptiony A']
        ]);

        $categoryB = Category::create([
            'title'       => ['en' => 'test title B', 'fr' => 'test titley B'],
            'intro'       => ['en' => 'test intro B', 'fr' => 'test introy B'],
            'description' => ['en' => 'test description B', 'fr' => 'test descriptiony B']
        ]);

        $post->setCategories(collect([$categoryA, $categoryB]));

        $image = $post->setTitleImage(UploadedFile::fake()->image('testpic.png'));

        $post->publish();
        $post->publish(Carbon::today()->addWeek());

        $expected = [
            'id'                   => $post->id,
            'slug'                 => 'test-title',
            'title'                => ['en' => 'test title'],
            'intro'                => ['en' => ''],
            'description'          => ['en' => ''],
            'body'                 => ['en' => ''],
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
            'categories'           => [
                [
                    'id'          => $categoryA->id,
                    'slug'        => 'test-title-a',
                    'title'       => ['en' => 'test title A', 'fr' => 'test titley A'],
                    'intro'       => ['en' => 'test intro A', 'fr' => 'test introy A'],
                    'description' => ['en' => 'test description A', 'fr' => 'test descriptiony A']
                ],
                [
                    'id'          => $categoryB->id,
                    'slug'        => 'test-title-b',
                    'title'       => ['en' => 'test title B', 'fr' => 'test titley B'],
                    'intro'       => ['en' => 'test intro B', 'fr' => 'test introy B'],
                    'description' => ['en' => 'test description B', 'fr' => 'test descriptiony B']
                ]
            ]
        ];

        $this->assertEquals($expected, $this->getResourceResponseData(new PostResource($post->fresh())));
    }


}