<?php


namespace Dymantic\MultilingualPosts\Tests;


trait ComparesResources
{
    protected function getResourceResponseData($resource) {
        return json_decode($resource->response()->getContent(), true);
    }
}