<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->index('teacher_id');
            $table->index('created_by');
            $table->index('status');
        });

        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->index('subject_id');
            $table->index('teacher_id');
            $table->index('status');
            $table->index('expires_at');
        });

        Schema::table('attendance_records', function (Blueprint $table) {
            $table->index('session_id');
            $table->index('student_id');
            $table->index('status');
            $table->index('scanned_at');
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropIndex(['teacher_id']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['status']);
        });

        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->dropIndex(['subject_id']);
            $table->dropIndex(['teacher_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['expires_at']);
        });

        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dropIndex(['session_id']);
            $table->dropIndex(['student_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['scanned_at']);
        });
    }
};