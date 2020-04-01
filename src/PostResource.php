<?php


namespace Dymantic\MultilingualPosts;



use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request)
    {
        $data = PostDataPresenter::dataArray($this);
        $data['categories'] = CategoryResource::collection($this->categories);
        return $data;
    }
}