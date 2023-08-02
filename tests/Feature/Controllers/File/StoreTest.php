<?php

namespace Tests\Feature\Controllers\File;

use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_a_file_with_original_name():void
    {
        $user = User::factory()->create();

        Storage::fake();

        $uploadedFile = UploadedFile::fake()->create('file.js', 1024);
        $name = $uploadedFile->getClientOriginalName();

        $response = $this->actingAs($user)
            ->post('/files', [
                'file' => $uploadedFile
            ]);

        $response->assertStatus(201);
        
        $this->assertDatabaseHas('files', [
            'name' => $name
        ]);
        
        $file = File::where('name', $name)->first();

        Storage::assertExists($file->path);
    }
}
