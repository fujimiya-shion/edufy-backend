<?php

namespace Database\Seeders;

use App\Enums\CourseLevel;
use App\Enums\CourseStatus;
use App\Enums\LessonStatus;
use App\Enums\MediaStatus;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Course;
use App\Models\CourseMedia;
use App\Models\CourseSchedule;
use App\Models\Lesson;
use App\Models\LessonMedia;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentLog;
use App\Models\Ribbon;
use App\Models\RibbonItem;
use App\Models\Teacher;
use App\Models\TrainingCenter;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EntryPointSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $faker = fake();

            $users = User::query()->limit(20)->get();

            if ($users->isEmpty()) {
                if (method_exists(User::class, 'factory')) {
                    $users = User::factory()->count(12)->create();
                } else {
                    $seedUsers = [];
                    for ($i = 1; $i <= 12; $i++) {
                        $seedUsers[] = [
                            'name' => "User {$i}",
                            'email' => "user{$i}@example.com",
                            'password' => Hash::make('password'),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    DB::table('users')->insert($seedUsers);
                    $users = User::query()->limit(12)->get();
                }
            }

            $courseLevel = $this->randomEnumValue(CourseLevel::class);
            $courseStatus = $this->randomEnumValue(CourseStatus::class);
            $lessonStatus = $this->randomEnumValue(LessonStatus::class);
            $mediaStatus = $this->randomEnumValue(MediaStatus::class);

            $timezones = ['Asia/Ho_Chi_Minh', 'Asia/Tokyo', 'Asia/Singapore', 'Asia/Bangkok'];

            $trainingCenters = collect();
            for ($i = 1; $i <= 4; $i++) {
                $name = $faker->company() . ' Academy';
                $trainingCenters->push(TrainingCenter::query()->create([
                    'name' => $name,
                    'slug' => Str::slug($name) . '-' . Str::lower(Str::random(6)),
                    'code' => 'TC' . str_pad((string)$i, 3, '0', STR_PAD_LEFT),
                    'email' => $faker->unique()->safeEmail(),
                    'phone' => $faker->numerify('0#########'),
                    'website' => $faker->url(),
                    'address_line1' => $faker->streetAddress(),
                    'address_line2' => 'Apt ' . $faker->numberBetween(1, 999) . ', ' . $faker->streetName(),
                    'city' => $faker->city(),
                    'state' => $faker->state(),
                    'country' => $faker->country(),
                    'postal_code' => $faker->postcode(),
                    'timezone' => $faker->randomElement($timezones),
                    'meta' => [
                        'brand_color' => $faker->hexColor(),
                        'hotline' => $faker->numerify('1900####'),
                        'rating' => round($faker->randomFloat(2, 4.1, 4.95), 2),
                    ],
                ]));
            }

            $teachersByCenter = [];
            foreach ($trainingCenters as $center) {
                $teachers = collect();
                $count = $faker->numberBetween(6, 10);

                for ($i = 1; $i <= $count; $i++) {
                    $fullName = $faker->name();
                    $teachers->push(Teacher::query()->create([
                        'training_center_id' => $center->id,
                        'full_name' => $fullName,
                        'slug' => Str::slug($fullName) . '-' . Str::lower(Str::random(6)),
                        'email' => $faker->unique()->safeEmail(),
                        'phone' => $faker->numerify('0#########'),
                        'title' => $faker->randomElement(['Senior Instructor', 'Lead Teacher', 'Mentor', 'Instructor']),
                        'bio' => $faker->paragraphs(2, true),
                        'avatar_path' => null,
                        'is_active' => $faker->boolean(90),
                        'skills' => $faker->randomElements(
                            ['IELTS', 'JLPT', 'Toeic', 'Math', 'Physics', 'Chemistry', 'Piano', 'Guitar', 'Coding', 'Design'],
                            $faker->numberBetween(3, 6)
                        ),
                    ]));
                }

                $teachersByCenter[$center->id] = $teachers;
            }

            $coursesByCenter = [];
            $allCourses = collect();

            foreach ($trainingCenters as $center) {
                $courses = collect();
                $count = $faker->numberBetween(10, 16);

                for ($i = 1; $i <= $count; $i++) {
                    $title = $faker->randomElement([
                        'Foundation',
                        'Intensive',
                        'Accelerator',
                        'Bootcamp',
                        'Masterclass',
                        'Crash Course',
                        'Exam Prep',
                        'Conversation',
                        'Project-based',
                        'Advanced'
                    ]) . ' ' . $faker->randomElement([
                        'English',
                        'Japanese',
                        'Korean',
                        'Python',
                        'Web Development',
                        'UI/UX',
                        'Data Analysis',
                        'Piano',
                        'Guitar',
                        'Math'
                    ]);

                    $start = Carbon::now($center->timezone)->addDays($faker->numberBetween(-10, 25))->startOfDay();
                    $end = (clone $start)->addWeeks($faker->numberBetween(4, 16));

                    $tuition = $faker->numberBetween(1500000, 18000000);

                    $course = Course::query()->create([
                        'training_center_id' => $center->id,
                        'title' => $title,
                        'slug' => Str::slug($title) . '-' . Str::lower(Str::random(6)),
                        'code' => strtoupper($faker->bothify('CRS-####-??')),
                        'short_description' => $faker->sentence(18),
                        'description' => $faker->paragraphs(4, true),
                        'level' => $this->pickEnumValue($courseLevel),
                        'status' => $this->pickEnumValue($courseStatus),
                        'duration_hours' => $faker->numberBetween(18, 120),
                        'capacity' => $faker->numberBetween(10, 40),
                        'tuition_fee' => $tuition,
                        'start_date' => $start->toDateString(),
                        'end_date' => $end->toDateString(),
                        'cover_image_path' => null,
                        'meta' => [
                            'tags' => $faker->randomElements(['popular', 'new', 'recommended', 'evening', 'weekend'], $faker->numberBetween(1, 3)),
                            'language' => $faker->randomElement(['vi', 'en']),
                            'rating' => round($faker->randomFloat(2, 4.0, 5.0), 2),
                        ],
                    ]);

                    if ($faker->boolean(10)) {
                        $course->delete();
                    }

                    $teachers = $teachersByCenter[$center->id];
                    $attachCount = $faker->numberBetween(1, 3);
                    $picked = $teachers->random($attachCount)->values();

                    $sync = [];
                    foreach ($picked as $idx => $teacher) {
                        $sync[$teacher->id] = [
                            'role' => $idx === 0 ? 1 : $faker->randomElement([2, 3]),
                            'sort_order' => $idx + 1,
                        ];
                    }
                    $course->teachers()->sync($sync);

                    $courses->push($course);
                    $allCourses->push($course);

                    $scheduleCount = $faker->numberBetween(2, 4);
                    $days = collect([1, 2, 3, 4, 5, 6, 0])->shuffle()->take($scheduleCount)->values();
                    for ($s = 0; $s < $scheduleCount; $s++) {
                        $teacher = $picked->random();
                        $startHour = $faker->randomElement([8, 9, 10, 13, 14, 18, 19]);
                        $startTime = Carbon::now($center->timezone)->startOfDay()->setTime($startHour, 0, 0);
                        $endTime = (clone $startTime)->addMinutes($faker->randomElement([90, 120, 150]));

                        CourseSchedule::query()->create([
                            'course_id' => $course->id,
                            'teacher_id' => $teacher->id,
                            'day_of_week' => $days[$s],
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'timezone' => $center->timezone,
                            'location' => $faker->randomElement(['On-site', 'Online', 'Hybrid']),
                            'room' => $faker->randomElement(['Room A', 'Room B', 'Room C', 'Lab 1', 'Studio 2', null]),
                            'active_from' => $start->toDateString(),
                            'active_to' => $end->toDateString(),
                            'is_active' => $faker->boolean(92),
                            'notes' => [
                                'note' => $faker->boolean(40) ? $faker->sentence(10) : null,
                            ],
                        ]);
                    }

                    $mediaCount = $faker->numberBetween(2, 6);
                    $courseMedias = collect();
                    for ($m = 1; $m <= $mediaCount; $m++) {
                        $cm = CourseMedia::query()->create([
                            'course_id' => $course->id,
                            'title' => "Video {$m}: " . $faker->sentence(4),
                            'description' => $faker->sentence(16),
                            'disk' => 'public',
                            'original_path' => "media/original/{$course->id}/" . Str::uuid() . ".mp4",
                            'original_mime' => 'video/mp4',
                            'original_size' => $faker->numberBetween(30_000_000, 600_000_000),
                            'duration_seconds' => $faker->numberBetween(120, 3600),
                            'playback_manifest_path' => "media/hls/{$course->id}/" . Str::uuid() . "/master.m3u8",
                            'renditions' => [
                                ['height' => 360, 'path' => "media/hls/{$course->id}/" . Str::uuid() . "/360p.m3u8"],
                                ['height' => 720, 'path' => "media/hls/{$course->id}/" . Str::uuid() . "/720p.m3u8"],
                            ],
                            'thumbnails' => [
                                ['time' => 1, 'path' => "media/thumbs/{$course->id}/" . Str::uuid() . ".jpg"],
                                ['time' => 10, 'path' => "media/thumbs/{$course->id}/" . Str::uuid() . ".jpg"],
                            ],
                            'status' => $this->pickEnumValue($mediaStatus),
                            'processing_job_id' => $faker->boolean(30) ? (string)Str::uuid() : null,
                            'failure_reason' => null,
                            'sort_order' => $m,
                            'meta' => [
                                'source' => $faker->randomElement(['upload', 'import', 'recorded']),
                            ],
                        ]);
                        $courseMedias->push($cm);
                    }

                    $lessonCount = $faker->numberBetween(6, 12);
                    $lessons = collect();
                    for ($l = 1; $l <= $lessonCount; $l++) {
                        $lt = "Lesson {$l}: " . $faker->sentence(3);
                        $lesson = Lesson::query()->create([
                            'course_id' => $course->id,
                            'title' => $lt,
                            'slug' => Str::slug($lt) . '-' . Str::lower(Str::random(6)),
                            'summary' => $faker->sentence(20),
                            'sort_order' => $l,
                            'status' => $this->pickEnumValue($lessonStatus),
                        ]);

                        if ($faker->boolean(6)) {
                            $lesson->delete();
                        }

                        $lessons->push($lesson);
                    }

                    foreach ($lessons as $lesson) {
                        $attach = $courseMedias->shuffle()->take($faker->numberBetween(1, 2))->values();
                        foreach ($attach as $idx => $cm) {
                            LessonMedia::query()->create([
                                'lesson_id' => $lesson->id,
                                'course_media_id' => $cm->id,
                                'title' => $faker->boolean(60) ? $cm->title : ("Lesson clip: " . $faker->sentence(3)),
                                'sort_order' => $idx + 1,
                            ]);
                        }
                    }
                }

                $coursesByCenter[$center->id] = $courses;
            }

            $ribbons = collect();
            $ribbonTitles = [
                'Trending This Week',
                'Best Sellers',
                'New & Noteworthy',
                'Weekend Picks',
                'Recommended For You',
            ];

            for ($i = 1; $i <= 4; $i++) {
                $title = $faker->randomElement($ribbonTitles);
                $ribbon = Ribbon::query()->create([
                    'title' => $title,
                    'slug' => Str::slug($title) . '-' . Str::lower(Str::random(6)),
                    'description' => $faker->sentence(18),
                    'status' => 1,
                    'order' => $i,
                ]);
                $ribbons->push($ribbon);

                $items = $allCourses->shuffle()->take($faker->numberBetween(8, 12))->values();
                foreach ($items as $idx => $course) {
                    RibbonItem::query()->create([
                        'ribbon_id' => $ribbon->id,
                        'course_id' => $course->id,
                        'order' => $idx + 1,
                    ]);
                }
            }

            $users->shuffle()->take(min(10, $users->count()))->each(function ($user) use ($faker, $allCourses) {
                $cart = Cart::query()->create([
                    'user_id' => $user->id,
                    'status' => $faker->randomElement(['active', 'abandoned', 'converted']),
                    'subtotal' => 0,
                    'discount_total' => 0,
                    'total' => 0,
                    'meta' => [
                        'source' => $faker->randomElement(['web', 'mobile', 'referral']),
                        'coupon' => $faker->boolean(25) ? strtoupper($faker->bothify('SAVE##')) : null,
                    ],
                ]);

                $courses = $allCourses->whereNull('deleted_at')->shuffle()->take($faker->numberBetween(1, 4))->values();

                $subtotal = 0;
                foreach ($courses as $course) {
                    $qty = 1;
                    $unit = (float) $course->tuition_fee;
                    $line = $qty * $unit;
                    $subtotal += $line;

                    CartItem::query()->create([
                        'cart_id' => $cart->id,
                        'course_id' => $course->id,
                        'quantity' => $qty,
                        'unit_price' => $unit,
                        'line_total' => $line,
                        'meta' => [
                            'course_title_snapshot' => $course->title,
                        ],
                    ]);
                }

                $discount = $faker->boolean(35) ? (int) round($subtotal * $faker->randomFloat(2, 0.03, 0.15)) : 0;
                $total = max(0, $subtotal - $discount);

                $cart->update([
                    'subtotal' => $subtotal,
                    'discount_total' => $discount,
                    'total' => $total,
                ]);

                if ($faker->boolean(60)) {
                    $orderStatus = $faker->randomElement(['pending', 'processing', 'completed', 'cancelled']);
                    $paymentStatus = $orderStatus === 'completed'
                        ? 'paid'
                        : $faker->randomElement(['unpaid', 'paid', 'failed']);

                    $order = Order::query()->create([
                        'order_number' => strtoupper('ORD-' . now()->format('ymd') . '-' . $faker->bothify('####??')),
                        'user_id' => $user->id,
                        'cart_id' => $cart->id,
                        'status' => $orderStatus,
                        'currency' => 'VND',
                        'subtotal' => $cart->subtotal,
                        'discount_total' => $cart->discount_total,
                        'tax_total' => 0,
                        'total' => $cart->total,
                        'payment_method' => $faker->randomElement(['bank_transfer', 'stripe', 'momo', 'zalopay']),
                        'payment_status' => $paymentStatus,
                        'note' => $faker->boolean(25) ? $faker->sentence(12) : null,
                        'meta' => [
                            'ip' => $faker->ipv4(),
                            'user_agent' => $faker->userAgent(),
                        ],
                    ]);

                    $cart->items()->each(function (CartItem $ci) use ($order) {
                        OrderItem::query()->create([
                            'order_id' => $order->id,
                            'course_id' => $ci->course_id,
                            'quantity' => $ci->quantity,
                            'unit_price' => $ci->unit_price,
                            'line_total' => $ci->line_total,
                            'meta' => $ci->meta,
                        ]);
                    });

                    $paymentCount = $paymentStatus === 'paid' ? 1 : ($faker->boolean(25) ? 1 : 0);

                    for ($p = 1; $p <= $paymentCount; $p++) {
                        $provider = $order->payment_method;
                        $status = $paymentStatus === 'paid' ? 'succeeded' : $faker->randomElement(['requires_payment_method', 'failed']);

                        $payment = Payment::query()->create([
                            'order_id' => $order->id,
                            'user_id' => $user->id,
                            'provider' => $provider,
                            'provider_payment_id' => strtoupper($faker->bothify('pay_################')),
                            'provider_charge_id' => strtoupper($faker->bothify('ch_################')),
                            'client_secret' => $faker->boolean(60) ? Str::random(24) : null,
                            'amount' => $order->total,
                            'currency' => $order->currency,
                            'status' => $status,
                            'payload' => [
                                'provider' => $provider,
                                'attempt' => $p,
                                'order_number' => $order->order_number,
                            ],
                            'paid_at' => $status === 'succeeded' ? now()->subMinutes($faker->numberBetween(1, 240)) : null,
                        ]);

                        $events = $status === 'succeeded'
                            ? ['payment_intent.created', 'payment_intent.succeeded']
                            : ['payment_intent.created', 'payment_intent.payment_failed'];

                        foreach ($events as $idx => $event) {
                            PaymentLog::query()->create([
                                'payment_id' => $payment->id,
                                'event' => $event,
                                'level' => $idx === count($events) - 1 && $status !== 'succeeded' ? 'error' : 'info',
                                'payload' => [
                                    'event' => $event,
                                    'status' => $status,
                                    'time' => now()->toISOString(),
                                ],
                            ]);
                        }
                    }
                }
            });
        });
    }

    private function randomEnumValue(string $enumClass): array
    {
        if (!enum_exists($enumClass)) {
            return ['cases' => [], 'backed' => false];
        }

        $cases = $enumClass::cases();
        $backed = is_subclass_of($enumClass, \BackedEnum::class);

        return ['cases' => $cases, 'backed' => $backed];
    }

    private function pickEnumValue(array $meta)
    {
        if (empty($meta['cases'])) {
            return null;
        }

        $case = $meta['cases'][array_rand($meta['cases'])];

        return $meta['backed'] ? $case->value : $case->name;
    }
}
