<?php


namespace Dymantic\MultilingualPosts;


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
}