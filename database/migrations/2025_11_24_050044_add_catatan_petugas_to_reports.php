<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            // Cek dulu apakah kolom sudah ada
            if (!Schema::hasColumn('reports', 'catatan_petugas')) {
                $table->text('catatan_petugas')->nullable()->after('catatan_admin');
            }
            
            if (!Schema::hasColumn('reports', 'alasan_penolakan')) {
                $table->text('alasan_penolakan')->nullable()->after('catatan_petugas');
            }
        });
    }

    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'catatan_petugas')) {
                $table->dropColumn('catatan_petugas');
            }
            
            if (Schema::hasColumn('reports', 'alasan_penolakan')) {
                $table->dropColumn('alasan_penolakan');
            }
        });
    }
};