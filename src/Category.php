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

    public static function create($category_attributes)
    {
        if(!array_key_exists('title', $category_attributes)) {
            throw new InvalidAttributesException('a title is required to create a category');
        }

        $valid_titles = collect($category_attributes['title'])->filter(function($title, $lang) {
            return !empty($title);
        });

        if($valid_titles->count() < 1) {
            throw new InvalidAttributesException('a title is required to create a category');
        }
        return (new static())->query()->create($category_attributes);
    }

    public function safeUpdate($update_attributes)
    {
        if(array_key_exists('title', $update_attributes)) {
            $valid_titles = collect($update_attributes['title'])->filter(function($title, $lang) {
                return !empty($title);
            });

            if($valid_titles->count() === 0) {
                throw new InvalidAttributesException('a title is required for the category');
            }
        }
        $translations = collect($update_attributes)->flatMap(function($value, $field) {
            if(is_string($value)) {
                return [$field => [app()->getLocale() => $value]];
            }

            return [$field => $value];
        })->all();
        $this->update($translations);
    }
}