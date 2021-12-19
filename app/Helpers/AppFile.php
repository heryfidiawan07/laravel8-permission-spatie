<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class AppFile
{
    public static function upload(string $disk, $file)
    {
        // $fileEx   = $file->extension();
        $fileEx = $file->getClientOriginalExtension();
        $filename = sha1(date('YmdHis')) . '.' . $fileEx;
        $path     = Storage::disk($disk)->putFileAs('', $file, $filename);

        return $path;
    }

    public static function url(string $disk, $filename)
    {
        if (Storage::disk($disk)->exists($filename)) {
            return Storage::url($disk . '/' . $filename);
        }
        return false;
    }

    public static function path(string $disk, $filename)
    {
        if (Storage::disk($disk)->exists($filename)) {
            return Storage::disk($disk)->path($filename);
        }
        return false;
    }

    public static function download(string $disk, $filename)
    {   
        if (Storage::disk($disk)->exists($filename)) {
            return Storage::disk($disk)->download($filename);
        }
        return false;
    }

    public static function delete(string $disk, $filename)
    {   
        if ( Storage::disk($disk)->exists($filename) ) {
            Storage::disk($disk)->delete($filename);
        }
        return true;
    }

    public static function mkdirIfNotExist($directory)
    {
        if (!Storage::directories($directory)) {
            Storage::makeDirectory($directory);
        }
        return true;
    }    
}