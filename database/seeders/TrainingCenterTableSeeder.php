<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TrainingCenter;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class TrainingCenterTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        for ($i = 1; $i <= 5; $i++) {
            $name = $faker->company();

            TrainingCenter::create([
                'name' => $name,
                'slug' => Str::slug($name) . '-' . $i,
                'code' => strtoupper(Str::random(5)),
                'email' => $faker->unique()->companyEmail(),
                'phone' => $faker->phoneNumber(),
                'website' => $faker->url(),
                'address_line1' => $faker->streetAddress(),
                'address_line2' => null,
                'city' => $faker->city(),
                'state' => null,
                'country' => 'Việt Nam',
                'postal_code' => $faker->postcode(),
                'timezone' => $faker->randomElement([
                    'Asia/Ho_Chi_Minh',
                    'Asia/Hanoi',
                    'Asia/Bangkok'
                ]),
                'meta' => [
                    'founded_year' => $faker->year(),
                    'students_count' => $faker->numberBetween(50, 500),
                    'rating' => $faker->randomFloat(1, 3, 5),
                ],
            ]);
        }
    }
}
