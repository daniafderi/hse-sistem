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
        Schema::create('daily_safety_patrols', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->bigInteger('jumlah_pekerja');
            $table->text('deskripsi')->nullable();
            $table->bigInteger('jam_kerja');
            $table->enum('permit', ['Gabungan','Ketinggian','Penggalian','Crane','Listrik']);
            $table->string('reward')->nullable();
            $table->string('punishment')->nullable();
            $table->string('nearmiss')->nullable();
            $table->string('kecelakaan')->nullable();
            $table->string('status_validasi')->default('menunggu validasi');
            $table->foreignId('project_safety_id')->constrained('project_safeties')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_safety_patrols');
    }
};
