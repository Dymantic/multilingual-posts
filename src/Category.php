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
        $attributes = new CategoryAttributes($category_attributes);

        if ($attributes->isMissingTitle() || $attributes->hasEmptyTitle()) {
            throw new InvalidAttributesException('a title is required to create a category');
        }

        return (new static())->query()->create($category_attributes);
    }

    public function safeUpdate($update_attributes)
    {
        $attributes = new CategoryAttributes($update_attributes);
        if ($attributes->hasEmptyTitle()) {
            throw new InvalidAttributesException('a title is required for the category');
        }

        $this->update($attributes->translated());
    }
}