<?php

namespace App\Http\Controllers;

use App\Http\Requests\File\CreateRequest;
use App\Http\Requests\File\UpdateRequest;
use App\Models\File;
use App\Services\Thumbnail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Inertia\Inertia;

class FileController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Files/Index', [
            'files' => $request->user()->files
        ]);
    }

    public function create()
    {
        return Inertia::render('Files/CreateForm');
    }

    public function store(CreateRequest $request)
    {
        $userId = $request->user()->id;
        $path = $request->file('file')
            ->store(
                "$userId",
                'files'
            );
        $thumbnailUrl = null;
        $thumbnailPath = null;
        
        if(exif_imagetype($request->file('file'))) {
            $thumbnailPath = $request->file('file')
                ->store(
                    "$userId",
                    'thumbnails'
                );

            Thumbnail::make(
                Storage::disk('thumbnails')->path($thumbnailPath)
            );

            $thumbnailUrl = Storage::disk('thumbnails')->url($thumbnailPath);
        }

        $name = $request->filled('name') 
            ? $request->name 
            : $request->file('file')->getClientOriginalName();

        $file = File::create([
            'user_id' => $userId,
            'name' => $name,
            'path' => $path,
            'size' => $request->file('file')->getSize(),
            'extension' => $request->file('file')->extension(),
            'thumbnail_url' => $thumbnailUrl,
            'thumbnail_path' => $thumbnailPath
        ]);

        return redirect()->route('files');
    }

    public function update(File $file, UpdateRequest $request)
    {
        $name = $file->name;
        $path = $file->path;
        $size = $file->size;
        $extension = $file->extension;

        if($request->hasFile('file')) {
            Storage::disk('files')->delete($file->path);

            $path = $request->file('file')
            ->store(
                "{$request->user()->id}",
                'files'
            );

            $name = $request->input(
                'name',
                $request->file('file')->getClientOriginalName()
            );

            $size = $request->file('file')->getSize();
            $extension = $request->file('file')->extension();
        }

        $file->fill([
            'name' => $name,
            'path' => $path,
            'size' => $size,
            'extension' => $extension
        ]);
        
        $file->save();
    }

    public function destroy(File $file)
    {
        Storage::disk('files')->delete($file->path);
        
        if($file->thumbnail_path) {
            Storage::disk('thumbnails')->delete($file->thumbnail_path);
        }

        $file->delete();

        return redirect()->route('files');
    }

    public function download(File $file)
    {
        if(Storage::disk('files')->exists($file->path)) {
            return Storage::disk('files')->download($file->path, $file->name);
        }
        return abort(404, 'File Not Found');
    }
}
