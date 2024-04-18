<?php

namespace App\Helpers;

trait ImageManager
{

    public function uploadImage($path, $file)
    {
        if ($file) {
            $file_name = $file->hashName();
            $file_type = $file->getClientOriginalExtension();
            $file->move($path, $file_name);
            $filePath = $path;
            return $file = [
                'fileName' => $file_name,
                'fileType' => $file_type,
                'filePath' => $filePath,
                'fileSize' => '100kb'
            ];
        }
    }

    public function fileSize($file, $precision = 2)
    {
        $size = $file->getSize();
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array('bytes', ' KB', ' MB', ' GB', ' TB');
            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        }
        return $size;
    }
    public function bannerEmptyImage()
    {
        return [
            'id' => 1,
            'url' => asset('/assets/media/svg/avatars/blank.svg')
        ];
    }

    public function categoryEmptyImage()
    {
        return [
            'id' => 1,
            'url' => asset('/assets/media/svg/avatars/blank.svg')
        ];
    }
    public function serviceEmptyImage()
    {
        return [
            'id' => 1,
            'url' => asset('/assets/media/svg/avatars/blank.svg')
        ];
    }
}
