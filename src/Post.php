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

    protected $dates = ['publish_date', 'first_published_on'];

    protected $casts = ['is_draft' => 'boolean'];

    public static function create($post_attributes)
    {
        return PostFactory::make($post_attributes);
    }

    public function safeUpdate($post_attributes)
    {
        if(array_key_exists('title', $post_attributes) && empty($post_attributes['title'])) {
            throw new InvalidAttributesException('the title attribute cannot be empty');
        }

        $translated = collect($post_attributes)
            ->flatMap(function($value, $field) {
                if(in_array($field, $this->translatable) && is_string($value)) {
                    return [$field => [app()->getLocale() => $value]];
                }
                return [$field => $value];
            })->all();

        $this->update($translated);

        if(array_key_exists('category_id', $post_attributes)) {
            $this->setCategories(Category::find($post_attributes['category_id']));
        }
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source'   => 'title',
                'onUpdate' => !$this->hasBeenPublished()
            ]
        ];
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'multilingual_category_post');
    }

    public function addCategory($category)
    {
        $this->categories()->attach($category);
    }

    public function removeCategory($category)
    {
        $this->categories()->detach($category);
    }

    public function setCategories($categories)
    {
        $ids = $categories ? $categories->pluck('id') : [];
        $this->categories()->sync($ids);
    }

    public function publish(Carbon $date = null)
    {
        if (is_null($date)) {
            $date = Carbon::today();
        }
        if (!$this->hasBeenPublished()) {
            $this->first_published_on = $date;
        }

        $this->is_draft = false;
        $this->publish_date = $date;
        $this->save();
    }

    public function hasBeenPublished()
    {
        if (is_null($this->first_published_on)) {
            return false;
        }

        return $this->first_published_on->startOfDay()->isPast();
    }

    public function isLive()
    {
        if ($this->is_draft || is_null($this->publish_date)) {
            return false;
        }

        return $this->publish_date->startOfDay()->lte(Carbon::today());
    }

    public function retract()
    {
        if (!$this->hasBeenPublished()) {
            $this->first_published_on = null;
        }
        $this->is_draft = true;
        $this->publish_date = null;
        $this->save();
    }

    public function titleImage($conversion = '')
    {
        $image = $this->getFirstMedia(static::TITLE_IMAGES);

        if (!$image) {
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