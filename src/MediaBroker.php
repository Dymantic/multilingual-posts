<?php


namespace Dymantic\MultilingualPosts;


interface MediaBroker
{
    public function setTitleImage($post, $file): Image;

    public function titleImage($post) : Image;

    public function attachImage($post, $file): Image;
}