<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('attendance_sessions')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->enum('status', ['present', 'late', 'absent', 'excused'])->default('present');
            $table->timestamp('scanned_at')->useCurrent();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('within_geofence')->nullable();
            $table->decimal('distance_meters', 10, 2)->nullable();
            $table->boolean('manually_overridden')->default(false);
            $table->foreignId('overridden_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('override_note')->nullable();
            $table->json('meta')->nullable();
            $table->unique(['session_id', 'student_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};