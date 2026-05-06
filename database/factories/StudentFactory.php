<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        $fakeEmbedding = array_map(function () {
            return fake()->randomFloat(6, -1, 1);
        }, array_fill(0, 512, null));

        return [
            'name' => fake()->name(),
            'matricula' => fake()->unique()->numerify('######'),
            'is_active' => fake()->boolean(90),
            'photo_path' => 'https://i.pravatar.cc/150?u=' . fake()->uuid(),
            'face_embedding' => $fakeEmbedding,
        ];
    }
}
