<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            // Kriteria SAW - tambahkan setelah kolom yang sudah ada
            $table->integer('tingkat_kerusakan')->default(0)->after('tanggal_selesai_perbaikan');
            $table->integer('lokasi_strategis')->default(0)->after('tingkat_kerusakan');
            $table->integer('jumlah_pengguna')->default(0)->after('lokasi_strategis');
            $table->integer('kedekatan_fasum')->default(0)->after('jumlah_pengguna');
            
            // Hasil perhitungan SAW
            $table->decimal('skor_saw', 8, 4)->nullable()->after('kedekatan_fasum');
            $table->integer('prioritas')->nullable()->after('skor_saw');
            
            // Tambahkan index untuk performa
            $table->index('prioritas');
            $table->index('skor_saw');
        });
    }

    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropIndex(['prioritas']);
            $table->dropIndex(['skor_saw']);
            
            $table->dropColumn([
                'tingkat_kerusakan',
                'lokasi_strategis',
                'jumlah_pengguna',
                'kedekatan_fasum',
                'skor_saw',
                'prioritas'
            ]);
        });
    }
};