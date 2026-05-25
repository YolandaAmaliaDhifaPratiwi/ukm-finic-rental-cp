<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['member', 'admin'])->default('member');
            $table->string('student_id')->nullable(); // e.g. M10423
            $table->string('avatar')->nullable();
            $table->integer('reliability_score')->default(100);
            $table->enum('membership_level', ['bronze', 'silver', 'gold'])->default('bronze');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};