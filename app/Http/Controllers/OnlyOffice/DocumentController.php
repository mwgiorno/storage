<?php

namespace App\Http\Controllers\OnlyOffice;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Services\OnlyOffice\FormatManager;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function save(Request $request)
    {
        if($request->status == 2) {
            $file = File::find($request->key);

            $oldPath = $file->path;
            $url = Str::replace('localhost:8080', 'document-server', $request->url);
            $contents = file_get_contents($url);

            $path = "{$file->user->id}/" . Str::uuid() . ".{$request->filetype}";
            Storage::disk('files')->put($path, $contents);

            $file->path = $path;
            $file->save();

            Storage::disk('files')->delete($oldPath);
        }

        return "{\"error\":0}";
    }

    public function download(Request $request, FormatManager $formats)
    {
        $file = File::find($request->input('file'));
        
        $config = [
            "async" => false,
            "filetype" => $file->extension,
            "key" => strval($file->id),
            "outputtype" => "pdf",
            "title" => $file->name,
            "url" => Storage::disk('files')->url($file->path)
        ];

        $token = JWT::encode($config, env('JWT_SECRET'), env('JWT_ALGORITHM'));
        $content = json_encode($config);

        $response = Http::acceptJson()
            ->withToken($token)
            ->post(env('DOCUMENT_SERVER_CONVERT_SERVICE_URL'), $content);

        $convertedFileInfo = json_decode($response->body());
        
        if(property_exists($convertedFileInfo, 'fileUrl')) {
            $response = Http::get($convertedFileInfo->fileUrl);
            $contents = $response->body();

            return response()
                ->streamDownload(function() use($contents) {
                    echo $contents;
                }, Str::after($convertedFileInfo->fileUrl, 'filename='));
        }  

        return abort(500, 'An error ocurred while trying to convert the file to PDF.');
    }
}
