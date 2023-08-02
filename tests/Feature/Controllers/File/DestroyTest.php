<?php

namespace Tests\Feature\Controllers\File;

use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Testing\FileFactory;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_deleting_a_file():void
    {
        $user = User::factory()->create();

        Storage::fake('files');

        $fakeFile = (new FileFactory)->create('file.js', 1024);

        $file = File::create([
            'user_id' => $user->id,
            'name' => $fakeFile->getClientOriginalName(),
            'path' => $fakeFile->path(),
            'size' => $fakeFile->getSize(),
            'extension' => $fakeFile->extension()
        ]);

        $response = $this->actingAs($user)
            ->delete('/files/' . $file->id);

        $response->assertStatus(200);
        
        $this->assertModelMissing($file);

        Storage::disk('files')->assertMissing($file->path);
    }
}
