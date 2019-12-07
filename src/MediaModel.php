<?php


namespace Dymantic\MultilingualPosts;


use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class MediaModel extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $table = 'multilingual_posts_media_models';

    protected $fillable = ['post_id'];

    public function registerMediaConversions(Media $media = null)
    {
        $conversions = collect(config('multilingual-posts.conversions') ?? $this->defaultConversions());
        $conversions
            ->map(function ($conversion) {
                return new PostImageConversion($conversion);
            })
            ->each(function ($conversion) {
                $this->addConversion($conversion);
            });
    }

    private function addConversion(PostImageConversion $conversion)
    {
        if($conversion->optimize) {
            return $this->addMediaConversion($conversion->name)
                        ->fit($conversion->manipulation, $conversion->width, $conversion->height)
                        ->optimize()
                        ->performOnCollections(...$conversion->collections);
        }

        return $this->addMediaConversion($conversion->name)
                    ->fit($conversion->manipulation, $conversion->width, $conversion->height)
                    ->performOnCollections(...$conversion->collections);
    }

    private function defaultConversions()
    {
        return [
            ['name'         => 'thumb',
             'manipulation' => 'crop',
             'width'        => 400,
             'height'       => 300,
             'title'        => true,
             'post'         => true
            ],
            ['name'         => 'web',
             'manipulation' => 'fit',
             'width'        => 800,
             'height'       => 1800,
             'title'        => true,
             'post'         => true
            ],
            ['name'         => 'banner',
             'manipulation' => 'fit',
             'width'        => 1400,
             'height'       => 1000,
             'title'        => true,
             'post'         => false
            ],
        ];
    }
}