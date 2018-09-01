<?php


namespace Dymantic\MultilingualPosts;


use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Translatable\HasTranslations;

class Post extends Model
{
    use HasTranslations, Sluggable;

    protected $table = 'multilingual_posts';

    protected $fillable = ['title', 'intro', 'description', 'body'];

    public $translatable = ['title', 'intro', 'description', 'body'];

    protected $dates = ['published_on'];

    protected $casts = ['is_draft' => 'boolean'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'onUpdate' => ! $this->published_on
            ]
        ];
    }

    public function publish()
    {
        if(! $this->published_on) {
            $this->published_on = Carbon::now();
        }

        $this->is_draft = false;
        $this->save();
    }
}