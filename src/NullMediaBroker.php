<?php


namespace Dymantic\MultilingualPosts;


class NullMediaBroker implements MediaBroker
{

    public function setTitleImage($post, $file): Image
    {
        return new Image();
    }

    public function titleImage($post): Image
    {
        return new Image();
    }

    public function attachImage($post, $file): Image
    {
        return new Image();
    }
}