<?php


namespace Dymantic\MultilingualPosts;


use Illuminate\Support\Str;

class Image
{
    public $src;

    public $conversions;

    public function __construct($src = "", $conversions = [])
    {
        $this->src = $src;
        $this->conversions = $conversions;
    }

    public function getUrl($conversion = "")
    {
        if(!$conversion) {
            return $this->src;
        }
        return $this->conversions[$conversion] ?? null;
    }

    public function getFullUrl($conversion = "")
    {
        return (string) Str::of(config('app.url'))
            ->trim('/')
            ->append(Str::of($this->getUrl($conversion))->start('/'));
    }
}