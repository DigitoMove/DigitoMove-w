<?php
namespace App\Support;

class LocalAsset
{
    /** Serve checked-in assets without requiring a Mix dev server or manifest. */
    public static function url(string $path): string
    {
        $path = ltrim($path, '/');
        $file = public_path($path);
        return asset($path).(is_file($file) ? '?v='.filemtime($file) : '');
    }
}
