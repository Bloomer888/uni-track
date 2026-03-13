<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'teacher', 'student'])->default('student')->after('email');
            $table->string('token', 64)->unique()->after('role');
            $table->boolean('is_active')->default(true)->after('token');
            $table->string('student_id')->nullable()->after('is_active');
            $table->string('employee_id')->nullable()->after('student_id');
            $table->string('phone')->nullable()->after('employee_id');
            $table->string('avatar')->nullable()->after('phone');
            $table->json('meta')->nullable()->after('avatar');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 'token', 'is_active',
                'student_id', 'employee_id',
                'phone', 'avatar', 'meta',
            ]);
        });
    }
};