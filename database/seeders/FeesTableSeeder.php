<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('fees')->insert([
            ['athlet_id' => 1, 'fees' => 500],
            ['athlet_id' => 2, 'fees' => 600],
        ]);
    }
}
