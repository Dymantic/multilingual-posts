<?php


namespace Dymantic\MultilingualPosts;


class TestMediaBroker implements MediaBroker
{
    public function setTitleImage($post, $file): Image {
        return new Image('test-image-from-test-broker.png', [
            'thumb' => 'test-image-from-test-broker.png',
            'web' => 'test-image-from-test-broker.png',
            'banner' => 'test-image-from-test-broker.png',
        ]);
    }

    public function titleImage($post) : Image {
        return new Image('test-image-from-test-broker.png', [
            'thumb' => 'test-image-from-test-broker.png',
            'web' => 'test-image-from-test-broker.png',
            'banner' => 'test-image-from-test-broker.png',
        ]);
    }

    public function attachImage($post, $file): Image {
        return new Image('test-image-from-test-broker.png', [
            'thumb' => 'test-image-from-test-broker.png',
            'web' => 'test-image-from-test-broker.png',
            'banner' => 'test-image-from-test-broker.png',
        ]);
    }

}