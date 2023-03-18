<?php


namespace Dymantic\MultilingualPosts;


use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Image\Manipulations;
use Spatie\Translatable\HasTranslations;

class Post extends Model
{
    use HasTranslations, Sluggable;

    const TITLE_IMAGES = 'title-images';
    const BODY_IMAGES = 'body-images';

    protected $table = 'multilingual_posts';

    protected $fillable = ['title', 'intro', 'description', 'body'];

    public $translatable = ['title', 'intro', 'description', 'body'];

    protected $dates = [];

    protected $casts = [
        'is_draft' => 'boolean',
        'publish_date' => 'datetime',
        'first_published_on' => 'datetime',
    ];

    public static function create($post_attributes)
    {
        $attributes = new TranslatableAttributes($post_attributes);

        if ($attributes->isMissingTitle() || $attributes->hasEmptyTitle()) {
            throw new InvalidAttributesException('a title is required to create a post');
        }
        $model = new static();

        $post = $model->query()->create($attributes->translated($model->translatable, true));

        if ($attributes->has('category_id')) {
            $post->setCategories(Category::find($attributes->category_id));
        }

        return $post;
    }

    public function safeUpdate($post_attributes)
    {
        $attributes = new TranslatableAttributes($post_attributes);
        if ($attributes->hasEmptyTitle()) {
            throw new InvalidAttributesException('the title attribute cannot be empty');
        }

        $this->update($attributes->translated((new static())->translatable));

        if ($attributes->has('category_id')) {
            $this->setCategories(Category::find($attributes->category_id));
        }
    }

    public function scopeLive($query)
    {
        return $query->where('is_draft', false)->where('publish_date', '<=', Carbon::today()->endOfDay());
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

    public function next()
    {
        if (!$this->isLive()) {
            return null;
        }

        return static::live()
                     ->where('publish_date', '>', $this->publish_date)
                     ->orderBy('publish_date')
                     ->first();
    }

    public function prev()
    {
        if (!$this->isLive()) {
            return null;
        }

        return static::live()
                     ->where('publish_date', '<', $this->publish_date)
                     ->orderBy('publish_date', 'desc')
                     ->first();
    }

    public function titleImage()
    {
        return app(MediaBroker::class)->titleImage($this);
    }

    public function setTitleImage($file)
    {
        return app(MediaBroker::class)->setTitleImage($this, $file);
    }

    public function attachImage($file)
    {
        return app(MediaBroker::class)->attachImage($this, $file);
    }

    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->firstOrFail();
    }

    public function asDataArray()
    {
        return PostDataPresenter::dataArray($this);
    }

    public function asDataArrayFor($lang)
    {
        return PostDataPresenter::dataArrayFor($lang, $this);
    }

}