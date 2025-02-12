<?php

use App\Models\Athlet;
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
        Schema::create('boxes', function (Blueprint $table) {
            $table->id();
            $table->string('box_number');
            $table->string('expire_date');
            $table->foreignId('athlet_id')->nullable()->constrained('athlets')->nullOnDelete();
            $table->timestamps();
        });

        // Seed data
        DB::table('boxes')->insert([
            ['box_number' => 'B001', 'expire_date' => '2025-12-31'],
            ['box_number' => 'B002', 'expire_date' => '2025-12-31'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boxes');
    }
};
