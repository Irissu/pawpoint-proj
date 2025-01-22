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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->foreignId('vet_id')->nullable()->constrained('users');
            $table->foreignId('owner_id')->nullable()->constrained('users');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('summary')->nullable();
            $table->text('treatment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
