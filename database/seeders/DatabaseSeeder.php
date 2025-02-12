<?php

namespace Database\Seeders;

use App\Models\Athlet;
use App\Models\Box;
use App\Models\Fee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Boxes
        $box1 = Box::create([
            'box_number' => 'B001',
            'expire_date' => now()->addMonth(),
        ]);

        $box2 = Box::create([
            'box_number' => 'B002',
            'expire_date' => now()->addMonth(),
        ]);

        $box3 = Box::create([
            'box_number' => 'B003',
            'expire_date' => now()->addMonth(),
        ]);

        // Seed Fees
        $fee1 = Fee::create([
            'fees' => 500,
            'athlet_id' => 1,
        ]);

        $fee2 = Fee::create([
            'fees' => 600,
            'athlet_id' => 1,
        ]);

        $fee3 = Fee::create([
            'fees' => 700,
            'athlet_id' => 1,
        ]);

        // Seed Athlets
        Athlet::create([
            'name' => 'جان دو',
            'father_name' => 'آقای دو',
            'phone_number' => '123456789',
            'photo' => null,
            'admission_type' => 'Monthly',
            'admission_expiry_date' => now()->addMonth(),
            'box_id' => $box1->id,
            'details' => 'جزئیات ورزشکار اول',
            'status' => true,
        ]);

        Athlet::create([
            'name' => 'جین اسمیت',
            'father_name' => 'آقای اسمیت',
            'phone_number' => '987654321',
            'photo' => null,
            'admission_type' => 'Quarterly',
            'admission_expiry_date' => now()->addMonths(3),
            'box_id' => $box2->id,
            'details' => 'جزئیات ورزشکار دوم',
            'status' => true,
        ]);

        Athlet::create([
            'name' => 'سم براون',
            'father_name' => 'آقای براون',
            'phone_number' => '456123789',
            'photo' => null,
            'admission_type' => 'Yearly',
            'admission_expiry_date' => now()->addYear(),
            'box_id' => $box3->id,
            'details' => 'جزئیات ورزشکار سوم',
            'status' => true,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            BoxesTableSeeder::class,
            FeesTableSeeder::class, // Seed fees before athlets
            AthletsTableSeeder::class,
        ]);
    }
}
