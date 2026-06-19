<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add Rafi (user 1004) to tb_lecturers
        DB::table('tb_lecturers')->insertOrIgnore([
            'id_user' => 1004,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Add Rafi (user 1004) to tb_homerooms with class 105
        DB::table('tb_homerooms')->insertOrIgnore([
            'id_user' => 1004,
            'id_class' => '105',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('tb_lecturers')->where('id_user', 1004)->delete();
        DB::table('tb_homerooms')->where('id_user', 1004)->where('id_class', '105')->delete();
    }
};
