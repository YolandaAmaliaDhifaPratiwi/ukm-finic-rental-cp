<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['camera', 'lens', 'tripod', 'lighting', 'accessory'])->default('camera');
            $table->enum('condition', ['excellent', 'good', 'fair', 'needs_repair'])->default('excellent');
            $table->enum('status', ['available', 'borrowed', 'maintenance'])->default('available');
            $table->string('serial_number')->nullable(); // UKM-FIN-XXXXXX
            $table->string('asset_tag')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};