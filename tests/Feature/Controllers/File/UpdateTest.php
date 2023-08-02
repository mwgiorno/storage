<?php

namespace Tests\Feature\Controllers\File;

use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Testing\FileFactory;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_replacing_a_file():void
    {
        $user = User::factory()->create();

        Storage::fake('files');

        $fakeFile = (new FileFactory)->create('file.js', 1024);
        $newFakeFile = (new FileFactory)->create('file2.js', 2048);

        $file = File::create([
            'user_id' => $user->id,
            'name' => $fakeFile->getClientOriginalName(),
            'path' => $fakeFile->path(),
            'size' => $fakeFile->getSize(),
            'extension' => $fakeFile->extension()
        ]);

        $response = $this->actingAs($user)
            ->patch('/files/' . $file->id, ['file' => $newFakeFile]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('files', [
            'name' => $newFakeFile->getClientOriginalName(),
            'size' => $newFakeFile->getSize(),
            'extension' => $newFakeFile->extension()
        ]);

        $file->refresh();

        Storage::disk('files')->assertMissing($fakeFile->path());
        Storage::disk('files')->assertExists($file->path);
    }
}
