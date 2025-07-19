<?php

namespace App\Http\Controllers;

use App\Support\Image;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'filepond' => 'required|file|max:5120'
        ]);

        return Image::fromUploadedFile($request->file('filepond'))->path;
    }

    public function delete(Request $request)
    {
        Storage::disk('public')->delete($request->getContent());

        return response()->noContent();
    }

    public function load(Request $request)
    {
        try {
            $image = new Image($request->input('path'));
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
