<?php


namespace Dymantic\MultilingualPosts;


class TestMediaBroker implements MediaBroker
{
    public function setTitleImage($post, $file): Image {
        return new Image('test-image-from-test-broker.png', $this->getConversions());
    }

    public function titleImage($post) : Image {
        return new Image('test-image-from-test-broker.png', $this->getConversions());
    }

    public function attachImage($post, $file): Image {

        return new Image('test-image-from-test-broker.png', $this->getConversions());
    }

    private function getConversions()
    {
        return ImageConversions::configured()->flatMap(function($conversion) {
            return [$conversion->name => 'test-image-from-test-broker.png'];
        });
    }

}