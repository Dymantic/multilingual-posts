<?php


namespace Dymantic\MultilingualPosts;


use Dymantic\MultilingualPosts\Rules\RequiresLanguage;
use Illuminate\Routing\Controller;

class CategoriesController extends Controller
{
    public function store()
    {
        request()->validate([
            'title' => ['required', 'array', new RequiresLanguage]
        ]);

        return Category::create(request()->all(['title', 'intro', 'description']));
    }

    public function update($categoryId)
    {
        $category = Category::findOrFail($categoryId);

        $category->update(request()->only(['title', 'intro', 'description']));
    }

    public function destroy($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $category->delete();
    }
}