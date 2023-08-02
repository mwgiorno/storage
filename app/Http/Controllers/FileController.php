<?php

namespace App\Http\Controllers;

use App\Http\Requests\File\CreateRequest;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function store(CreateRequest $request)
    {
        $userId = $request->user()->id;
        $path = $request->file('file')
            ->store(
                "$userId",
                'files'
            );

        $name = $request->input(
            'name',
            $request->file('file')->getClientOriginalName()
        );

        $file = File::create([
            'user_id' => $userId,
            'name' => $name,
            'path' => $path,
            'size' => $request->file('file')->getSize(),
            'extension' => $request->file('file')->getExtension()
        ]);
    }

    public function destroy(File $file)
    {
        Storage::disk('files')->delete($file->path);

        $file->delete();
    }
}
