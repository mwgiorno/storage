<?php

namespace App\Http\Controllers;

use App\Http\Requests\File\CreateRequest;
use App\Http\Requests\File\UpdateRequest;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {
        return view('files.index');
    }

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
            'extension' => $request->file('file')->extension()
        ]);
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

        $file->delete();
    }
}
