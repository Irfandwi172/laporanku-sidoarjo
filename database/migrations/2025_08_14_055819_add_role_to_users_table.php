<?php
// ============================================
// SOLUSI 1: Update Migration Yang Sudah Ada
// FILE: database/migrations/2025_08_14_055819_add_role_to_users_table.php
// ============================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // UBAH DARI ['admin', 'user'] MENJADI ['admin', 'user', 'petugas']
            $table->enum('role', ['admin', 'user', 'petugas'])->default('user')->after('email');
            $table->boolean('is_active')->default(true)->after('role');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active']);
        });
    }
};
