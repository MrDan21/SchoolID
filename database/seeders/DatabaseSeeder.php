<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (!User::where('email', 'admin@admin.com')->exists()) {
            User::factory()->create([
                'name' => 'Super Administrador',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
            ]);
        }

        $students = Student::factory(50)->create();

        foreach ($students as $student) {
            if ($student->is_active) {
                $count = rand(2, 5);
                
                for ($i = 0; $i < $count; $i++) {
                    Attendance::factory()->create([
                        'student_id' => $student->id
                    ]);
                }
            }
        }
    }
}
