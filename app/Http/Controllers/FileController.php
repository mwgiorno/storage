<?php

namespace App\Http\Controllers;

use App\Http\Requests\File\CreateRequest;
use App\Http\Requests\File\UpdateRequest;
use App\Models\File;
use App\Services\OnlyOffice\FormatManager;
use App\Services\Thumbnail;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class FileController extends Controller
{
    public function index(Request $request, FormatManager $formats)
    {
        $files = $request->user()
            ->files()
            ->when($request->input('search'), function($query, $search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })->paginate(50)
            ->withQueryString();
        
        foreach($files as $file) {
            $file->viewable = $formats->viewableExtension($file->extension);
        }
        
        $total = $files->total();
        $count = (
                ($files->currentPage() - 1) * 
                $files->perPage()
            ) + $files->count();

        return Inertia::render('Files/Index', [
            'files' => $files,
            'total' => $total,
            'count' => $count,
        ]);
    }

    public function show(File $file, FormatManager $formats)
    {
        $config = [
            'document' => [
                'fileType' => $file->extension,
                'key' => strval($file->id),
                'title' => $file->name,
                'url' => Storage::disk('files')->url($file->path)
            ],
            'documentType' => $formats->getDocumentType($file->extension),
            'mode' => 'view'
        ];

        $config['token'] = JWT::encode($config, env('JWT_SECRET'), env('JWT_ALGORITHM'));

        return Inertia::render('Files/Show', [
            'file' => $file,
            'config' => $config,
            'serverUrl' => env('DOCUMENT_SERVER_URL')
        ]);
    }

    public function create()
    {
        return Inertia::render('Files/Create');
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

    public function edit(File $file)
    {
        $this->authorize('view', $file);

        return Inertia::render('Files/Edit', [
            'file' => $file
        ]);
    }

    public function update(File $file, UpdateRequest $request)
    {
        $this->authorize('update', $file);

        if($request->hasFile('file')) {
            Storage::disk('files')->delete($file->path);

            if($file->thumbnail_path) {
                Storage::disk('thumbnails')->delete($file->thumbnail_path);
                $file->thumbnail_path = null;
                $file->thumbnail_url = null;
            }

            $file->path = $request->file('file')
                ->store(
                    "{$request->user()->id}",
                    'files'
                );

            $file->name = $request->file('file')
                ->getClientOriginalName();

            $file->size = $request->file('file')->getSize();
            $file->extension = $request->file('file')->extension();

            if(exif_imagetype($request->file('file'))) {
                $file->thumbnail_path = $request->file('file')
                    ->store(
                        "{$request->user()->id}",
                        'thumbnails'
                    );
    
                Thumbnail::make(
                    Storage::disk('thumbnails')->path($file->thumbnail_path)
                );
    
                $file->thumbnail_url = Storage::disk('thumbnails')
                    ->url($file->thumbnail_path);
            }
        }

        $file->name = $request->input(
            'name',
            $file->name
        );
        
        $file->save();

        return to_route('files');
    }

    public function destroy(File $file)
    {
        $this->authorize('update', $file);

        Storage::disk('files')->delete($file->path);
        
        if($file->thumbnail_path) {
            Storage::disk('thumbnails')->delete($file->thumbnail_path);
        }

        $file->delete();

        return redirect()->route('files');
    }

    public function download(File $file)
    {
        $this->authorize('view', $file);
        
        if(Storage::disk('files')->exists($file->path)) {
            return Storage::disk('files')->download($file->path, $file->name);
        }
        return abort(404, 'File Not Found');
    }
}
