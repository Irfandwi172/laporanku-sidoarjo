<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Ubah kolom status menjadi enum atau string yang lebih panjang
        DB::statement("ALTER TABLE reports MODIFY COLUMN status VARCHAR(50) DEFAULT 'Menunggu Verifikasi'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE reports MODIFY COLUMN status VARCHAR(30) DEFAULT 'Menunggu Verifikasi'");
    }
};