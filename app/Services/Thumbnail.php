<?php

namespace App\Services;

use Intervention\Image\Facades\Image;

class Thumbnail
{
    public static function make(
        $file,
        int $width = 100,
        int $height = 100
    ) {
        $image = Image::make($file);

        if($image->height() > $image->width()) {
            $image->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else {
            $image->resize(null, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $image->crop($width, $height);
            
        $image->save(null, null, 'jpg');
    }
}