<?php

namespace Database\Seeders;

use App\Models\TrainingCenter;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TeacherTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        $trainingCenters = TrainingCenter::pluck('id')->toArray();

        for ($i = 1; $i <= 30; $i++) {
            $fullName = $faker->name();

            Teacher::create([
                'training_center_id' => $faker->randomElement($trainingCenters),
                'full_name' => $fullName,
                'email' => $faker->unique()->safeEmail(),
                'phone' => $faker->phoneNumber(),
                'title' => $faker->randomElement(['Giảng viên', 'Trợ giảng', 'Chuyên gia', 'Giáo sư']),
                'bio' => $faker->paragraph(3),
                'avatar_path' => $faker->imageUrl(300, 300, 'people', true, 'Teacher'),
                'is_active' => $faker->boolean(80),
                'skills' => $faker->randomElements([
                    'Laravel', 'React', 'Python', 'AI', 'UI/UX', 'Docker', 'Flutter', 'MySQL'
                ], rand(2, 5)),
            ]);
        }
    }
}
