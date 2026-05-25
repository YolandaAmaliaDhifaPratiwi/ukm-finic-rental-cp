<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique(); // TRX-1001
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
            $table->date('borrow_date');
            $table->date('return_date');
            $table->date('actual_return_date')->nullable();
            $table->text('purpose')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'returned', 'overdue'])->default('pending');
            $table->enum('final_condition', ['excellent', 'good', 'minor_scratches', 'needs_repair'])->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};