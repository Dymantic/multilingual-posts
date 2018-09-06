<?php


namespace Dymantic\MultilingualPosts;


use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Spatie\Translatable\HasTranslations;

class Post extends Model implements HasMedia
{
    use HasTranslations, Sluggable, HasMediaTrait;

    const TITLE_IMAGES = 'title-images';
    const BODY_IMAGES = 'body-images';

    protected $table = 'multilingual_posts';

    protected $fillable = ['title', 'intro', 'description', 'body'];

    public $translatable = ['title', 'intro', 'description', 'body'];

    protected $dates = ['published_on'];

    protected $casts = ['is_draft' => 'boolean'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source'   => 'title',
                'onUpdate' => !$this->published_on
            ]
        ];
    }

    public function publish()
    {
        if (!$this->hasBeenPublished()) {
            $this->published_on = Carbon::now();
        }

        $this->is_draft = false;
        $this->save();
    }

    public function hasBeenPublished()
    {
        return !is_null($this->published_on);
    }

    public function retract()
    {
        $this->is_draft = true;
        $this->save();
    }

    public function titleImage($conversion = '')
    {
        $image = $this->getFirstMedia(static::TITLE_IMAGES);

        if(! $image) {
            return null;
        }

        return $image->getUrl($conversion);
    }

    public function setTitleImage($file)
    {
        $this->clearTitleImage();

        return $this->addMedia($file)
                    ->preservingOriginal()
                    ->toMediaCollection(static::TITLE_IMAGES);
    }

    public function clearTitleImage()
    {
        $this->clearMediaCollection(static::TITLE_IMAGES);
    }

    public function attachImage($file)
    {
        return $this->addMedia($file)
                    ->preservingOriginal()
                    ->toMediaCollection(static::BODY_IMAGES);
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('banner')
             ->fit(Manipulations::FIT_MAX, 1400, 1000)
             ->optimize()
             ->performOnCollections(static::TITLE_IMAGES);

        $this->addMediaConversion('web')
             ->fit(Manipulations::FIT_MAX, 800, 600)
             ->optimize()
             ->performOnCollections(static::TITLE_IMAGES, static::BODY_IMAGES);

        $this->addMediaConversion('thumb')
             ->fit(Manipulations::FIT_MAX, 400, 300)
             ->optimize()
             ->performOnCollections(static::TITLE_IMAGES, static::BODY_IMAGES);
    }

}