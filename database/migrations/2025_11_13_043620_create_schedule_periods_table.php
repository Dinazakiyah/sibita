<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedule_periods', function (Blueprint $table) {
            $table->id();
            $table->string('period_name'); // Ganjil 2024/2025, Genap 2024/2025, etc
            $table->date('start_date');
            $table->date('end_date');
            $table->date('registration_deadline');
            $table->date('seminar_start_date')->nullable();
            $table->date('seminar_end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_periods');
    }
};
