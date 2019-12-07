<?php


namespace Dymantic\MultilingualPosts;


use Spatie\MediaLibrary\Models\Media;

class MediaLibraryMediaBroker implements MediaBroker
{

    public function setTitleImage($post, $file): Image
    {
        $mediaModel = $this->getMediaModel($post->id);
        $mediaModel->clearMediaCollection(Post::TITLE_IMAGES);

        $spatie_image = $mediaModel->addMedia($file)->toMediaCollection(Post::TITLE_IMAGES);

        return new Image($spatie_image->getUrl(), $this->getImageConversions($spatie_image));
    }

    private function getImageConversions(Media $image)
    {
        return $image
            ->fresh()
            ->getGeneratedConversions()
            ->flatMap(function($exists, $name) use ($image) {
                return [$name => $image->getUrl($name)];
            })->all();
    }

    public function titleImage($post): Image
    {
        $mediaModel = $this->getMediaModel($post->id);
        $image = $mediaModel->getFirstMedia(Post::TITLE_IMAGES);

        if(!$image) {
            return new Image();
        }

        return new Image($image->getUrl(), $this->getImageConversions($image));
    }

    public function attachImage($post, $file): Image
    {
        $mediaModel = $this->getMediaModel($post->id);

        $image = $mediaModel->addMedia($file)->toMediaCollection(Post::BODY_IMAGES);

        return new Image($image->getUrl(), $this->getImageConversions($image));
    }

    private function getMediaModel($post_id)
    {
        return MediaModel::firstOrCreate(['post_id' => $post_id]);
    }
}