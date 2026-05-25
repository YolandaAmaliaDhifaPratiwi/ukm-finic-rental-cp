<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrowing_id')->constrained('borrowings')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'confirmed', 'rejected'])->default('pending');
            $table->text('condition_notes')->nullable(); // catatan kondisi dari member
            $table->text('admin_notes')->nullable();     // catatan dari admin
            $table->string('photo')->nullable();         // foto kondisi barang
            $table->timestamp('returned_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });

        // Tambah kolom return_status ke borrowings
        Schema::table('borrowings', function (Blueprint $table) {
            $table->enum('return_status', ['none', 'pending', 'confirmed', 'rejected'])->default('none')->after('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returns');
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn('return_status');
        });
    }
};
