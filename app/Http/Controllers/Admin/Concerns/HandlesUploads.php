<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

trait HandlesUploads
{
    protected function upload(?UploadedFile $file, string $folder): ?string
    {
        if (! $file) return null;
        $name = uniqid($folder.'-', true).'.'.$file->getClientOriginalExtension();
        File::ensureDirectoryExists(public_path('uploads/'.$folder));
        $file->move(public_path('uploads/'.$folder), $name);
        return 'uploads/'.$folder.'/'.$name;
    }
}
