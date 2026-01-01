<?php

// ==================== MIGRATION FILES ====================

// database/migrations/2025_01_01_000001_create_reports_table.php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelapor');
            $table->string('nomor_hp');
            $table->string('email')->nullable();
            $table->string('lokasi');
            $table->text('deskripsi');
            $table->string('foto')->nullable();
            $table->enum('status', ['Menunggu Verifikasi', 'Diverifikasi', 'Dalam Perbaikan', 'Selesai'])
                  ->default('Menunggu Verifikasi');
            $table->date('tanggal_mulai_perbaikan')->nullable();
            $table->date('tanggal_selesai_perbaikan')->nullable();
            $table->integer('estimasi_durasi')->nullable(); // dalam hari
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
};