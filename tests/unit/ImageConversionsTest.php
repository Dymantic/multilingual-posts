<?php


namespace Dymantic\MultilingualPosts\Tests\unit;


use Dymantic\MultilingualPosts\ImageConversions;
use Dymantic\MultilingualPosts\PostImageConversion;
use Dymantic\MultilingualPosts\Tests\TestCase;

class ImageConversionsTest extends TestCase
{
    /**
     *@test
     */
    public function it_presents_the_required_conversions()
    {
        config(['multilingual-posts.conversions' => [
            ['name' => 'web', 'manipulation' => 'crop', 'width' => 1600, 'height' => 1000, 'title' => false, 'post' => true],
            ['name' => 'thumb', 'manipulation' => 'fit', 'width' => 400, 'height' => 400, 'title' => false, 'post' => true],
        ]]);

        $required_conversions = ImageConversions::configured();

        $this->assertCount(2, $required_conversions);
        $required_conversions->each(function($conversion) {
            $this->assertInstanceOf(PostImageConversion::class, $conversion);
        });

        $this->assertNotNull($required_conversions->first(function($conversion) {
            return $conversion->name === 'web';
        }));

        $this->assertNotNull($required_conversions->first(function($conversion) {
            return $conversion->name === 'thumb';
        }));
    }

    /**
     *@test
     */
    public function it_uses_default_conversions_if_none_in_config()
    {
        $required_conversions = ImageConversions::configured();

        $this->assertCount(3, $required_conversions);
        $required_conversions->each(function($conversion) {
            $this->assertInstanceOf(PostImageConversion::class, $conversion);
        });

        $this->assertNotNull($required_conversions->first(function($conversion) {
            return $conversion->name === 'web';
        }));

        $this->assertNotNull($required_conversions->first(function($conversion) {
            return $conversion->name === 'thumb';
        }));

        $this->assertNotNull($required_conversions->first(function($conversion) {
            return $conversion->name === 'banner';
        }));
    }
}