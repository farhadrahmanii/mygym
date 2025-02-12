<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BoxesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('boxes')->insert([
            ['box_number' => 'B001', 'expire_date' => '2025-12-31'],
            ['box_number' => 'B002', 'expire_date' => '2025-12-31'],
        ]);
    }
}