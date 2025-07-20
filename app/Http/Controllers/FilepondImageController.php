<?php

namespace App\Http\Controllers;

use App\Support\Filepond\Image;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FilepondImageController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'filepond' => 'required|file|max:5120'
        ]);

        return Image::createFromUploadedFile($request->file('filepond'))->id;
    }

    public function remove(Request $request)
    {
        Image::remove($request->getContent());

        return response()->noContent();
    }

    public function load(Request $request)
    {
        try {
            $image = Image::from($request->input('path'));
        } catch (Exception $exception) {
            abort(404);
        }

        // Возвращаем файл с правильными заголовками
        return response($image->get(), 200, [
            'Content-Type' => $image->getType(),
            'Content-Disposition' => 'inline; filename="' . $image->getName() . '"',
        ]);
    }
}
