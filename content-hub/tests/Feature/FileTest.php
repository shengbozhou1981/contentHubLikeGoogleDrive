<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class FileTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testIndex()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);
        $file = File::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/api/files');

        $response->assertStatus(200);
        $response->assertJson(['files' => [$file->toArray()]]);
    }

    public function testStore()
    {
        Storage::fake('local');

        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $file = UploadedFile::fake()->create('document.pdf');

        $response = $this->actingAs($user)->post('/api/files', [
            'name' => 'Test File',
            'file' => $file,
            'type' => 'pdf',
        ]);
        // if ($response->status() != 201) {
        //     dd($response->getContent());
        // }
        
        $response->assertStatus(201);
    }

    public function testUpdate()
    {
        Storage::fake('local');

        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $file = File::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put('/api/files/' . $file->id, [
            'name' => 'Updated File',
            'type' => 'pdf',
        ]);
        // if ($response->status() != 201) {
        //     dd($response->getContent());
        // }
        $response->assertStatus(200);
    }

    public function testDestroy()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $file = File::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete('/api/files/' . $file->id);

        $response->assertStatus(204);
    }
}