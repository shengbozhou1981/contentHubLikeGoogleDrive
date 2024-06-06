<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\User;
use App\Models\Folder;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    protected $model = File::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'user_id' => User::factory()->create()->id,
            'folder_id' => Folder::factory()->create()->id,
            'type' => $this->faker->randomElement(['pdf','doc','docx','xls','xlsx','ppt','pptx','txt']),
            'path' => $this->faker->filePath(),
        ];
    }
}