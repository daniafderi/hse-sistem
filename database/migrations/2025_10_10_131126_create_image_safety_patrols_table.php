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
        Schema::create('image_safety_patrols', function (Blueprint $table) {
            $table->id();
            $table->string('image_url')->nullable();
            $table->foreignId('daily_safety_patrol_id');
            $table->string('label');
            $table->string('text')->nullable();
            $table->string('status')->nullable();
            $table->string('tindakan_perbaikan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_safety_patrols');
    }
};
