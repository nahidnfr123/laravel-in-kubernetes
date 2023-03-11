<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function moveFile($url, $directory): string
    {
        $linkWithoutParameters = explode('?', $url);
        $link = $linkWithoutParameters[0];
        $link = 'tmp/' . explode('tmp/', $link)[1];
        $fileBaseName = basename($link);
        $target = $directory . '/' . $fileBaseName;
        Storage::move($link, $target);
        return $target;
    }
}
