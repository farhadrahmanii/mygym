<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('athlet_id');
            $table->string('fees')->nullable();
            $table->timestamps();
        });

        // Seed data
        DB::table('fees')->insert([
            ['athlet_id' => 1, 'fees' => 500],
            ['athlet_id' => 2, 'fees' => 600],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fees');
    }
};
