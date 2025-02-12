<?php

use App\Models\Box;
use App\Models\Fee;
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
        Schema::create('athlets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('father_name')->nullable();
            $table->string('phone_number');
            $table->string('photo')->nullable();
            $table->string('admission_type');
            $table->string('admission_expiry_date')->nullable();
            $table->foreignIdFor(Box::class)->onDelete('set null')->nullable()->unique();
            $table->text('details')->nullable();
            $table->boolean('status')->default(false);
            $table->timestamps();
        });

        // Seed data
        // DB::table('athlets')->insert([
        //     [
        //         'name' => 'John Doe',
        //         'father_name' => 'Richard Roe',
        //         'phone_number' => '1234567890',
        //         'photo' => null,
        //         'fee_id' => 1,
        //         'admission_type' => 'Regular',
        //         'admission_expiry_date' => '2025-12-31',
        //         'box_id' => 1,
        //         'details' => 'Sample details'
        //     ],
        //     [
        //         'name' => 'Jane Smith',
        //         'father_name' => 'John Smith',
        //         'phone_number' => '0987654321',
        //         'photo' => null,
        //         'fee_id' => 2,
        //         'admission_type' => 'Regular',
        //         'admission_expiry_date' => '2025-12-31',
        //         'box_id' => 2,
        //         'details' => 'Sample details'
        //     ],
        // ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('athlets');
    }
};
