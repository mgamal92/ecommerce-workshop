<?php

namespace App\Traits;

use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\FileAdder;


trait InteractsWithHashedMedia
{
    use InteractsWithMedia {
        InteractsWithMedia::addMedia as parentAddMedia;
    }
    /*
    this is to get a hashed version of the image, so the image should be saved hashed (keeping the original name DB)
    */
    public function addMedia($file): FileAdder
    {
        return $this->parentAddMedia($file)
            ->usingFileName($file->hashName());
    }
}
