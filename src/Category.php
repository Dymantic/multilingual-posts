<?php


namespace Dymantic\MultilingualPosts;


use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations, Sluggable;

    protected $table = 'multilingual_categories';

    protected $fillable = ['title', 'intro', 'description'];

    public $translatable = ['title', 'intro', 'description'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}