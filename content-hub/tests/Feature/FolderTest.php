<?php

namespace Tests\Feature;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class FolderTest extends TestCase
{
    use RefreshDatabase;
    

    public function test_can_create_folder()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/folders', [
            'name' => 'Test Folder',
        ]);
        // if ($response->status() != 201) {
        //     dd($response->getContent());
        // }
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'folder' => [
                'id',
                'name',
                'user_id',
                'parent_id',
                'created_at',
                'updated_at',
            ],
        ]);

    }

    public function test_can_get_folders()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/folders');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'folders' => [
                '*' => [
                    'id',
                    'name',
                    'user_id',
                    'parent_id',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
    }

    public function test_can_get_folder()
    {
        $user = User::factory()->create();
        $folder = Folder::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->getJson("/api/folders/{$folder->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'folder' => [
                'id',
                'name',
                'user_id',
                'parent_id',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    public function test_can_update_folder()
    {
        $user = User::factory()->create();
        $folder = Folder::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->putJson("/api/folders/{$folder->id}", [
            'name' => 'Updated Folder',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'folder' => [
                'id',
                'name',
                'user_id',
                'parent_id',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    public function test_can_delete_folder()
    {
        $user = User::factory()->create();
        $folder = Folder::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/folders/{$folder->id}");

        $response->assertStatus(204);
    }
}