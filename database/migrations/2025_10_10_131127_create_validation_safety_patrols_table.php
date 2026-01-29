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
        Schema::create('validation_safety_patrols', function (Blueprint $table) {
            $table->id();
            $table->foreignId('validator_id');
            $table->foreignId('safety_patrol_id');
            $table->string('status');
            $table->text('komentar')->nullable();
            $table->foreign('validator_id')->references('id')->on('users');
            $table->foreign('safety_patrol_id')->references('id')->on('daily_safety_patrols')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validation_safety_patrols');
    }
};
