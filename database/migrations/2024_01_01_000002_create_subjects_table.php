<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('class_code', 10)->unique();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['active', 'inactive', 'archived'])->default('active');
            $table->string('semester')->nullable();
            $table->string('academic_year')->nullable();
            $table->string('schedule')->nullable();
            $table->string('room')->nullable();
            $table->boolean('default_geofencing')->default(false);
            $table->decimal('default_latitude', 10, 7)->nullable();
            $table->decimal('default_longitude', 10, 7)->nullable();
            $table->integer('default_radius_meters')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};