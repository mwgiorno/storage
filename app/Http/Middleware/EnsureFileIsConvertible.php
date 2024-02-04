<?php

namespace App\Http\Middleware;

use App\Models\File;
use App\Services\OnlyOffice\FormatManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class EnsureFileIsConvertible
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $format): Response
    {
        $formats = App::make(FormatManager::class);
        $file = File::findOrFail($request->file);

        if(!$formats->convertibleTo($file->extension, $format)) {
            return redirect('files');
        }

        return $next($request);
    }
}
